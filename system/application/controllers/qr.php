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
| FILENAME - qr.php
| -------------------------------------------------------------------------     
| 
| This controller file is used for generating qr codes
|
*/


class Qr extends Public_Controller {

    function __construct()
    {
        parent::__construct();
		
		//load required models
		$this->load->model('init_model', 'init');
    }

    // ------------------------------------------------------------------------

    function index()
    {
        $data = $this->security_model->_load_config('public', __CLASS__,  __FUNCTION__);
		
		//initialize site with required db config
		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
        require_once(APPPATH . '/libraries/phpqrcode/qrlib.php');

		$link = _public_url() .  _jrox_slash_item(AFF_TOOLS_ROUTE) . $this->uri->segment(4) . '/tool/' . $this->uri->segment(2) . '/' . $this->uri->segment(3);
		
		QRcode::png($link);
    }

    // ------------------------------------------------------------------------

}
?>