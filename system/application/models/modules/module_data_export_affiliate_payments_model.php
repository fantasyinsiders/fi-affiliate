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
| FILENAME - module_data_export_affiliate_payments_model.php
| -------------------------------------------------------------------------     
| 
| This controller file is for functions for exporting affiliate_payments
|
*/


class Module_Data_Export_Affiliate_Payments_Model extends CI_Model {

	function _install_jrox_module($id = '')
	{	
		//insert required config rows in settings table
		$config = array(
							'settings_key'	=>	'module_data_export_affiliate_payments_delimiter',
							'settings_value'	=>	',',
							'settings_module'	=>	'data_export',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'1',
							'settings_function'	=>	'none',
							);
		
		//insert into settings table
		$this->db->insert('settings', $config);
		
		//insert required config rows in settings table
		$config = array(
							'settings_key'	=>	'module_data_export_affiliate_payments_total_rows',
							'settings_value'	=>	'1000',
							'settings_module'	=>	'data_export',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'2',
							'settings_function'	=>	'none',
							);
		
		//insert into settings table
		$this->db->insert('settings', $config);
		
		
		$config = array(
							'settings_key'	=>	'module_data_export_affiliate_payments_starting_rows',
							'settings_value'	=>	'0',
							'settings_module'	=>	'data_export',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'3',
							'settings_function'	=>	'none',
							);
		
		//insert into settings table
		if ($this->db->insert('settings', $config))
		{
			return true;
		}
		
		return false;
	}
	
	
	function _get_payments()
	{
		$sql = 'SELECT ' . $this->db->dbprefix('affiliate_payments') . '.*, FROM_UNIXTIME(payment_date) as payment_date
				FROM  ' . $this->db->dbprefix('affiliate_payments') . '
				ORDER BY ' . $this->db->dbprefix('affiliate_payments') . '.id ASC LIMIT ' . $this->config->item('module_data_export_affiliate_payments_starting_rows') . ', ' . $this->config->item('module_data_export_affiliate_payments_total_rows');
	
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