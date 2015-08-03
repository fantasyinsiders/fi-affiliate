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
| FILENAME - reports_archive.php
| -------------------------------------------------------------------------     
|
*/

class Reports_Archive extends Admin_Controller {
	
	function __construct()
	{
	
		parent::__construct();
		
		$this->load->model('reports_model', 'reports');
		
		$this->config->set_item('menu', 'r');
		
		$this->load->helper('inflector');	
			
	}
	
	// ------------------------------------------------------------------------
	
	function view_archive()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['reports'] = $this->reports->_get_reports_archive($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);

		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'report_archive', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column']);

		load_admin_tpl('admin', 'tpl_adm_manage_reports_archive', $data);	
	}
	
	// ------------------------------------------------------------------------
	
	function view_report()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$reports = $this->reports->_get_archive_details($this->uri->segment(4));
		
		$data['page_title'] = $reports[0]['report_name'];
		$data['report_id'] = $reports[0]['id'];
		$data['print_page'] = $this->uri->segment('5', '0');
		
		if ($data['print_page'] == 'print')
		{
			$top = false;
			$bottom = false;
			$data['graph_width'] = '700';
			$data['graph_height'] = '150';
		}
		
		$reports_html = $reports[0]['report_html'];
		
		$data['reports_html'] = html_entity_decode($reports_html);	
		
		load_admin_tpl('admin', 'tpl_adm_view_report_archive', $data);
	}
	
	// ------------------------------------------------------------------------
	
	function delete_report()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->reports->_delete_archived_report($this->uri->segment(4)))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));
		}
		
		redirect(admin_url() . 'reports_archive/view_archive');
	}
}
?>