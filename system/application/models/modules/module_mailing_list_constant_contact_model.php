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
| FILENAME - module_mailing_list_constant_contact_model.php
| -------------------------------------------------------------------------     
| 
|
*/

require_once(APPPATH.'/libraries/constant_contact/Ctct/autoload.php');
		
use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Exceptions\CtctException;
		
class Module_Mailing_List_Constant_Contact_Model extends CI_Model {	
	
	
	function _install_jrox_module($id = '')
	{	
		//check admin session
		$this->security_model->_check_admin_session();
		
		$config = array(
							'settings_key'	=>	'module_mailing_list_constant_contact_api_url',
							'settings_value'	=>	'http://api2.constant_contact.com',
							'settings_module'	=>	'mailing_list',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'1',
							'settings_function'	=>	'none',
							);
		
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_mailing_list_constant_contact_api_key',
							'settings_value'	=>	'',
							'settings_module'	=>	'mailing_list',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'2',
							'settings_function'	=>	'encrypt',
							);
		
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_mailing_list_constant_contact_access_token',
							'settings_value'	=>	'',
							'settings_module'	=>	'mailing_list',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'3',
							'settings_function'	=>	'encrypt',
							);
		
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_mailing_list_constant_contact_list_id',
							'settings_value'	=>	'',
							'settings_module'	=>	'mailing_list',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'4',
							'settings_function'	=>	'none',
							);
		
		$this->db->insert('settings', $config);
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _run_module_constant_contact($type = 'public', $id = '')
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
		
		$api_key = $this->encrypt->decode($this->config->item('module_mailing_list_constant_contact_api_key'));
		$access_token = $this->encrypt->decode($this->config->item('module_mailing_list_constant_contact_access_token'));
		
		$cc = new ConstantContact($api_key);
		
		$list_id = $this->config->item('module_mailing_list_constant_contact_list_id');
		
		if (empty($list_id))
		{
			try{
			$lists = $cc->getLists($access_token);
			} catch (CtctException $ex) {
				foreach ($ex->getErrors() as $error) {
					print_r($error);
				}     
			}
			
			$list_id = $lists[0]->id;
			
			//update the database list ID
			$this->db_validation_model->_update_db_settings(array( 'module_mailing_list_constant_contact_list_id' => $list_id));
		}
		
		$response = $cc->getContactByEmail($access_token, $data['primary_email']);

        // create a new contact if one does not exist
        if (empty($response->results)) {
            $action = "Creating Contact";

            $contact = new Contact();
            $contact->addEmail($data['primary_email']);
            $contact->addList($list_id);
            $contact->first_name = $data['fname'];
            $contact->last_name = $data['lname'];
            $returnContact = $cc->addContact($access_token, $contact); 

        // update the existing contact if address already existed
        } else {            
            $action = "Updating Contact";

            $contact = $response->results[0];
            $contact->addList($list_id);
            $contact->first_name = $data['fname'];
            $contact->last_name = $data['lname'];
            $returnContact = $cc->updateContact($access_token, $contact);  
        }
		
		
	}
	
	// ------------------------------------------------------------------------	
}
?>