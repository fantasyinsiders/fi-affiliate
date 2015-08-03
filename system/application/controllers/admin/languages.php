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
| FILENAME - languages.php
| -------------------------------------------------------------------------     
| 
*/

class languages extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('languages_model');
		
		$this->config->set_item('menu', 's');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . 'languages/view_languages');
	}
	
	// ------------------------------------------------------------------------
	
	function view_languages()
	{

		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['languages'] = $this->languages_model->_get_languages($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'languages', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column']);
		
		load_admin_tpl('admin', 'tpl_adm_manage_languages', $data);	
	}
	
	// ------------------------------------------------------------------------
	
	function add_language()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->_check_language() == false)
		{
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string;	
			}
			
			$this->load->helper('country_helper');
		
			$countries = _load_countries_array();
			
			$a = array();
			$b = array();
	
			foreach ($countries as $value)
			{
				array_push($a, strtolower($value['country_iso_code_2']));
				array_push($b, substr($value['country_name'], 0, 25));
			}
			
			$data['flags_array'] = combine_array($a, $b);
		
			load_admin_tpl('admin', 'tpl_adm_add_language', $data);	
		}
		else
		{		
			if ($this->languages_model->_add_language())		
			{
				$this->session->set_flashdata('success', $this->lang->line('item_added_successfully'));
			}
		}
		
		redirect(admin_url() . 'languages/view_languages/');			
	}
	
	// ------------------------------------------------------------------------
	
	function delete_language()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->languages_model->_delete_language((int)($this->uri->segment(4))))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . 'languages/view_languages/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
		}	
		
		redirect(admin_url() . 'languages/view_languages');
	}
	
	// ------------------------------------------------------------------------
	
	function update_status()
	{

		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		if ($this->db_validation_model->_change_status_field('languages', 'language_id', (int)$this->uri->segment(4), 'status'))
		{
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
				
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . 'languages/view_languages/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
		}	
			
		redirect(admin_url() . 'languages/view_languages');	
	}
	
	// ------------------------------------------------------------------------
	
	function update_language()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = 'manage_language';
		
		$this->load->helper('file');
		
		if ($this->_check_update_language() == false)
		{		
			if (empty($_POST))
			{
				$slang = $this->languages_model->_get_language((int)($this->uri->segment(4)));
				
				$data['type'] = $this->uri->segment(5) == 'admin' ? 'adm_main' : 'common';
				
				if (file_exists(APPPATH . 'language/' . $slang['name'] . '/' . $data['type'] . '_lang.php'))
				{
					$data['writeable'] = is_writable(APPPATH . 'language/' . $slang['name'] . '/' . $data['type'] . '_lang.php') ? true : false;
					
					include(APPPATH . 'language/' . $slang['name'] . '/' . $data['type'] . '_lang.php');
					
					$data['lang_file'] = $lang;

					if (file_exists(APPPATH . 'language/' . $slang['name'] . '/' . $data['type'] . '_custom_lang.php'))
					{
						$data['writeable'] = is_writable(APPPATH . 'language/' . $slang['name'] . '/' . $data['type'] . '_custom_lang.php') ? true : false;
						
						include(APPPATH . 'language/' . $slang['name'] . '/' . $data['type'] . '_custom_lang.php');
					
						if (!empty($lang) && is_array($lang))
						{
							$data['lang_file'] = array_merge($data['lang_file'], $lang);	
						}
					}
					/*
					ksort($data['lang_file']);
					
					foreach ($data['lang_file'] as $k => $v)
					{
						echo '$lang[\'' . $k . '\'] = \'' . addslashes($v). '\';<br />';
					}
					exit();					
					
					
					foreach ($data['lang_file'] as $k => $v)
					{
						$up = array('line' => $k,  'text' => $v);
						//$this->db->insert('language_entries', $up);	
					}
					*/
					$data['name'] = $slang['name'];
					
					$data['language_id'] = $slang['language_id'];
					
					load_admin_tpl('admin', 'tpl_adm_manage_language', $data);	
				}
				else
				{
					show_error($this->lang->line('no_language_found'));
				}	
							
			}
			else
			{				
				echo '<div class="error" id="error-messages">' . $this->validation->error_string . '</div>';
				echo '<script>alert(\'' . $this->validation->error_string . '\')</script>';		
				exit();	
			}		
		}
		else
		{	
	
			if ($this->languages_model->_update_language((int)$this->uri->segment(4), $this->uri->segment(5)) == true)	
			{
				echo '<script>alert(\'' . $this->lang->line('system_updated_successfully') . '\')</script>';		
				exit();	
			}
			else
			{
				echo '<script>alert(\'' . $this->lang->line('could_not_update_language_file') . '\')</script>';		
				exit();	
			}
			
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function set_default()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$code = $this->uri->segment(4);
		
		$sdata = array('sts_site_default_language' => $code);
		
		if ($this->db_validation_model->_update_db_settings($sdata))
		{
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
		}
		
		redirect(admin_url() . 'languages/view_languages/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
		
	}

	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	
	// ------------------------------------------------------------------------	
	
	function _check_update_language()
	{
		if (!empty($_POST))
		{
			$data = $this->db_validation_model->_clean_data($_POST);
		
			return true;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_language()
	{
		if (!empty($_FILES) && $_FILES['zip_file']['error'] != 4)
		{
			$rules['name'] = 'trim|required|min_length[2]|max_length[50]';
			$rules['code'] = 'trim|required|max_length[5]';
			$rules['image'] = 'trim|required|max_length[255]';
		
			$sconfig['upload_path'] = PUBPATH . '/import/';
			$sconfig['allowed_types'] = 'zip';
			$sconfig['max_size']	= $this->config->item('sts_support_max_upload_size');
			$sconfig['encrypt_name'] = false;
			$sconfig['remove_spaces'] = true;
			
			$this->load->model('uploads_model');
			
			$sdata = $this->uploads_model->_upload_file('zip_file', $sconfig);
			
			if ($sdata['success'])
			{
				$zip = new ZipArchive;
				
				$file = $sdata['info']['full_path'];
				
				chmod($file,0777);
				
				if ($zip->open($file) === TRUE) 
				{
					$zip->extractTo(APPPATH . 'language/');
					
					$zip->close();
						
				} 
				else 
				{
					show_error($this->lang->line('cannot_unzip_file'));
					exit();
				}
				
				@unlink($file);
			}
		}
		else
		{
			$rules['name'] = 'trim|required|min_length[2]|max_length[50]|callback__check_lang_path';
			$rules['code'] = 'trim|required|max_length[5]';
			$rules['image'] = 'trim|required';
		}
		
		$this->validation->set_rules($rules);

		$fields['name'] = $this->lang->line('language_name');
		$fields['code'] = $this->lang->line('code');
		$fields['image'] = $this->lang->line('image');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
		
	}
	
	// ------------------------------------------------------------------------
	
	function _check_lang_path()
	{
		$vpath = APPPATH . 'language/' . $this->validation->name . '/common_lang.php';
		
		if (!file_exists($vpath))
		{
			$this->validation->set_message('_check_lang_path', $this->lang->line('invalid_language_path'));
			
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_image_path()
	{
		$vpath = $this->config->item('base_physical_path') . '/images/misc/flags/' . $this->validation->image;
		
		if (!file_exists($vpath))
		{
			$this->validation->set_message('_check_image_path', $this->lang->line('invalid_language_image_path'));
			
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
}
?>