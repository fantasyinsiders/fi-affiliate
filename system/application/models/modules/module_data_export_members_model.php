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
| FILENAME - module_data_export_members_csv_model.php
| -------------------------------------------------------------------------     
| 
| This controller file is for exporting members functions
|
*/


class Module_Data_Export_Members_Model extends CI_Model {

	function _install_jrox_module($id = '')
	{	
		//insert required config rows in settings table
		$config = array(
							'settings_key'	=>	'module_data_export_members_delimiter',
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
							'settings_key'	=>	'module_data_export_members_total_rows',
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
							'settings_key'	=>	'module_data_export_members_starting_rows',
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
	
	// ------------------------------------------------------------------------
	
	function _get_members()
	{
		$starting_row = $this->config->item('module_data_export_members_starting_rows') == '' ? '1' : $this->config->item('module_data_export_members_starting_rows');
		$total_rows = $this->config->item('module_data_export_members_total_rows') == '' ? '500' : $this->config->item('module_data_export_members_total_rows');
		
		$sql = 'SELECT ' . $this->db->dbprefix('members') . '.*,  
				FROM_UNIXTIME(signup_date) as signup_date,
				FROM_UNIXTIME(last_login_date) as last_login_date,
				FROM_UNIXTIME(updated_on) as updated_on,
				(SELECT ' . $this->db->dbprefix('countries') . '.country_name 
				FROM ' . $this->db->dbprefix('countries') . ' 
				WHERE ' . $this->db->dbprefix('countries') . '.country_id = ' . $this->db->dbprefix('members') . '.billing_country) as billing_country, 
				(SELECT ' . $this->db->dbprefix('countries') . '.country_name 
				FROM ' . $this->db->dbprefix('countries') . ' 
				WHERE ' . $this->db->dbprefix('countries') . '.country_id = ' . $this->db->dbprefix('members') . '.payment_country) as payment_country,
				(SELECT ' . $this->db->dbprefix('members_groups') . '.group_id 
				FROM ' . $this->db->dbprefix('members_groups') . ' 
				WHERE ' . $this->db->dbprefix('members_groups') . '.member_id = ' . $this->db->dbprefix('members') . '.member_id) as group_id
				FROM  ' . $this->db->dbprefix('members') . '
				ORDER BY ' . $this->db->dbprefix('members') . '.member_id ASC LIMIT ' . $starting_row . ', ' . $total_rows;

					//ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;

		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{ 
			$data['list_fields'] = array();
			$data['result_array'] = array();
			
			$i = 0;
			foreach ($query->result_array() as $row)
			{
				if ($i == 0)
				{
					foreach ($row as $k => $v)
					{
						array_push($data['list_fields'], $k);	
					}
				}
				else
				{
				
					
					array_push($data['result_array'], $row);
				}
				$i++;
			}
			
			return $data;
		}
		 
		return  false;

	}
	
	// ------------------------------------------------------------------------
	
	
}
?>