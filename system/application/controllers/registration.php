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
| FILENAME - home.php
| -------------------------------------------------------------------------     
| 
| This controller file is used for default home page
|
*/


class Registration extends Public_Controller {

	function __construct()
	{
		parent::__construct();
		//load css body style
		$this->config->set_item('css_body', 'jroxHome');
		
		//load required models
		$this->load->model('init_model', 'init');	
		$this->load->model('forms_model', 'forms');
		$this->load->model('groups_model');

		
		$this->load->helper('captcha');
		$this->load->helper('country');
		
		//get countries
		$this->country_options = _load_countries_dropdown($countries = _load_countries_array(true));
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{	
	
		$this->init->_set_default_program($this->uri->segment(2));
		
		//set data array
		$data = $this->security_model->_load_config('public', __CLASS__,  __FUNCTION__);
		
		if (!empty($data['sts_auto_login_redirect_registration'])) redirect_301($data['sts_auto_login_redirect_registration'], true);
		
		//initialize site with required db config
		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		//set up the languages array
		$data['languages'] = $sdata['languages'];
		
		//set referral info
		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		//run facebook config
		$fb = $this->security_model->_check_fb_connect(false, 'fb_redir.php');
		
		if (!empty($fb))
		{
			foreach ($fb as $key => $val)
			{
				$data[$key] = $val;
			} 
		}
		
		$this->show_message = '';
		$this->validation->sponsor = ''; 
		$this->validation->sponsor_error = '';
		//$this->validation->recaptcha_response_field_error = '';
		
		$data['form_fields'] = array();
		$this->form_fields = $this->forms->_get_registration_fields($data['prg_program_id']);
		
		$this->validation->set_error_delimiters('<label class="error">', '</label>');
		
		//run admin validation check
		if ($this->_check_registration_data() == false)
		{	
			$s_billing_country = !$this->input->post('billing_country') ?  $data['sts_site_default_country'] : $this->input->post('billing_country');
			$s_payment_country = !$this->input->post('payment_country') ?  $data['sts_site_default_country'] : $this->input->post('payment_country');
			
			//set the default countries
			foreach ($this->form_fields as $k => $v)
			{
				$str = str_replace('enable_', '', $k);
				$required = $v == 1 ? 'required' : '';
				$fdata = array(
								'name'        => 	$str,
								'id'          =>	$str,
								'value'       => 	$this->validation->$str,
								'size'        => 	'50',
								'class'       => 	'form-control ' . $required,
								);
				
				$row = array(
								'required' 			=> 		$v == 1 ? '*' : '',
								'form_description'	=>		$this->lang->line($str),
								'form_name'	 		=>		$str,
							);
				
				switch ($k)
				{
					case 'enable_primary_email':
						
						$fdata['class'] = 'form-control required email';
						$row['form_field'] = form_input($fdata);
						
					break;
					
					case 'enable_billing_country':
					case 'enable_payment_country':	
						
						$country = !$this->input->post($str) ?  $data['sts_site_default_country'] : $this->input->post('billing_country');
						$attributes = 'id="' . $str . '" class="form-control"';
						$row['form_field'] = form_dropdown($str, $this->country_options, $country, $attributes);
						
					break;
					
					case 'enable_bank_transfer':
						
						$fdata = array(
								'name'        => 	$str,
								'id'          =>	$str,
								'value'       => 	$this->validation->$str,
								'size'        => 	'50',
								'class'       => 	'form-control ' . $required,
								);
						
						$row['form_field'] = form_textarea($fdata);
					
					break;
					
					case 'show_tos':
						
						$attributes = 'id="' . $str . '" class="form-control required"';
						$agree = array('0' => $this->lang->line('agree_with_tos_no'),
									   '1' => $this->lang->line('agree_with_tos_yes')
									   );
						
						$row['form_field'] = form_dropdown($str, $agree, '', $attributes);
					
					break;
					
					default:
					
						if ($k == 'enable_password')
						{
							$row['form_field'] = form_password($fdata);
						}
						else
						{
							$row['form_field'] = form_input($fdata);
						}
					break;
				}
				
				$error_str = $str . '_error';
				$row['form_field'] .= $this->validation->$error_str; 
				
				array_push($data['form_fields'], $row);
			}	
			
			$data['sponsor'] = '';
			$data['sponsor_required'] = '';
			

			if ($this->config->item('sts_affiliate_require_referral_code') == 1)
			{	
				if (check_tracking_cookie() == false)
				{
					$sponsor_field = array(
									  'name'        => 'sponsor',
									  'id'          => 'sponsor',
									  'value'       => !$this->validation->sponsor ? '' : $this->validation->sponsor,
									  'size'        => '50',
									  'class'       => ' form-control required',
									);
					
					$data['sponsor']  = form_input($sponsor_field) . $this->validation->sponsor_error . ' ' . form_hidden('sponsor_required', '1');
				}	
			}
			
			
			$data['captcha'] = _generate_captcha();
			
			if ($this->validation->error_string)
			{
				$data['error'] = $this->lang->line('view_form_errors');	
			}
			
			//set the meta tags
			$data['page_title'] = $data['prg_program_name'] . ' - ' . $this->lang->line('registration');
			
			$this->parser->_JROX_load_view('tpl_registration', 'none', $data);
		}
		else
		{
			//check if there is username and password if not, auto generate
			if (empty($_POST['username']))
			{
				$_POST['username'] = _generate_random_username($this->validation->fname, _generate_random_string('3'));
				$this->validation->username = $_POST['username'];
			}
			
			if (empty($_POST['password']))
			{
				$_POST['password'] = _generate_random_string('6');
				$this->validation->password = $_POST['password'];
			}
			
			$_POST['status'] = '1';
			//$_POST['affiliate_groups'] = '1';
			
			if ($this->config->item('member_enable_group_change_registration') == true)
			{
				if ($this->uri->segment(3))
				{
					$id = $this->groups_model->_check_group_code($this->uri->segment(3));
																					 
					$_POST['affiliate_groups'] = !empty($id) ? $id : '';
				}
			}
			
			//set the email confirmation feature
			$_POST['login_status'] = $this->config->item('sts_sec_require_email_confirmation');
			$_POST['status'] = $this->config->item('sts_sec_require_admin_approval') == 1 ? 0 : 1;
			
			//add member
			unset($_POST['show_tos']);
			$userdata = $this->members_model->_add_member($_POST, 'register');	
			
			//generate a commission	
			$this->load->model('commissions_model');
			
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
				$userdata['program_id'] = $this->config->item('prg_program_id');
				
				$usr_enable = $this->config->item('prg_enable_affiliate_signup_bonus');
				$userdata['aff_bonus'] = $this->config->item('prg_affiliate_signup_bonus_amount');
				
				$ref_enable = $this->config->item('prg_enable_referral_bonus');
				$userdata['ref_bonus'] = $this->config->item('prg_referral_bonus_amount');
			}
			
			if ($usr_enable == 1)
			{
				$this->commissions_model->_add_affiliate_bonus($userdata);
			}
			
			if ($ref_enable == 1)
			{
				$this->commissions_model->_add_referral_bonus($userdata);	
			}
			
			//now let's send email alerts for new signups
			$this->load->model('emailing_model');
			
			//send out alert email if set
			if ($this->config->item('sts_affiliate_alert_downline_signup') == '1' && !empty($userdata['sponsor_id']))
			{
				$sponsor_info = $this->members_model->_get_member_basic($userdata['sponsor_id']);
				
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
			
			
			
			if ( $this->config->item('sts_sec_require_admin_approval') == 1)
			{
				$data['show_message'] = $this->lang->line('affiliate_admin_approval_required');
				
				$data['sub_message'] = '';	
				
				$this->parser->_JROX_load_view('tpl_general_message', 'none', $data);
			}
			else
			{
				//login the user
				$row = $this->login_model->_check_user('members', $this->validation->primary_email);
				
				if (!$row)
				{	
					redirect();
					exit();
				}
				
				//run session setup
				$this->login_model->_set_session('members', $row);				
	
				
				//add to mailing list if we don't need to confirm
				if ($userdata['login_status'] != 1)
				{
					//check the mailing list based on the affiliate group
					$list_id = !empty($_POST['affiliate_groups']) ? (int)$_POST['affiliate_groups'] : '1';
					$list_group = $this->groups_model->_get_list_group($list_id, true);
					
					$this->members_model->_update_member_list('add', $userdata['member_id'], $list_group);
					
					//run mailing list modules
					$this->_run_member_modules('mailing_list', $userdata);
					
					//run post registration modules
					$this->_run_member_modules('account_add', $userdata);
				}
				
				//now send the welcome email	
				if (!$this->config->item('member_disable_registration_email'))
				{
					$userdata['password'] = $this->input->post('password', true);			
					$this->emailing_model->_send_template_email('member', $userdata, 'member_login_details_template', false, $userdata['program_id']);
				}
				
				if ($redirect_enable == 1 && !empty($redirect_url))
				{
					$url = $redirect_url;
				}
				else
				{
					$url = site_url('members');
				}
				
				header("Location:" . $url);
				exit();
			}
		}
		
	}
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	
	// ------------------------------------------------------------------------		
	
	function _check_email_add()
	{

		if ($this->security_model->_check_free_email_accounts($this->validation->primary_email) == true)
		{
			$this->validation->set_message('_check_email_add', $this->lang->line('free_email_accounts_not_allowed'));
			return false;
		}
		
		$mem = $this->db_validation_model->_get_details('members', '*', 'primary_email', $this->validation->primary_email);
		
		if (!empty($mem))
		{
			//check for multiple program login
			if ($this->config->item('sts_site_showcase_multiple_programs') == 0)
			{
				//check for members programs
				$prog = $this->members_model->_check_member_program($mem[0]['member_id']);
				if (empty($prog))
				{
					//add to members programs table with confirm ID
					$prog = $this->members_model->_add_member_program($mem[0]['member_id'], $this->config->item('prg_program_id'));
				}
				
				if (!empty($prog['confirm_id']))
				{
					//send the confirmation email
					$confirm = $mem[0];
					$confirm['confirm_link'] = _public_url() . 'confirm/program/' . $this->config->item('prg_signup_link') .  '/' . $this->config->item('prg_program_id') . '-' .$confirm['member_id'] . '-' . $prog['confirm_id'];
					$confirm['signup_link'] = $this->config->item('prg_signup_link');
					
					$this->emailing_model->_send_template_email('member', $confirm, 'member_affiliate_program_confirm_template', false, $this->config->item('prg_program_id'));
				
					//log success
					log_message('info', 'email confirmation email sent to ' . $mem[0]['primary_email']);
					redirect_301(_public_url() . 'confirm/program/' . $this->config->item('prg_signup_link'), true, false);
					exit();
				}
			}
			
			$this->validation->set_message('_check_email_add', $this->lang->line('member_email_taken'));
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_registration_data()
	{
		//check for required sponsor
		if ($this->config->item('sts_affiliate_require_referral_code') == 1)
		{
			if ($this->input->post('sponsor_required') == 1)
			{
				$rules['sponsor'] = 'trim|strtolower|required|max_length[25]|alpha_numeric|callback__check_sponsor_add';
				$fields['sponsor'] = $this->lang->line('sponsor');
			}
		}
		
		foreach ($this->form_fields as $k => $v)
		{
			$str = str_replace('enable_', '', $k);
		
			switch ($str)
			{
				case 'fname':
				
					$rules[$str] = 'trim|min_length[2]|strip_tags';
					
				break;
				
				case 'primary_email':
					
					$rules[$str] = 'trim|strtolower|strip_tags|valid_email|callback__check_email_add';
					
				break;
				
				case 'password':
				
					$rules[$str] = 'trim|strip_tags|min_length[6]|max_length[25]|alpha_dash';
					
				break;	
				
				case 'username':
				
					$rules[$str] = 'trim|strip_tags|strtolower|min_length[' . $this->config->item('member_min_username_length') . ']|max_length[25]|alpha_numeric|callback__check_username_add';
					
				break;
				
				case 'website':
				
					$rules[$str] = 'trim|strip_tags|strtolower|prep_url';
				
				break;
				
				case 'payment_preference_amount':
				
					$rules[$str] = 'trim|numeric';
				
				break;
				
				case 'facebook_id':
				case 'twitter_id':
				case 'myspace_id':
				case 'linkedin_id':
				case 'paypal_id':
				case 'payza_id':
				case 'custom_id':
				
					$rules[$str] = 'trim|strip_tags|strtolower|callback__check_' . $str;
				
				break;
	
				default:
					
					$rules[$str] = 'trim|strip_tags';
					
				break;
			}
			
			if ($v == 1) { $rules[$str] .= '|required'; }
				
			$fields[$str] = $this->lang->line($str);
		}
		
		//check tos agreement
		if ($this->config->item('sts_form_enable_tos_checkbox') == 1)
		{
			$rules['show_tos'] = 'trim|required|callback__check_tos';
		}
		
		$fields['show_tos'] = $this->lang->line('show_tos');
		
		//check captcha
		if ($this->config->item('sts_sec_enable_captcha') == 1)
		{
			$rules['recaptcha_response_field'] = 'trim|required|callback__check_captcha';
		}
		
		$fields['recaptcha_response_field'] = $this->lang->line('recaptcha_response_field');
		
		$this->validation->set_rules($rules);
		$this->validation->set_fields($fields);
		
		if ($this->validation->run() == FALSE)	
		{
			$this->show_message .= $this->validation->error_string;
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_payza_id()
	{
		return $this->_check_custom_ids('payza_id', $this->validation->payza_id, '_check_payza_id', 'payza_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_custom_id()
	{
		return $this->_check_custom_ids('custom_id', $this->validation->custom_id, '_check_custom_id', 'custom_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_facebook_id()
	{
		return $this->_check_custom_ids('facebook_id', $this->validation->facebook_id, '_check_facebook_id', 'facebook_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_linkedin_id()
	{
		return $this->_check_custom_ids('linkedin_id', $this->validation->linkedin_id, '_check_linkedin_id', 'linkedin_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_myspace_id()
	{
		return $this->_check_custom_ids('myspace_id', $this->validation->myspace_id, '_check_myspace_id', 'myspace_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_moneybookers_id()
	{
		return $this->_check_custom_ids('moneybookers_id', $this->validation->moneybookers_id, '_check_moneybookers_id', 'moneybookers_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_twitter_id()
	{
		return $this->_check_custom_ids('twitter_id', $this->validation->twitter_id, '_check_twitter_id', 'twitter_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_paypal_id()
	{
		return $this->_check_custom_ids('paypal_id', $this->validation->paypal_id, '_check_paypal_id', 'paypal_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_custom_ids($type = '', $id = '', $callback = '', $lang = '')
	{
		if (empty($id))
		{
			$this->validation->set_message($callback, $this->lang->line($id) . ' ' .$this->lang->line('required'));
			return false;
		}
		
		if ($this->db_validation_model->_validate_field('members', $type, $id))
		{
			$this->validation->set_message($callback, $this->lang->line($lang));
			return false;
		}
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_username_add() 
	{
		if ($this->db_validation_model->_validate_field('members', 'username', $this->validation->username))
		{
			$this->validation->set_message('_check_username_add', $this->lang->line('member_username_taken'));
			return false;
		}
		elseif (check_subdomain($this->validation->username))
		{
			$this->validation->set_message('_check_username_add', $this->lang->line('member_username_taken'));
			return false;
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_captcha()
	{
		if (_verify_recaptcha($this->input->post("recaptcha_challenge_field"), $this->input->post("recaptcha_response_field"), $this->config->item('sts_sec_recaptcha_private_key')) == false)
		{
			$this->validation->set_message('_check_captcha', $this->lang->line('invalid_verification_code'));
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_tos()
	{
		if ($this->validation->tos_check != '1')
		{
			$this->validation->set_message('_check_tos', $this->lang->line('you_must_agree_tos'));
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_sponsor_add()
	{
		if ($this->db_validation_model->_validate_field('members', 'username', $this->validation->sponsor))
		{	
			return true;
		}
		
		$this->validation->set_message('_check_sponsor_add', $this->lang->line('invalid_referred_by'));
		return false;
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