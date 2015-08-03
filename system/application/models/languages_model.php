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
| FILENAME - languages_model.php
| -------------------------------------------------------------------------     
|
*/

class Languages_Model extends CI_Model {	
	
	function _get_languages($limit = 25, $offset = 0, $sort_column = '', $sort_order = '')
	{
		//get all the admins from db for list view
		if (!$sort_order) $sort_order = $this->config->item('dbs_lng_order');
		if (!$sort_column) $sort_column = $this->config->item('dbs_lng_column');

		$this->db->order_by($sort_column, $sort_order); 	
		$query = $this->db->get('languages', $limit, $offset);
		
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------
	
	function _get_language($id = '')
	{
		$this->db->where('language_id', $id);
		$query = $this->db->get('languages');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		 
		return  false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _add_language()
	{
		$data = $this->db_validation_model->_clean_data($_POST);
		
		//insert into db
		if (!$this->db->insert('languages', $data))
		{
			show_error($this->lang->line('could_not_add_language'));
			
			log_message('error', 'Could not insert module into modules table');
			
			return false;
		}
		else
		{
			$id = $this->db->insert_id();
			
			log_message('info', 'language '. $id . ' inserted into languages table');
		}
		
		return $data;
	}
	
	// ------------------------------------------------------------------------	
	
	function _update_language($id = '', $type = '')
	{
		$data = $_POST;
		
		$file = $type == 'admin' ? 'adm_main' : 'common';
		
		$sdata = $this->_get_language($id);
		
		if (file_exists(APPPATH . 'language/' . $sdata['name'] . '/' . $file . '_custom_lang.php'))
		{
			if (is_writable(APPPATH . 'language/' . $sdata['name'] . '/' . $file . '_custom_lang.php'))
			{		
					

					$message = '<?php if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');
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
| FILENAME - ' . $file . '_lang.php
| -------------------------------------------------------------------------
|
| This is the language file for the site
|
*/

';
			
					foreach ($data as $k => $v)
					{
						$v = str_replace("'", "\'", $v);
						$message .= '$lang[\'' . $k . '\'] = \'' . (xss_clean(html_entity_decode($v, ENT_QUOTES, 'UTF-8'))) . '\';' . "\n";
					}
					
					$message .= '?>';
			
					if ( write_file(APPPATH . 'language/' . $sdata['name'] . '/' . $file . '_custom_lang.php', $message))
					{
						return true;
					}
	
			}
			else
			{
				show_error($this->lang->line('language_file_not_writeable'));
			}
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _delete_language($id = '')
	{
		if ($id == 1)
		{
			return false;
		}	
		
		//get currency details first
		$this->db->where('language_id', $id);
		$query = $this->db->get('languages');
		
		if ($query->num_rows() > 0)
		{
			//update default currency
			$row = $query->result_array();
			
			if ($this->config->item('sts_site_default_language') == $row[0]['code'])
			{
				$sdata = array('sts_site_default_language' => 'english',
								'sts_admin_default_language' => 'english',
								);
				
				$this->db_validation_model->_update_db_settings($sdata);
			}
		
			//delete currency
			$this->db->where('language_id', $id);
			if ($this->db->delete('languages'))
			{
				
				//log success
				log_message('info', 'language ID #' . $id . ' deleted successfully');
			}
			else
			{
				show_error($this->lang->line('could_not_delete_language'));
				
				//log error
				log_message('error', 'language ID #' . $id . ' could not be deleted');
			}
		}
		
		
		
		return true;
	}
}
?>