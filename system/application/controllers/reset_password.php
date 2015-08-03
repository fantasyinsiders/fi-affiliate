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
| FILENAME - reset_password.php
| -------------------------------------------------------------------------     
| 
| This controller file is used for the reset password page
|
*/


class Reset_password extends Public_Controller {

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
		
		$data = $this->security_model->_load_config('public', __CLASS__,  __FUNCTION__);

		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		$data['languages'] = $sdata['languages'];
		
		if(empty($_POST))
		{
			$data['page_title'] = $data['prg_program_name'] . ' - ' . $this->lang->line('reset_password');
			
			$this->parser->_JROX_load_view('tpl_reset_password', 'none', $data);
		}
		else
		{
			//check for valid email
			$row = $this->_check_reset_password();
			
			if (!$row)
			{
				echo _show_msg('error', $this->lang->line('invalid_email_address'));
			}
			else
			{
				//load emailing model and send password
				$this->load->model('emailing_model');
				
				if ($this->emailing_model->_send_template_email('member', $row, 'member_reset_password_template', false, $data['prg_program_id']))
				{
					//log success
					log_message('info', 'Member Password Email Sent to ' . $row['primary_email']);
					
					echo _show_msg('success', $this->lang->line('password_reset_successfully'));
					exit();
				}
				else 
				{
					show_error($this->lang->line('could_not_send_email') . '. ' . $this->lang->line('check_email_settings'));
					
					log_message('error', 'Could not send admin email to ' . $row['primary_email'] . '. Check email settings.');
				}
			}
		}
	}
	
	// ------------------------------------------------------------------------
	
	/*--------------------------------
	| supporting functions are below
	---------------------------------*/
	
	// ------------------------------------------------------------------------
	
	function _check_reset_password()
	{
		$email = strip_tags($_POST['primary_email']);
		
		$row = $this->login_model->_check_reset('members', $email);
																		
		if ($row)
		{
			return $row;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------

}
?>