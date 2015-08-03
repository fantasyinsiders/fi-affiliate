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
*/

class Logout extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$this->session->sess_destroy();
		
		if ($this->uri->segment(4) == 'timer_expired')
		{
			$url = '';
			if ($this->uri->segment(5))
			{
				$this->load->library('convert');
				
				$url = $this->convert->AsciiToHex(base64_encode($this->uri->segment(5)));
			}
			
			redirect(ADMIN_LOGIN_ROUTE.'/index/timer_expired/' . $url);
		}
		else
		{
			redirect(ADMIN_LOGIN_ROUTE);
		}
	}
}
?>