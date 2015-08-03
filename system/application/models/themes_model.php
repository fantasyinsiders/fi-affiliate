<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| COPYRIGHT NOTICE                                                     
| Copyright 2007-2015 JROX Technologies, Inc.  All Rights Reserved.    
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
| FILENAME - themes_model.php
| -------------------------------------------------------------------------     
|
*/

class Themes_Model extends CI_Model {	
	
	function _add_theme($data = '')
	{		
		//insert into db
		if (!$this->db->insert('layout_themes', $data))
		{
			show_error($this->lang->line('could_not_add_theme'));
			
			//log error
			log_message('error', 'Could not insert theme into themes table');
			
			return false;
		}
		else
		{
			$theme_id = $this->db->insert_id();
			
			//log success
			log_message('info', 'theme '. $theme_id . ' inserted into themes table');
		}
		
		return $theme_id;
	}
	
	// ------------------------------------------------------------------------
	
	function _delete_theme($id = '')
	{
		if ($id == 1)
		{
			return true;
		}	
		
		//delete theme
		$this->db->where('theme_id', $id);
		if ($this->db->delete('layout_themes'))
		{
			
			//log success
			log_message('info', 'theme ID #' . $id . ' deleted successfully');
		}
		else
		{
			show_error($this->lang->line('could_not_delete_theme'));
			
			//log error
			log_message('error', 'theme ID #' . $id . ' could not be deleted');
		}
		
		return true;
	}	
	
	// ------------------------------------------------------------------------

	function _get_theme_details($id = '', $category = '')
	{
		//get the data from themes table
		$this->db->where('theme_id', $id);

		$query = $this->db->get('themes');
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return false;
		}
	}
	
	// ------------------------------------------------------------------------

	function _get_program_themes($type = 'main')
	{
		
		$this->load->helper('directory');
		
		$map = directory_map('./themes/' . $type);
		
		$themes = array();
		
		foreach ($map as $k => $v)
		{
			if (file_exists(PUBPATH . '/themes/' . $type . '/' . $k . '/theme_info.php'))
			{
				include(PUBPATH . '/themes/' . $type . '/' . $k . '/theme_info.php');
				
				$theme = array(	'file_name'=> $k, 
								'name' => !empty($theme_name) ? $theme_name : '',
								'author' => !empty($theme_author) ? $theme_author : '',
								'description' => !empty($theme_description) ? $theme_description : '',
								'preview_image' => !empty($theme_preview_image) ? $theme_preview_image : '',
				); 
				
				array_push($themes, $theme);
			
			}
		}
	
		return $themes;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_themes($limit = 9, $offset = 0, $sort_column = '', $sort_order = '', $where = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_the_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_the_column');
		
		if (!empty($where))
		{
			$this->db->where($where);
		}
		
		$this->db->order_by($sort_column, $sort_order); 	
		$query = $this->db->get('layout_themes', $limit, $offset);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}

	// ------------------------------------------------------------------------
	
	function _set_default($id = '')
	{
		//first set all to 0
	
		$sdata = array('default_theme' => $id);
		$this->db_validation_model->_update_db_settings($sdata);
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
}
?>