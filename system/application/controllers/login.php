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
| FILENAME - login.php
| -------------------------------------------------------------------------     
| 
| This controller file is used for the login page
|
*/


class Login extends Public_Controller {

	function __construct()
	{
		parent::__construct();
		//load css body style
		$this->config->set_item('css_body', 'jroxHome');
		
		//load required models
		$this->load->model('init_model', 'init');		
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{	
	
		$this->init->_set_default_program($this->uri->segment(2));
		
		//set data array
		$data = $this->security_model->_load_config('public', __CLASS__,  __FUNCTION__);
		
		if (!empty($data['sts_auto_login_redirect_login'])) redirect_301($data['sts_auto_login_redirect_login'], true);

		//initialize site with required db config
		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		//run facebook config
		$fb = $this->security_model->_check_fb_connect(false, 'fb_redir.php');
		
		if (!empty($fb))
		{
			foreach ($fb as $key => $val)
			{
				$data[$key] = $val;
			} 
		}
		
		//set up the languages array
		$data['languages'] = $sdata['languages'];
		
		//set referral info
		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		$data['show_message'] = '';
		
		if ($this->_check_login() == false)
		{
			if (!empty($_POST))
			{
				//add autoblock
				
				$data['error'] =  $this->validation->error_string;
				
				//invalid login attempted 
				$this->security_model->_auto_block_ip('block', $this->input->ip_address());	
				
				log_message('debug', 'Failed Member Login detected: username - ' . $this->validation->username . ' Remote IP: ' . $this->input->ip_address());
			}	

			//set the meta tags
			$data['page_title'] = $data['prg_program_name'] . ' - ' . $this->lang->line('login');
			
			$this->parser->_JROX_load_view('tpl_login', 'none', $data);
		}
		else
		{
			//run login modules
			$this->_run_member_modules('login', $this->session->userdata('userid'));
			
			//remove autoblock
			$this->security_model->_auto_block_ip('remove', $this->input->ip_address());	
			
			if ($this->config->item('sts_site_showcase_multiple_programs') == 1)
			{
				$userdata['program_id'] = '1';
				
				$redirect_enable = $this->config->item('sts_site_enable_custom_login');
				$redirect_url = $this->config->item('sts_site_url_redirect_login');
			}
			else
			{
				$userdata['program_id'] = $this->config->item('prg_program_id');
				
				$redirect_enable = $this->config->item('prg_enable_custom_login');
				$redirect_url = $this->config->item('prg_url_redirect_login');
			}
			
			if ($redirect_enable == 1 && !empty($redirect_url))
			{
				header("Location:" . $redirect_url);
				exit();
			}
			else
			{
				//redirect to members area
				redirect_301(site_url('members'), true, false);
			}			
		}
	}
	
	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	
	// ------------------------------------------------------------------------
	
	function _check_login ()
	{
		//set form validation rules
		$rules['username'] = "trim|required|strip_tags|valid_email|callback__check_user";
		$rules['password'] = "trim|strip_tags|required";
		
		$this->validation->set_rules($rules);

		//repopulate form
		$fields['username'] = $this->lang->line('email_address');
		$fields['password'] = $this->lang->line('password');
		
		$this->validation->set_fields($fields);
		
		$data['show_message'] = '';
			
		if ($this->validation->run() == false)
		{
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_user()
	{
		$this->validation->set_message('_check_user', $this->lang->line('invalid_login'));
		
		$row = $this->login_model->_check_user('members', $this->validation->username);	
		
		if (!empty($row))
		{	
			//now check if the user is allowed to login to the program
			if ($this->config->item('sts_site_showcase_multiple_programs') == '0')
			{
				$prog = $this->login_model->_check_program_access($row['member_id'], $this->config->item('prg_program_id'));
				
				if (empty($prog))
				{
					return false;
				}
				elseif (!empty($prog['confirm_id']))
				{
					//send the confirmation email
					$confirm = $row;
					$confirm['confirm_link'] = _public_url() . 'confirm/program/' . $this->config->item('prg_signup_link') .  '/' . $this->config->item('prg_program_id') . '-' .$row['member_id'] . '-' . $prog['confirm_id'];
					$confirm['signup_link'] = $this->config->item('prg_signup_link');
					
					$this->emailing_model->_send_template_email('member', $confirm, 'member_affiliate_program_confirm_template', false, $this->config->item('prg_program_id'));
				
					//log success
					log_message('info', 'email confirmation email sent to ' . $row['primary_email']);
					redirect_301(_public_url() . 'confirm/program/' . $this->config->item('prg_signup_link'), true, false);
					exit();
				}
			}
			
			//run session setup
			$this->login_model->_set_session('members', $row);
			
			return true;
		}	
		
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