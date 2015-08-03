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
| FILENAME - auto_login.php
| -------------------------------------------------------------------------     
| 
| This file controls auto login for JAM
|
*/
class Automate extends Main_Controller {
	
	function __construct()
	{
		parent::Main_Controller();	
		
		//load css body style
		$this->config->set_item('css_body', 'jroxHome');
		
		$this->load->library('session');				
		$this->session->sess_run('public');

		$this->load->library('user_agent');
		
		//load required models
		$this->load->model('downline_model', 'downline');
		$this->load->model('commissions_model', 'comm');
		$this->load->model('affiliate_marketing_model', 'aff');
		$this->load->model('login_model', 'login');
		$this->load->model('members_model', 'members');
		$this->load->model('init_model', 'init');	
		$this->load->model('groups_model', 'groups');
		$this->load->model('programs_model', 'programs');
		
	}
	
	// ------------------------------------------------------------------------
	
	function update_group()
	{
		$this->login->_check_automation();	
		
		if (!empty($_POST['id']) && !empty($_POST['field']) && !empty($_POST['group_id']))
		{
			$user = $this->db_validation_model->_get_details('members', '*',  $this->input->post('field', true),  $this->input->post('id', true));	
			
			if (!empty($user)) 
			{
				if ($this->groups->_get_aff_group_details((int)$_POST['group_id']))
				{
					if ($this->groups->_update_member_group($user[0]['member_id'], (int)$_POST['group_id']))
					{
						die(_encode_return('SUCCESS: user updated successfully',1 ));
					}	
				}
			}
		}
		
		die(_encode_return('ERROR: user group not updated',0 ));
	}	
	
	// ------------------------------------------------------------------------
	
