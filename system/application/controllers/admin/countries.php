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
| FILENAME - countries.php
| -------------------------------------------------------------------------     
| 
*/

class Countries extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('zones_model');
		
		$this->config->set_item('menu', 's');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . 'countries/view_countries');
	}
	
	// ------------------------------------------------------------------------
	
	function add_country()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->_check_country() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
			
			load_admin_tpl('admin', 'tpl_adm_manage_country', $data);
		}
		else
		{	
			$id = $this->zones_model->_add_country($_POST);		
			
			$this->session->set_flashdata('success', $this->lang->line('item_added_successfully'));
			
			redirect(admin_url() . 'countries/view_countries');
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function delete_country()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->zones_model->_delete_country((int)($this->uri->segment(4))))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . 'countries/view_countries/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
		}
		
		redirect(admin_url() . 'countries/view_countries');
	}
	
	// ------------------------------------------------------------------------	
	
	function update_countries()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->input->post('country') AND count($this->input->post('country')) > 0)
		{
			$this->zones_model->_change_status($this->input->post('country'), $this->input->post('change-status'));
			
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
		
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . 'countries/view_countries/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
		}

		redirect(admin_url() . 'countries/view_countries');
	}
	
	// ------------------------------------------------------------------------
	
	function view_countries()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['countries'] = $this->zones_model->_get_countries($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);

		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'countries', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column']);
		
		load_admin_tpl('admin', 'tpl_adm_manage_countries', $data);	
	}
	
	// ------------------------------------------------------------------------
	
	
	function update_country()
	{

		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->_check_country() == false)
		{		
			$this->validation->id = (int)$this->uri->segment(4);
			
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
			
			if (empty($_POST))
			{
				$m = $this->zones_model->_get_country_details((int)$this->uri->segment(4));
				
				if (!empty($m))
				{	
					foreach ($m as $k => $v)
					{
						$this->validation->$k = $v;
					}
				}
				else
				{
					redirect(admin_url() . 'countries/view_countries');
					exit();
				}
							
			}
			
			load_admin_tpl('admin', 'tpl_adm_manage_country', $data);
		}
		else
		{				
			if ($this->zones_model->_update_country((int)$this->uri->segment(4), $_POST))
			{
				$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			}
			
			redirect($this->uri->uri_string());		
		}		
	}
	
	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/

	// ------------------------------------------------------------------------	
	
	function _check_tiers()
	{
		$data = $this->db_validation_model->_clean_data($_POST);
	
		foreach ($data as $k => $v)
		{
			if (strstr($k, "country") == true) 
			{
				$rules[$k] = 'trim|required|numeric';
				$fields[$k] = $k ;
			}
		}
		
		$this->validation->set_rules($rules);
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_country()
	{
		$rules['country_name'] = 'trim|required|min_length[2]|max_length[50]';
		$rules['country_iso_code_2'] = 'trim|required|min_length[2]|max_length[2]';
		$rules['country_iso_code_3'] = 'trim|required|min_length[3]|max_length[3]';
		
		$this->validation->set_rules($rules);

		$fields['country_name'] = $this->lang->line('country_name');
		$fields['country_iso_code_2'] = $this->lang->line('iso_2');
		$fields['country_iso_code_3'] = $this->lang->line('iso_3');
		
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