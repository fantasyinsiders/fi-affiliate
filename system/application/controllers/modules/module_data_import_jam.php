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
| FILENAME - module_data_import_jam.php
| -------------------------------------------------------------------------     
| 
| This controller file is for importing JAM data
|
*/


class Module_Data_Import_Jam extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('import_model', 'import');
		
		$this->load->model('modules/module_data_import_jam_model', 'data_import');
		
		$this->config->set_item('menu', 'x');
		
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		//redirect to default view
		redirect('modules/module_data_import_jam/run_module');
	}
	
	// ------------------------------------------------------------------------
	
	function do_import()
	{
		set_time_limit(0);

		//set data array
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = $this->lang->line('import_jam_database');
		$data['submit_url'] = base_url() . $data['site_index_page'] . 'modules/module_data_import_jam/run_module/'.(int)$this->uri->segment(4);	
		
		if (!empty($_POST))
		{
			//run import
			$sdata = $this->data_import->_import_data($_POST);	
			
			$total_members = $this->db->count_all('members');
			
			$data['show_message'] = '<div class="success">' . $sdata . ' ' . $this->lang->line('users_imported_successfully') . '</div>';
	
			if ($this->config->item('module_data_import_jam_affiliate_offset') < $total_members)
			{
				$data['do_import'] = true;	
			}		
			
			foreach ($_POST as $k => $v)
			{
				$this->validation->$k = $v;
			}
			
			load_admin_tpl('modules', 'tpl_adm_manage_data_import_jam', $data);
		}
		else
		{
			show_error('invalid access');	
		}
		
	}
	
	// ------------------------------------------------------------------------	
	
	function run_module()
	{
		//set data array
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		//set page title
		$data['page_title'] = 'import_jam_database';
		$data['submit_url'] = base_url() . $data['site_index_page'] . 'modules/module_data_import_jam/run_module/'.(int)$this->uri->segment(4);	
		$data['show_message'] = '';
		
		if ($this->_check_module() == false)
		{		
			if (!empty($_POST))
			{
				$data['show_message'] = '<div class="error" id="error-messages">' . $this->validation->error_string . '</div>';
			}
		}
		else
		{	
			$data['submit_url'] = base_url() . $data['site_index_page'] . 'modules/module_data_import_jam/do_import/'.(int)$this->uri->segment(4);
			
			$data['do_import'] = true;
		}	
		
		//load template
		load_admin_tpl('modules', 'tpl_adm_manage_data_import_jam', $data);		
	}
	
	// ------------------------------------------------------------------------	
	
	function update()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		$data['page_title'] = 'import_jam_database'; 
		
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
	
	function _check_module()
	{
	
		$rules['module_data_import_jam_server'] = 'trim|required|callback__check_jam_db_connect';
		$rules['module_data_import_jam_database'] = 'trim|required';
		$rules['module_data_import_jam_username'] = 'trim|required';
		$rules['module_data_import_jam_password'] = 'trim';
		$rules['module_data_import_jam_last_id'] = 'trim';
		//$rules['module_data_import_jam_folder_path'] = 'trim|callback__check_jam_path';
		
		$this->validation->set_rules($rules);
		
		//repopulate form
		$fields['module_data_import_jam_server'] = $this->lang->line('module_data_import_jam_server');
		$fields['module_data_import_jam_database'] = $this->lang->line('module_data_import_jam_database');
		$fields['module_data_import_jam_username'] = $this->lang->line('module_data_import_jam_username');
		$fields['module_data_import_jam_password'] = $this->lang->line('module_data_import_jam_password');
		$fields['module_data_import_jam_last_id'] = $this->lang->line('module_data_import_jam_last_id');
		//$fields['module_data_import_jam_folder_path'] = $this->lang->line('module_data_import_jam_folder_path');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}

	
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_update()
	{
	
		$rules['module_data_import_jam_segment_affiliates'] = 'trim|required';
		$rules['module_data_import_jam_affiliate_limit'] = 'trim|integer|required';
		$rules['module_data_import_jam_affiliate_offset'] = 'trim|integer|required';
		
		$this->validation->set_rules($rules);
		
		//repopulate form
		$fields['module_data_import_jam_segment_affiliates'] = $this->lang->line('module_data_import_jam_segment_affiliates');
		$fields['module_data_import_jam_affiliate_limit'] = $this->lang->line('module_data_import_jam_affiliate_limit');
		$fields['module_data_import_jam_affiliate_offset'] = $this->lang->line('module_data_import_jam_affiliate_offset');
		

		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}

	
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_jam_path()
	{
		$path = rtrim($this->validation->module_data_import_jam_folder_path, '//') . '/index.php';
		
		if (file_exists($path))
		{
			return true;
		}

		$this->validation->set_message('_check_jam_path', $this->lang->line('invalid_file_path'));			
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_jam_db_connect()
	{
		$con = @mysql_connect($this->validation->module_data_import_jam_server,$this->validation->module_data_import_jam_username,$this->validation->module_data_import_jam_password);
		if (!$con)
		{
			$this->validation->set_message('_check_jam_db_connect', $this->lang->line('could_not_connect_database_server'));
			return FALSE;
		}
		else
		{
			if (!mysql_select_db($this->validation->module_data_import_jam_database, $con))
			{
				$this->validation->set_message('_check_jam_db_connect', $this->lang->line('could_not_connect_database'));
				return false;
			}
		}
		
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	
}
?>