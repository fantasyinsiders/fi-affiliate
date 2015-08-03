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
| FILENAME - dashboard.php
| -------------------------------------------------------------------------     
| 
*/

class Dashboard extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('dashboard_model', 'dash');

	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		$data['page_title'] = 'dashboard';

		$days = date('t') - (date('t') - date('d'));
		
		$data['latest_members'] = $this->dash->_latest_members();
		
		$data['latest_commissions'] = $this->dash->_latest_commissions();
		
		$data['month_signups'] = $this->dash->_total_signups(date('m'), date('Y'));
		
		$data['month_clicks'] = $this->dash->_total_clicks(date('m'), date('Y'));
		
		$data['month_clicks_avg'] = $data['month_clicks'] / $days;		
		
		$data['month_comm'] = $this->dash->_total_commissions(date('m'), date('Y'));
		
		$data['month_comm_avg'] = $data['month_comm'] / $days;
		
		$data['total_programs'] = $this->dash->_total_programs();
		
		$data['total_members'] = $this->db->count_all('members');
		
		$data['total_commissions'] = $this->dash->_total_commissions();
		
		load_admin_tpl('admin', 'tpl_adm_dashboard', $data);
	}
}
?>