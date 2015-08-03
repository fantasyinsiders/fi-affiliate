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


class Confirm extends Public_Controller {

	function __construct()
	{
		parent::__construct();
		//load css body style
		$this->config->set_item('css_body', 'jroxHome');
		
		//load required models
		$this->load->model('init_model', 'init');		
	}
	
	// ------------------------------------------------------------------------
	
	function account()
	{	
	
		$this->init->_set_default_program($this->uri->segment(3));
		
		//set data array
		$data = $this->security_model->_load_config('public', __CLASS__,  __FUNCTION__);

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
		
		//set page title
		$data['page_title'] = $this->lang->line('login');
		
		$data['show_message'] = $this->lang->line('thank_you_signing_up');
		
		if ($this->config->item('sts_sec_require_admin_approval') == 1)
		{
			$data['sub_message'] = $this->lang->line('affiliate_admin_approval_required');
		}
		else
		{
			$data['sub_message'] = $this->lang->line('account_confirmation_required');
		}
		
		if ($this->uri->segment(4) == true)
		{
			if ($this->login_model->_check_email_confirmation($this->uri->segment(4)))
			{
				
				$sid = explode('-', $this->uri->segment(4));
				
				$this->load->model('members_model');
				
				//set the mailing list group
				$this->load->model('groups_model');
				$list_group = $this->groups_model->_get_list_group($this->session->userdata('m_affiliate_group'), true);
				
				//add to default mailing list
				$this->members_model->_update_member_list('add', $sid[0], 1);
				
				//add to the default group if needed
				if ($this->config->item('member_add_to_default_list_on_registration'))
				{
					//add to default mailing list
				$this->members_model->_update_member_list('add', $sid[0], $this->config->item('member_add_to_default_list_on_registration'));	
				}
				
				//set the login status
				if ($this->config->item('sts_sec_require_admin_approval') == 1)
				{
					$data['show_message'] = $this->lang->line('affiliate_admin_approval_required');
				
					$data['sub_message'] = '';	
				}
				else
				{					
					$this->session->set_userdata('m_login_status', '0');
					
					//run mailing list modules	
					$this->_run_member_modules('mailing_list', $sid[0]);
						
					//run account add modules
					$this->_run_member_modules('account_add', $sid[0]);
				
					$data['show_message'] = $this->lang->line('account_successfully_confirmed');
				
					$data['sub_message'] = '<a href="' . site_url('members') . '" class="btn btn-success">' . $this->lang->line('click_here_to_continue') . '</a>';
				}
			}
			else
			{
				$data['show_message'] = $this->lang->line('account_confirmation_invalid');
			}
		}
		
		if ($this->config->item('jrox_custom_redirect_after_confirmation_page'))
		{
			redirect_301($this->config->item('jrox_custom_redirect_after_confirmation_page'), true, false);
			exit();
		}
		else
		{
			$this->parser->_JROX_load_view('tpl_general_message', 'none', $data);
		}
	}
	
	// ------------------------------------------------------------------------
	
	function program()
	{	
	
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
		
		//set page title
		$data['page_title'] = $this->lang->line('login');
		
		$data['show_message'] = $this->lang->line('thank_you_signing_up');
		$data['sub_message'] = $this->lang->line('account_confirmation_required');
		
		
		if ($this->uri->segment(4) == true)
		{
			if ($this->login_model->_check_program_confirmation($this->uri->segment(4)))
			{
				
				$sid = explode('-', $this->uri->segment(4));
				
				$row = $this->members_model->_get_member_basic($sid[1]);
				
				//run session setup
				$row['login_status'] = 0;
				$this->login_model->_set_session('members', $row);
				
				//run member modules
				$this->_run_member_modules('program_add', $sid[0]);
				
				$data['show_message'] = $this->lang->line('account_successfully_confirmed');
				
				$data['sub_message'] = '<a href="' . $this->config->item('members_login_url') . 'members/">' . $this->lang->line('click_here_to_continue') . '</a>';
			}
			else
			{
				$data['show_message'] = $this->lang->line('account_confirmation_invalid');
			}
		}
		
		//setup per program or default values 
		$redirect_enable = $this->config->item('sts_site_enable_custom_signup');
		$redirect_url = $this->config->item('sts_site_url_redirect_signup');
			
		if ($redirect_enable == 1 && !empty($redirect_url))
		{
			redirect_301($redirect_url, true, false);
			exit();
		}
		else
		{
			$this->parser->_JROX_load_view('tpl_general_message', 'none', $data);
		}
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