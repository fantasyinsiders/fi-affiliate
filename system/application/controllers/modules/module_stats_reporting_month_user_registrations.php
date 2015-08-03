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
| FILENAME - Module_Stats_Reporting_Month_user_registrations.php
| -------------------------------------------------------------------------     
| 
| This controller file is used to manage reports
|
*/

class Module_Stats_Reporting_Month_User_Registrations extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();
		//load modules model
		$this->load->model('modules/module_stats_reporting_month_user_registrations_model', 'modules');
		
		$this->load->model('reports_model', 'reports');
		
		//load css body style
		$this->config->set_item('css_body', 'KR');
		
		$this->load->helper('inflector');
		
		//set random graph colors
		$colors = array(	'#003366',
            				'#0066CC',
							'#FF3300'
            			);
		
		$this->config->set_item('random_bg_color', random_element($colors));		
		
		//module name
		$this->config->set_item('module_name', 'module_stats_reporting_month_user_registrations');
		
		$this->config->set_item('menu', 'r');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		//redirect to default view
		redirect('reports/view_reports');
	}
	
	// ------------------------------------------------------------------------
	
	function generate()
	{
		
		/*
		| -----------------------------------------
		| generate report
		| -----------------------------------------
		*/

		//set data array
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = $this->lang->line('monthly_user_registrations_by_day');
		$data['row_name'] = 'day_of_month';
		$data['row_amount'] = 'total';
		
		$data['report_id'] = 'stats_graph';
		
		//reset total amount
		$data['total_amount'] = '0';
		
		//check for current month and date
		$m = $this->uri->segment('4');
		$y = $this->uri->segment('5');
		
		$data['cmonth'] = !(empty($m)) ? $m : date("m");
		$data['cyear'] = !(empty($y)) ? $y : date("Y");
		
		//setup dropdown for selecting months
		$data['select_months'] = _generate_month_dropdown($data['cmonth'] . '/' . $data['cyear']);
	
		//generate the data
		$stats = $this->modules->_generate_report($data['cmonth'], $data['cyear']);
		
		if (!empty($stats))
		{
			$srow = $stats[0];
			
			$data['stats'] = array();
			
			//setup the data for flash
			foreach ($srow as $k => $v)
			{	
				if ($k == 'id') continue;
				
				$arr = array('date' => $k,
							 'amount' => $v);
							 
				array_push($data['stats'], $arr);
			}
			
			//$data['graph_data'] = $this->reports->_generate_flash_data($srow);
			$data['show_graph'] = $this->reports->_generate_js_chart_data($srow, $data['report_id'], 'HighRollerColumnChart', $data['page_title'], '#DB887F', true, '#FFFFFF' );
	
		}
		
		$data['print_page'] = $this->uri->segment('6', '0');
		
		$top = true;
		$bottom = true;
		$data['graph_width'] = '920';
		$data['graph_height'] = '290';
		
		if ($data['print_page'] == 'print')
		{
			$top = false;
			$bottom = false;
			$data['graph_width'] = '700';
			$data['graph_height'] = '150';
		}	
		
		$data['set_integer'] = true;
		
		//archive the report
		if ($data['print_page'] == 'archive')
		{
			
			$name = date('F', mktime(0, 0, 0, $data['cmonth']  , '1', $data['cyear'])) . ' ' . $data['cyear'] . ' ' . $data['page_title'] . ': ' . date($data['sts_admin_time_format'], _generate_timestamp());
			
			$data['page_title'] = $name;	
			
			$html_data = load_form('modules', 'tpl_adm_bar_graph_report', $data, true);
			
			$sdata = $this->reports->_generate_archive($name, htmlentities($html_data));
		
			if ($sdata)
			{
				redirect(admin_url() . 'reports_archive/view_archive/');
				exit();
			}

		}
		
		//load template
		load_admin_tpl('modules', 'tpl_adm_bar_graph_report', $data, $top, $bottom);
						
	}
	
	// ------------------------------------------------------------------------
	
}
?>