<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| COPYRIGHT NOTICE                                                     
| Copyright 2014-2015 JROX Technologies, Inc.  All Rights Reserved.    
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
| FILENAME - module_mailing_list_mailchimp_model.php
| -------------------------------------------------------------------------     
| 
|
*/

class Module_Mailing_List_Mailchimp_Model extends CI_Model {	
	
	function _install_jrox_module($id = '')
	{	
		//check admin session
		$this->security_model->_check_admin_session();
		
		$config = array(
							'settings_key'	=>	'module_mailing_list_mailchimp_api_key',
							'settings_value'	=>	'',
							'settings_module'	=>	'mailing_list',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'1',
							'settings_function'	=>	'encrypt',
							);
		
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_mailing_list_mailchimp_list_id',
							'settings_value'	=>	'',
							'settings_module'	=>	'mailing_list',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'2',
							'settings_function'	=>	'none',
							);
		
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_mailing_list_mailchimp_double_optin',
							'settings_value'	=>	'0',
							'settings_module'	=>	'mailing_list',
							'settings_type'	=>	'dropdown',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'3',
							'settings_function'	=>	'boolean',
							);
		
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_mailing_list_mailchimp_update_existing',
							'settings_value'	=>	'0',
							'settings_module'	=>	'mailing_list',
							'settings_type'	=>	'dropdown',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'4',
							'settings_function'	=>	'boolean',
							);
		
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_mailing_list_mailchimp_send_welcome',
							'settings_value'	=>	'1',
							'settings_module'	=>	'mailing_list',
							'settings_type'	=>	'dropdown',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'5',
							'settings_function'	=>	'boolean',
							);
		
		$this->db->insert('settings', $config);
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _run_module_mailchimp($type = 'public', $id = '')
	{
		
		if (is_array($id))
		{
			$data = $id;	
		}
		else
		{
			$m = $this->members_api->_get_member_details($id);
			
			$data = $m['member_data'];
		}

		require_once(APPPATH.'/libraries/mailchimp/MailChimp.php');
		
		$MailChimp = new MailChimp($this->encrypt->decode($this->config->item('module_mailing_list_mailchimp_api_key')));
		
		$sub =  array(
                'id'                => $this->config->item('module_mailing_list_mailchimp_list_id'),
                'email'             => array('email'=> $data['primary_email']),
                'merge_vars'        => array('FNAME'=> $data['fname'], 'LNAME'=> $data['lname']),
                'double_optin'      => $this->config->item('module_mailing_list_mailchimp_double_optin'),
                'update_existing'   => $this->config->item('module_mailing_list_mailchimp_update_existing'),
                'replace_interests' => false,
                'send_welcome'      => $this->config->item('module_mailing_list_mailchimp_send_welcome'),
            );
		
		$result = $MailChimp->call('lists/subscribe',$sub);	
	}
	
	// ------------------------------------------------------------------------	
}
?>