	function register()
	{	
		
		$this->login->_check_automation();
		
		if (!empty($_POST['email']))
		{
			$user = $this->db_validation_model->_get_details('members', 'primary_email', 'primary_email', $this->input->post('email', true));	
			
			if (!empty($user)) { 
				die(_encode_return('ERROR: ' . $this->input->post('email', true) . ' already registered',0 ));
			}
			
			$program_id = empty($_POST['program_id']) ? '1' : $_POST['program_id'];
			$username = !empty($_POST['username']) ? $this->members->_check_username(strtolower(trim($_POST['username']))) : _generate_random_username($_POST['fname'], _generate_random_string('3'));
			$password = empty($_POST['password']) ? _generate_random_string('6') : $_POST['password'];
			$affiliate_group = empty($_POST['affiliate_group']) ? '1' : (int)$_POST['affiliate_group'];
			
			//get program data
		
			$prog = $this->programs_model->_get_program_basic('program_id', $program_id);
			
			if (empty($prog)) 
			{ 
				die(_encode_return('ERROR: invalid program',0 ));
			}
			
			$this->init->_set_sale_program($prog);
			
			//check sponsor
			$sponsor = '0';
			if (!empty($_POST['sponsor']))
			{
				$sp_data = explode('-', $_POST['sponsor']);
				if (!empty($sp_data))
				{
					$aff_data = $this->aff->_validate_user($sp_data[2]);
					if (!empty($aff_data)) { $sponsor = $aff_data['username']; }
				}
			}
			elseif (!empty($_POST['sponsor_username']))
			{
				$sponsor = $_POST['sponsor_username'];		
			}
			 
			$sdata = array(
							'username' 				=>	$username,
							'sponsor'				=> $sponsor,
							'primary_email' 		=>	strtolower(trim($_POST['email'])),
							'password'				=>	$password,
							'encrypted'				=> 	!empty($_POST['encrypted']) ? true : '',
							'status'				=>	$this->input->post('status') == 'inactive' ? '0' : '1',
							'login_status'			=> 	$this->input->post('login_status') == 'unconfirmed' ? '1' : '0',
							'fname' 				=> 	empty($_POST['fname']) ? 'fname' : $_POST['fname'],
							'lname' 				=> 	$this->input->post('lname'),
							'billing_address_1' 	=> 	$this->input->post('billing_address_1'),
							'billing_address_2' 	=> 	$this->input->post('billing_address_2'),
							'billing_city' 			=> 	$this->input->post('billing_city'),
							'billing_state' 		=> 	$this->input->post('billing_state'),
							'billing_country' 		=> 	$this->input->post('billing_country'),
							'billing_postal_code' 	=> 	$this->input->post('billing_postal_code'),
						    'affiliate_groups'		=>	$affiliate_group,
						   );
		
			//check for a program ID
			if (!empty($_POST['program_id']))
			{
				$sdata['programs'] = array($_POST['program_id']);	
			}
			
			//add to default mailing list
			$list_id = !empty($_POST['list_id']) ? (int)$_POST['list_id'] : '1';
			$sdata['email_mailing_lists'] = array($list_id);
			
			$userdata = $this->members->_add_member($sdata, 'autoregister');	
			
			//setup per program or default values 
			$redirect_enable = $this->config->item('sts_site_enable_custom_signup');
			$redirect_url = $this->config->item('sts_site_url_redirect_signup');
			
			if ($this->config->item('sts_site_showcase_multiple_programs') == 1)
			{
				$userdata['program_id'] = '1';
				
				$usr_enable = $this->config->item('sts_affiliate_enable_signup_bonus');
				$userdata['aff_bonus'] = $this->config->item('sts_affiliate_enable_signup_bonus_amount');
				
				$ref_enable = $this->config->item('sts_affiliate_enable_referral_signup_bonus');
				$userdata['ref_bonus'] = $this->config->item('sts_affiliate_enable_referral_signup_bonus_amount');
			}
			else
			{
				$userdata['program_id'] = $program_id;
				
				$usr_enable = $this->config->item('prg_enable_affiliate_signup_bonus');
				$userdata['aff_bonus'] = $this->config->item('prg_affiliate_signup_bonus_amount');
				
				$ref_enable = $this->config->item('prg_enable_referral_bonus');
				$userdata['ref_bonus'] = $this->config->item('prg_referral_bonus_amount');
			}
			
			if ($usr_enable == 1)
			{
				$this->comm->_add_affiliate_bonus($userdata);
			}
			
			if ($ref_enable == 1)
			{
				$this->comm->_add_referral_bonus($userdata);	
			}
			
			//now let's send email alerts for new signups
			$this->load->model('emailing_model');
			
			//send out alert email if set
			if ($this->config->item('sts_affiliate_alert_downline_signup') == '1' && !empty($userdata['sponsor_id']))
			{
				$sponsor_info = $this->members->_get_member_basic($userdata['sponsor_id']);
				
				if (!empty($sponsor_info))
				{
					if ($sponsor_info['alert_downline_signup'] != '0')  //send out the email only if the user wants it
					{
						$sponsor_info['downline_name'] = $userdata['fname'] . ' ' . $userdata['lname'];
						$sponsor_info['downline_email'] = $userdata['primary_email'];
						foreach ($userdata as $k => $v)
						{
							$sponsor_info['downline_' . $k] = $v;	
						}
						
						$this->emailing_model->_send_template_email('member', $sponsor_info, 'member_affiliate_downline_signup', false, $userdata['program_id']);
					}
				}
			}
			
			//get all admins for sending out admin alerts
			$this->load->model('admins_model');
			$admin_users = $this->admins_model->_get_all_admins();
			
			//send out new order alerts to admins
			foreach ($admin_users as $admin_user)
			{
				if ($admin_user['alert_affiliate_signup'] == 1) //send out the admin alert
				{
					foreach ($userdata as $key => $value)
					{				
						if ($key == 'sponsor_data') { continue; }
						$admin_user['member_' . $key] = $value;
					}
					
					$admin_user['member_name'] = $userdata['fname'] . ' ' . $userdata['lname'];
					$admin_user['member_username'] = $userdata['username'];
					$admin_user['signup_ip'] = $this->input->ip_address();
					
					$this->emailing_model->_send_template_email('admin', $admin_user, 'admin_alert_new_signup_template', false, $userdata['program_id']);
				}
			}
			
			//add to mailing list if we don't need to confirm
			if ($userdata['login_status'] != 1 && !empty($affiliate_group))
			{
				//check the mailing list based on the affiliate group
				$this->load->model('groups_model');
				
				$list_group = $this->groups_model->_get_list_group((int)$affiliate_group, true);
				
				$this->members->_update_member_list('add', $userdata['member_id'], $list_group);
				
				//run post registration modules
				$this->_run_member_modules('account_add', $userdata);
			}
			
			//now send the welcome email	
			if (!empty($_POST['send_registration_email']))
			{
				$this->load->model('emailing_model');
				
				$userdata['password'] = $this->input->post('password', true);			
				$this->emailing_model->_send_template_email('member', $userdata, 'member_login_details_template', false, $userdata['program_id']);
			}
			
			die(_encode_return('SUCCESS: User added successfully',1 ));
		}
	}
	
	// ------------------------------------------------------------------------
	
