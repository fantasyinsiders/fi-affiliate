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
| FILENAME - module_mailing_list_aweber_model.php
| -------------------------------------------------------------------------     
| 
|
*/

class Module_Mailing_List_Aweber_Model extends CI_Model {	
	
	function _install_jrox_module($id = '')
	{	
		//check admin session
		$this->security_model->_check_admin_session();
		
		$config = array(
							'settings_key'	=>	'module_mailing_list_aweber_app_id',
							'settings_value'	=>	'',
							'settings_module'	=>	'mailing_list',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'1',
							'settings_function'	=>	'none',
							);
		
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_mailing_list_aweber_authorization_code',
							'settings_value'	=>	'',
							'settings_module'	=>	'mailing_list',
							'settings_type'	=>	'textarea',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'2',
							'settings_function'	=>	'none',
							);
		
		$this->db->insert('settings', $config);
		
		
		$config = array(
							'settings_key'	=>	'module_mailing_list_aweber_list_id',
							'settings_value'	=>	'',
							'settings_module'	=>	'mailing_list',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'3',
							'settings_function'	=>	'none',
							);
		
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_mailing_list_aweber_consumer_key',
							'settings_value'	=>	'',
							'settings_module'	=>	'mailing_list',
							'settings_type'	=>	'hidden',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'4',
							'settings_function'	=>	'none',
							);
		
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_mailing_list_aweber_consumer_secret',
							'settings_value'	=>	'',
							'settings_module'	=>	'mailing_list',
							'settings_type'	=>	'hidden',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'5',
							'settings_function'	=>	'none',
							);
		
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_mailing_list_aweber_access_key',
							'settings_value'	=>	'',
							'settings_module'	=>	'mailing_list',
							'settings_type'	=>	'hidden',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'6',
							'settings_function'	=>	'none',
							);
		
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_mailing_list_aweber_access_secret',
							'settings_value'	=>	'',
							'settings_module'	=>	'mailing_list',
							'settings_type'	=>	'hidden',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'7',
							'settings_function'	=>	'none',
							);
		
		$this->db->insert('settings', $config);
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _run_module_aweber($type = 'public', $id = '')
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
		require_once(APPPATH . 'libraries/aweber_api/aweber_api.php');
		
		
		if ($this->config->item('module_mailing_list_aweber_authorization_code'))
		{
			if ($this->config->item('module_mailing_list_aweber_access_key'))
			{			
				$this->application = new AWeberAPI($this->config->item('module_mailing_list_aweber_consumer_key'), $this->config->item('module_mailing_list_aweber_consumer_secret'));
				$this->account = $this->application->getAccount($this->config->item('module_mailing_list_aweber_access_key'),  $this->config->item('module_mailing_list_aweber_access_secret'));

				try {
					$foundLists = $this->account->lists->find(array('name' => $this->config->item('module_mailing_list_aweber_list_id')));
	
					try {					
						$listUrl = "/accounts/{$this->account->id}/lists/" . $foundLists[0]->id;
		
						$list = $this->account->loadFromUrl($listUrl);
						
						$subscriber = array(
							'email' => $data['primary_email'],
							'name'  => $data['fname'],
						);
		
						$newSubscriber = $list->subscribers->create($subscriber);
					}
			
					catch(Exception $exc) {
					  // print $exc; exit();
					}
						
					}
		
				catch(Exception $exc) {
					//print $exc;
				}
			
				
			}
		}
	}
	
	// ------------------------------------------------------------------------	
}
?>