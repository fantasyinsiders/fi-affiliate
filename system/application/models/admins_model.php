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
| FILENAME - admins_model.php
| -------------------------------------------------------------------------     
| 
|
*/

class Admins_Model extends CI_Model {
	
	// ------------------------------------------------------------------------	
	
	function _add_admin($post = array())
	{	
		
		$data = $this->db_validation_model->_clean_data($post);
		
		if (!empty($data['permissions']))
		{
			$data['permissions'] = implode(',', $data['permissions']);
		}
		else
		{
			$data['permissions'] = '';
		}
		
		$data['admin_photo'] = rand(1,5) . '.jpg';
			
		if (!$this->db->insert('admin_users', $data))
		{
			show_error($this->lang->line('could_not_add_admin'));
			
			log_message('error', 'Could not insert admin into admin_users table');
			
			return false;
		}
		
		log_message('info', 'Admin user added');
		
		return $this->db->insert_id();
		
	}
	
	// ------------------------------------------------------------------------	
	
	function _delete_admin_photo($id = '')
	{
		$this->db->where('admin_id', $id);
		
		$query = $this->db->get('admin_users');
		
		if ($query->num_rows() > 0)
		{
			$photo_data = $query->row_array();
			
			if ($photo_data['admin_photo'])
			{
				@unlink('./images/' . $this->config->item('images_admins_dir') . '/' . $photo_data['admin_photo']);
			}
		}
		
		$this->db->where('admin_id', $id);			
		
		$update = array('admin_photo' => '');
		
		if ($this->db->update('admin_users', $update))
		{
		
			log_message('info', 'photo for admin ID #' . $id . ' updated in admin_users table');
			
			return true;
		}	
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _change_status($data = '', $type = 'inactive')
	{

		foreach ($data as $id)
		{
			if ($id == 1)
			{
				continue;
			}
			
			$this->db->where('admin_id', $id);
			
			if ($type == 'delete')
			{
				if (!$this->db->delete('admin_users'))
				{
					show_error($this->lang->line('could_not_delete_admin'));
					
					log_message('error', 'Could not delete admin ID #' . $id . ' in admin_users table');
					
					return false;
				}
				
				log_message('info', 'Admin ID #' . $id . ' deleted');
			}
			else
			{				
				if (!$this->db->update('admin_users', array('status' => $type)))
				{
					show_error($this->lang->line('could_not_update_admin'));
					
					log_message('error', 'Could not update admin ID #' . $id . ' in admin_users table');
					
					return false;
				}
				
				log_message('info', 'Status Changed for admin ID# ' . $id);
			}
			
		}
		
		return true;		
	}
	
	// ------------------------------------------------------------------------	
	
	function _delete_admin($id = '')
	{
		if ($id > 1)
		{
			$this->db->where('admin_id', $id);		
			
			if (!$this->db->delete('admin_users'))
			{
				show_error($this->lang->line('could_not_delete_admin'));
				
				log_message('error', 'Could not delete admin ID #' . $id . ' in admin_users table');
				
				return false;
			}
			
			log_message('info', 'Admin ID #' . $id . ' deleted');
		}

		return true;	
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_admins($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		if (!$sort_order) $sort_order = $this->config->item('dbs_au_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_au_column');
		
		$this->db->order_by($sort_column, $sort_order); 	
		
		$query = $this->db->get('admin_users', $limit, $offset);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_all_admins()
	{	
		$query = $this->db->get('admin_users');
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_admin_details($id = '')
	{
		$this->db->where('admin_users.admin_id', $id);
	
		$query = $this->db->get('admin_users');
		
		return $query->row_array();
	}
	
	// ------------------------------------------------------------------------	
	
	function _update_admin($id = '', $post = array())
	{
		
		$data = $this->db_validation_model->_clean_data($post);
		
		if ($id == 1)
		{
			$data['permissions'] = '';
			$data['status'] = 'active';
		}
		else
		{
			if (!empty($data['permissions']))
			{
				$data['permissions'] = implode(',', $data['permissions']);
			}
			else
			{
				$data['permissions'] = '';
			}
		}
			
		if (empty($data['admin_photo']))
		{
			$admin_data = $this->_get_admin_details($id);
			
			if (!empty($admin_data['admin_photo']))
			{
				@unlink('./images/' . $this->config->item('images_admins_dir') . '/' . $admin_data['admin_photo']);
			}
		}
		
		$this->db->where('admin_id', $id);
		
		if (!$this->db->update('admin_users', $data))
		{
			show_error($this->lang->line('could_not_update_admin'));
			
			log_message('error', 'Could not update admin ID #' . $id . ' in admin_users table');
			
			return false;
		}
		
		log_message('info', 'Admin ID #' . $id . ' updated');
		
		return $id;
	}
	
	// ------------------------------------------------------------------------	
}
?>