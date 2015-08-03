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
| FILENAME - modules_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for managing modules
|
*/

class Modules_Model extends CI_Model {	
	
	function _add_module($post = array())
	{
		$data = $this->db_validation_model->_clean_data($post);
		
		$data['module_status'] = '0';
		
		//insert into db
		if (!$this->db->insert('modules', $data))
		{
			show_error($this->lang->line('could_not_add_module'));
			
			//log error
			log_message('error', 'Could not insert module into modules table');
			
			return false;
		}
		else
		{
			$data['module_id'] = $this->db->insert_id();
			
			//log success
			log_message('info', 'module '. $data['module_id'] . ' inserted into modules table');
		}
		
		return $data;
	}
	
	// ------------------------------------------------------------------------
	
	function _list_modules($type = 'add')
	{
		$this->load->helper('directory_helper');
			
		$mods = directory_map('system/application/controllers/modules');		
		
		$i = array();
		if ($type == 'add')
		{
			$this->db->select('module_file_name');
			$query = $this->db->get('modules');
			
			foreach ($query->result_array() as $v)
			{
				array_push($i, $v['module_file_name']);
			}
		}
		
		$a = array();
		foreach ($mods as $b)
		{
			if (substr($b, 0,6) == 'module')
			{
				$e = $b;
				$b = str_replace('module_', '', $b);
				$b = str_replace('.php', '', $b);
				foreach ($this->module_options as $c => $d)
				{
					$b = str_replace($c . '_', '', $b);	
				}
				
				if (!in_array($b, $i))
				{
					$a[$b] = $e;	
				}
			}
		}
		
		return $a;
	}
	
	// ------------------------------------------------------------------------
	
	function _delete_module($id = '')
	{
		
		//update product modules first
		$data = array('post_purchase_module_id' => 0);
		
		//get the module details first
		$this->db->where('module_id', $id);
		$get = $this->db->get('modules');
		
		$row = $get->result_array();
		
		if (!empty($row))
		{	
			//delete from settings table
			$this->db->where('settings_group', $id);
			$this->db->where('settings_module', $row[0]['module_type']);
			$query = $this->db->delete('settings');
			
		}
	
		//delete module
		$this->db->where('module_id', $id);
		if ($this->db->delete('modules'))
		{
			
			//log success
			log_message('info', 'module ID #' . $id . ' deleted successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_delete_module'));
			
			//log error
			log_message('error', 'module ID #' . $id . ' could not be deleted');
		}
		
		return true;
	}	
	
	// ------------------------------------------------------------------------	
	
	function _get_module_options($id = '', $module_type = '')
	{
		$this->db->where('module_id', $id);
		
		if (!empty($module_type))
		{
			$this->db->where('module_type', $module_type);
		}
		
		$query = $this->db->get('modules');
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();	
			
			$row['sts_config'] = array();
			
			$this->db->where('settings_module', $row['module_type']);
			$this->db->where('settings_group', $id);
			$this->db->order_by('settings_sort_order', 'ASC');
			
			$query = $this->db->get('settings');
	
			foreach ($query->result_array() as $options)
			{
				array_push($row['sts_config'], $options);
			}
			
			return $row;
		}
		
	}
	
	// ------------------------------------------------------------------------

	function _get_module_details($id = '')
	{
		//get the data from modules table
		$this->db->where('module_id', $id);
		$query = $this->db->get('modules');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
		{
			return false;
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _get_module_column($column = '', $value = '')
	{
		$this->db->where($column, $value);
		$query = $this->db->get('modules');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return false;
	}
	
	
	// ------------------------------------------------------------------------
	
	function _get_modules($limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $where_column = '', $where_value = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_mod_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_mod_column');

		if (!empty($where_column) && !empty($where_value))
		{
			$this->db->where($where_column, $where_value);	
		}

		$this->db->order_by($sort_column, $sort_order); 	
		$query = $this->db->get('modules', $limit, $offset);
		
		if ($query->num_rows() > 0)
		{
			$data = array();
			$data['rows'] =  $query->result_array();
			
			if (!empty($where_column) && !empty($where_value))
			{
				$this->db->where($where_column, $where_value);	
			}
			$data['total_rows'] = $this->db->count_all_results('modules');
			
			return $data;
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_module_install($type = '', $name = '', $status = false)
	{
		//check if module is already installed
		$this->db->where('module_file_name', $name);
		$this->db->where('module_type', $type);
		$query = $this->db->get('modules');
		
		if ($query->num_rows() > 0)
		{
			if ($status == true)
			{
				$row = $query->row_array();
				if ($row['module_status'] == 1)
				{
					return true;	
				}
			}
			else
			{
				return true;
			}
		}
		
		return false;
	}

	// ------------------------------------------------------------------------	
	
	function _run_modules($type = '')
	{
		$this->db->where('module_type', $type);
		$this->db->where('module_status', '1');
		
		$query = $this->db->get('modules');
		
		if ($query->num_rows() > 0)
		{
			$data =  $query->result_array();
			
			return $data;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _run_module($id = '')
	{
		$this->db->where('module_id', $id);
		$this->db->where('module_status', '1');
		
		$query = $this->db->get('modules');
		
		if ($query->num_rows() > 0)
		{
			$data =  $query->row_array();
			
			return $data;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _update_options($post = array())
	{
		$data = $this->db_validation_model->_clean_data($post);
		
		if ($this->db_validation_model->_update_db_settings($data))
		{
			log_message('info', 'settings table updated successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_update_settings'));
			
			log_message('error', 'Could not update settings table');
			
			return false;		
		}
		
		return true;
	}	
	
	// ------------------------------------------------------------------------
	
	function _update_module($id = '')
	{
		//clean the data first
		$data = $this->db_validation_model->_clean_data($_POST);
		
		//update module data
		$this->db->where('module_id', $id);
		
		if (!$this->db->update('modules', $data))
		{
			show_error($this->lang->line('could_not_update_module'));
			
			//log error
			log_message('error', 'Could not update module ID ' . $id . 'in modules table');
			
			return false;
		}
		else
		{
			
			//log success
			log_message('info', 'module ID '. $id . ' updated in modules table');
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
}
?>