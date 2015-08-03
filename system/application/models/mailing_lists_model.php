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
| FILENAME - mailing_lists_model.php
| -------------------------------------------------------------------------     
| 
*/

class Mailing_Lists_Model extends CI_Model {	
	
	function _add_mailing_list($post = array())
	{
		$data = $this->db_validation_model->_clean_data($post);
		
		if (isset($data['userfile']))
		{	
			unset($data['userfile']);
		}
		
		//insert into db
		if (!$this->db->insert('email_mailing_lists', $data))
		{
			show_error($this->lang->line('could_not_add_mailing_list'));
			
			//log error
			log_message('error', 'Could not insert mailing_list into mailing_lists table');
			
			return false;
		}
		else
		{
			$mailing_list_id = $this->db->insert_id();
			
			//log success
			log_message('info', 'mailing_list '. $mailing_list_id . ' inserted into mailing_lists table');
		}
		
		return $mailing_list_id;
	}
	
	// ------------------------------------------------------------------------	
	
	function _mass_remove_mailing_list($name = '', $list = '')
	{
		//check if category name is valid
		$this->db->where('mailing_list_name', $list);
		$query = $this->db->get('email_mailing_lists');

		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			
			$id = $row['mailing_list_id'];
			
			//now loop through the products
			foreach ($name as $v)
			{
				//check if name and price is not blank
				$this->db->where('member_id', $v);
				$this->db->where('mailing_list_id', $id);
				$this->db->delete('email_mailing_list_members');
			}	
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _mass_add_mailing_list($name = '', $list = '')
	{
		//check if category name is valid
		$this->db->where('mailing_list_name', $list);
		$query = $this->db->get('email_mailing_lists');

		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			
			$id = $row['mailing_list_id'];
			
			//now loop through the products
			foreach ($name as $v)
			{
				//check if name and price is not blank
				$this->db->where('member_id', $v);
				$this->db->where('mailing_list_id', $id);
				$squery = $this->db->get('email_mailing_list_members');
				
				if ($squery->num_rows() < 1)
				{
					$insert = array('member_id' => $v,
									'mailing_list_id' => $id,
									'sequence_id' => 1,
									'send_date' => _generate_timestamp(),
									);
					$this->db->insert('email_mailing_list_members', $insert);
				}
			}	
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _delete_mailing_list($id = '')
	{
		if ($id == 1)
		{
			return false;
		}	
		
		//delete mailing list members
		$this->db->where('mailing_list_id', $id);
		if ($this->db->delete('email_mailing_list_members'))
		{
			
			//log success
			log_message('info', 'mailing_list members ID deleted successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_delete_mailing_list'));
			
			//log error
			log_message('error', 'mailing_list ID #' . $id . ' could not be deleted');
		}
		

		
		
		//delete mailing_list
		$this->db->where('mailing_list_id', $id);
		if ($this->db->delete('email_mailing_lists'))
		{
			
			//log success
			log_message('info', 'mailing_list ID #' . $id . ' deleted successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_delete_mailing_list'));
			
			//log error
			log_message('error', 'mailing_list ID #' . $id . ' could not be deleted');
		}
		
		return true;
	}	

	// ------------------------------------------------------------------------
		
	function _get_custom_templates()
	{
	
		$this->db->where('email_template_type', 'custom');
		$query = $this->db->get('email_templates');
		
		if ($query->num_rows() > 0)
		{
			$templates = $query->result_array();
		
			$data = format_array($templates, 'id', 'email_template_name', true, 'load_custom_template');
		
			return $data;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------

	function _get_mailing_list_details($id = '')
	{
		$this->db->where('mailing_list_id', $id);
	
		$query = $this->db->get('email_mailing_lists');
		
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
	
	function _change_follow_up_sequence($mailing_list_id = '')
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		foreach ($data as $k => $v)
		{
			if (strstr($k, "sequence") == true) 
			{
				$id = explode("-", $k);

				$seq = 'sequence-' . $id[1];
				
				$this->db->where('member_id', $id[1]);
				$this->db->where('mailing_list_id', $mailing_list_id);
				
				//update member in db
					
				$sdata = array('sequence_id' => $data[$seq]);
				
				if (!$this->db->update('email_mailing_list_members', $sdata))
				{
					show_error($this->lang->line('could_not_update_mailing_list'));
					
					//log error
					log_message('error', 'Could not update list member #' . $k . ' in email_mailing_list_members table');
					return false;
				}
			}
		}
		
		
		
		return true;		
	}
	
	
	// ------------------------------------------------------------------------
	
	function _get_list_members($limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $mailing_list_id = '')
	{
		if (!$sort_order) $sort_order = $this->config->item('dbs_mlu_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_mlu_column');

		$sql = 'SELECT ' . $this->db->dbprefix('email_mailing_list_members') . '.*,
				(SELECT CONCAT(' . $this->db->dbprefix('members') . '.fname ,\' \', ' . $this->db->dbprefix('members'). '.lname) from ' . $this->db->dbprefix('members') . ' 
				WHERE ' . $this->db->dbprefix('email_mailing_list_members') . '.member_id = ' . $this->db->dbprefix('members') . '.member_id) as member
				FROM ' . $this->db->dbprefix('email_mailing_list_members') . '
				WHERE mailing_list_id = \'' . $mailing_list_id . '\'
				ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_all_mailing_lists()
	{
		$query =$this->db->get('email_mailing_lists');
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_mailing_lists($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_mll_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_mll_column');

		$sql = 'SELECT ' . $this->db->dbprefix('email_mailing_lists') . '.*,
				(SELECT COUNT(*) FROM ' . $this->db->dbprefix('email_follow_ups') . '
				WHERE ' . $this->db->dbprefix('email_mailing_lists') . '.mailing_list_id = ' . $this->db->dbprefix('email_follow_ups') . '.mailing_list_id) as follow_ups, 
				(SELECT COUNT(*) FROM ' . $this->db->dbprefix('email_mailing_list_members') . ' 
				WHERE ' . $this->db->dbprefix('email_mailing_lists') . '.mailing_list_id = ' . $this->db->dbprefix('email_mailing_list_members') . '.mailing_list_id) as total
				FROM ' . $this->db->dbprefix('email_mailing_lists') . '
				ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}

	// ------------------------------------------------------------------------
	
	function _update_mailing_list($id = '', $post = array())
	{
		//clean the data first
		$data = $this->db_validation_model->_clean_data($post);
		
		//get mailing_list data
		$mailing_list_data = $this->_get_mailing_list_details($id);
		
		//update mailing_list data
		$this->db->where('mailing_list_id', $id);
		
		if (!$this->db->update('email_mailing_lists', $data))
		{
			show_error($this->lang->line('could_not_update_mailing_list'));
			
			//log error
			log_message('error', 'Could not update mailing_list ID ' . $id . 'in mailing_lists table');
			
			return false;
		}
		else
		{
			
			//log success
			log_message('info', 'mailing_list ID '.$id . ' updated in mailing_lists table');
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
}
?>