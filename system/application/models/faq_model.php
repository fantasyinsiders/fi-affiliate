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
| FILENAME - faq_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for managing faq
|
*/

class Faq_Model extends CI_Model {	
	
	// ------------------------------------------------------------------------
	
	function _get_faqs($id = '')
	{
		$this->db->where('status', '1');
		//$this->db->where('date_published < ', _generate_timestamp());
	
		if ($this->config->item('sts_site_showcase_multiple_programs') == 0)
		{
			$this->db->where('program_id', $id);	
		}
	
		$query = $this->db->get('faq_articles');
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------
	
	function _add_faq_article()
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		$pub = explode('/', $data['date_published']);
		$data['date_published'] = mktime(date('H'),date('i'),date('s'), $pub[0], $pub[1], $pub[2]);
		$data['content_type'] = 'custom';
		
		unset ($data['content_type']);
		
		if (empty($data['program_id'])) { $data['program_id'] = '1'; }
		
		//add modified by
		$data['modified_by'] = $this->session->userdata('username');
		
		if ($this->config->item('content_enable_javascript_code') == true)
		{
			$data['content_body'] = $_POST['content_body'];
		}
		
		
		//insert into db
		if (!$this->db->insert('faq_articles', $data))
		{
			show_error($this->lang->line('could_not_add_faq'));
			
			//log error
			log_message('error', 'Could not insert faq into faq table');
			
			return false;
		}
		else
		{
			$faq_id = $this->db->insert_id();
			
			//log success
			log_message('info', 'faq '. $faq_id . ' inserted into faq table');
		}
		
		return $faq_id;
	}
	
	// ------------------------------------------------------------------------
	
	function _delete_faq_article($id = '')
	{
		if ($id == 1)
		{
			return false;
		}	
		
		//delete faq
		$this->db->where('article_id', $id);
		if ($this->db->delete('faq_articles'))
		{
			
			//log success
			log_message('info', 'faq ID #' . $id . ' deleted successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_delete_faq'));
			
			//log error
			log_message('error', 'faq ID #' . $id . ' could not be deleted');
		}
		
		return true;
	}	
	
	// ------------------------------------------------------------------------

	function _get_faq_article_details($id = '')
	{
		$this->db->where('article_id', $id);
		
		$query = $this->db->get('faq_articles');
		
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
	
	function _get_faq_categories()
	{
		
		$query = $this->db->get('faq_categories');
		
		if ($query->num_rows() > 0)
		{
			$cat = $query->result_array();
			$a = format_array($cat, 'category_id', 'category_name');
			
			return $a;
		}
		
		return false;
		
	}
	
	// ------------------------------------------------------------------------
	
	function _get_faq_articles($limit = 25, $offset = 0, $sort_column = '', $sort_order = '', $public = false, $category_id = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_faq_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_faq_column');
		
		$sql = 'SELECT ' . $this->db->dbprefix('faq_articles') . '.*
				FROM ' . $this->db->dbprefix('faq_articles') . ' 
				ORDER BY ' . $sort_column . ' ' . $sort_order . ' LIMIT ' . $offset. ', ' . $limit;
				 
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			if ($public == true)
			{
				$data['rows'] = array();
				
				foreach ($query->result_array() as $row)
				{
					$row['content_title_url'] = url_title($row['content_title']);
					
					array_push($data['rows'], $row);
				}
				
				$sql = 'SELECT ' . $this->db->dbprefix('faq_articles') . '.*
						FROM ' . $this->db->dbprefix('faq_articles') . '
						WHERE status = \'1\'';
				
				$squery = $this->db->query($sql);
				
				$data['total_rows'] = $squery->num_rows();
				
				return $data;
			}
			else
			{
				return $query->result_array();
			}
		}
		 
		return  false;
	}

	// ------------------------------------------------------------------------
	
	function _update_faq_article($id = '')
	{
		//clean the data first
		$data = $this->db_validation_model->_clean_data($_POST);
		
		$pub = explode('/', $data['date_published']);
		$data['date_published'] = mktime(date('H'),date('i'),date('s'), $pub[0], $pub[1], $pub[2]);
		
		//add modified by
		$data['modified_by'] = $this->session->userdata('username');
		
		if (empty($data['program_id'])) { $data['program_id'] = '1'; }
		
		if ($this->config->item('content_enable_javascript_code') == true)
		{
			$data['content_body'] = $_POST['content_body'];
		}
		
		
		//update faq data
		$this->db->where('article_id', $id);
		
		if (!$this->db->update('faq_articles', $data))
		{
			show_error($this->lang->line('could_not_update_faq'));
			
			//log error
			log_message('error', 'Could not update faq ID ' . $id . 'in faq table');
			
			return false;
		}
		else
		{
			
			//log success
			log_message('info', 'faq ID '. $id . ' updated in faq table');
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
}
?>