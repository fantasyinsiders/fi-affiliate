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
| FILENAME - tracking_log_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for managing tracking logs
|
*/

class Tracking_Log_Model extends CI_Model {	
	
	// ------------------------------------------------------------------------
	
	function _change_status($data = array())
	{
		foreach ($data as $id)
		{
			$this->db->where('id', $id);
			
			$this->_delete_log($id);
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _delete_log($id = '')
	{
		$this->db->where('id', $id);
		if ($this->db->delete('tracking_log'))
		{
			//log success
			log_message('info', 'Member ID #' . $id . ' deleted from tracking_log table');
	
			return true;	
		}
		else
		{
			show_error($this->lang->line('could_not_delete_tracking_log'));
				
			//log error
			log_message('error', 'Could not delete Member ID #' . $id . ' from tracking_log table');
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_tracking_logs($id = '', $limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		//get all the affiliate groups from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_trk_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_trk_column');
		
		$sql = 'SELECT ' . $this->db->dbprefix('tracking_log') . '.*
				FROM ' . $this->db->dbprefix('tracking_log') . '
				WHERE member_id = \'' . $id . '\'
				ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------
	
	
}
?>