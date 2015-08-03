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
| FILENAME - unsubscribe.php
| -------------------------------------------------------------------------     
| 
| This controller file is used to unsubscribe users from mailing lists
|
*/

class Unsubscribe extends Public_Controller {
	
	function __construct()
	{
		parent::__construct();
		//load css body style
		$this->config->set_item('css_body', 'jroxUnsubscribe');
			
		//load required models
		$this->load->model('init_model', 'init');
		$this->load->model('emailing_model');
		$this->load->model('members_model');
	}
	
	function index()
	{
		redirect();
	}

	// ------------------------------------------------------------------------
	
	function id()
	{
		//unsubscribe a user from mailing list
		
		$this->init->_set_default_program(1, false, true);
		
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
		$data['page_title'] = $this->lang->line('unsubscribe');
		$data['meta_keywords'] = '';
		$data['lang_heading'] = $this->lang->line('unsubscribe');
		
		$data['show_message'] = $this->lang->line('mailing_list_subscription');
		$data['sub_message'] = '';
		
		if ($this->uri->segment(3) && $this->uri->segment(4))
		{
			//get the member info 
			$members = $this->members_model->_get_member_basic((int)$this->uri->segment(3));
			
			if (!empty($members))
			{
				$this->emailing_model->_unsubscribe((int)$this->uri->segment(3), (int)$this->uri->segment(4));
				$data['sub_message'] = $members['primary_email'] . ' ' . $this->lang->line('user_unsubscribed_successfully');
				
				$this->parser->_JROX_load_view('tpl_general_message', 'none', $data);
			}
			else
			{
				show_error('invalid_data');
			}
		}	
		else
		{
			redirect();
		}		
	}
}

?>