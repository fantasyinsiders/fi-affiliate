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
| FILENAME - module_member_reporting_month_sales_stats_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for generating reports
|
*/

class Module_Member_Reporting_Month_Sales_Stats_Model extends CI_Model {	
	
	function _install_jrox_module($id = '')
	{	
		//check admin session
		$this->security_model->_check_admin_session();
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _generate_report($id = '', $cmonth = '', $cyear = '')
	{				
		$days = date('t', mktime(0, 0, 0, $cmonth  , '1', $cyear));
		$month = date('M', mktime(0, 0, 0, $cmonth  , '1', $cyear));
		
		$sql = 'SELECT comm_id as id, ';
		
		for ($i = 1; $i <=$days; $i++)
		{
			//day 7
			$start = mktime(0, 0, 0, $cmonth  , $i, $cyear);
			$end = mktime(23, 59, 59, $cmonth  , $i, $cyear);
			$day = date('d', mktime(12, 0, 0, $cmonth  , $i, $cyear));
			
			if ($this->config->item('sts_affiliate_show_pending_comms_members') == 0)
			{
				$sql .= '(SELECT SUM(sale_amount) 
						  FROM ' . $this->db->dbprefix('commissions') . ' 
						  WHERE date >= ' . $start . ' 
						  AND date <= ' . $end . ' AND member_id = ' . $id . '
						  AND member_id = \'' . $id . '\' 
						  AND comm_status != \'pending\') 
						  AS ' . $month . '_' . $day . '
						  ';
			}
			else
			{
			
				$sql .= '(SELECT SUM(sale_amount) 
						  FROM ' . $this->db->dbprefix('commissions') . ' 
						  WHERE date >= ' . $start . ' 
						  AND date <= ' . $end . ' AND member_id = ' . $id . ') 
						  AS ' . $month . '_' . $day;
			}
			
			if ($i < $days) $sql .= ', ';
		}
		
		$sql .= ' FROM ' . $this->db->dbprefix('commissions') . ' LIMIT 1';
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
}
?>