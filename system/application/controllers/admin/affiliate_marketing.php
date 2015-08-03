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
| FILENAME - affiliate_marketing.php
| -------------------------------------------------------------------------     
| 
*/

class Affiliate_Marketing extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();		
		
		$this->config->set_item('menu', 'a');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . strtolower( __CLASS__) . '/view_affiliate_tools');
	}
	
	// ------------------------------------------------------------------------
	
	function view_affiliate_tools()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$tools_per_page =  '16';
		
		$data['modules'] = $this->aff->_get_affiliate_marketing($tools_per_page, $data['offset'], $data['sort_column'], $data['sort_order']);

		$data['total'] = $this->db_validation_model->_get_count('modules', 'WHERE module_type = \'affiliate_marketing\'');
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'modules', $tools_per_page, 4, $data['sort_order'], $data['sort_column'], $data['total']);
		
		load_admin_tpl('admin', 'tpl_adm_manage_affiliate_marketing', $data);	
	}
	
	// ------------------------------------------------------------------------
	
	function set_default()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		if ($this->db_validation_model->_change_status_field('modules', 'module_id', (int)$this->uri->segment(4), 'module_status'))
		{
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
		}	
		
		redirect(admin_url() . strtolower( __CLASS__) . '/view_affiliate_tools');
	}
	
	// ------------------------------------------------------------------------
	
	
	function change_status()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->db_validation_model->_change_status_field('modules', 'module_id', (int)$this->uri->segment(4), 'module_status'))
		{
			$this->session->set_flashdata('success', $this->lang->line('module_updated_successfully'));
		}
		
		redirect(admin_url() . strtolower( __CLASS__) . '/view_affiliate_tools');
	}
	
	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/

	// ------------------------------------------------------------------------	
	
	function _check_sort_order()
	{
		$data = $this->db_validation_model->_clean_data($_POST);
	
		foreach ($data as $k => $v)
		{
			if (strstr($k, "affiliate_marketing") == true) 
			{
				$rules[$k] = 'trim|required|numeric';
		
				$fields[$k] = $k ;
			}
		}
		
		$this->validation->set_rules($rules);
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
	}
}
?>