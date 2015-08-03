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
| FILENAME - tracking.php
| -------------------------------------------------------------------------     
|
*/

class Tracking extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('tracking_model');
		
		$this->config->set_item('menu', 'a');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . 'tracking/view_tracking');
	}
	
	// ------------------------------------------------------------------------
	
	function add_tracking()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$this->validation->id = '';
		
		if ($this->_check_tracking() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
			
			load_admin_tpl('admin', 'tpl_adm_manage_tracking', $data);	
		}
		else
		{	
			$data = $this->tracking_model->_add_tracking();		

			$this->session->set_flashdata('success', $this->lang->line('item_added_successfully'));

			redirect(admin_url() . strtolower( __CLASS__) . '/view_tracking');
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function delete_tracking_referral()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->tracking_model->_delete_tracking_referral((int)($this->uri->segment(4))))
		{
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . 'tracking/view_tracking/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
		}
		
		redirect(admin_url() . 'tracking/view_tracking');
		
	}
	
	// ------------------------------------------------------------------------
	
	function delete_tracking()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->tracking_model->_delete_tracking((int)($this->uri->segment(4))))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . 'tracking/view_tracking/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
		}
		
		redirect(admin_url() . 'tracking/view_tracking');
	}
	
	// ------------------------------------------------------------------------
	
	function reset_tracking()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->tracking_model->_reset_tracking((int)($this->uri->segment(4))))
		{
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . 'tracking/view_tracking/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
		}
		
		redirect(admin_url() . 'tracking/view_tracking');
	}
	
	// ------------------------------------------------------------------------
	
	function view_referrals()
	{	
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$row = $this->tracking_model->_get_tracking_referrals($this->uri->segment(8), $this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);

		$data['tracking'] = $row['tracking'];	
		$data['tid'] = $this->uri->segment(8);
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'tracking', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column'], $row['totals'], $data['where_column'], $data['show_where_value'], 'admin', $data['where_column2'], $data['where_value2']);
		
		load_admin_tpl('admin', 'tpl_adm_manage_tracking_referrals', $data);
	}
	
	
	// ------------------------------------------------------------------------
	
	function view_tracking()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['tracking'] = $this->tracking_model->_get_tracking($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);
		
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'tracking', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column']);
		
		load_admin_tpl('admin', 'tpl_adm_manage_tracking_ids', $data);
	}
	
	// ------------------------------------------------------------------------
	
	
	function update_tracking()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->_check_tracking() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
			else
			{
				$m = $this->tracking_model->_get_tracking_details((int)$this->uri->segment(4));
			
				if (!empty($m))
				{	
					foreach ($m as $k => $v)
					{
						$this->validation->$k = $v;
					}
				}
				else
				{
					redirect(admin_url() . 'tracking/view_tracking');
					exit();
				}
			}
			
			load_admin_tpl('admin', 'tpl_adm_manage_tracking', $data);
		
		}
		else
		{	
			$data = $this->tracking_model->_update_tracking((int)$this->uri->segment(4));	
			
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));

			redirect($this->uri->uri_string());
		}		
	}
	
	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	
	// ------------------------------------------------------------------------	
	
	function _check_recur()
	{
		
		if ($this->validation->recur > 0)
		{
			return true;
		}
		else
		{
			$this->validation->set_message('_check_recur', $this->lang->line('invalid_recur'));
			return false;
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_tracking()
	{		
		$rules['name'] = 'trim|required|min_length[2]|max_length[50]';
		$rules['url'] = 'trim|required|prep_url';
		$rules['cost'] = 'trim|numeric';
		$rules['cost_type'] = 'trim';
		
		if ($this->input->post('cost_type') == 'recur')
		{
			$rules['recur'] = 'trim|integer|required|callback__check_recur';
		}
		
		$this->validation->set_rules($rules);

		$fields['name'] = $this->lang->line('tracking_name');
		$fields['url'] = $this->lang->line('tracking_url');
		$fields['cost'] = $this->lang->line('cost_type');
		$fields['cost_type'] = $this->lang->line('cost_type');
		$fields['recur'] = $this->lang->line('recur');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
		
	}
}
?>