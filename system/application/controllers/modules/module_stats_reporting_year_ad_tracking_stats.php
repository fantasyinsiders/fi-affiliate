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
| FILENAME - Module_Stats_Reporting_Year_Ad_Tracking_Stats.php
| -------------------------------------------------------------------------     
| 
| This controller file is used to manage reports
|
*/

class Module_Stats_Reporting_Year_Ad_Tracking_Stats extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();
		//load modules model
		$this->load->model('modules/module_stats_reporting_year_ad_tracking_stats_model', 'modules');
		
		$this->load->model('tracking_model');
		
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
		$this->config->set_item('module_name', 'module_stats_reporting_year_ad_tracking_stats');
		
		$this->config->set_item('menu', 'r');
		
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		//redirect to default view
		redirect(admin_url() . 'reports/view_reports');
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
		
		//get ad tracking details
		$tracker = $this->tracking_model->_get_tracking_details((int)$this->uri->segment(4));
		
		$data['page_title'] = $tracker['name'] . ' - ' . $this->lang->line('monthly_ad_tracking_stats_by_year') . ' (' . $this->lang->line('traffic') . ')';
		$data['row_name'] = 'month';
		$data['row_amount'] = 'clicks';
		$data['type'] = 'generate';
		$data['stype'] = 'generate_sales';
		$data['view_report'] = $this->lang->line('click_view_commission_stats');
		
		$data['num_options'] = get_default_currency($data['sts_site_default_currency']);
		
		$data['report_id'] = 'stats_graph';
		
		//reset total amount
		$data['total_amount'] = '0';
		
		//check for current month and date
		$m = $this->uri->segment('5');
		$y = $this->uri->segment('6');
		
		$data['cmonth'] = 1;
		$data['cyear'] = !(empty($y)) ? $y : date("Y");
		
		$data['tracking_id'] = $this->uri->segment(4);
		
		//setup dropdown for selecting months
		$data['select_months'] = _generate_year_dropdown($data['cmonth'] . '/' . $data['cyear']);
	
		//generate the data
		$stats = $this->modules->_generate_report($data['cyear'], (int)$this->uri->segment(4));
		
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
			
			//$data['graph_data'] = $this->reports->_generate_flash_data($srow, true);
			$data['show_graph'] = $this->reports->_generate_js_chart_data($srow, $data['report_id'], 'HighRollerColumnChart', $data['page_title'], '#DB887F' );
		}
		$data['print_page'] = $this->uri->segment('7', '0');
		
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
		
		$data['tracker_id'] = (int)$this->uri->segment(4);

		//archive the report
		if ($data['print_page'] == 'archive')
		{
			
			$name = date('F', mktime(0, 0, 0, $data['cmonth']  , '1', $data['cyear'])) . ' ' . $data['cyear'] . ' ' . $data['page_title'] . ': ' . date($data['sts_admin_time_format'], _generate_timestamp());
			
			$data['page_title'] = $name;	
			
			$html_data = load_form('modules', 'tpl_adm_bar_graph_report2', $data, true);
			
			$sdata = $this->reports->_generate_archive($name, htmlentities($html_data));
		
			if ($sdata)
			{
				redirect(admin_url() . 'reports_archive/view_archive/');
				exit();
			}

		}
		
		//load template
		load_admin_tpl('modules', 'tpl_adm_bar_graph_report2', $data, $top, $bottom);
						
	}
	
	// ------------------------------------------------------------------------
	
	function generate_sales()
	{
		
		/*
		| -----------------------------------------
		| generate report
		| -----------------------------------------
		*/

		//set data array
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		//get ad tracking details
		$tracker = $this->tracking_model->_get_tracking_details((int)$this->uri->segment(4));
		
		$data['page_title'] = $tracker['name'] . ' - ' . $this->lang->line('monthly_ad_tracking_stats_by_year') . ' (' . $this->lang->line('commissions') . ')';
		$data['row_name'] = 'month';
		$data['row_amount'] = 'amount';
		$data['type'] = 'generate_sales';
		$data['stype'] = 'generate';
		$data['view_report'] = $this->lang->line('click_view_click_stats');
		
		$data['num_options'] = get_default_currency($data['sts_site_default_currency']);
		
		$data['report_id'] = 'stats_graph';
		
		//reset total amount
		$data['total_amount'] = '0';
		
		//check for current month and date
		$m = $this->uri->segment('5');
		$y = $this->uri->segment('6');
		
		$data['cmonth'] = 1;
		$data['cyear'] = !(empty($y)) ? $y : date("Y");
		
		$data['tracking_id'] = (int)$this->uri->segment(4);
		
		//setup dropdown for selecting months
		$data['select_months'] = _generate_year_dropdown($data['cmonth'] . '/' . $data['cyear']);
	
		//generate the data
		$stats = $this->modules->_generate_sales_report($data['cyear'], (int)$this->uri->segment(4));
		
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
			$data['show_graph'] = $this->reports->_generate_js_chart_data($srow, $data['report_id'], 'HighRollerColumnChart', $data['page_title'], '#DB887F' );
		}
		$data['print_page'] = $this->uri->segment('7', '0');
		
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

		
		$data['tracker_id'] = (int)$this->uri->segment(4);

		//archive the report
		if ($data['print_page'] == 'archive')
		{
			
			$name = date('F', mktime(0, 0, 0, $data['cmonth']  , '1', $data['cyear'])) . ' ' . $data['cyear'] . ' ' . $data['page_title'] . ': ' . date($data['sts_admin_time_format'], _generate_timestamp());
			
			$data['page_title'] = $name;	
			
			$html_data = load_form('modules', 'tpl_adm_bar_graph_report2', $data, true);
			
			$sdata = $this->reports->_generate_archive($name, htmlentities($html_data));
		
			if ($sdata)
			{
				redirect(admin_url() . 'reports_archive/view_archive/');
				exit();
			}

		}
		
		//load template
		load_admin_tpl('modules', 'tpl_adm_bar_graph_report2', $data, $top, $bottom);
						
	}
	
	// ------------------------------------------------------------------------
	
}
?>