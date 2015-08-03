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
| FILENAME - groups_model.php
| -------------------------------------------------------------------------     
| 
*/

class Groups_Model extends CI_Model {	
	
	function _get_member_group_id($id = '', $type = '')
	{
		$this->db->where('group_type', $type);
		$this->db->where('member_id', $id);
		
		$query = $this->db->get('members_groups');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_group_code($code = '')
	{
		$this->db->where('aff_group_code', $code);
		
		$query = $this->db->get('affiliate_groups');
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			
			return $row['group_id'];
		}
		
		return false;	
	}
	
	// ------------------------------------------------------------------------
	
	function _add_affiliate_group($post = array())
	{
		$data = $this->db_validation_model->_clean_data($post);
		
		if (!$this->db->insert('affiliate_groups', $data))
		{
			show_error($this->lang->line('could_not_add_group'));
			
			log_message('error', 'Could not insert group into affiliate groups table');
			
			return false;
		}
		else
		{
			$id = $this->db->insert_id();
			
			log_message('info', 'affiliate group '. $id . ' inserted into affiliate groups table');
		}
		
		return $id;
	}

	// ------------------------------------------------------------------------
	
	function _update_member_group($id = '', $group_id = '')
	{
		$this->db->where('member_id', $id);
		
		$query = $this->db->get('members_groups');
		
		if ($query->num_rows() > 0)
		{
			$aff_group = array('group_id' => $group_id);
			
			$this->db->where('member_id', $id);
			
			if ($this->db->update('members_groups', $aff_group))
			{
				return true;
			}
		}
		else
		{
			$aff_group = array('group_id' => $group_id,
							   'member_id' => $id
							   );
			
			if ($this->db->insert('members_groups', $aff_group))
			{
				return true;
			}
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _change_aff_group_status()
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		foreach ($data as $k => $v)
		{
			if (strstr($k, "group") == true) 
			{
				$id = explode("-", $k);
			
				$this->db->where('group_id', $id[1]);
					
				$data = array('tier' => $v);
				
				if (!$this->db->update('affiliate_groups', $data))
				{
					show_error($this->lang->line('could_not_update_affiliate_group'));
					
					log_message('error', 'Could not update affiliate group #' . $k . ' in affiliate groups table');
					
					return false;
				}
			}
		}
	
		$this->db_validation_model->_db_sort_order('affiliate_groups', 'group_id', 'tier'); 
		
		return true;		
	}
	
	// ------------------------------------------------------------------------
	
	function _delete_aff_group($id = '')
	{
		if ($id == 1)
		{
			return false;
		}	
		
		$this->db->where('group_id', $id);
		
		$data = array('group_id' => 1);

		if ($this->db->update('members_groups', $data))
		{
			log_message('info', 'members group ID #' . $id . ' updated successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_delete_group'));
			
			log_message('error', 'members group ID #' . $id . ' could not be updated');
		}
		

		$this->db->where('group_id', $id);

		if ($this->db->update('programs', array('group_id' => 1)))
		{
			log_message('info', 'program group ID #' . $id . ' updated successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_delete_group'));
			
			log_message('error', 'program group ID #' . $id . ' could not be updated');
		}
	
		$this->db->where('group_id', $id);
		
		if ($this->db->delete('affiliate_groups'))
		{
			log_message('info', 'group ID #' . $id . ' deleted successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_delete_group'));
			
			log_message('error', 'affiliate group ID #' . $id . ' could not be deleted');
		}
		
		return true;
	}	
	
	
	// ------------------------------------------------------------------------
	
	function _get_list_group($id = '', $default = false)
	{
		$group = $this->_get_aff_group_details($id);	
		
		if (empty($group))
		{
			if ($default == true)
			{
				return '1';	
			}
		}
		
		return $group['mailing_list_id'];
	}
	
	// ------------------------------------------------------------------------

	function _get_aff_group_details($id = '')
	{
		$this->db->where('group_id', $id);
	
		$query = $this->db->get('affiliate_groups');
		
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
	
	function _check_user_group($type = '', $id = '')
	{
		$this->db->where('group_type', $type);
		$this->db->where('member_id', $id);
		$this->db->join($type . '_groups', 'members_groups.group_id = ' . $type . '_groups.group_id', 'left');
	
		$query = $this->db->get('members_groups');
		
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
	
	function _get_all_affiliate_groups($sort_order = '', $sort_column = '')
	{
		if (!$sort_order) $sort_order = $this->config->item('dbs_ag_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_ag_column');
		
		$sql = 'SELECT ' . $this->db->dbprefix('affiliate_groups') . '.*
				FROM ' . $this->db->dbprefix('affiliate_groups') . '
				ORDER BY ' . $sort_column . ' ' . $sort_order;
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_affiliate_groups($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		if (!$sort_order) $sort_order = $this->config->item('dbs_ag_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_ag_column');

		$sql = 'SELECT ' . $this->db->dbprefix('affiliate_groups') . '.*,
				(SELECT COUNT(*) from ' . $this->db->dbprefix('members_groups') . ' 
				WHERE ' . $this->db->dbprefix('affiliate_groups') . '.group_id = ' . $this->db->dbprefix('members_groups') . '.group_id ) as total
				FROM ' . $this->db->dbprefix('affiliate_groups') . '
				ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}

	// ------------------------------------------------------------------------
	
	function _update_affiliate_group($id = '', $post = array())
	{
		$data = $this->db_validation_model->_clean_data($post);
		
		$this->db->where('group_id', $id);
		
		if (!$this->db->update('affiliate_groups', $data))
		{
			show_error($this->lang->line('could_not_update_group'));
			
			log_message('error', 'Could not update group ID ' . $id . 'in affiliate groups table');
			
			return false;
		}
		else
		{
			log_message('info', 'group ID '. $id . ' updated in affiliate groups table');
		}
	}
	
	// ------------------------------------------------------------------------	
}
?>