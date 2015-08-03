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
| FILENAME - module_data_export_commissions_model.php
| -------------------------------------------------------------------------     
| 
| This controller file is for functions for exporting commissions
|
*/


class Module_Data_Export_Commissions_By_Date_Model extends CI_Model {

	function _install_jrox_module($id = '')
	{	
		//insert required config rows in settings table
		$config = array(
							'settings_key'	=>	'module_data_export_commissions_by_date_delimiter',
							'settings_value'	=>	',',
							'settings_module'	=>	'data_export',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'1',
							'settings_function'	=>	'none',
							);
		
		//insert into settings table
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_data_export_commissions_by_date_commission_type',
							'settings_value'	=>	'all',
							'settings_module'	=>	'data_export',
							'settings_type'	=>	'dropdown',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'2',
							'settings_function'	=>	'commission_status',
							);
		
		//insert into settings table
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_data_export_commissions_by_date_start_date',
							'settings_value'	=>	_generate_timestamp(),
							'settings_module'	=>	'data_export',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'3',
							'settings_function'	=>	'date',
							);
		
		//insert into settings table
		$this->db->insert('settings', $config);
		
		$config = array(
							'settings_key'	=>	'module_data_export_commissions_by_date_end_date',
							'settings_value'	=>	_generate_timestamp(),
							'settings_module'	=>	'data_export',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'4',
							'settings_function'	=>	'date',
							);
		
		//insert into settings table
		if ($this->db->insert('settings', $config))
		{
			return true;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_commissions_by_date($start = '', $end = '', $id = '')
	{
		$sql = 'SELECT ' . $this->db->dbprefix('commissions') . '.*,
				FROM_UNIXTIME(date) as date,
				FROM_UNIXTIME(date_paid) as date_paid
				FROM  ' . $this->db->dbprefix('commissions') . '
				WHERE date > \'' . $start . '\' AND 
				date < \'' . $end . '\'';
				
		if ($this->config->item('module_data_export_commissions_by_date_commission_type') != 'all')
		{
			$sql .= ' AND comm_status = \'' . $this->config->item('module_data_export_commissions_by_date_commission_type') . '\'';
		}
		
		if (!empty($id))
		{
			$sql .= ' AND member_id = \'' . (int)$id . '\'';	
		}
		
		$sql .= ' ORDER BY ' . $this->db->dbprefix('commissions') . '.comm_id ASC LIMIT ' . $this->config->item('module_data_export_commissions_starting_rows') . ', ' . $this->config->item('module_data_export_commissions_total_rows');

		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			return $query;
		}
		 
		return  false;

	}
	
	// ------------------------------------------------------------------------
	
	
}
?>