<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| COPYRIGHT NOTICE                                                     
| Copyright 2007 - 2015 JROX Technologies, Inc.  All Rights Reserved.   
| -------------------------------------------------------------------------    
| This script may be only used and modified in accordance to the license      
| agreement attached (license.txt) except where expressly noted within      
| commented areas of the code body. This copyright notice and the  
| comments above and below must remain intact at all times.  By using this 
| code you agree to indemnify JROX Technologies, Inc, its corporate agents   
| and affiliates from any liability that might arise from its use.                                                        
|                                                                           
| Selling the code for this program without prior written consent is       
| expressly forbidden and in violation of Domestic and International 
| copyright laws.
|
| -------------------------------------------------------------------------
| FILENAME - action_commissions.php
| -------------------------------------------------------------------------
|
*/

class Action_Commissions extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('programs_model', 'programs');
		
		$this->config->set_item('menu', 'c');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . strtolower( __CLASS__) . '/view_action_commissions');
	}
	
	// ------------------------------------------------------------------------
	
	function view_action_commissions()
	{
		
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
				
		$data['action_commissions'] = $this->comm->_get_action_commissions($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'action_commissions', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column']);
		
		load_admin_tpl('admin', 'tpl_adm_manage_action_commissions', $data);	

	}
	
	// ------------------------------------------------------------------------

	function add_action_commission()
	{		
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->_check_action_commission() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
				
			load_admin_tpl('admin', 'tpl_adm_manage_action_commission', $data);
		}
		else
		{	
			$data = $this->comm->_add_action_commission($_POST);		
			
			$this->session->set_flashdata('success', $this->lang->line('item_added_successfully'));

			redirect(admin_url() . strtolower( __CLASS__) . '/view_action_commissions');
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function delete_action_commission()
	{		
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->comm->_delete_action_commission((int)($this->uri->segment(4))))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));	
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url()  . strtolower( __CLASS__) . '/view_action_commissions/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
		}
		
		redirect(admin_url()  . strtolower( __CLASS__) . '/view_action_commissions');
		
	}
	
	// ------------------------------------------------------------------------
	
	function update_action_commission()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$this->load->model('mailing_lists_model');
		
		$lists = $this->mailing_lists_model->_get_all_mailing_lists();
		
		$data['lists'] = format_array($lists, 'mailing_list_id', 'mailing_list_name', true);
		
		$this->validation->id = (int)$this->uri->segment(4);
		
		if ($this->_check_action_commission() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
			else
			{		
				$m = $this->comm->_get_action_commission_details((int)$this->uri->segment(4));
				
				if (!empty($m))
				{	
					foreach ($m as $k => $v)
					{
						$this->validation->$k = $v;
					}
				}
				else
				{
					redirect(admin_url() . strtolower( __CLASS__) . '/view_action_commissions');
					exit();
				}
			}
				
			load_admin_tpl('admin', 'tpl_adm_manage_action_commission', $data);
								
		}
		else
		{				
			$data = $this->comm->_update_action_commission((int)$this->uri->segment(4), $_POST);	

			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			redirect($this->uri->uri_string());	
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function update_status()
	{		
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		if ($this->db_validation_model->_change_status_field('action_commissions', 'id', (int)$this->uri->segment(4), 'status'))
		{
			
			if ($this->uri->segment(5) == 2)
			{	
				redirect(admin_url()  . strtolower( __CLASS__) . '/view_action_commissions/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
			}
			else
			{
				$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));	
			}
		}	
	
		redirect(admin_url() . strtolower( __CLASS__) . '/view_action_commissions');
	}
	
	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/

	// ------------------------------------------------------------------------	
	
	function _check_action_commission()
	{		
		$rules['action_commission_name'] = 'trim|required|min_length[2]|max_length[50]|alpha_dash|callback__check_code';
		$rules['action_commission_description'] = 'trim|max_length[255]';
		$rules['amount'] = 'trim|required|numeric';
		$rules['auto_approve'] = 'trim|integer';
		$rules['type'] = 'trim|required';
		$rules['status'] = 'trim|required';
		
		$this->validation->set_rules($rules);

		$fields['action_commission_name'] = $this->lang->line('action_commission_name');
		$fields['action_commission_description'] = $this->lang->line('action_commission_description');
		$fields['amount'] = $this->lang->line('amount');
		$fields['auto_approve'] = $this->lang->line('auto_approve');
		$fields['type'] = $this->lang->line('commission_type');
		$fields['status'] = $this->lang->line('status');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_code()
	{

		if ($this->config->item('function') == 'update_action_commission')
		{
			if ($this->db_validation_model->_validate_field('action_commissions', 'action_commission_name', $this->validation->action_commission_name, 'id', $this->validation->id))
			{
				$this->validation->set_message('_check_code', $this->lang->line('code_is_used'));
			
				return false;
			}
		}
		else
		{
			if ($this->db_validation_model->_validate_field('action_commissions', 'action_commission_name', $this->validation->action_commission_name))	
			{
				$this->validation->set_message('_check_code', $this->lang->line('code_is_used'));
			
				return false;
			}
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
}
?>