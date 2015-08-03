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
| FILENAME - search_model.php
| -------------------------------------------------------------------------     
| 
*/

class Search_Model extends CI_Model {	
	
	// ------------------------------------------------------------------------
	
	function _advanced_search($type = 'commissions', $post_array = array(), $offset = '0')
	{
		$data = $this->db_validation_model->_clean_data($post_array);	

		if (!empty($data['search_term']) && !empty($data['search_fields_' . $type]))
		{
			$fields = implode(',', $data['search_fields_' . $type]);
			
			if ($type == 'commissions')
			{
				$sql = 'SELECT *, (SELECT ' . $this->db->dbprefix('members') . '.username FROM ' . $this->db->dbprefix('members') . ' 
						WHERE ' . $this->db->dbprefix('commissions') . '.member_id = ' . $this->db->dbprefix('members') . '.member_id) as username 
						FROM ' . $this->db->dbprefix($data['table']);
			}
			else
			{
				$sql = 'SELECT * FROM ' . $this->db->dbprefix($data['table']);
			}
			$total_sql = 'SELECT COUNT(*) as total FROM ' . $this->db->dbprefix($data['table']); 	
			   
			if (count($data['search_fields_' . $type]) > '0')
			{		
				for ($i = 0; $i < count($data['search_fields_' . $type]); $i++)
				{
					if ($i == 0)
					{
						$sql .= ' WHERE (' . $data['search_fields_' . $type][$i] . ' LIKE \'%' . $data['search_term'] . '%\'';
						$total_sql .= ' WHERE (' . $data['search_fields_' . $type][$i] . ' LIKE \'%' . $data['search_term'] . '%\'';
					}
					else
					{
						$sql .= ' OR  ' . $data['search_fields_' . $type][$i] . ' LIKE \'%' . $data['search_term']. '%\'';
						$total_sql .= ' OR  ' . $data['search_fields_' . $type][$i] . ' LIKE \'%' . $data['search_term']. '%\'';
					}
				}	
			}
			
			$sql .= ')'; 
			$total_sql .= ')'; 
			
			if ($type == 'commissions' && !empty($data['filter_by_date']))
			{
				if (count($data['search_fields_' . $type]) > 0)
				{
					$sql .= ' AND ';
					$total_sql .= ' AND ';
				}
				else
				{
					$sql .= ' WHERE ';
					$total_sql .= ' WHERE ';
				}
				
				$sql .= ' (date > ' .  _save_date($data['start_date'], false, 'min') . ' AND date < ' .  _save_date($data['end_date'], false, 'max') . ')';
				$total_sql .= ' (date > ' .  _save_date($data['start_date'], false, 'min') . ' AND date < ' .  _save_date($data['end_date'], false, 'max') . ')';
			}
			
			
			$sql .= ' ORDER BY ' . $data['sort_column_' . $type] . ' ' . $data['sort_order_' . $type] . ' LIMIT ' . $offset. ', ' . $data['rows'];
			
			$query = $this->db->query($sql);
			
			if ($query->num_rows() > 0)
			{
				$sdata['rows'] = $query->result_array();	
			
				$t = $this->db->query($total_sql);
				$s = $t->row_array();
				$sdata['total_rows'] = $s['total'];
			
				return $sdata;
			}
		}
		
		return false;
	}
}
?>