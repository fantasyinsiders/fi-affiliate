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
| FILENAME - fb.php
| -------------------------------------------------------------------------     
| 
| This controller file is used for facebook connect functions
|
*/


class Fb extends Public_Controller {

	function __construct()
	{
		parent::__construct();
		//load css body style
		$this->config->set_item('css_body', 'jroxHome');
		
		//load required models
		$this->load->model('init_model', 'init');		
	}
	
	// ------------------------------------------------------------------------
	
	function login()
	{	
		//check for fb session
		//if (!$this->security_model->_get_fb_session()) { redirect(); }
		
		$this->init->_set_default_program($this->uri->segment(3));

		//set data array
		$data = $this->security_model->_load_config('public', __CLASS__,  __FUNCTION__);

		//initialize site with required db config
		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		//set up the languages array
		$data['languages'] = $sdata['languages'];
		
		//set referral info
		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		$data['show_message'] = '';
		
		//set the meta tags
		$data['page_title'] = $data['prg_program_name'] . ' - ' . $this->lang->line('login');
		
		//check fb
		$fb = $this->security_model->_check_fb_connect(false);
		
		if (!empty($fb))
		{
			foreach ($fb as $key => $val)
			{
				$data[$key] = $val;
			} 
			
			//check for fb data (login or register)
			$userdata = $this->security_model->_check_fb_user($fb['fb_login_info']);
			
			if ($userdata == false)
			{
				if (!empty($fb['fb_login_info'])) //add the user to the db
				{	
	
					//generate user data
					$post = array('fname' => $fb['fb_login_info']['first_name'],
								  'lname' => $fb['fb_login_info']['last_name'],
								  'primary_email' => $fb['fb_login_info']['email'],
								  'facebook_id' => $fb['fb_login_info']['id'],
								  'facebook_link' => $fb['fb_login_info']['link'],
								  'username' => _generate_random_username($fb['fb_login_info']['name'], _generate_random_string('3')),
								  'sponsor' => !empty($data['sponsor_referring_username']) ? $data['sponsor_referring_username'] : '',
								  'password' => _generate_random_string('6'),
								  'status' => 1,
								  'email_mailing_lists' => array('1'),
								  'login_status' => 0,
								  );
					
					//add user to database
					$userdata = $this->members_model->_add_member($post);	
					
					//post on wall
					$this->login_model->_post_to_fb($fb, $userdata);
					
					//generate a commission	
					$this->load->model('commissions_model');
					
					//check for signup bonuses
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
								
								$this->emailing_model->_send_template_email('member', $sponsor_info, 'member_affiliate_downline_signup', true, $userdata['program_id']);
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
				}				
			}
				
			//set the last login date and ip			
			$this->login_model->_update_last_login_info($userdata['member_id']);
		}
		
		//load fb connect file
		$data['fb_init'] = $this->config->item('enable_facebook_connect') == 1 ? $this->parser->_JROX_parse('tpl_fb_connect',  APPPATH . 'views/main', $data, true, true) : '';
		
		//run login modules
		$this->_run_member_modules('login', $userdata);
		
		$this->parser->_JROX_load_view('tpl_fb_login', 'none', $data, true, true);
	}
	
	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	
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