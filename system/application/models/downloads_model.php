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
| FILENAME -downloads_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for generating downloads
|
*/

class Downloads_Model extends CI_Model {
	
	// ------------------------------------------------------------------------
	
	function _add_download($data = '')
	{
		$data = $this->db_validation_model->_clean_data($data);
		
		//insert into db
		if (!$this->db->insert('downloads', $data))
		{
			show_error($this->lang->line('could_not_add_download'));
			
			//log error
			log_message('error', 'Could not insert download into downloads table');
			
			return false;
		}
		else
		{
			$id = $this->db->insert_id();
			
			//log success
			log_message('info', 'commission '. $id . ' inserted into downloads table');
		}
		
		return $id;
	}
	
	// ------------------------------------------------------------------------

	function _get_download_details($id = '')
	{
		//get the data fromdownloads table
		$sql = 'SELECT ' . $this->db->dbprefix('downloads') . '.*
						FROM ' . $this->db->dbprefix('downloads') . '
						WHERE id = \'' . $id . '\'';
		
		$query = $this->db->query($sql);
		
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
	
	function _update_download($id = '', $data = '')
	{
		//clean the data first
		$data = $this->db_validation_model->_clean_data($_POST);
		
		$this->db->where('id', $id);
		
		if (!$this->db->update('downloads', $data))
		{
			show_error($this->lang->line('could_not_update_download'));
			
			//log error
			log_message('error', 'Could not update download ID ' . $id . 'in download table');
			
			return false;
		}
		else
		{
			//log success
			log_message('info', 'download ID '. $id . ' updated in download table');
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _get_downloads($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_dwn_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_dwn_column');

		$this->db->order_by($sort_column, $sort_order); 	
		$query = $this->db->get('downloads', $limit, $offset);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}

	// ------------------------------------------------------------------------
	
	function _get_site_downloads($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('mem_dbs_dwn_order');
		if (!$sort_column) $sort_column = $this->config->item('mem_dbs_dwn_column');
		
		$this->db->where('status', '1');
		if ($this->config->item('sts_site_showcase_multiple_programs') == 0)
		{
			$this->db->where('program_id', $this->config->item('prg_program_id'));
		}
		$this->db->order_by($sort_column, $sort_order); 	
		
		
		$query = $this->db->get('downloads', $limit, $offset);
		
		if ($query->num_rows() > 0)
		{			
			$sdata['downloads'] = array();
			
			foreach ($query->result_array() as $row)
			{
				//check for affiliate group filter
				if (!empty($row['group_id']))
				{
					if ($row['group_id'] != $this->session->userdata('m_affiliate_group'))
					{
						continue;
					}
				}
				
				$row['s_download_link_1'] = !empty($row['download_location_1']) ? anchor(MEMBERS_ROUTE.'/downloads/get/' . $row['id'] . '/1', end(explode('/', $row['download_location_1']))) : '';
				$row['s_download_link_2'] = !empty($row['download_location_2']) ? anchor(MEMBERS_ROUTE.'/downloads/get/' . $row['id'] . '/2', end(explode('/', $row['download_location_2']))) : '';
				$row['s_download_link_3'] = !empty($row['download_location_3']) ? anchor(MEMBERS_ROUTE.'/downloads/get/' . $row['id'] . '/3',  end(explode('/', $row['download_location_3']))) : '';
				$row['s_download_link_4'] = !empty($row['download_location_4']) ? anchor(MEMBERS_ROUTE.'/downloads/get/' . $row['id'] . '/4',  end(explode('/', $row['download_location_4']))) : '';
				$row['s_download_link_5'] = !empty($row['download_location_5']) ? anchor(MEMBERS_ROUTE.'/downloads/get/' . $row['id'] . '/5',  end(explode('/', $row['download_location_5']))) : '';
					
				array_push($sdata['downloads'], $row);
			}
			
			$this->db->where('status', '1');
			if ($this->config->item('sts_site_showcase_multiple_programs') == 0)
			{
				$this->db->where('program_id', $this->config->item('prg_program_id'));
			}
			$this->db->from('downloads');
			$sdata['total_rows'] = $this->db->count_all_results();
		
			return $sdata;
		}
		 
		return  false;
	}

	// ------------------------------------------------------------------------
	
	function _delete_download($id = '')
	{
		$this->db->where('id', $id);
		
		if ($this->db->delete('downloads'))
		{
			
			//log success
			log_message('info', 'download #' . $id . ' deleted successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_delete_download'));
			
			//log error
			log_message('error', 'download #' . $id . ' could not be deleted');
		}
		
		return true;
	}
}
?>