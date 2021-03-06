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
| FILENAME - Module_Stats_Reporting_Year_Ad_Tracking_Stats_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for generating reports
|
*/

class Module_Stats_Reporting_Year_Ad_Tracking_Stats_Model extends CI_Model {	
	
	function _install_jrox_module($id = '')
	{	
		//check admin session
		$this->security_model->_check_admin_session();
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _generate_report($cyear = '', $id = '')
	{
		$fdate = explode(':', $this->config->item('sts_admin_date_format'));
		
		$sql = 'SELECT tracker as id, ';
		
		for ($i = 1; $i <=12; $i++)
		{
			//number of days in month
			$days = date('t', mktime(0, 0, 0, $i  , '1', $cyear));

			$start = mktime(0, 0, 0, $i  , '1', $cyear);
			$end = mktime(23, 59, 59, $i  ,$days, $cyear);
			
			$month = date('M', mktime(0, 0, 0, $i  , '1', $cyear));
			
			$sql .= '(SELECT COUNT(traffic_id) 
					 FROM ' . $this->db->dbprefix('traffic') . ' 
					  WHERE tracker = \'' . $id . '\'
					  AND date >= ' . $start . ' 
					  AND date <= ' . $end . ') as ' . '_' . $month;
			
			if ($i < 12) $sql .= ', ';
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
	
	function _generate_sales_report($cyear = '', $id = '')
	{
		$fdate = explode(':', $this->config->item('sts_admin_date_format'));
				
		$days = date('t', mktime(0, 0, 0, 1  , '1', $cyear));
		
		//get tracking sales
		$sql = 'SELECT invoice_id as id, ';
	
		for ($i = 1; $i <=12; $i++)
		{
			//number of days in month
			$days = date('t', mktime(0, 0, 0, $i  , '1', $cyear));

			$start = mktime(0, 0, 0, $i  , '1', $cyear);
			$end = mktime(23, 59, 59, $i  ,$days, $cyear);
			
			$month = date('M', mktime(0, 0, 0, $i  , '1', $cyear));
			
			$sql .= '(SELECT SUM(commission_amount) 
					  FROM ' . $this->db->dbprefix('commissions') . ' 
					  WHERE tracking_id = \' ' . $id . '\'
					  AND date >= ' . $start . ' 
					  AND date <= ' . $end . ')  as ' . '_' . $month . ' ';
			
			if ($i < 12) $sql .= ', ';
		}
		
		$sql .= ' FROM ' . $this->db->dbprefix('commissions') . ' LIMIT 1';
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$row = $query->result_array();
			
			return $row;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
}
?>