	function delete()
	{	
		$this->login->_check_automation();
		
		if (!empty($_POST['email']))
		{
			if ($this->members->_delete_member(strtolower(trim($_POST['email'])), 'primary_email'))
			{
				die(_encode_return('SUCCESS: User deleted successfully', 1 ));
			}
		}
		
		die(_encode_return('ERROR: user not deleted',0 ));
	}
	
	// ------------------------------------------------------------------------
	
	function deactivate()
	{	
		$this->login->_check_automation();
		
		if (!empty($_POST['email']))
		{
			if ($this->members->_set_user_status(strtolower(trim($_POST['email'])), '0'))
			{
				die(_encode_return('SUCCESS: User deactivated successfully', 1 ));
			}
		}
		
		die(_encode_return('ERROR:  user not deactivated', 0 ));
	}
	
	// ------------------------------------------------------------------------
	
	function activate()
	{	
		$this->login->_check_automation();
		
		if (!empty($_POST['email']))
		{
			if ($this->members->_set_user_status(strtolower(trim($_POST['email'])), '1'))
			{
				die(_encode_return('SUCCESS: User activated successfully', 1 ));
			}
		}
		
		die(_encode_return('ERROR:  user not activated', 0 ));
	}
	
	// ------------------------------------------------------------------------
	
	function logout()
	{	
		$this->login->_check_automation();
		
		if (!empty($_POST['session']))
		{
			$this->db->where('session_id', $_POST['session']);
			if ($this->db->delete('sessions'))
			{
				die(_encode_return('SUCCESS: User logged out successfully', 1 ));
			}
		}
		
		die(_encode_return('ERROR:  no logout session found', 0 ));
	}
	
	// ------------------------------------------------------------------------
	
	function set_tracking_cookie()
	{
		$this->login->_check_automation();
		
		if ($this->input->post('subdomain'))
		{
			$sub = $this->input->post('subdomain', true);
		
			if ($this->aff->_check_subdomain($sub)) 
			{	
				$aff_data = $this->aff->_validate_user($sub);
			}
		}
		elseif ($this->input->post('mid'))
		{
			$aff_data = $this->aff->_validate_user($this->input->post('mid'), true);	
		}
		
		if (!empty($aff_data))
		{
			//set tracking cookie
			$pid = empty($_POST['program_id']) ? '1' : (int)$_POST['program_id'];
			$prog = $this->programs->_get_program_basic('program_id', $pid);
			
			$code = $this->aff->_generate_traffic_code();
			$cid = $aff_data['mid'] . '-' . $pid . '-' . $aff_data['username'] .  '-'. $code;
			
			//insert the tracking data
			$sdata = array(	'date'	=> _generate_timestamp(),
							'member_id'	=> $aff_data['mid'],
							'program_id' => $pid,
							'tracking_code' => $code,
							'tool_type'	=>	'dynamic',
							'tool_id'	=>	0,
							'referrer'	=>	$this->agent->referrer(),
							'ip_address'	=>	$this->input->post('ip'),
							'user_agent'	=>	$this->input->post('user_agent'),
							'os'			=>	$this->agent->platform(),
							'browser'		=>	$this->agent->browser(),
							'isp'			=> @gethostbyaddr($this->input->post('ip')),
							);
			$this->aff->_insert_affiliate_traffic($sdata);
		
		
			$tracking_cookie = array('name'   => $this->config->item('aff_cookie_name'),
									 'value'  => $cid,
									 'expire' => _generate_timestamp() + (60 * 60 * 24 * $this->config->item('sts_affiliate_cookie_timer')),
									 'path'	=> $this->config->item('cookie_path'), 
									 'domain'	=> !empty($prog['remote_domain_name']) ? $prog['remote_domain_name'] :  $this->config->item('cookie_domain'), 
								   );
			
			
			die (_encode_return($tracking_cookie, 1 ));			
		}	
		
		die(_encode_return('ERROR:  tracking not set', 0 ));
	}
	
	// ------------------------------------------------------------------------
	
