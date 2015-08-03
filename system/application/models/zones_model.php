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
| FILENAME - zones_model.php
| -------------------------------------------------------------------------     
| 
*/

class Zones_Model extends CI_Model {	

	// ------------------------------------------------------------------------	
	
	function _change_status($data = '', $type = '0')
	{

		foreach ($data as $id)
		{
			$ship_to = $type;	
				
			if ($id == $this->config->item('sts_site_default_country')) 
			{ 
				$ship_to = '1';
				
			}

			$this->db->where('country_id', $id);

			$data = array(
							'ship_to' => $ship_to,
						);
			
			if (!$this->db->update('countries', $data))
			{
				show_error($this->lang->line('could_not_update_country'));
				
				log_message('error', 'Could not update country ID #' . $id . ' in countries table');
				
				return false;
			}
			
			log_message('info', 'Status Changed for country ID# ' . $id);
			
		}
		 
		return true;		
	}
	
	// ------------------------------------------------------------------------
	
	function _add_country($post = array())
	{
		$data = $this->db_validation_model->_clean_data($post);
		
		if (!$this->db->insert('countries', $data))
		{
			show_error($this->lang->line('could_not_add_country'));
			
			//log error
			log_message('error', 'Could not insert country into zones table');
			
			return false;
		}
		else
		{
			$country_id = $this->db->insert_id();
			
			//log success
			log_message('info', 'zone '. $country_id . ' inserted into country table');
		}
		
		return $country_id;
	}
	
	// ------------------------------------------------------------------------
	
	function _delete_country($id = '')
	{
		if ($id == 223)
		{
			return false;
		}	
		
		//delete country
		$this->db->where('country_id', $id);
		if ($this->db->delete('countries'))
		{
			
			//log success
			log_message('info', 'country ID #' . $id . ' deleted successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_delete_country'));
			
			//log error
			log_message('error', 'country ID #' . $id . ' could not be deleted');
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------

	function _get_country_details($id = '')
	{
		$this->db->where('country_id', $id);
		$query = $this->db->get('countries');
		
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
	
	function _get_countries($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_con_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_con_column');

		$this->db->order_by($sort_column, $sort_order); 	
		$query = $this->db->get('countries', $limit, $offset);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------
	
	function _update_country($id = '', $post = array())
	{
		//clean the data first
		$data = $this->db_validation_model->_clean_data($post);
		
		//update data
		$this->db->where('country_id', $id);
		if (!$this->db->update('countries', $data))
		{
			show_error($this->lang->line('could_not_update_country'));
			
			//log error
			log_message('error', 'Could not update country ID ' . $id . 'in countries table');
			
			return false;
		}
		else
		{
			
			//log success
			log_message('info', 'zone ID '. $id . ' updated in countries table');
		}
		
		return true;
	}
	

	// ------------------------------------------------------------------------	

}
?>