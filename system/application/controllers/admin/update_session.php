<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| COPYRIGHT NOTICE                                                     
| Copyright 2013 JROX Technologies, Inc.  All Rights Reserved.    
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
| FILENAME - update_session.php
| -------------------------------------------------------------------------     
| 
*/
class Update_Session extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect();
	}
	
	// ------------------------------------------------------------------------
	
	function rows()
	{	
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$rows = $this->uri->segment(4, $this->session->userdata('per_page'));
		
		$this->session->set_userdata('per_page', $rows);
		
		$ret = referer_redirect('admin', $this->uri->segment(5, 0), ':');
		
		redirect($ret);
	}
	
	// ------------------------------------------------------------------------
	
	function view_table()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$table = $this->uri->segment(4) == 'table' ? 'table' : 'card';
		
		$this->session->set_userdata('view_members_page', $table);
		
		$ret = referer_redirect('admin', $this->uri->segment(5, 0), ':');
		
		redirect($ret);
	}
	
	// ------------------------------------------------------------------------
	
	function module_rows()
	{	
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$rows = $this->uri->segment(4, $this->session->userdata('per_page'));
		
		$this->session->set_userdata('per_page', $rows);
		
		$ret = referer_redirect('modules', $this->uri->segment(5, 0));
		
		redirect($ret);
	}	
}
?>