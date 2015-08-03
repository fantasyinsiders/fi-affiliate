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
| FILENAME - follow_ups_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for managing follow_ups
|
*/

class follow_ups_Model extends CI_Model {	
	
	function _add_follow_up()
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		//check the sequence number
		$total = $this->db->count_all('email_follow_ups');
		$data['sequence'] = $total + 1;
		$data['days_apart'] = '1';
		
		//insert into db
		if (!$this->db->insert('email_follow_ups', $data))
		{
			show_error($this->lang->line('could_not_add_follow_up'));
			
			//log error
			log_message('error', 'Could not insert follow_up into follow_ups table');
			
			return false;
		}
		else
		{
			$follow_up_id = $this->db->insert_id();
			
			//log success
			log_message('info', 'follow_up '. $follow_up_id . ' inserted into follow_ups table');
		}
		
		//make sure tiers are numbered sequentially
		$this->db_validation_model->_db_sort_order('email_follow_ups', 'follow_up_id', 'sequence', 'mailing_list_id = \'' . $data['mailing_list_id'] . '\''); 
		
		return $follow_up_id;
	}
	
	// ------------------------------------------------------------------------
	
	function _change_follow_up_sequence($post = '')
	{
		$data = $this->db_validation_model->_clean_data($post);
		
		foreach ($data as $k => $v)
		{
			if (strstr($k, "sequence") == true) 
			{
				$id = explode("-", $k);
				
				$days = 'days-' . $id[1];
				$seq = 'sequence-' . $id[1];
				
				$this->db->where('follow_up_id', $id[1]);
			
				//update member in db
					
				$sdata = array('sequence' => $data[$seq],
							  'days_apart' => $data[$days]
							  );
				
				if (!$this->db->update('email_follow_ups', $sdata))
				{
					show_error($this->lang->line('could_not_update_follow_ups'));
					
					//log error
					log_message('error', 'Could not update follow_ups #' . $k . ' in email_follow_ups table');
					return false;
				}
			}
		}
		
		//make sure tiers are numbered sequentially
		$this->db_validation_model->_db_sort_order('email_follow_ups', 'follow_up_id', 'sequence', 'mailing_list_id = \'' . $data['mailing_list_id'] . '\''); 
		
		return true;		
	}
	
	
	// ------------------------------------------------------------------------
	
	function _delete_follow_up($id = '')
	{
		
		//delete mailing list members
		$this->db->where('follow_up_id', $id);
		if ($this->db->delete('email_follow_ups'))
		{
			
			//log success
			log_message('info', 'follow_up members ID deleted successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_delete_follow_up'));
			
			//log error
			log_message('error', 'follow_up ID #' . $id . ' could not be deleted');
		}
		

		
		
		//delete follow_up
		$this->db->where('follow_up_id', $id);
		if ($this->db->delete('email_follow_ups'))
		{
			
			//log success
			log_message('info', 'follow_up ID #' . $id . ' deleted successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_delete_follow_up'));
			
			//log error
			log_message('error', 'follow_up ID #' . $id . ' could not be deleted');
		}
		
		return true;
	}	
	
	// ------------------------------------------------------------------------

	function _get_follow_up_details($id = '')
	{
		//get the data from follow_ups table
		$this->db->where('follow_up_id', $id);
		$query = $this->db->get('email_follow_ups');
		
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
	
	function _get_follow_ups($limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $mailing_list_id = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_flo_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_flo_column');

		$this->db->where('mailing_list_id', $mailing_list_id);
		$this->db->order_by($sort_column, $sort_order); 	
		$query = $this->db->get('email_follow_ups', $limit, $offset);
		
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}

	// ------------------------------------------------------------------------
	
	function _update_follow_up($id = '')
	{
		//clean the data first
		$data = $this->db_validation_model->_clean_data($_POST);
		
		//update follow_up data
		$this->db->where('follow_up_id', $id);
		
		if (!$this->db->update('email_follow_ups', $data))
		{
			show_error($this->lang->line('could_not_update_follow_up'));
			
			//log error
			log_message('error', 'Could not update follow_up ID ' . $id . 'in follow_ups table');
			
			return false;
		}
		else
		{
			
			//log success
			log_message('info', 'follow_up ID '.$id . ' updated in follow_ups table');
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
}
?>