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
| FILENAME - modules.php
| -------------------------------------------------------------------------     
| 
*/

class Modules extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('modules_model');
		
		$this->load->helper('inflector');

		$this->config->set_item('uri', ADMIN_ROUTE. '/' . $this->uri->segment(2) . '/table');
		
		$this->module_options = array(					
										'account_add' => 'account add',
										'account_update' => 'account update',
										'account_delete' => 'account delete',
										'login' => 'account login',
										'affiliate_marketing' => 'affiliate marketing',
										'affiliate_payment' => 'affiliate payment',
										'data_import'	=> 'data import',
										'data_export'	=>	'date export',
										'mailing_list' => 'mailing list',
										'member_reporting' => 'member reporting',
										'post_commission_generation' => 'post commission generation',
										'stats_reporting' => 'stats and reporting',
								);
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . 'modules/view_modules');
	}
	
	// ------------------------------------------------------------------------
	
	function add_module()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['module_options'] = $this->module_options;
		
		if ($this->_check_module() == false)
		{	
			$this->validation->module_type = $this->uri->segment(4);
			
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
			
			$data['module_list'] = $this->modules_model->_list_modules();
			
			
			load_admin_tpl('admin', 'tpl_adm_manage_module', $data);	
		}
		else
		{	
			$data = $this->modules_model->_add_module($_POST);	

			$module_model = '/modules/module_' . $data['module_type'] . '_' . $data['module_file_name'] . '_model';
			
			$this->load->model($module_model, 'modules');

			$this->modules->_install_jrox_module($data['module_id']);
			
			$this->session->set_flashdata('success', $this->lang->line('item_added_successfully'));
			
			redirect(admin_url() . strtolower(__CLASS__) . '/update_module/' . $data['module_id']);
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function change_status()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		if ($this->db_validation_model->_change_status_field('modules', 'module_id', (int)$this->uri->segment(4), 'module_status'))
		{
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() .  strtolower(__CLASS__) . '/view_modules/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
			}
		}
		
		redirect(admin_url() . strtolower(__CLASS__) . '/update_module/' . (int)$this->uri->segment(4));
			
	}
	
	// ------------------------------------------------------------------------
	
	function delete_module()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->modules_model->_delete_module((int)($this->uri->segment(4))))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . 'modules/view_modules/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
			}
		}
		
		redirect(admin_url() . strtolower(__CLASS__) . '/view_modules');
	}
	
	// ------------------------------------------------------------------------
	
	function view_modules()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		$row  = $this->modules_model->_get_modules($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order'], $data['where_column'], $data['where_value']);
		
		$data['modules'] = $row['rows'];		
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'modules', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column'], $row['total_rows']);
		
		load_admin_tpl('admin', 'tpl_adm_manage_modules', $data);	
	}
	
	// ------------------------------------------------------------------------
	
	
	function update_module()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['module_options'] = $this->module_options;
		
		$this->validation->id = (int)$this->uri->segment(4);
		
		if ($this->_check_module() == false)
		{	
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
			
			$data['module_list'] = $this->modules_model->_list_modules('update');
				
			if (empty($_POST))
			{
				$m = $this->modules_model->_get_module_options((int)$this->uri->segment(4));

				if (!empty($m))
				{	
					foreach ($m as $k => $v)
					{
						$this->validation->$k = $v;
					}	
					
					$data['module_config'] = array();
				
					foreach ($m['sts_config'] as  $v)
					{
						$this->validation->$v['settings_key'] = $v['settings_value'];
		
						array_push($data['module_config'], $v);
					
					}
				}
				else
				{
					redirect(admin_url() . strtolower(__CLASS__) . '/view_modules');
					exit();
				}							
			}
			
			load_admin_tpl('admin', 'tpl_adm_manage_module', $data);
		}
		else
		{	
			$data = $this->modules_model->_update_module((int)$this->uri->segment(4));	
			
			$this->session->set_flashdata('success', $this->lang->line('update_module_success'));
			
			redirect(admin_url() . strtolower(__CLASS__) . '/update_module/' . (int)$this->uri->segment(4));
		}		
	}
	
	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	
	// ------------------------------------------------------------------------	
	
	function _check_module()
	{
		$rules['module_type'] = 'trim|required';
		$rules['module_status'] = 'trim';
		$rules['module_name'] = 'trim|required|min_length[2]|max_length[50]';
		$rules['module_description'] = 'trim|max_length[255]';

		if (!empty($_FILES) && $_FILES['zip_file']['error'] != 4)
		{
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
				
				@chmod($file,0777);
				
				if ($zip->open($file) === TRUE) 
				{
					$zip->extractTo(PUBPATH);
				
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
		
		$rules['module_file_name'] = 'trim|max_length[255]|callback__check_module_path';
		
		$this->validation->set_rules($rules);

		$fields['module_type'] = $this->lang->line('module_type');
		$fields['module_status'] = $this->lang->line('module_status');
		$fields['module_name'] = $this->lang->line('module_name');
		$fields['module_description'] = $this->lang->line('module_description');
		$fields['module_file_name'] = $this->lang->line('module_file_name');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
		
	}
	
	// ------------------------------------------------------------------------
	
	function _check_module_path()
	{		
		switch ($this->validation->module_type)
		{
			case 'affiliate_marketing':
			case 'affiliate_payment':
			case 'data_import':
			case 'data_export':
			case 'mailing_list':
			case 'member_reporting':						
			case 'stats_reporting':
				$vpath = APPPATH . 'controllers/modules/module_' . $this->validation->module_type . '_' . $this->validation->module_file_name . '.php';				
				if (!file_exists($vpath))
				{
					$this->validation->set_message('_check_module_path', 'controller - ' . $this->lang->line('invalid_module_file_path'));
					return false;
				}
		
			break;
		}
		
		$cpath = APPPATH . 'models/modules/module_' . $this->validation->module_type . '_' . $this->validation->module_file_name . '_model.php';
		
		if (!file_exists($cpath))
		{
			$this->validation->set_message('_check_module_path', 'model - ' . $this->lang->line('invalid_model_file_path'));
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
}
?>