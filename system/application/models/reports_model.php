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
| FILENAME - reports_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for generating reports
|
*/

class Reports_Model extends CI_Model {	
	
	
	function _generate_weekly_stats_signups($mid = '')
	{
		$fdate = explode(':', $this->config->item('sts_admin_date_format'));

		$cmonth = date('m', _generate_timestamp());
		$cyear = date('Y', _generate_timestamp());
		$total = 6;
		//$days = date('t', mktime(0, 0, 0, $cmonth  , '1', $cyear));
		
		$days = 7;
		$month = date('M', mktime(0, 0, 0, $cmonth  , '1', $cyear));
		
		$sql = 'SELECT member_id as id, ';
		
		for ($i = 0; $i <=$total; $i++)
		{
			//day 7
	
			$cday = date('j') - $i; 
			
			$start = mktime(0, 0, 0, $cmonth  , $cday, $cyear);
			$end = mktime(23, 59, 59, $cmonth  , $cday, $cyear);
			$day = date('d', mktime(12, 0, 0, $cmonth  , $cday, $cyear));
		
			$sql .= '(SELECT COUNT(member_id) 
					  FROM ' . $this->db->dbprefix('members') . ' 
					  WHERE (signup_date >= ' . $start . ' 
					  AND signup_date <= ' . $end . ')';
			
			if (!empty($mid))
			{
				$sql .= ' AND member_id = \'' . $mid . '\'';	
			}
						
			$sql .= ') AS ' . $month . '_' . $day;
			
			if ($i < $total) $sql .= ', ';
			
			$days--;
			
		}
		
		$sql .= ' FROM ' . $this->db->dbprefix('members') . ' LIMIT 1';
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$row = $query->result_array();
			
			return $row;
		}
		
