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
| FILENAME - Module_Stats_Reporting_Month_Affiliate_Clicks_Stats.php
| -------------------------------------------------------------------------     
|
*/

class Module_Stats_Reporting_Month_Affiliate_Clicks_Stats extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('modules/module_stats_reporting_month_affiliate_clicks_stats_model', 'modules');
		
		$this->load->model('reports_model', 'reports');
		
		$this->load->helper('inflector');
		
		$this->config->set_item('module_name', 'module_stats_reporting_month_affiliate_clicks_stats');
		
		$this->config->set_item('menu', 'r');
		
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect('reports/view_reports');
	}
	
	// ------------------------------------------------------------------------
	
	function generate()
	{

		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = $this->lang->line('monthly_affiliate_clicks_stats_by_day');
		$data['row_name'] = 'day_of_month';
		$data['row_amount'] = 'amount';
		
		$data['report_id'] = 'stats_graph';

		$data['total_amount'] = '0';

		$m = $this->uri->segment('4');
		$y = $this->uri->segment('5');
		
		$data['cmonth'] = !(empty($m)) ? $m : date("m");
		$data['cyear'] = !(empty($y)) ? $y : date("Y");

		$data['select_months'] = _generate_month_dropdown($data['cmonth'] . '/' . $data['cyear']);

		$stats = $this->modules->_generate_report($data['cmonth'], $data['cyear']);
		
		$random_color = random_element($this->config->item('report_graph_colors'));
		
		
		if (!empty($stats))
		{
			$srow = $stats[0];
			
			$data['stats'] = array();

			foreach ($srow as $k => $v)
			{	
				if ($k == 'id') continue;
				
				$arr = array('date' => $k,
							 'amount' => $v);
							 
				array_push($data['stats'], $arr);
			}

			$data['show_graph'] = $this->reports->_generate_js_chart_data($srow, $data['report_id'], 'HighRollerColumnChart', $data['page_title'], $random_color );
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

		load_admin_tpl('modules', 'tpl_adm_bar_graph_report', $data, $top, $bottom);
						
	}
	
	// ------------------------------------------------------------------------
	
}
?>