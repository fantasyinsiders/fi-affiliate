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
| FILENAME - module_mailing_list_getresponse_model.php
| -------------------------------------------------------------------------     
| 
|
*/

class Module_Mailing_List_Getresponse_Model extends CI_Model {	
	
	function _install_jrox_module($id = '')
	{	
		//check admin session
		$this->security_model->_check_admin_session();
		
		$config = array(
							'settings_key'	=>	'module_mailing_list_getresponse_api_url',
							'settings_value'	=>	'http://api2.getresponse.com',
							'settings_module'	=>	'mailing_list',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'1',
							'settings_function'	=>	'none',
							);
		
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_mailing_list_getresponse_api_key',
							'settings_value'	=>	'',
							'settings_module'	=>	'mailing_list',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'2',
							'settings_function'	=>	'encrypt',
							);
		
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_mailing_list_getresponse_campaign',
							'settings_value'	=>	'',
							'settings_module'	=>	'mailing_list',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'3',
							'settings_function'	=>	'none',
							);
		
		$this->db->insert('settings', $config);
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _run_module_getresponse($type = 'public', $id = '')
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

		require_once(APPPATH.'/libraries/jsonRPCClient.php');
		
		$client = new jsonRPCClient($this->config->item('module_mailing_list_getresponse_api_url'));

		$campaigns = $client->get_campaigns(
			$this->encrypt->decode($this->config->item('module_mailing_list_getresponse_api_key')),
			array (
				# find by name literally
				'name' => array ( 'EQUALS' => $this->config->item('module_mailing_list_getresponse_campaign'))
			)
		);
		$CAMPAIGN_ID = array_pop(array_keys($campaigns));

		$result = $client->add_contact(
										$this->encrypt->decode($this->config->item('module_mailing_list_getresponse_api_key')),
										array (
											'campaign'  => $CAMPAIGN_ID,
											'cycle_day' => '0',
											'ip' => $this->input->ip_address(),
											'name'      => $data['fname'],
											'email'     => $data['primary_email'],
										)
									);
	}
	
	// ------------------------------------------------------------------------	
}
?>