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
| FILENAME - Module_Mailing_List_Mailchimp.php
| -------------------------------------------------------------------------     
|
*/

class Module_Mailing_List_Mailchimp extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('modules/module_mailing_list_mailchimp_model', 'list_model');
		
		$this->config->set_item('menu', 'e');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect($this->uri->uri_string() . '/view/');
	}
	
	// ------------------------------------------------------------------------
	
	function update()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->_check_mailchimp_options() == false)
		{		
			if (!empty($_POST))
			{
				$this->session->set_flashdata('error', $this->validation->error_string);
			}
		}
		else
		{	
			$_POST['module_mailing_list_mailchimp_api_key'] =  $this->encrypt->encode($_POST['module_mailing_list_mailchimp_api_key']);
			
			$data = $this->modules_model->_update_options($_POST);	
			
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			if (!empty($_POST['redirect']))
			{
				redirect($this->input->post('redirect'));
			}

		}	
		
		redirect($this->input->post('redirect'));		
	}
	
	// ------------------------------------------------------------------------
	
	function _check_mailchimp_options()
	{
		$rules['module_mailing_list_mailchimp_api_key'] = 'trim|required';
		
		$this->validation->set_rules($rules);

		$fields['module_mailing_list_mailchimp_api_key'] = $this->lang->line('api_key');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;	
	}
}
?>