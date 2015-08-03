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
| FILENAME - import.php
| -------------------------------------------------------------------------     
| 
*/

class Import extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('import_model', 'import');
		
		$this->config->set_item('menu', 'x');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . 'import/view_import_modules');
	}
	
	// ------------------------------------------------------------------------
	
	function view_import_modules()
	{

		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['module'] = $this->import->_get_import($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);		

		$row_count = $this->db_validation_model->_get_count('modules', 'WHERE module_type = \'data_import\'');
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'import', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column'], $row_count);
		
		load_admin_tpl('admin', 'tpl_adm_manage_import', $data);	
	}
	
	// ------------------------------------------------------------------------
	
	function change_status()
	{
		
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->db_validation_model->_change_status_field('modules', 'module_id', (int)$this->uri->segment(4), 'module_status'))
		{
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
		}
		
		redirect(admin_url() . 'import/view_import_modules');
	}
}
?>