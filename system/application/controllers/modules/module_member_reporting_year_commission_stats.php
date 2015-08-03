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
| FILENAME - Module_Member_Reporting_Year_Commmission_Stats.php
| -------------------------------------------------------------------------     
|
*/

class Module_Member_Reporting_Year_Commission_Stats extends Member_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('modules/module_member_reporting_year_commission_stats_model', 'modules');
		
		$this->load->model('reports_model', 'reports');
		
		$this->load->helper('inflector');

		$this->config->set_item('module_name', strtolower(__CLASS__));
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(site_url('members') . '/reports/view');
	}
	
	// ------------------------------------------------------------------------
	
	function generate()
	{		
		$this->init->_set_default_program($this->session->userdata('m_signup_link'));
		
		$data = $this->security_model->_load_config('members', __CLASS__,  __FUNCTION__);

		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);

		$data['languages'] = $sdata['languages'];
		
		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		$data['total_amount'] = '0';
		
		$data['page_title'] = $this->lang->line('yearly_commission_stats_by_month');

		$m = $this->uri->segment('4');
		$y = $this->uri->segment('5');
		
		$data['cmonth'] = '1';
		$data['cyear'] = !(empty($y)) ? $y : date("Y");
		
		$data['select_months'] = _generate_year_dropdown($data['cmonth'] . '/' . $data['cyear'], 'form-control' , 'document.location=\'' .  site_url('members') . '/report/' . $data['module_name'] . '/' . '\' + this.value');
		
		include_once ($data['base_physical_path'] . '/themes/main/' . $data['default_theme'] . '/theme_info.php');
		
		$stats = $this->modules->_generate_report($this->session->userdata('userid'), $data['cmonth'], $data['cyear']);
		
		if (!empty($stats))
		{
			$srow = $stats[0];
			
			$data['stats'] = array();

			foreach ($srow as $k => $v)
			{	
				if ($k == 'id') continue;
				
				$arr['date'] = $k;
				$arr['amount'] = $v;
				$arr['s_amount'] = format_amounts($v, $data['num_options']);
				$arr['s_date'] = humanize($k) . ' ' . $data['cyear'];
				
				$arr['row_style'] = alternator('jroxRowStyle1', 'jroxRowStyle2');
				
				 
				array_push($data['stats'], $arr);
				
				$data['total_amount'] += $v;	
			}
			
			$data['total_amount'] = format_amounts($data['total_amount'], $data['num_options']);

			$data['show_graph'] = $this->reports->_generate_js_chart_data($srow, 'stats_graph', 'HighRollerAreaChart', $data['page_title'], $chart_grid_color , false, $chart_bg_color);
		}

		$this->parser->_JROX_load_view('tpl_members_bar_graph_report', 'members', $data);
						
	}
	
	// ------------------------------------------------------------------------
	
}
?>