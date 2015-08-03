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
| FILENAME - Module_Mailing_List_aweber.php
| -------------------------------------------------------------------------     
|
*/

class Module_Mailing_List_Aweber extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('modules/module_mailing_list_aweber_model', 'list_model');
		
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
		
		if ($this->_check_aweber_options() == false)
		{		
			if (!empty($_POST))
			{
				$this->session->set_flashdata('error', $this->validation->error_string);
			}
		}
		else
		{	
			if (!$this->config->item('module_mailing_list_aweber_authorization_code'))
			{
				require_once(APPPATH . 'libraries/aweber_api/aweber_api.php');
				 try {
						$credentials = AWeberAPI::getDataFromAweberID($this->input->post('module_mailing_list_aweber_authorization_code'));
						list($consumerKey, $consumerSecret, $accessKey, $accessSecret) = $credentials;
				}
		
				catch(Exception $e) {
					show_error('invalid aweber authorization code: '  . $e->getMessage());
				}
				
				$keys = array(
					'module_mailing_list_aweber_consumer_key' => $consumerKey,
					'module_mailing_list_aweber_consumer_secret'  =>  $consumerSecret,
					'module_mailing_list_aweber_access_key' => $accessKey,
					'module_mailing_list_aweber_access_secret' => $accessSecret,
				);
	
				$this->db_validation_model->_update_db_settings($keys);
			}
			
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
	
	function _check_aweber_options()
	{
		$rules['module_mailing_list_aweber_app_id'] = 'trim|required';
		$rules['module_mailing_list_aweber_authorization_code'] = 'trim';
		$rules['module_mailing_list_aweber_list_id'] = 'trim|required';
		
		$this->validation->set_rules($rules);

		$fields['module_mailing_list_aweber_app_id'] = $this->lang->line('app_id');
		$fields['module_mailing_list_aweber_authorization_code'] = $this->lang->line('authorization_code');
		$fields['module_mailing_list_aweber_list_id'] = $this->lang->line('list_id');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;	
	}
}
?>