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
| FILENAME - module_stats_reporting_affiliate_click_traffic
| -------------------------------------------------------------------------     
| 
| This controller file is used to manage affiliate clicks
|
*/

class Module_Stats_Reporting_Affiliate_Click_Traffic extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('modules/module_stats_reporting_affiliate_click_traffic_model', 'modules');
		
		$this->load->model('reports_model', 'reports');

		$this->config->set_item('module_name', 'module_stats_reporting_affiliate_click_traffic');
		
		$this->config->set_item('menu', 'r');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url());
	}
	

	// ------------------------------------------------------------------------
	
	function update()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->input->post('traffic') AND count($this->input->post('traffic')) > 0)
		{
			$this->modules->_delete_clicks($this->input->post('traffic'));
		}
		
		$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
		
		redirect($this->input->post('redirect'));
	}
	
	// ------------------------------------------------------------------------
	
	function generate()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = $this->lang->line('view_affiliate_clicks');

		$row = $this->modules->_get_traffic($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);

		$data['traffic'] = $row['traffic'];	

		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'traffic', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column'], $row['total_rows']);
		
		load_admin_tpl('modules', 'tpl_adm_manage_affiliate_clicks', $data);	
	}
	
	// ------------------------------------------------------------------------
}
?>