	function login()
	{	
		$this->login->_check_automation();
		
		//check for post data
		if (!empty($_POST['email']))
		{
			//check login	
			$this->validation->username = strtolower(trim($_POST['email']));
			
			if (!empty($_POST['password']))
			{
				$this->validation->password = $_POST['password'];
			}
			
			$encrypted = !empty($_POST['encrypted']) ?  true : false;
			$automation = !empty($_POST['bypass_pwd']) ? true : false;
			$use_username = !empty($_POST['use_username']) ? 'username' : 'primary_email';
			
			$row = $this->login->_check_user('members', $this->validation->username, $encrypted, $automation, $use_username);	
			
			if (!empty($row))
			{
				//run session setup
				$login_data = $this->login->_set_session('members', $row, true);
				
				$sess_data = serialize($login_data);
				
				$sessid = '';
				while (strlen($sessid) < 32) 
				{    
					$sessid .= mt_rand(0, mt_getrandmax());
				}
				 
				$session = md5(uniqid($sessid, TRUE));
				
				$now = _generate_timestamp();
				
				$this->userdata = array(
									'session_id' 	=> $session,
									'ip_address' 	=> !empty($_POST['ip']) ? $_POST['ip'] : $this->input->ip_address(),
									'user_agent' 	=> substr($_POST['user_agent'], 0, 50),
									'last_activity'	=>  $now,
									'session_data'	=>	$sess_data,
									);
				
				$this->db->query($this->db->insert_string('sessions', $this->userdata));
				
				$this->sess_cookie = $this->config->item('cookie_prefix').$this->config->item('sess_cookie_name');
		
				$autologin_cookie = array(
										  'name' => $this->sess_cookie,
										  'value' => $session,
										  'length' => $this->config->item('sess_expiration_pub') + $now,
										  'path'	=> $this->config->item('cookie_path'), 
										  'domain'	=> $this->config->item('cookie_domain'), 
										  );	
			
				 die(_encode_return($autologin_cookie, 1 ));			
			}	
		
			die(_encode_return('ERROR:  cookie not set', 0 ));	
			
		}
	}
	
	// ------------------------------------------------------------------------
	
	function delete_commission()
	{
		$this->login->_check_automation();
		
		if (!empty($_POST['trans_id']))
		{
			if ($this->input->post('refund_type') == 'delete')
			{
				$this->comm->_delete_commission($this->input->post('trans_id'), 'trans_id');	
				
			}
		}
		
		die(_encode_return('ERROR: no commission deleted', 0 ));
	}
	
	// ------------------------------------------------------------------------
	
	function update_commission()
	{
		$this->login->_check_automation();		
		
		if (!empty($_POST['trans_id']) && !empty($_POST['key']) && !empty($_POST['status']))
		{
			if ($this->comm->_change_trans_status($this->input->post('key'), $this->input->post('trans_id'), $this->input->post('status')))
			{
				die(_encode_return('SUCCESS: commission updated successfully', 1 ));	
			}
		}
		
		die(_encode_return('ERROR: commission not updated', 0 ));
	}
	
	// ------------------------------------------------------------------------
	
	function update_program()
	{
		$this->login->_check_automation();
		
		if (!empty($_POST['id']) && !empty($_POST['field']) && !empty($_POST['program_id']))
		{
			$user = $this->db_validation_model->_get_details('members', '*',  $this->input->post('field', true),  $this->input->post('id', true));	
			
			if (!empty($user)) 
			{
				if ($this->programs->_get_program_basic('program_id', (int)$_POST['program_id']))
				{
					if ($this->members->_update_member_program($user[0]['member_id'], (int)$_POST['program_id']))
					{
						die(_encode_return('SUCCESS: user program updated successfully', 1 ));
					}
				}
			}
		}
		
		die(_encode_return('ERROR: user program not updated', 0 ));
	}
	
	// ------------------------------------------------------------------------	
	
	function _run_member_modules($type = '', $data = '')
	{
		//load members model
		$this->load->model('modules_model');
		
		//run modules
		$sdata = $this->modules_model->_run_modules($type, 'public');
		
		if (!empty($sdata))
		{
			//load the api config
			$this->config->load('api');
			
			//load models
			if ($this->config->item('module_api_' . $type . '_models'))
			{
				$api_models = explode(',', $this->config->item('module_api_' . $type . '_models'));
				
				foreach ($api_models as $api)
				{
					$this->load->model(trim($api . '_model'), $api . '_api');
				}
			}
			
			//load helpers
			if ($this->config->item('module_api_' . $type . '_helpers'))
			{
				$helpers = explode(',', $this->config->item('module_api_' . $type . '_helpers'));
				
				foreach ($helpers as $helper)
				{
					$this->load->helper(trim($helper));
				}
			}
			
			for ($i=0; $i<count($sdata); $i++)
			{
				
				$module_model = 'modules/module_' . $sdata[$i]['module_type'] . '_' . $sdata[$i]['module_file_name'] . '_model';
				
				$this->load->model($module_model, $sdata[$i]['module_file_name']);
				
				$function = '_run_module_' . $sdata[$i]['module_file_name'];
				
				$this->$sdata[$i]['module_file_name']->$function('public', $data);	
			}
		}
	}
}
?>