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
*/

class Reports extends Admin_Controller {
	
	function __construct()
	{
	
		parent::__construct();

		$this->load->model('reports_model', 'reports');
		
		$this->config->set_item('menu', 'r');
		
		$this->load->helper('inflector');
		
		$colors = array(	'#003366',
            				'#0066CC',
							'#FF3300'
            			);
		
		$this->config->set_item('random_bg_color', random_element($colors));		
			
	}
	
	// ------------------------------------------------------------------------
	
	function user_quick_stats()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['report_id'] = 'quickstatstraffic';

		$num_options = get_default_currency($data['sts_site_default_currency']);
		
		$this->config->set_item('num_options', $num_options);

		$stats = $this->reports->_generate_quick_stats_traffic((int)$this->uri->segment(4));
		
		if (empty($stats))
		{
            echo '<h1 align="center" style="color: #666; font-family: Arial; margin: 1em; padding-top:2em; background: #fff;  height: 260px">' . $this->lang->line('no_affiliate_traffic') . '</h1>';
			exit();
		}
		else
		{
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
				
				$stats2 = $this->reports->_generate_quick_stats_commissions((int)$this->uri->segment(4));
				
				$srow2 = !empty($stats2) ? $stats2[0] : '';
				
				$data['graph_data'] = $this->reports->_generate_js_chart_data($srow, $data['report_id'], 'HighRollerColumnChart', $this->lang->line('traffic'), '#DB887F', false, '#ffffff', false, $srow2, '#4572A7', 'comms');
			}
		}
		
		load_form('admin', 'tpl_adm_reports_quickstats', $data);	
	}
	
	// ------------------------------------------------------------------------
	
	function quick_stats_traffic()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['report_id'] = 'quickstatstraffic';
		
		$stats = $this->reports->_generate_quick_stats_traffic($this->uri->segment(4));
		
		
		if (empty($stats))
		{
			echo '<h1 align="center" style="color: #666; font-family: Arial; margin: 1em; padding-top:2em; background: #fff;  height: 260px">' . $this->lang->line('no_affiliate_traffic') . '</h1>';
			exit();
		}
		else
		{
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
				
				$data['graph_data'] = $this->reports->_generate_js_chart_data($srow, $data['report_id'], 'HighRollerColumnChart', $this->lang->line('traffic'), '#4572A7');

			}
		}
		
		
		
		load_form('admin', 'tpl_adm_reports_quickstats', $data);
	}
	
	// ------------------------------------------------------------------------
	
	function quick_stats_commissions()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['report_id'] = 'quickstatscomm';

		$stats = $this->reports->_generate_quick_stats_commissions();
		
		if (empty($stats))
		{
            echo '<h1 align="center" style="color: #666; font-family: Arial; margin: 1em; padding-top:2em; background: #fff;  height: 260px">' . $this->lang->line('no_commissions_made') . '</h1>';
		}
		else
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
		
			$data['graph_data'] = $this->reports->_generate_js_chart_data($srow, $data['report_id'], 'HighRollerAreaChart', $this->lang->line('commissions'), '#DB887F' );

		}
		
		
		load_form('admin', 'tpl_adm_reports_quickstats', $data);
	}
	
	// ------------------------------------------------------------------------
	
	function quick_stats_signups()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['report_id'] = 'quickstatscomm';

		$stats = $this->reports->_generate_weekly_stats_signups();
		
		if (empty($stats))
		{
            echo '<h1 align="center" style="color: #666; font-family: Arial; margin: 1em; padding-top:2em; background: #fff;  height: 260px">' . $this->lang->line('no_new_signups') . '</h1>';
			exit();
		}
		else
		{
			$srow = $stats[0];
			
			$data['stats'] = array();
			
			foreach ($srow as $k => $v)
			{	
				if ($k == 'id') continue;
				
				$arr = array($k, (int)$v);
							 
				array_push($data['stats'], $arr);
			}

			$data['graph_data'] = $this->reports->_generate_js_chart_data($data['stats'], $data['report_id'], 'HighRollerPieChart', $this->lang->line('commissions'), '#DB887F' );

		}
		
		load_form('admin', 'tpl_adm_reports_quickstats', $data);
	}
	
	// ------------------------------------------------------------------------
	
	function view_reports()
	{

		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		$data['reports'] = $this->reports->_get_reports($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);

		$row_count = $this->db_validation_model->_get_count('modules', 'WHERE module_type = \'stats_reporting\' AND module_status = \'1\'');
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'reports', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column'], $row_count);

		load_admin_tpl('admin', 'tpl_adm_manage_reports', $data);	

	}
	
	// ------------------------------------------------------------------------
}
?>