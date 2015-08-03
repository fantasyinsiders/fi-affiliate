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
| FILENAME - module_data_export_commissions.php
| -------------------------------------------------------------------------     
| 
*/


class Module_Data_Export_Commissions_By_Date extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('export_model', 'export');

		$this->load->model('modules/module_data_export_commissions_by_date_model', 'data_export');
		
		$this->config->set_item('menu', 'x');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect('modules/module_data_export_commissions_by_date/run_module');
	}
	
	// ------------------------------------------------------------------------
	
	function run_module()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$this->load->dbutil();

		$commissions = $this->data_export->_get_commissions_by_date($this->config->item('module_data_export_commissions_by_date_start_date'), $this->config->item('module_data_export_commissions_by_date_end_date'));
		
		if (!empty($commissions))
		{
			$delimiter = $this->config->item('module_data_export_commissions_by_date_delimiter');
	
			$newline = "\r\n";
			
			$sdata = $this->dbutil->csv_from_result($commissions, $delimiter, $newline); 
			
			$this->load->helper('download');
			
			$ext = $delimiter == 'tab' ? 'txt' : 'csv';
			
			$name = $this->lang->line('filename_export_commissions').'-'.date('m-d-Y').'.' . $ext;
	
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

		$data['page_title'] = 'export_commissions_by_date';
		
		$data['submit_url'] = base_url() . $data['site_index_page'] . 'modules/module_data_export_commissions_by_date/update/'.(int)$this->uri->segment(4);
		
		if ($this->_check_update() == false) 
		{		 
			if (!empty($_POST))
			{
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
					switch ($v['settings_key'])
					{
						case 'module_data_export_commissions_by_date_start_date':
						case 'module_data_export_commissions_by_date_end_date':
							
							$this->validation->$v['settings_key'] = _format_date($v['settings_value'], $data['format_date2']);

						break;
						
						default: 
							$this->validation->$v['settings_key'] = $v['settings_value'];
						break;
					}
					array_push($data['sts_config'], $v);
				
				}
					
				load_admin_tpl('modules', 'tpl_adm_manage_data_export_commissions_by_date', $data);

			}
			else
			{
				redirect(admin_url() . 'export/view_export_modules');
				exit();
			}			
		}
		else
		{	
			$_POST['module_data_export_commissions_by_date_start_date'] = _save_date($_POST['module_data_export_commissions_by_date_start_date'], '', 'min');
			$_POST['module_data_export_commissions_by_date_end_date']  = _save_date($_POST['module_data_export_commissions_by_date_end_date'], '', 'max');
			
			$data = $this->export->_update_export((int)$this->uri->segment(4));	
			
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			$url = !empty($_POST['redirect']) ? $_POST['redirect'] : admin_url() . 'export/view_export_modules';
			
			redirect($url);
		}			
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_update()
	{
	
		$rules['module_data_export_commissions_by_date_delimiter'] = 'trim|required';
		$rules['module_data_export_commissions_by_date_commission_type'] = 'trim';
		$rules['module_data_export_commissions_by_date_start_date'] = 'trim|required';
		$rules['module_data_export_commissions_by_date_end_date'] = 'trim|required';
		
		$this->validation->set_rules($rules);

		$fields['module_data_export_commissions_by_date_delimiter'] = $this->lang->line('module_data_export_commissions_by_date_delimiter');
		$fields['module_data_export_commissions_by_date_commission_type'] = $this->lang->line('module_data_export_commissions_by_date_commission_type');
		$fields['module_data_export_commissions_by_date_start_date'] = $this->lang->line('module_data_export_commissions_by_date_start_date');
		$fields['module_data_export_commissions_by_date_end_date'] = $this->lang->line('module_data_export_commissions_by_date_end_date');
		
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