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
| FILENAME - data_export.php
| -------------------------------------------------------------------------     
| 
| This controller file allows users to export data
|
*/

class Data_Export extends Member_Controller {
	
	function __construct()
	{
		parent::__construct();
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(site_url('members/data_export/view'));
	}
	
	// ------------------------------------------------------------------------
	
	function view()
	{
		$this->init->_set_default_program($this->session->userdata('m_signup_link'));
		
		$data = $this->security_model->_load_config('members', __CLASS__,  __FUNCTION__);

		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		$data['languages'] = $sdata['languages'];
		
		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		$data['page_title'] = $this->lang->line('data_export');
		$data['header'] = $this->lang->line('data_export');
		
		if ($this->_check_update() == false)
		{		
			if (!empty($_POST))
			{
				$data['show_message'] =  $this->validation->error_string;
			}
			
			$this->validation->module_data_export_commissions_by_date_start_date = date($data['format_date2'], _generate_timestamp());
			$this->validation->module_data_export_commissions_by_date_end_date = date($data['format_date2'], _generate_timestamp());
			
			$this->parser->_JROX_load_view('tpl_members_data_export', 'members', $data);	
		}
		else
		{	
			$this->load->dbutil();
	
			$this->load->model('modules/module_data_export_commissions_by_date_model', 'data_export');
			
			$start = _save_date($_POST['module_data_export_commissions_by_date_start_date'], false, 'min');
			$end = _save_date($_POST['module_data_export_commissions_by_date_end_date'], false, 'max');
			
			$commissions = $this->data_export->_get_commissions_by_date($start, $end, $this->session->userdata('userid'));
			
			if (!empty($commissions))
			{
				switch  ($_POST['module_data_export_commissions_by_date_delimiter'])
				{
					case 'tab':
						$delimiter = "\t";
					break;
					
					default:
						$delimiter = ',';
					break;
				}
		
				$newline = "\r\n";
				
				$sdata = $this->dbutil->csv_from_result($commissions, $delimiter, $newline); 
				
				$this->load->helper('download');
				
				$ext = $delimiter == 'tab' ? 'txt' : 'csv';
				
				$name = 'commissions-'.date('m-d-Y').'.' . $ext;
		
				force_download($name, $sdata); 
			}
			else
			{
				show_error($this->lang->line('no_data_found'));	
			}
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
		
		//repopulate form
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
}

?>