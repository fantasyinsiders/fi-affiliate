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
| FILENAME - module_data_export_members_csv.php
| -------------------------------------------------------------------------     
| 
*/


class Module_Data_Export_Members extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('export_model', 'export');
		
		$this->load->model('modules/module_data_export_members_model', 'data_export');
		
		$this->config->set_item('menu', 'x');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect('modules/module_data_export_members/run_module');
	}
	
	// ------------------------------------------------------------------------
	
	function run_module()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$this->load->dbutil();

		$members = $this->data_export->_get_members();
		
		if (!empty($members))
		{
			$delimiter = $this->config->item('module_data_export_members_delimiter');
			$newline = "\r\n";
			
			$sdata = _csv_from_result($members, $delimiter, $newline);  
			
			$this->load->helper('download');
			
			$ext = $delimiter == 'tab' ? 'txt' : 'csv';
			
			$name = $this->lang->line('filename_export_members').'-'.date('m-d-Y', _generate_timestamp()).'_' . _generate_timestamp() . '.' . $ext;
	
			force_download($name, $sdata); 
		}
		else
		{
			show_error($this->lang->line('no_data_found'));	
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function update()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = 'export_members';

		if ($this->_check_update() == false) 
		{		 
			if (!empty($_POST))
			{
				if (!empty($_POST['redirect']))
				{
					$this->session->set_flashdata('error', $this->validation->error_string);
					
					redirect($this->input->post('redirect'));
				}
				
				$data['error'] =  $this->validation->error_string;	
			}
			
			$m = $this->export->_get_export_details((int)$this->uri->segment(4));
			
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
				
				load_admin_tpl('modules', 'tpl_adm_manage_data_export_members', $data);

			}
			else
			{
				redirect(admin_url() . 'export/view_export_modules');
				exit();
			}			
		}
		else
		{	
			$data = $this->export->_update_export((int)$this->uri->segment(4));	
			
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			if (!empty($_POST['redirect']))
			{
				redirect($this->input->post('redirect'));
			}
			
			$url = !empty($_POST['redirect']) ? $_POST['redirect'] : admin_url() . 'export/view_export_modules';
			
			redirect($url);
		}			
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_update()
	{
	
		$rules['module_data_export_members_delimiter'] = 'trim|required';
		$rules['module_data_export_members_total_rows'] = 'trim|integer';
		$rules['module_data_export_members_starting_rows'] = 'trim|integer';
		
		$this->validation->set_rules($rules);
		
		//repopulate form
		$fields['module_data_export_members_delimiter'] = $this->lang->line('module_data_export_members_delimiter');
		$fields['module_data_export_members_total_rows'] = $this->lang->line('module_data_export_members_total_rows');
		$fields['module_data_export_members_starting_rows'] = $this->lang->line('module_data_export_members_starting_rows');
		
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