		return false;
	}	

	// ------------------------------------------------------------------------		
	
	function _generate_quick_stats_traffic($mid = '')
	{
		$fdate = explode(':', $this->config->item('sts_admin_date_format'));

		$cmonth = date('m', _generate_timestamp());
		$cyear = date('Y', _generate_timestamp());
		
		$days = date('t', mktime(0, 0, 0, $cmonth  , '1', $cyear));
		$month = date('M', mktime(0, 0, 0, $cmonth  , '1', $cyear));
		
		$sql = 'SELECT traffic_id as id, ';
		
		for ($i = 1; $i <=$days; $i++)
		{
			//day 7
			$start = mktime(0, 0, 0, $cmonth  , $i, $cyear);
			$end = mktime(23, 59, 59, $cmonth  , $i, $cyear);
			$day = date('d', mktime(12, 0, 0, $cmonth  , $i, $cyear));
		
			$sql .= '(SELECT COUNT(traffic_id) 
					  FROM ' . $this->db->dbprefix('traffic') . ' 
					  WHERE (date >= ' . $start . ' 
					  AND date <= ' . $end . ')';
			
			if (!empty($mid))
			{
				$sql .= ' AND member_id = \'' . $mid . '\'';	
			}
						
			$sql .= ') AS ' . $month . '_' . $day;
			
			if ($i < $days) $sql .= ', ';
		}
		
		$sql .= ' FROM ' . $this->db->dbprefix('traffic') . ' LIMIT 1';
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$row = $query->result_array();
			
			return $row;
		}
		
		return false;
	}	
	
	// ------------------------------------------------------------------------	
	
	function _generate_quick_stats_commissions($mid = '')
	{
		$fdate = explode(':', $this->config->item('sts_admin_date_format'));
		
		$cmonth = date('m', _generate_timestamp());
		$cyear = date('Y', _generate_timestamp());
		
		$days = date('t', mktime(0, 0, 0, $cmonth  , '1', $cyear));
		$month = date('M', mktime(0, 0, 0, $cmonth  , '1', $cyear));
		
		$sql = 'SELECT comm_id as id, ';
		
		for ($i = 1; $i <=$days; $i++)
		{
			//day 7
			$start = mktime(0, 0, 0, $cmonth  , $i, $cyear);
			$end = mktime(23, 59, 59, $cmonth  , $i, $cyear);
			$day = date('d', mktime(12, 0, 0, $cmonth  , $i, $cyear));
			
			$sql .= '(SELECT SUM(commission_amount) 
					  FROM ' . $this->db->dbprefix('commissions') . ' 
					  WHERE (date >= ' . $start . ' 
					  AND date <= ' . $end . ')';
			
			if (!empty($mid))
			{
				$sql .= ' AND member_id = \'' . $mid . '\'';	
			}
						
			$sql .= ') AS ' . $month . '_' . $day;
			
			if ($i < $days) $sql .= ', ';
		}
		
		$sql .= ' FROM ' . $this->db->dbprefix('commissions') . ' LIMIT 1';
		
		$query = $this->db->query($sql);
		
		$row = $query->result_array();
	
		return $row;
	}	
	
	// ------------------------------------------------------------------------	
	
	function _get_reports($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_rep_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_rep_column');
		
		$this->db->order_by($sort_column, $sort_order); 	
		$this->db->where('module_type', 'stats_reporting');
		$this->db->where('module_status', '1');
		$query = $this->db->get('modules', $limit, $offset);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_member_reports($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('mem_dbs_rep_order');
		if (!$sort_column) $sort_column = $this->config->item('mem_dbs_rep_column');
		
		$this->db->order_by($sort_column, $sort_order); 	
		$this->db->where('module_type', 'member_reporting');
		$this->db->where('module_status', '1');
		$query = $this->db->get('modules', $limit, $offset);
		
		if ($query->num_rows() > 0)
		{
			$a = array();
			foreach ($query->result_array() as $row)
			{
				$row['row_style'] = alternator('jroxRowStyle1', 'jroxRowStyle2');
				
				array_push($a, $row);
			}
			
			return $a;
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_reports_archive($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_arc_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_arc_column');
		
		$this->db->order_by($sort_column, $sort_order); 	
		$query = $this->db->get('report_archive', $limit, $offset);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_archive_details($id = '')
	{
		
		$this->db->where('id', $id); 	
		$query = $this->db->get('report_archive');
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _delete_archived_report($id = '')
	{
		
		$this->db->where('id', $id); 	
		if ($query = $this->db->delete('report_archive'))
		{
			return true;
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _generate_js_chart_data($srow = '', $report_id = 'linechart', $graph_type = 'HighRollerLineChart', $title = 'graph', $color = '#036', $currency = false, $bg_color = '#ffffff', $top = false, $srow2 = '', $color2 = '#DB887F', $name2 = '', $currency2 = true)
	{
		$values = array();
		$keys = array();
		
		//generate data for flash table
		require_once(APPPATH . 'libraries/highcharts/HighRoller.php');
		require_once(APPPATH . 'libraries/highcharts/HighRollerSeriesData.php');
	  	require_once(APPPATH . 'libraries/highcharts/' . $graph_type . '.php');
		
		$name = 'total';
		
		if ($graph_type == 'HighRollerPieChart')
		{
			$values = $srow;
		}	
		else
		{
			if ($top == true)
			{
				foreach ($srow as $v)
				{	
					array_push($values, (float)$v['amount']);
					array_push($keys, $v['name']);
				}
			}
			else
			{
				foreach ($srow as $k =>$v)
				{			
					if ($k == 'id') continue;
					
					if ($currency == true)
					{
						if (empty($v)) $v = '0.00';
					}
					else
					{
						if (empty($v))
						{
							$v = '0';		
						}
					}
					
					
					array_push ($values, (float)$v);
					$x = explode('_', $k);
					array_push ($keys, $x[1]);
				}
			
				$name = $x[0];
			}
		
		
			//check for second graph
		
			if (!empty($srow2))
			{
				$values2 = array();
				$keys2 = array();
		
				foreach ($srow2 as $k =>$v)
				{			
					if ($k == 'id') continue;
					
					if ($currency2 == true)
					{
						if (empty($v)) $v = '0.00';
					}
					else
					{
						if (empty($v))
						{
							$v = '0';		
						}
					}
					
					
					array_push ($values2, (float)$v);
					$x = explode('_', $k);
					array_push ($keys2, $x[1]);
				}
			}
			
		}
		
		 //TEST
		/*
		$values = array();
		$values2 = array();
		$t = $graph_type == 'HighRollerColumnChart' ? 30 : 7;
		for ($i=1;$i<=$t;$i++)
		{
			$a = rand(21,200);
			array_push($values,$a);
			$a = rand(21,200);
			//array_push($values2,$a);	
		}
		*/
		
		
		//$values = array(array('Foo', 0), array('Bar', 1), array('Baz', 0), array('Fooey',0), array('Barry', 0), array('Bazzy', 0));
		//echo '<pre>'; print_r($srow); _pr($values);
		$series1 = new HighRollerSeriesData();
		$series1->addName($name)->addColor($color)->addData($values);

		if (!empty($values2))
		{
			$series2 = new HighRollerSeriesData();
			$series2->addName($report_id)->addData($values2);
			$series2->addName($name2)->addColor($color2)->addData($values2);
			
		}
		
		
		$linechart = new $graph_type();
		
		$linechart->yAxis = new stdClass();
		$linechart->xAxis = new stdClass();
		$linechart->yAxis->title = new stdClass();
		$linechart->credits = new stdClass();
		$linechart->legend = new stdClass();
		$linechart->plotOptions = new stdClass();
		$linechart->plotOptions->area = new stdClass();
		$linechart->plotOptions->area->marker = new stdClass();
		
		$linechart->chart->renderTo = $report_id;
		$linechart->chart->backgroundColor = $bg_color;
		$linechart->yAxis->title->text = '';
		
		if ($graph_type != 'HighRollerPieChart')
		{
			//sort the values
			if (!empty($values2))
			{
				$sort_array = array_merge($values, $values2);
			}
			else
			{
				$sort_array = $values;	
			}
		}
		
		if ($graph_type != 'HighRollerPieChart')
		{
			rsort($sort_array);
			$linechart->yAxis->max = $sort_array[0];
		}
		$linechart->xAxis->categories =  $keys;
		$linechart->plotOptions->area->marker->enabled = false;
		$linechart->credits->enabled = false;
		$linechart->legend->enabled = false;
		$linechart->addSeries($series1);
		
		if (!empty($values2))
		{
			$linechart->addSeries($series2);
		}
		return $linechart->renderChart();
	}
	
	// ------------------------------------------------------------------------	
	
	function _generate_archive($name = '', $data = '')
	{
		$sdata = array(
					  	'report_date' => _generate_timestamp(),
						'report_name' => $name,
						'report_html' => $data,
					  );
		
		if ($this->db->insert('report_archive', $sdata))
		{
			return $this->db->insert_id();
		}
		
		return false;
	}
}
?>