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
| FILENAME - program.php
| -------------------------------------------------------------------------     
| 
| This controller file is used for default home page
|
*/


class Program extends Public_Controller {

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
		
		$prog = $this->programs_model->_get_program_basic('program_id', '1', 'signup_link');
		
		$signup_link = $this->uri->segment(2, $prog['signup_link']);
									
		switch($data['sts_site_default_home_page'])
		{
			case 'login':
			case 'registration':
			
				redirect_301(site_url() . $data['sts_site_default_home_page'] . '/' . $signup_link, true, false);
			
			break;
			
			default:
				
				redirect_301(site_url() . PROGRAM_ROUTE . '/' . $signup_link, true, false);	
				
			break;
		}
	}
	
	// ------------------------------------------------------------------------
	
	function id()
	{	
	
		$this->init->_set_default_program($this->uri->segment(2));
		
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
		
		//set the meta tags
		$data['page_title'] = $data['prg_program_name'];
		
		$this->parser->_JROX_load_view('tpl_description', 'none', $data);
	}
	
	// ------------------------------------------------------------------------

}
?>