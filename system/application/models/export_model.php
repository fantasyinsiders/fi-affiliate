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
| FILENAME - import_export_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for import_export
|
*/

class Export_Model extends CI_Model {

	function _check_config($id = '')
	{
		$this->db->where('module_id', $id);
		$this->db->where('module_type', 'data_export');
		$query = $this->db->get('modules');
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
		return false;
	}

	// ------------------------------------------------------------------------	
	
	function _get_export_details($id = '')
	{
		//$this->db->join('settings', 'import_export.import_export_id = settings.settings_group');
		$this->db->where('module_id', $id);
		$this->db->where('module_type', 'data_export');
		$query = $this->db->get('modules');
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();	
			
			$row['sts_config'] = array();
			
			//get any configs
			$this->db->where('settings_module', 'data_export');
			$this->db->where('settings_group', $id);
			$this->db->order_by('settings_sort_order', 'ASC');
			$query = $this->db->get('settings');
		
			//add the options to the config array
			foreach ($query->result_array() as $options)
			{
				array_push($row['sts_config'], $options);
			}
			
			return $row;
		}
		
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_export($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_exp_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_exp_column');

		$this->db->order_by($sort_column, $sort_order); 	
		$this->db->where('module_type', 'data_export');
		$this->db->where('module_status', '1');
		$query = $this->db->get('modules', $limit, $offset);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}
	
	
	
	// ------------------------------------------------------------------------	
	
	function _update_export()
	{
		//clean the data first
		$data = $this->db_validation_model->_clean_data($_POST);
		
		if ($this->db_validation_model->_update_db_settings($data))
		{
			//log success
			log_message('info', 'settings table updated successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_update_settings'));
			
			//log error
			log_message('error', 'Could not update settings table');
			
			return false;		
		}
		
		return true;
	}	
	
	// ------------------------------------------------------------------------	
	
}
?>