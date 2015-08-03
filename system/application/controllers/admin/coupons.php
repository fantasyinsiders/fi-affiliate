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
| FILENAME - coupons.php
| -------------------------------------------------------------------------
|
*/

class Coupons extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('coupons_model', 'coupons');
		
		$this->config->set_item('menu', 'a');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . strtolower( __CLASS__) . '/view_coupons');
	}
	
	// ------------------------------------------------------------------------
	
	function view_coupons()
	{
		
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
				
		$data['coupons'] = $this->coupons->_get_coupons($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'coupons', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column']);
		
		load_admin_tpl('admin', 'tpl_adm_manage_coupons', $data);	

	}
	
	// ------------------------------------------------------------------------

	function add_coupon()
	{		
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->_check_coupon() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
				
			load_admin_tpl('admin', 'tpl_adm_manage_coupon', $data);
		}
		else
		{	
			$insert = $_POST;
			
			if (!empty($_POST['referring_affiliate']))
			{
				$member = $this->db_validation_model->_get_details('members', '*', 'username', $this->input->post('referring_affiliate'));
			}
			else
			{
				show_error($this->lang->line('no_member_found'));
			}	
			
			$insert['member_id'] = $member[0]['member_id'];
			
			$data = $this->coupons->_add_coupon($insert);		
			
			$this->session->set_flashdata('success', $this->lang->line('item_added_successfully'));

			redirect(admin_url() . strtolower( __CLASS__) . '/view_coupons');
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function delete_coupon()
	{		
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->coupons->_delete_coupon((int)($this->uri->segment(4))))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));	
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url()  . strtolower( __CLASS__) . '/view_coupons/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
		}
		
		redirect(admin_url()  . strtolower( __CLASS__) . '/view_coupons');
		
	}
	
	// ------------------------------------------------------------------------
	
	function update_coupon()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$this->load->model('mailing_lists_model');
		
		$lists = $this->mailing_lists_model->_get_all_mailing_lists();
		
		$data['lists'] = format_array($lists, 'mailing_list_id', 'mailing_list_name', true);
		
		$this->validation->id = (int)$this->uri->segment(4);
		
		if ($this->_check_coupon() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
			else
			{		
				$m = $this->coupons->_get_coupon_details((int)$this->uri->segment(4));
				
				if (!empty($m))
				{	
					foreach ($m as $k => $v)
					{
						$this->validation->$k = $v;
						
						if ($k == 'username') $this->validation->referring_affiliate = $m['username'];
					}
				}
				else
				{
					redirect(admin_url() . strtolower( __CLASS__) . '/view_coupons');
					exit();
				}
			}
				
			load_admin_tpl('admin', 'tpl_adm_manage_coupon', $data);
								
		}
		else
		{				
			$insert = $_POST;
			
			if (!empty($_POST['referring_affiliate']))
			{
				$member = $this->db_validation_model->_get_details('members', '*', 'username', $this->input->post('referring_affiliate'));
			}
			else
			{
				show_error($this->lang->line('no_member_found'));
			}	
			
			$insert['member_id'] = $member[0]['member_id'];
			
			$data = $this->coupons->_update_coupon((int)$this->uri->segment(4), $insert);	

			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			redirect($this->uri->uri_string());	
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function update_status()
	{		
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		if ($this->db_validation_model->_change_status_field('coupons', 'coupon_id', (int)$this->uri->segment(4), 'status'))
		{
			
			if ($this->uri->segment(5) == 2)
			{	
				redirect(admin_url()  . strtolower( __CLASS__) . '/view_coupons/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
			}
			else
			{
				$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));	
			}
		}	
	
		redirect(admin_url() . strtolower( __CLASS__) . '/view_coupons');
	}
	
	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/

	// ------------------------------------------------------------------------	
	
	function _check_coupon()
	{		
		$rules['coupon_code'] = 'trim|required|min_length[2]|max_length[50]|alpha_dash|callback__check_code';
		$rules['coupon_description'] = 'trim|max_length[255]';
		$rules['referring_affiliate'] = 'trim|required|callback__check_username';
		
		if ($this->input->post('use_program_comms') == 0)
		{
			$rules['amount'] = 'trim|required|numeric';
		}
		else
		{
			$rules['amount'] = 'trim|numeric';
		}
		
		$rules['type'] = 'trim|required';
		$rules['status'] = 'trim|required';
		$rules['use_program_comms'] = 'trim|required';
		
		$this->validation->set_rules($rules);

		$fields['coupon_code'] = $this->lang->line('coupon_code');
		$fields['coupon_description'] = $this->lang->line('description');
		$fields['referring_affiliate'] = $this->lang->line('referring_affiliate');
		$fields['amount'] = $this->lang->line('amount');
		$fields['type'] = $this->lang->line('commission_type');
		$fields['status'] = $this->lang->line('status');
		$fields['use_program_comms'] = $this->lang->line('use_program_defaults');
		
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

		if ($this->config->item('function') == 'update_coupon')
		{
			if ($this->db_validation_model->_validate_field('coupons', 'coupon_code', $this->validation->coupon_code, 'coupon_id', $this->validation->id))
			{
				$this->validation->set_message('_check_code', $this->lang->line('code_is_used'));
			
				return false;
			}
		}
		else
		{
			if ($this->db_validation_model->_validate_field('coupons', 'coupon_code', $this->validation->coupon_code))
			{
				$this->validation->set_message('_check_code', $this->lang->line('code_is_used'));
			
				return false;
			}
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_username()
	{
		if ($this->db_validation_model->_validate_field('members', 'username', $this->validation->referring_affiliate) == false)
		{
			$this->validation->set_message('_check_username', $this->lang->line('invalid_referring_affiliate'));
			
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
}
?>