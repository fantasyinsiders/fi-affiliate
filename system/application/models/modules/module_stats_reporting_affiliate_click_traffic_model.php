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
| FILENAME - Module_Stats_Reporting_Affiliate_Click_Traffic_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for managing affiliate clicks
|
*/

class Module_Stats_Reporting_Affiliate_Click_Traffic_Model extends CI_Model {	
	
	function _delete_traffic($id = '')
	{
		//delete traffic
		$this->db->where('traffic_id', $id);
		if ($this->db->delete('traffic'))
		{
			
			//log success
			log_message('info', 'traffic ID #' . $id . ' deleted successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_delete_traffic'));
			
			//log error
			log_message('error', 'traffic ID #' . $id . ' could not be deleted');
		}
		
		return true;
	}	

	
	// ------------------------------------------------------------------------
	
	function _get_traffic($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_trf_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_trf_column');

		$sql = 'SELECT ' . $this->db->dbprefix('traffic') . '.*,
				(SELECT username FROM ' . $this->db->dbprefix('members') . '
				WHERE ' . $this->db->dbprefix('traffic') . '.member_id = ' . $this->db->dbprefix('members') . '.member_id) as username
				FROM ' . $this->db->dbprefix('traffic') . '
				WHERE ' . $this->db->dbprefix('traffic') . '.member_id != \'0\'
				ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$row['traffic'] =  $query->result_array();
		
			$sql = 'SELECT COUNT(*) as total FROM ' . $this->db->dbprefix('traffic') . '
				WHERE ' . $this->db->dbprefix('traffic') . '.member_id != \'0\'';
				
			$query = $this->db->query($sql);
		
			$total = $query->row_array();
			
			$row['total_rows'] = $total['total'];
			
			return $row;
		}
		 
		return  false;
	}

	// ------------------------------------------------------------------------
	
	
	
	function _delete_clicks($data = '')
	{

		foreach ($data as $id)
		{
			$this->_delete_traffic((int)($id));
		}
		
		return true;		
	}
	
	// ------------------------------------------------------------------------	
}
?>