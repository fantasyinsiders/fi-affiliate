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
| FILENAME - reports.php
| -------------------------------------------------------------------------     
| 
| This controller file is used to show members reports
|
*/

class Reports extends Member_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->config->set_item('css_body', 'jroxMembersReports');
		
		$this->load->model('reports_model', 'reports');
		
		//create pagination links		
		$this->config->set_item('uri', $this->config->slash_item('base_folder_path') . $this->config->slash_item('site_index_page') . $this->uri->segment(1));
		$this->config->set_item('uri_string', str_replace('/', ':', trim_slashes($this->uri->uri_string())));
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(site_url('members/reports/view'));
	}
	
	// ------------------------------------------------------------------------
	
	function view()
	{
		$this->init->_set_default_program($this->session->userdata('m_signup_link'));
		
		//set data array
		$data = $this->security_model->_load_config('members', __CLASS__,  __FUNCTION__);

		//initialize site with required db config
		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		//set up the languages array
		$data['languages'] = $sdata['languages'];
		
		//set referral info
		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		$data['page_title'] = $this->lang->line('reports');
		
		$uri = $data['uri'] . '/reports/view/';

		$data['offset'] = $this->uri->segment(4, '0');
		$data['sort_column'] = $this->uri->segment(6, 0);
		$data['sort_order'] = $this->uri->segment(5, 0);
		$data['next_sort_order'] = $this->uri->segment(5) == 'DESC' ? 'ASC' : 'DESC';
		
		//get reports
		$row = $this->reports->_get_member_reports(MEMBERS_REPORTS_PER_PAGE, $data['offset'], $data['sort_column'], $data['sort_order']);

		$data['current_month'] = date('m');
		$data['current_year'] = date('Y');
		
		if (!empty($row))
		{
			//add the data to the array
			$data['rows'] = $row;	
		}
		
		//count all rows first
		$row_count = $this->db_validation_model->_get_count('modules', 'WHERE module_type = \'member_reporting\' AND module_status = \'1\'');
		
		$pagination = $this->db_validation_model->_set_pagination($uri, 'modules', MEMBERS_REPORTS_PER_PAGE, 4, $data['sort_order'], $data['sort_column'],  $row_count, '', '', 'public');
		
		$data['pagination_rows'] = $pagination['rows'];
			
		$data['num_pages'] = $pagination['num_pages'];
			
		$data['no_pages'] = $data['num_pages'] > 1 ? 1 : 0;

		//load the template
		$this->parser->_JROX_load_view('tpl_members_reports', 'members', $data);
	}	
}

?>