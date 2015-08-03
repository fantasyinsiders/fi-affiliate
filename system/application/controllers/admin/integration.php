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
| FILENAME - integration.php
| -------------------------------------------------------------------------     
| 
*/

class Integration extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('integration_model', 'integ');
		
		$this->config->set_item('menu', 'o');
	}
	
	// ------------------------------------------------------------------------	
	
	function index()
	{
		redirect(admin_url() . strtolower(__CLASS__) . '/options');
	}
	
	// ------------------------------------------------------------------------	
	
	function options()
	{	
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = 'website_integration';
		
		$data['integration'] = $this->integ->_get_integration_methods($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'program_integration', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column']);
		
		load_admin_tpl('admin', 'tpl_adm_manage_program_integration', $data);		
	}
	
	// ------------------------------------------------------------------------
	
	function updates()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		$this->load->model('update_model');
		
		$filename = 'jam-integration-updates.zip';
		$url =  'http://www.jrox.com/_mdownloads/jam/' . $filename; 
		$new_update = true;
		
		$path = PUBPATH . '/import/integration/' . $filename;
		
		//get updates if available
		if (is_writable(PUBPATH . '/import/integration/'))
		{
			
			if (!file_exists($path))
			{
				$update_file = connect_curl($url, false, '', 1, 30, false, $path);
			}
			 
			if (file_exists($path) && (extension_loaded('zip')))
			{
				//unzip the file
				$zip = new ZipArchive;

				@chmod($path,0777);
				if ($zip->open($path) === TRUE) 
				{
					$zip->extractTo(PUBPATH);
					$zip->close();
					@unlink($path);
				} 
				else 
				{
					show_error('<a href="' . $url . '">ERROR CODE: INT03: ' . $this->lang->line('new_update_found_needs_download') . '</a>');
				}
			}
			else 
			{
				show_error('<a href="' . $url . '">ERROR CODE: INT02: ' . $this->lang->line('new_update_found_needs_download') . '</a>');
			}
		}
		else 
		{
			//show_error(PUBPATH . '/import/' . ' is not writable (777)');
		}		
	
		$data = $this->update_model->_run_updates('integration');
		
		$msg = $this->lang->line('system_updated_successfully');
					
		if (!empty($data['success']) && !empty($data['files']))
		{					
			$msg = $data['files'];
		}	
		
		
				
		if (empty($new_update))
		{
			$msg =  $this->lang->line('no_updates_found');
		}
		$this->session->set_flashdata('success', $msg);	
		
		redirect(admin_url() .  strtolower(__CLASS__) . '/options');
	}
	
	// ------------------------------------------------------------------------
	
	function method()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = 'website_integration';
		
		$data['id'] = (int)$this->uri->segment(4);
		
		$data['int'] = $this->integ->_get_integration_option($data['id']);
		
		foreach ($data['int'] as $k => $v)
		{
			$data[$k] = $v;	
		} 
		
		$data['description'] = str_replace('{tracking_url}', base_url(), $data['description']);
		$data['description'] = str_replace('{aff_cookie_name}', $data['aff_cookie_name'], $data['description']);

		load_admin_tpl('admin', 'tpl_adm_manage_integration_method', $data);		
	}
	
	// ------------------------------------------------------------------------	
	
	function integration_profiles()
	{	
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['integration'] = $this->integ->_get_integration_profiles();
		
		load_admin_tpl('admin', 'tpl_adm_manage_integration_profiles', $data);	
	}
	
	// ------------------------------------------------------------------------
	
	function add_integration_profile()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		for ($i = 1; $i<=20; $i++)
		{
			$data['fields']['custom_commission_field_' . $i] = 'custom_field_' . $i;							
		}
		
		$programs = $this->programs_model->_get_all_programs();
		
		$data['programs'] = format_array($programs, 'program_id', 'program_name');	

		if ($this->_check_integration_profile() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
			
			load_admin_tpl('admin', 'tpl_adm_manage_integration_profile', $data);
		}
		else
		{				
			if ($this->integ->_add_integration_value((int)$this->uri->segment(4)))	
			{
				$this->session->set_flashdata('success', $this->lang->line('item_added_successfully'));
			}
			
			redirect(admin_url() .  strtolower(__CLASS__) . '/integration_profiles/');
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function delete_integration_profile()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->integ->_delete_integration_value((int)($this->uri->segment(4))))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));
		}
		
		redirect(admin_url() .  strtolower(__CLASS__) . '/integration_profiles');
	}
	
	// ------------------------------------------------------------------------
	
	function update_integration_profile()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		for ($i = 1; $i<=20; $i++)
		{
			$data['fields']['custom_commission_field_' . $i] = 'custom_field_' . $i;							
		}
		
		$programs = $this->programs_model->_get_all_programs();
		
		$data['programs'] = format_array($programs, 'program_id', 'program_name');	
			
		if ($this->_check_integration_profile() == false)
		{	
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
				
			if (empty($_POST))
			{
				$m = $this->integ->_get_integration_value((int)$this->uri->segment(4));
				
				if (!empty($m))
				{	
					foreach ($m as $k => $v)
					{
						$this->validation->$k = $v;
					}
				}
				else
				{
					redirect(admin_url() . 'integration/integration_profiles');
					exit();
				}
			}
			
			load_admin_tpl('admin', 'tpl_adm_manage_integration_profile', $data);
			
		}
		else
		{	
			if($this->integ->_update_integration_value((int)$this->uri->segment(4)))
			{
				$this->session->set_flashdata('success', $this->lang->line('integration_profiles_updated_successfully'));
			}
			
			redirect(admin_url() .  strtolower(__CLASS__) . '/integration_profiles/');
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function api()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);	
		
		load_admin_tpl('admin', 'tpl_adm_automation_api', $data);
	}
	
	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	
	// ------------------------------------------------------------------------	
	
	function _check_integration_profile()
	{
		$rules['name'] = 'trim|required||alpha_numeric|max_length[255]';	
		$rules['program_id'] = 'trim|required|integer';
		$rules['product_code'] = 'trim|max_length[255]';
		$rules['description'] = 'trim|max_length[255]';
		$rules['amount'] = 'trim|required|max_length[255]';	
		$rules['trans_id'] = 'trim|required|max_length[255]';	
		$rules['invoice_id'] = 'trim|max_length[255]';	
		$rules['customer_name'] = 'trim|max_length[255]';	
		$rules['first_name'] = 'trim|max_length[255]';	
		$rules['last_name'] = 'trim|max_length[255]';	
		$rules['lf_data'] = 'trim|max_length[255]';
		$rules['custom_field_1'] = 'trim|max_length[255]';
		$rules['custom_field_2'] = 'trim|max_length[255]';
		$rules['custom_field_3'] = 'trim|max_length[255]';
		$rules['custom_field_4'] = 'trim|max_length[255]';
		$rules['custom_field_5'] = 'trim|max_length[255]';
		$rules['custom_field_6'] = 'trim|max_length[255]';
		$rules['custom_field_7'] = 'trim|max_length[255]';
		$rules['custom_field_8'] = 'trim|max_length[255]';
		$rules['custom_field_9'] = 'trim|max_length[255]';
		$rules['custom_field_10'] = 'trim|max_length[255]';
		$rules['custom_field_11'] = 'trim|max_length[255]';
		$rules['custom_field_12'] = 'trim|max_length[255]';
		$rules['custom_field_13'] = 'trim|max_length[255]';
		$rules['custom_field_14'] = 'trim|max_length[255]';
		$rules['custom_field_15'] = 'trim|max_length[255]';
		$rules['custom_field_16'] = 'trim|max_length[255]';
		$rules['custom_field_17'] = 'trim|max_length[255]';
		$rules['custom_field_18'] = 'trim|max_length[255]';
		$rules['custom_field_19'] = 'trim|max_length[255]';
		$rules['custom_field_20'] = 'trim|max_length[255]';
		$rules['tracking_code'] = 'trim|required|max_length[255]';
		
		$this->validation->set_rules($rules);

		//repopulate form
		$fields['name'] = $this->lang->line('name');
		$fields['program_id'] =  $this->lang->line('program_id');
		$fields['product_code'] = $this->lang->line('product_code');
		$fields['description'] = $this->lang->line('description');
		$fields['amount'] = $this->lang->line('amount');
		$fields['trans_id'] = $this->lang->line('trans_id');
		$fields['invoice_id'] = $this->lang->line('trans_id');
		$fields['customer_name'] = $this->lang->line('trans_id');
		$fields['first_name'] = $this->lang->line('trans_id');
		$fields['last_name'] = $this->lang->line('custom_field_');	
		$fields['lf_data'] = $this->lang->line('custom_field_');
		$fields['custom_field_1'] = $this->lang->line('custom_commission_field_1');
		$fields['custom_field_2'] = $this->lang->line('custom_commission_field_2');
		$fields['custom_field_3'] = $this->lang->line('custom_commission_field_3');
		$fields['custom_field_4'] = $this->lang->line('custom_commission_field_4');
		$fields['custom_field_5'] = $this->lang->line('custom_commission_field_5');
		$fields['custom_field_6'] = $this->lang->line('custom_commission_field_6');
		$fields['custom_field_7'] = $this->lang->line('custom_commission_field_7');
		$fields['custom_field_8'] = $this->lang->line('custom_commission_field_8');
		$fields['custom_field_9'] = $this->lang->line('custom_commission_field_9');
		$fields['custom_field_10'] = $this->lang->line('custom_commission_field_10');
		$fields['custom_field_11'] = $this->lang->line('custom_commission_field_11');
		$fields['custom_field_12'] = $this->lang->line('custom_commission_field_12');
		$fields['custom_field_13'] = $this->lang->line('custom_commission_field_13');
		$fields['custom_field_14'] = $this->lang->line('custom_commission_field_14');
		$fields['custom_field_15'] = $this->lang->line('custom_commission_field_15');
		$fields['custom_field_16'] = $this->lang->line('custom_commission_field_16');
		$fields['custom_field_17'] = $this->lang->line('custom_commission_field_17');
		$fields['custom_field_18'] = $this->lang->line('custom_commission_field_18');
		$fields['custom_field_19'] = $this->lang->line('custom_commission_field_19');
		$fields['custom_field_20'] = $this->lang->line('custom_commission_field_20');
		$fields['tracking_code'] = $this->lang->line('tracking_code');
		
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