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
| FILENAME - module_stats_reporting_month_top_affiliate_clicks_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for generating reports
|
*/

class Module_Stats_Reporting_Month_Top_Affiliate_Clicks_Model extends CI_Model {	
	
	function _install_jrox_module($id = '')
	{	
		//check admin session
		$this->security_model->_check_admin_session();
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _generate_report($cmonth = '', $cyear = '')
	{
		$fdate = explode(':', $this->config->item('sts_admin_date_format'));
				
		$days = date('t', mktime(0, 0, 0, $cmonth  , '1', $cyear));
		$month = date('M', mktime(0, 0, 0, $cmonth  , '1', $cyear));
		
		$start = mktime(0, 0, 0, $cmonth  , '1', $cyear);
		$end = mktime(23, 59, 59, $cmonth  , $days, $cyear);
		
		$sql = 'SELECT member_id, COUNT(traffic_id) as amount,
				(SELECT CONCAT(fname, \' \',lname) FROM ' . $this->db->dbprefix('members') . '
				WHERE ' . $this->db->dbprefix('traffic') . '.member_id = ' . $this->db->dbprefix('members') . '.member_id) as name
				  FROM ' . $this->db->dbprefix('traffic') . ' 
				  WHERE date >= ' . $start . ' 
				  AND date <= ' . $end . ' 
				  GROUP BY member_id
				  ORDER BY amount DESC LIMIT 25';

		
		$query = $this->db->query($sql);
		
		$row = $query->result_array();
		
		return $row;
	}
	
	// ------------------------------------------------------------------------	
}
?>