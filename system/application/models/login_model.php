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
| FILENAME - login_model.php
| -------------------------------------------------------------------------     
| 
| This model handles login functions
|
*/

class Login_Model extends CI_Model {
	
	function _check_reset($table = '', $email = '')
	{		
		$this->db->where('primary_email', $email);
		if ($table == 'admin_users')
		{
			$this->db->where('status', 'active');
		}
		else 
		{
			$this->db->where('status', '1');
		}
		
		$query = $this->db->get($table);
	
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			
			$row['new_password'] = random_string('alnum', 8);
			
			
            if ($table == 'admin_users')
			{
				$confirm_id = substr(md5(time() . $row['new_password']),0,10);
				
				$update_data = array ('confirm_id' => $confirm_id);
				
				$row['admin_reset_password_link'] = base_url()  . 'admin_login/reset_confirmation/' . $confirm_id;
			
				$this->db->where('admin_id', $row['admin_id']);
			}
			else 
			{
				switch ($this->config->item('members_password_function'))
				{
					
					case 'mcrypt':
					
						$db_pass = $this->encrypt->encode($row['new_password']);
					
					break;
					
					case 'sha1':
					
						$db_pass = sha1($row['new_password']);
	
					break;
					
					default:
				
						$db_pass = md5($row['new_password']);
					
					break;
				}
				
				$update_data = array ('password' => $db_pass);
						
				$this->db->where('member_id', $row['member_id']);
			}
			
			$this->db->update($table, $update_data); 	
			
			return $row;
		}
		else
		{
			return false;
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _update_admin_pass($post = array())
	{
		$update = array('apassword' => $post['cpass'],
						'confirm_id' => ''
					);
		
		$data = $this->db_validation_model->_clean_data($update);
		
		$this->db->where('confirm_id', $post['confirm_id']);	
		
		if ($this->db->update('admin_users', $data))
		{
			return true;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_reset_confirmation($id = '')
	{
		if (strlen($id) ==	10)
		{
			$this->db->where('confirm_id', $id);
			
			$query = $this->db->get('admin_users');
			
			if ($query->num_rows() == 1)
			{
				return $query->row_array();	
			}
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_reset_access($email = '', $code = '')
	{
		$this->db->where('primary_email', $email);
		$this->db->where('status', 'active');
		
		if (!empty($code))
		{
			$this->db->where('confirm_id', $code);	
		}
		
		$query = $this->db->get('admin_users');
		
		if ($query->num_rows() == 1)
		{
			return $query->row_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_user_pass($user = '')
	{
		
		$this->db->select('*');
		$this->db->from('members');
		$this->db->where('primary_email', $user);
		$this->db->where('status', '1');
		$query = $this->db->get();
		
		if ($query->num_rows() > 0)
		{
			$a = $query->row_array();
			return $a;
		}
		
		return false;
	}	
	
	// ------------------------------------------------------------------------
	
	function _post_to_fb($fb = '', $data)
	{
		if ($this->config->item('sts_site_facebook_post_on_wall') && !empty($fb['fb_login_info']['id']))
		{
			$post = $this->config->item('sts_site_facebook_post_on_wall');
			
			foreach ($data as $k => $v)
			{
				$post = str_replace('{' . $k . '}', $v, $post);	
			}
			
			$post = str_replace('{affiliate_link}', _get_aff_link($data['username']), $post);	

			$fields = '';
			$url = 'https://graph.facebook.com/' . $fb['fb_login_info']['id'] . '/feed/';
			
			$vars = array('access_token' => $fb['access_token'],
						  'message' => $post
						 );
			foreach( $vars as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";
			
			$resp = connect_curl($url, true, $fields, 1,  $this->config->item('sts_site_set_curl_timeout'));
		}	
	}
	
	// ------------------------------------------------------------------------
	
	function _check_admin_login($user = '', $pass = '')
	{
		if (!empty($user) && !empty($pass))
		{	
			$user = $this->encrypt->decode($user, md5($this->config->item('encryption_key')));
			$pass = $this->encrypt->decode($pass, md5($this->config->item('encryption_key')));
			
			if (!empty($user) && !empty($pass))
			{
				$sql = 'SELECT * FROM ' . $this->db->dbprefix('members') . 
					   ' WHERE username = \'' . $user . '\' AND password = \'' . $pass . '\'';
				
				$query = $this->db->query($sql); 
				
				if ($query->num_rows() > 0)
				{
					$row = $query->row_array();
					if (!empty($row['last_login_date']))
					{
						//format date
						$format = explode(':', $this->config->item('sts_admin_date_format'));
						
						$row['last_login_date_time'] = date($this->config->item('sts_admin_time_format'), $row['last_login_date']);
						$row['last_login_date'] = date($format[2], $row['last_login_date']);
					}
					
					//run session setup
					$this->_set_session('members', $row);
					return true;
				}
			}
			
		}
		return false;
	} 
	
	// ------------------------------------------------------------------------
	
	function _check_program_access($mid = '', $pid = '')
	{
		$this->db->where('member_id', $mid);
		$this->db->where('program_id', $pid);
		$query = $this->db->get('members_programs');
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			
			return $row;			
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_user($table = '', $username = '', $encrypt = false, $automation = false, $column = 'primary_email')
	{			
		if ($table == 'admin_users')
		{
			$p = $this->config->item('admin_login_password_field');
			
			$this->db->where('username', $username);
			$this->db->where('apassword', md5($this->validation->$p));
			$this->db->where('status', 'active');
			
			//run query
			
			$query = $this->db->get($table);
		
			if ($query->num_rows() == 1)
			{
				$row = $query->row_array();
			}
		}
		else
		{
			if ($this->config->item('members_password_function') == 'mcrypt')
			{
				//get the password
				$user = $this->_get_user_pass($username);
				
				if (!empty($user))
				{
					$db_pass = $this->encrypt->decode($user['password']);
					//echo $db_pass . ' = '.  $this->validation->password; exit();
					if ($db_pass == $this->validation->password)
					{
						$row = $user;
					}
				}
			}
			else
			{			
				$this->db->where($column, strtolower($username));

				if ($automation == false)
				{
					if ($encrypt == true)
					{
						$this->db->where('password', $this->validation->password);
					}
					else
					{
						switch ($this->config->item('members_password_function'))
						{
							
							case 'custom':
								
								//run custom password encoding function
								$this->load->helper('custom_password');
				
								$this->db->where('password', _run_custom_password_encrypt($this->validation->password));
						
							break;
							
							case 'sha1':
							
								$this->db->where('password', sha1($this->validation->password));
			
							break;
							
							default:
						
								$this->db->where('password', md5($this->validation->password));	
							
							break;
						}
					}
				}
				
				$this->db->where('status', '1');
				
				//run query
				$query = $this->db->get($table);
				
				if ($query->num_rows() > 0)
				{
					$row = $query->row_array();
				}
			}

		}
		
		if (!empty($row)) //check if the user record exists
		{
			
			//update last login and IP address

			$update_data = array ('last_login_date' => _generate_timestamp(),
               				      'last_login_ip' => $_SERVER['REMOTE_ADDR']
            );
			
            if ($table == 'admin_users')
			{
				
				$this->db->where('admin_id', $row['admin_id']);
				$this->db->update('admin_users', $update_data); 			
			}
			else 
			{
				$this->db->where('member_id', $row['member_id']);
				$this->db->update('members', $update_data); 		
			}
			
			if (!empty($row['last_login_date']))
			{
				//format date
				$format = explode(':', $this->config->item('sts_admin_date_format'));
				
				$row['last_login_date_time'] = date($this->config->item('sts_admin_time_format'), $row['last_login_date']);
				$row['last_login_date'] = date($format[2], $row['last_login_date']);
			}
			
			return $row;
		}
		else //if no user exists
		{
			return false;
		}
		
	}
	
	// ------------------------------------------------------------------------
	
	function _update_last_login_info($mid = '')
	{
		$update_data = array ('last_login_date' => _generate_timestamp(),
               				  'last_login_ip' => $_SERVER['REMOTE_ADDR']
							 );
		
		$this->db->where('member_id', $mid);
		$this->db->update('members', $update_data); 		
	}
	
	// ------------------------------------------------------------------------
	
	function _check_email_confirmation($id = '')
	{
		//checks the email confirmation link for validity
		$data = explode('-', $id);
		
		if (count($data) == 2)
		{
			$this->db->where('member_id', $data[0]);
			$this->db->where('confirm_id', $data[1]);
			
			$query = $this->db->get('members');
	
			if ($query->num_rows() == 1)
			{
				//update the account and confirm
				$array = array('login_status' => '0');
				$this->db->where('member_id', $data[0]);
				$this->db->update('members', $array);
				
				return $data[0];	
			}
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_program_confirmation($id = '')
	{
		//checks the email confirmation link for validity
		$data = explode('-', $id);
		
		if (count($data) == 3)
		{
			$this->db->where('program_id', $data[0]);
			$this->db->where('member_id', $data[1]);
			
			$query = $this->db->get('members_programs');
	
			if ($query->num_rows() == 1)
			{
				//update the account and confirm
				$row = $query->row_array();
				
				if (empty($row['confirm_id']))
				{
					return $row;
				}
				
				$array = array('confirm_id' => '');
				$this->db->where('program_id', $data[0]);
				$this->db->where('member_id', $data[1]);
				$this->db->update('members_programs', $array);
				
				return $data[0];	
			}
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _verify_reset_access($code = '')
	{
		$code = substr($code, 0, 25);
		$this->db->where('confirm_id', $code );
		$this->db->where('status', 'active');
		
		$query = $this->db->get('admin_users');
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			
			//update SSL and IP restriction
			$array = array('sts_site_ssl_admin_area' => 0,
					'sts_sec_admin_restrict_ip' => '',
					'sts_sec_enable_admin_restrict_ip' => '0',
			);
			
			$this->db_validation_model->_update_db_settings($array);
			
			$this->db->where('admin_id', $row['admin_id']);
			$this->db->update('admin_users', array('confirm_id' => ''));
			
			return true;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_automation()
	{
		if ($this->config->item('sts_auto_login_key') ==  $this->input->post('access_key') && $this->config->item('sts_auto_login_secret') == $this->input->post('access_id'))
		{
			$this->lang->load('common', $this->config->item('sts_site_default_language'));
			return true;
		}
		
		die('ERROR: invalid api access');																	 
	}
	
	// ------------------------------------------------------------------------
	
	function _set_session($type = '', $row = '', $return = false)
	{
		/*
		| -------------------------------------------------------------------------
		| set the session variables up
		| -------------------------------------------------------------------------
		*/

		//set the session data			
		if ($type == 'admin')
		{
			$login_data = array('adminid' 		=> 	$row['admin_id'],
								'username'  	=> 	$row['username'],
								'email'     	=> 	$row['primary_email'],
								'll_date'		=>	$row['last_login_date'],
								'll_ip'			=>	$row['last_login_ip'],
								'll_type'		=> 'admin',
								'perms'			=> $row['permissions'],
								'per_page'		=> $row['rows_per_page'],
								'fname'			=> $row['fname'],
								'lname'			=> $row['lname'],
								'admin_photo' 	=> $row['admin_photo'],
			);
		
			if (!empty($row['login_language']))
			{
				$login_data['sess_admin_lang'] = $row['login_language'];	
			}
		}
		else 
		{
			
			//get user groups 
			$affiliate_group = '1';
			
			$this->db->where('member_id', $row['member_id']);
			$query = $this->db->get('members_groups');
			
			if ($query->num_rows() > 0)
			{
				$grow = $query->row_array();
				
				$affiliate_group = $grow['group_id'];
			}
			
			//get programs
			$programs = array();
			$query = $this->db->get('programs');
			if ($query->num_rows() > 0)
			{
				foreach ($query->result_array() as $k => $v)
				{
					array_push($programs, $v['program_id']);
				}
			}
			
			//get restricted programs
			$restricted = array();
			
			$this->db->where('member_id', $row['member_id']);
			$query = $this->db->get('members_programs');
			
			if ($query->num_rows() > 0)
			{
				foreach ($query->result_array() as $k => $v)
				{
					array_push($restricted, $v['program_id']);
				}
			}
			
			$mems = array();
			foreach ($programs as $k => $v)
			{
				if (in_array($v, $restricted))
				{
					unset ($programs[$k]);	
				}
			}
			
			//set membership cookies 
			set_membership_cookies($programs);
			
			$last_login_date = $row['last_login_date'] == '' ? $this->lang->line('none') : $row['last_login_date'];
			$last_login_ip = $row['last_login_ip'] == '' ? $this->lang->line('none') : $row['last_login_ip'];
			
			foreach ($row as $k => $v)
			{
				if ($k != 'password')
				{
					$login_data['m_' . $k] = $v;	
				}
			}
			
			//set the program data
			$login_data['userid'] = $row['member_id'];
			$login_data['m_email'] = $row['primary_email'];
			$login_data['m_ll_date'] = $last_login_date;
			$login_data['m_ll_ip'] = $last_login_ip;
			$login_data['m_ll_type'] = 'member';
			$login_data['m_view_hidden_programs'] = $row['view_hidden_programs'];
			$login_data['m_programs'] = $programs;
			$login_data['m_affiliate_group'] = $affiliate_group;
			$login_data['m_program_id'] = $this->config->item('prg_program_id');
			$login_data['m_program_name'] = $this->config->item('prg_program_name');
			$login_data['m_signup_link'] = $this->config->item('prg_signup_link');
			
			//assign the sponsor data
			if (!empty($row['sponsor_id']))
			{
				$aff_data = $this->aff->_validate_user($row['sponsor_id'], true);
				
				if (!empty($aff_data))
				{
					foreach ($aff_data as $k => $v)
					{
						if ($k != 'password')
						{
							$login_data['m_sponsor_' . $k] = $v;
						}
					}
				}
			}
			else
			{
				foreach ($row as $k => $v)
				{
					if ($k != 'password')
					{
						$login_data['m_sponsor_' . $k] = '';
					}	
				}
			}
		}
		
		if ($return == true)
		{
			return $login_data;
		}
		
		if ($this->session->set_userdata($login_data))
		{
			return true;
		}
		
		return  false;
	}
	
	// ------------------------------------------------------------------------
}
?>