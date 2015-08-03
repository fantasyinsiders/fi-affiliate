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
| FILENAME - module_data_import_banners_import.php
| -------------------------------------------------------------------------     
| 
*/
  

class Module_Data_Import_Banners_Import extends Admin_Controller {
	 
	function __construct()
	{
		parent::__construct();

		$this->load->model('import_model', 'import');
		
		$this->load->model('uploads_model', 'uploads');
		
		$this->load->model('modules/module_data_import_banners_import_model', 'data_import');

		$this->config->set_item('menu', 'x');		
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect();
	}
	
	// ------------------------------------------------------------------------
	
	function run_module()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = 'import_banners';
		
		$total = $this->data_import->_import_data();	
		
		if ($total)
		{
			$this->session->set_flashdata('success', $total . ' ' . $this->lang->line('items_imported_successfully'));
				
			redirect(modules_url() . 'module_affiliate_marketing_banners/view');
		}
		else
		{
			show_error($this->lang->line('no_banners_found'));	
		}
		
	}
	
	// ------------------------------------------------------------------------	
	
	function update()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = 'import_banners';
		
		if ($this->_check_update() == false) 
		{		 
			if (!empty($_POST))
			{
				$data['error'] =  $this->validation->error_string;	
			}
			
			$m = $this->import->_get_import_details((int)$this->uri->segment(4));
			
			if (!empty($m))
			{	
				foreach ($m as $k => $v)
				{	
					$this->validation->$k = $v;	
				}
				
				$data['sts_config'] = array();
				
				foreach ($m['sts_config'] as  $v)
				{
					$this->validation->$v['settings_key'] = $v['settings_value'];
	
					array_push($data['sts_config'], $v);
				
				}
				
				load_admin_tpl('modules', 'tpl_adm_manage_data_import_settings', $data);

			}
			else
			{
				redirect(admin_url() . 'import/view_import_modules');
				exit();
			}			
		}
		else
		{	
			$data = $this->import->_update_import((int)$this->uri->segment(4));	
			
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			$url = !empty($_POST['redirect']) ? $_POST['redirect'] : admin_url() . 'import/view_import_modules';
			
			redirect($url);
		}		
	}
	
	
	// ------------------------------------------------------------------------	
	
	function isAllowedExtension($fileName) 
	{
		$allowedExtensions = array("txt", "csv");
		
		if (in_array(end(explode(".", $fileName)), $allowedExtensions))
		{
			return true;
		}
		
		return false;
	}


	// ------------------------------------------------------------------------	
	
	function _check_file_path()
	{
		if (!is_dir($this->validation->file_path)) 
		{
			$this->validation->set_message('_check_file_path', $this->lang->line('invalid_file_path'));
			return false;
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_update()
	{
	
		$rules['module_data_import_banners_import_activate_banners'] = 'trim|required';
		
		$this->validation->set_rules($rules);

		$fields['module_data_import_banners_import_activate_banners'] = $this->lang->line('activate_banners_automatically');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}

	
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	
}
?>