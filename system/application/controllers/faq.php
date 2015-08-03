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
| FILENAME - faq.php
| -------------------------------------------------------------------------     
| 
| This controller file is used for faq page
|
*/


class Faq extends Public_Controller {

	function __construct()
	{
		parent::__construct();
		//load css body style
		$this->config->set_item('css_body', 'jroxHome');
		
		//load required models
		$this->load->model('init_model', 'init');	
		$this->load->model('faq_model', 'faq');
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
		
		//set the meta tags
		$data['page_title'] = $data['prg_program_name'] . ' - ' . $this->lang->line('faqs');
		
		$data['show_faq'] = 0;
		
		$data['lang_no_faqs_found'] = $this->lang->line('no_faqs_found');
		
		//get the faqs
		$data['faqs'] = $this->faq->_get_faqs($this->config->item('prg_program_id'));
		
		
		$this->parser->_JROX_load_view('tpl_faq', 'none', $data);
	}
	
	// ------------------------------------------------------------------------

}
?>