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
| FILENAME - module_data_import_members_model.php
| -------------------------------------------------------------------------     
| 
| This controller file is for functions for importing members
|
*/


class Module_data_import_members_Model extends CI_Model {

	function _install_jrox_module($id = '')
	{	
		//insert required config rows in settings table		
		$config = array(
							'settings_key'	=>	'module_data_import_members_delimiter',
							'settings_value'	=>	'comma',
							'settings_module'	=>	'data_import',
							'settings_type'	=>	'dropdown',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'1',
							'settings_function'	=>	'delimiter',
							);
		
		$this->db->insert('settings', $config);									
							
		//insert required config rows in settings table		
		$config = array(
							'settings_key'	=>	'module_data_import_members_generate_new_ids',
							'settings_value'	=>	'1',
							'settings_module'	=>	'data_import',
							'settings_type'	=>	'dropdown',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'3',
							'settings_function'	=>	'yes_no',					
						);
		
		//insert into settings table
		if ($this->db->insert('settings', $config))
		{
			return true;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _import_data()
	{
		$this->load->helper('file');
		
		if ($this->input->post('enable_file_path') == 'no' && !empty($_FILES))
		{
			
			if ($_FILES['userfile']['error'] == '4')
			{
				return 'error';
				exit();
			}
			
			$config['upload_path'] = './import/members/';
			$config['allowed_types'] = 'txt|csv';
			$config['max_size']	= '0';
			$config['encrypt_name'] = true;
			$config['remove_spaces'] = true;
			
			//upload the photo
			$data = $this->uploads->_upload_file('userfile', $config);
			
			//add photo to db
			if (!empty($data['success']))
			{				
				$file = $data['info'];
		
				$data = read_file('./import/members/' . $file['file_name']);
			}
			else
			{
				echo '<div class="error" id="error-messages">' .  $data['msg'] . '</div>';
				exit();
			}
		}
		else
		{
			$data = read_file('./import/members/' . $this->input->post('file_path'));
		}
		
		$array = explode("\n", $data);
		//echo '<pre>'; print_r($array); exit();
		//SET COLUMN NUMBER
		$ret['total'] = 0;
		$ret['not_added'] = '';
		$separator = $this->config->item('module_data_import_members_delimiter') == 'tab' ? "\t" : ",";
		
		$columns = array();
		
		//INSERT INTO MEMBERS TABLE
		foreach ($array as $value)
		{
			if (empty($value)) { continue; }
			
			$f = explode($separator,$value);
						
			$fields = array();
			
			foreach ($f as $field)
			{
				$a = $this->_string_check($field);
				
				array_push($fields, trim($a));
			}
		
			if ($fields[0] == 'member_id' || $fields[0] == 'sponsor_id')
			{
				
			
				foreach ($fields as $key => $row) //put all the column headers in one array first
				{
					array_push($columns, trim($row)); 
				}
				
				continue;
			}
			else
			{
				$srow = combine_array($columns, $fields);
				
				$primary_email = $this->_check_email($srow['primary_email']);
			
				if ($primary_email == false)
				{
					$ret['not_added'] .= $srow['fname'] . ' ' . $srow['lname'] . ' - ' .$srow['primary_email'] . '<br />';
					continue;
				}
				elseif (empty($srow['fname']) || empty($srow['lname']))
				{
					continue;
				}
				
				$pdata = array(
						'member_id'									=> 			$this->config->item('module_data_import_members_generate_new_ids') == 1 ? '' : $srow['member_id'],
						'sponsor_id'								=>			$this->_check_sponsor_id($srow['sponsor_id']),
						'status'									=>			$srow['status'] == '0' ? '0' : '1', 
						'fname'										=>			empty($srow['fname']) ? '' : $srow['fname'],
						'lname'										=>			empty($srow['lname']) ? '' : $srow['lname'],
						'username'									=>			$this->_check_username($srow['username']),
						'password'									=> 			$this->_check_password($srow['password']),
						'company'									=>			empty($srow['company']) ? '' : $srow['company'],
						'billing_address_1'							=>			empty($srow['billing_address_1']) ? '' : $srow['billing_address_1'],
						'billing_address_2'							=>			empty($srow['billing_address_2']) ? '' : $srow['billing_address_2'],
						'billing_city'								=> 			empty($srow['billing_city']) ? '' : $srow['billing_city'],
						'billing_state'								=>			empty($srow['billing_state']) ? '' : $srow['billing_state'],
						'billing_country'							=>			$this->_check_country($srow['billing_country']),
						'billing_postal_code'						=>			empty($srow['billing_postal_code']) ? '' : $srow['billing_postal_code'],
						'payment_name'								=>			empty($srow['payment_name']) ? '' : $srow['payment_name'],
						'payment_address_1'							=>			empty($srow['payment_address_1']) ? '' : $srow['payment_address_1'],
						'payment_address_2'							=>			empty($srow['payment_address_2']) ? '' : $srow['payment_address_2'],
						'payment_city'								=>			empty($srow['payment_city']) ? '' : $srow['payment_city'],
						'payment_state'								=>			empty($srow['payment_state']) ? '' : $srow['payment_state'],
						'payment_country'							=>			$this->_check_country($srow['payment_country']),
						'payment_postal_code'						=>			empty($srow['payment_postal_code']) ? '' : $srow['payment_postal_code'],
						'home_phone'								=>			empty($srow['home_phone']) ? '' : $srow['home_phone'],
						'work_phone'								=>			empty($srow['work_phone']) ? '' : $srow['work_phone'],
						'mobile_phone'								=>			empty($srow['mobile_phone']) ? '' : $srow['mobile_phone'],
						'fax'										=>			empty($srow['fax']) ? '' : $srow['fax'],
						'payment_preference_amount'					=>			$this->_check_numeric($srow['payment_preference_amount']),
						'primary_email'								=>			$primary_email,
						'paypal_id'									=>			$srow['primary_email'],
						'moneybookers_id'							=>			empty($srow['moneybookers_id']) ? '' : $srow['moneybookers_id'],
						'payza_id'									=>			empty($srow['payza_id']) ? '' : $srow['payza_id'],
						'custom_id'									=>			empty($srow['custom_id']) ? '' : $srow['custom_id'],
						'bank_transfer'								=>			empty($srow['bank_transfer']) ? '' : $srow['bank_transfer'],
						'enable_custom_url'							=>			$srow['enable_custom_url'] == '0' ? '0' : '1', 
						'custom_url_link'							=>			empty($srow['custom_url_link']) ? '' : $srow['custom_url_link'],	
						'website'									=>			empty($srow['website']) ? '' : $srow['website'],
						'program_custom_field_1'					=>			empty($srow['program_custom_field_1']) ? '' : $srow['program_custom_field_1'],	
						'program_custom_field_2'					=>			empty($srow['program_custom_field_2']) ? '' : $srow['program_custom_field_2'],
						'program_custom_field_3'					=>			empty($srow['program_custom_field_3']) ? '' : $srow['program_custom_field_3'],
						'program_custom_field_4'					=>			empty($srow['program_custom_field_4']) ? '' : $srow['program_custom_field_4'],
						'program_custom_field_5'					=>			empty($srow['program_custom_field_5']) ? '' : $srow['program_custom_field_5'],
						'program_custom_field_6'					=>			empty($srow['program_custom_field_6']) ? '' : $srow['program_custom_field_6'],
						'program_custom_field_7'					=>			empty($srow['program_custom_field_7']) ? '' : $srow['program_custom_field_7'],
						'program_custom_field_8'					=>			empty($srow['program_custom_field_8']) ? '' : $srow['program_custom_field_8'],
						'program_custom_field_9'					=>			empty($srow['program_custom_field_9']) ? '' : $srow['program_custom_field_9'],
						'program_custom_field_10'					=>			empty($srow['program_custom_field_10']) ? '' : $srow['program_custom_field_10'],
						'program_custom_field_11'					=>			empty($srow['program_custom_field_11']) ? '' : $srow['program_custom_field_11'],	
						'program_custom_field_12'					=>			empty($srow['program_custom_field_12']) ? '' : $srow['program_custom_field_12'],
						'program_custom_field_13'					=>			empty($srow['program_custom_field_13']) ? '' : $srow['program_custom_field_13'],
						'program_custom_field_14'					=>			empty($srow['program_custom_field_14']) ? '' : $srow['program_custom_field_14'],
						'program_custom_field_15'					=>			empty($srow['program_custom_field_15']) ? '' : $srow['program_custom_field_15'],
						'program_custom_field_16'					=>			empty($srow['program_custom_field_16']) ? '' : $srow['program_custom_field_16'],
						'program_custom_field_17'					=>			empty($srow['program_custom_field_17']) ? '' : $srow['program_custom_field_17'],
						'program_custom_field_18'					=>			empty($srow['program_custom_field_18']) ? '' : $srow['program_custom_field_18'],
						'program_custom_field_19'					=>			empty($srow['program_custom_field_19']) ? '' : $srow['program_custom_field_19'],
						'program_custom_field_20'					=>			empty($srow['program_custom_field_20']) ? '' : $srow['program_custom_field_20'],
						'alert_downline_signup'						=>			$this->_check_global($srow['alert_downline_signup']), //0 1 2
						'alert_new_commission'						=>			$this->_check_global($srow['alert_new_commission']), //0 1 2
						'alert_payment_sent'						=>			$this->_check_global($srow['alert_payment_sent']), //0 1 2
						'allow_downline_view'						=>			$this->_check_global($srow['allow_downline_view']), //0 1 2
						'allow_downline_email'						=>			$this->_check_global($srow['allow_downline_email']), //0 1 2
						'last_login_date'							=>			_generate_timestamp(),
						'last_login_ip'								=>			'',
						'signup_date'								=>			empty($srow['signup_date']) ? _generate_timestamp() : $this->_format_date($srow['signup_date']),
						'updated_on'								=>			_generate_timestamp(),
						'updated_by'								=>			$this->session->userdata('username'),
						'login_status'								=>			'0',
						'confirm_id'								=>			_generate_random_string(5, true),
		
						);
					
				$this->db->insert('members', $pdata);
				
				$id = $this->db->insert_id();				
				
				$list_id = '1';
				if (!empty($srow['mailing_lists'])) 
				{ 
					if (is_numeric($srow['mailing_lists']))
					{
						$list_id = $srow['mailing_lists'];
					}
				} 
				
				//insert into mailing list
				$list_data = array(
									'mailing_list_id'	=>	$list_id,
									'member_id'			=>	$id,
									'sequence_id'		=>	'1',
									'send_date'			=>	_generate_timestamp(),
									);
									
				$this->db->insert('email_mailing_list_members', $list_data);
				
				$group_id = '1';
				if (!empty($srow['group_id'])) 
				{ 
					if (is_numeric($srow['group_id']))
					{
						$group_id = $srow['group_id'];
					}
				} 
				
				//insert groups
				$sql = 'INSERT INTO `jam_members_groups` 
						(`group_id`, `member_id`) 
						VALUES
						(' . $group_id . ', ' . $id . ')';
				
				$query = $this->db->query($sql);
				
				$ret['total']++;
			
			}
		}
		
		@unlink($file['full_path']);
		
		return $ret;
		
	}
	
	// ------------------------------------------------------------------------
	
	function _check_email($id = '')
	{		
		$this->db->where('primary_email',$id);
		$cquery = $this->db->get('members');
		
		if ($cquery->num_rows() > 0)
		{
			$cn = $cquery->row_array();
			
			return false;
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------
	
	function _string_check($id = '')
	{
		$id = str_replace('"', '', $id);
		
		return $id;
	}	
	
	// ------------------------------------------------------------------------
	
	function _check_country($id = '')
	{
		//get the country ID	
		$country_id = '223';
		
		$this->db->where('country_name',$id);
		$cquery = $this->db->get('countries');
		
		if ($cquery->num_rows() > 0)
		{
			$cn = $cquery->row_array();
			
			$country_id = $cn['country_id'];
		}
		
		return $country_id;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_sponsor_id($id = '')
	{
		
		return $id;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_username($id = '')
	{
		
		$this->db->where('username', $id);
		$query = $this->db->get('members');
		
		if ($query->num_rows() > 0)
		{
			return _generate_random_string(6);
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_password($id = '')
	{
		if (empty($id))
		{
			$id = _generate_random_string(6);
		}
		
		switch ($this->config->item('members_password_function'))
		{
			case 'sha1':
			
				$id = sha1($id);
			
			break;
			
			case 'mcrypt':
				
				$id = $this->encrypt->encode($id);
				
			break;
		
			default:
			
				$id = md5($id);
			
			break;
		}

		return $id;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_numeric($id = '')
	{
		if (!is_numeric($id))
		{
			return '0';
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------
	
	
	function _format_date($id = '')
	{
		return strtotime($id);
	}
	
	
	// ------------------------------------------------------------------------
	
	
	function _check_global($id = '')
	{
		if ($id != '0' || $id != '1' || $id != '2')
		{
			return '2';
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------
	
	
	
}
?>