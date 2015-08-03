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
| FILENAME - integration_model.php
| -------------------------------------------------------------------------     
|  
| This model handles the functions for managing integration profiles
|
*/

class Integration_Model extends CI_Model {	
	
		// ------------------------------------------------------------------------
	
	function _get_integration_value($id = '')
	{
		$this->db->where('id', $id);
		$query = $this->db->get('integration_profiles');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_integration_option($id = '')
	{
		$this->db->where('id', $id);
		$query = $this->db->get('program_integration');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return false;
	}
	
	
	// ------------------------------------------------------------------------
	
	function _delete_integration_value($id = '')
	{
		$this->db->where('id', $id);
		if ($this->db->delete('integration_profiles'))
		{
			return true;	
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _update_integration_value($id = '')
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		$this->db->where('id', $id);
		//insert into db
		if (!$this->db->update('integration_profiles', $data))
		{
			show_error($this->lang->line('could_not_update_integration_value'));
			
			//log error
			log_message('error', 'Could not update integration value in integration values table');
			
			return false;
		}
		else
		{	
			//log success
			log_message('info', 'integration value '. $id . ' update in integration values table');
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _add_integration_value()
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		//insert into db
		if (!$this->db->insert('integration_profiles', $data))
		{
			show_error($this->lang->line('could_not_add_integration_value'));
			
			//log error
			log_message('error', 'Could not insert integration value into integration values table');
			
			return false;
		}
		else
		{
			$id = $this->db->insert_id();
			
			//log success
			log_message('info', 'integration value '. $id . ' inserted into integration values table');
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_integration_methods($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		if (!$sort_order) $sort_order = $this->config->item('dbs_int_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_int_column');

		$this->db->order_by($sort_column, $sort_order); 	
		
		$query = $this->db->get('program_integration', $limit, $offset);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_integration_profiles()
	{
		$query = $this->db->get('integration_profiles');
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
		return false;
	}
}
?>