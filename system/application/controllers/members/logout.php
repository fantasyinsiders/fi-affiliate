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
| FILENAME - logout.php
| -------------------------------------------------------------------------     
| 
| This controller logs out the member
|
*/

class Logout extends Public_Controller {
	
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
		//redirect facebook
		$fb = $this->security_model->_check_fb_connect(false, 'fb_redir.php');
		
		if (!empty($fb))
		{
			foreach ($fb as $key => $val)
			{
				$data[$key] = $val;
			} 
		}
		
		//first get all the users memberships
		$mem = $this->session->userdata('jrox_current_memberships');
		
		delete_cookie('jrox_login');
		
		if (!empty($mem))
		{
			foreach ($mem as $row)
			{
				delete_cookie('jrox_group_login_' . $row);
			}
		}
		
		//logout the membe and destroy the session
		$this->session->sess_destroy();
		
		if (!empty($fb['fb_logout_url']))
		{
			header("Location:" . $fb['fb_logout_url']);
			exit();		
		}
		
		if ($this->config->item('member_url_logout_redirect'))
		{
			redirect_301($this->config->item('member_url_logout_redirect'), true, false);
		}
		else
		{
			redirect_301(_public_url(), true, false);
		}
	}
}
?>