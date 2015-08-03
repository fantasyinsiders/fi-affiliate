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
| FILENAME - themes.php
| -------------------------------------------------------------------------     
|
*/

class Themes extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		//load themes model
		$this->load->model('themes_model');
		
		//load css body style
		$this->config->set_item('css_body', 'OP');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . strtolower(__CLASS__) . '/view_themes');
	}
	
	// ------------------------------------------------------------------------
	
	function view_themes()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = 'manage_themes';
		
		$data['themes'] = $this->themes_model->_get_program_themes();
		
		$this->validation->id =  $this->uri->segment(4,1);
		
		$m = $this->programs_model->_get_program_basic('program_id', $this->validation->id, 'program_logo');
			
		foreach ($m as $k => $v)
		{
			$this->validation->$k = $v;
		}

		load_admin_tpl('admin', 'tpl_adm_manage_themes', $data);	
	}
	
	// ------------------------------------------------------------------------
	
	function gen_thumb()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$config['image_library'] = $this->config->item('sts_image_library');
		$config['source_image'] =  $this->config->item('base_physical_path') . '/themes/main/' . $this->uri->segment(4) . '/' . $this->uri->segment(5);
		$config['quality'] = $this->config->item('images_quality');
		$config['maintain_ratio'] = $this->config->item('images_maintain_ratio');
		$config['thumb_marker'] = '_jrox';
		$config['width'] = '200';
		$config['height'] = '200';
		$config['dynamic_output'] = true;
		
		$this->load->library('image_lib', $config);
		$this->image_lib->initialize($config); 
		$this->image_lib->resize();
	
	}
	
	// ------------------------------------------------------------------------
	
	function upload_theme()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if (!empty($_POST))
		{
			if (!empty($_FILES) && $_FILES['zip_file']['error'] != 4)
			{
				$max_size = str_replace('M', '', $this->config->item('sts_site_upload_max_filesize')) * 1024;
				
				$sconfig['upload_path'] = PUBPATH . '/import/';
				$sconfig['allowed_types'] = 'zip';
				$sconfig['max_size']	= $max_size;
				$sconfig['encrypt_name'] = false;
				$sconfig['remove_spaces'] = true;
				
				$this->load->model('uploads_model');
				
				$sdata = $this->uploads_model->_upload_file('zip_file', $sconfig);
				
				if ($sdata['success'])
				{
					//unzip the file
					$zip = new ZipArchive;
					$file = $sdata['info']['full_path'];
					@chmod($file,0777);
					if ($zip->open($file) === TRUE) 
					{
						$zip->extractTo(PUBPATH . '/themes/main/');
						$zip->close();
							
					} 
					else 
					{
						show_error($this->lang->line('cannot_unzip_file'));
						exit();
					}
					
					@unlink($file);
					
					$this->session->set_flashdata('success', $this->lang->line('theme_uploaded_successfully'));
				}
			}
		}
		 
		redirect(jrox_admin_url() . 'themes/view_themes');
	}
	
	// ------------------------------------------------------------------------
	
	function download_theme()
	{
		
		//set data array
		$data = $this->security_model->_load_config('admin');
		
		if ($this->uri->segment(4))
		{
			$name = $this->uri->segment(4);
			
			$this->load->library('zip');
			
			$path = $this->config->item('base_physical_path') . '/themes/main/' . $name . '/';
			
			$this->zip->read_dir($path, false);
			
			$this->zip->download($name . '.zip');
			
			 	
		}
		else
		{
			show_error($this->lang->line('no_theme_found'));
		}
	}
	
	// ------------------------------------------------------------------------
	
	function set_default()
	{
		$data = $this->security_model->_load_config('admin');
		
		$this->themes_model->_set_default(($this->uri->segment(4)));
		
		$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
		redirect(ADMIN_ROUTE . '/themes/view_themes');	
	}
	
	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	
	// ------------------------------------------------------------------------	
	
	function _check_layout_theme()
	{
		//set form validation rules
		$rules['theme_name'] = 'trim|required|min_length[5]|max_length[50]|callback__check_theme_file_path';
		
		$this->validation->set_rules($rules);

		$fields['theme_name'] = $this->lang->line('theme_name');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_theme_file_path()
	{
		
		$cpath = $this->config->item('base_physical_path') . '/themes/main/' . $this->validation->theme_name . '/theme_info.php';
	
		if (!file_exists($cpath))
		{
			$this->validation->set_message('_check_theme_file_path', $this->lang->line('invalid_theme_file_path'));
			return false;
		}
		elseif ($this->db_validation_model->_validate_field('layout_themes', 'theme_name', $this->validation->theme_name))
		{
			$this->validation->set_message('_check_theme_file_path', $this->lang->line('theme_file_name_in_use'));
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
}
?>