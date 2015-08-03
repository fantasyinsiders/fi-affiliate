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
| FILENAME - access.php
| -------------------------------------------------------------------------     
| 
| This file controls redirection based on access codes
|
*/
class Access extends Public_Controller {

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
		
		//initialize site with required db config
		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		//set up the languages array
		$data['languages'] = $sdata['languages'];
		
		//set all text
		foreach ($sdata['text'] as $key => $val)
		{
			$data[$key] = $val;
		}
		
		//set referral info
		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		}
	}
	
	// ------------------------------------------------------------------------
	
	function switch_language()
	{
		//set data array
		$data = $this->security_model->_load_config('public', __CLASS__,  __FUNCTION__);
		
		//initialize site with required db config
		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		if ($this->uri->segment('2'))
		{
			$this->session->set_userdata('user_language_set', $this->uri->segment(2));
		}
		
		if ($this->agent->referrer())
		{
			redirect_301($this->agent->referrer(), true, false);
		}
		else
		{
			redirect_301();
		}
	}
}
?>