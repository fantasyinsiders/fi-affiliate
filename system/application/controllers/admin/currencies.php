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
| FILENAME - currencies.php
| -------------------------------------------------------------------------     
| 
*/

class Currencies extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('currencies_model');
		
		$this->config->set_item('menu', 's');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . 'currencies/view_currencies');
	}
	
	// ------------------------------------------------------------------------
	
	function add_currency()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->_check_currency() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
			else
			{
				$this->validation->decimal_point = '.';
				$this->validation->thousands_point = ',';
				$this->validation->decimal_places = '2';	
			}
			
			load_admin_tpl('admin', 'tpl_adm_manage_currency', $data);
		}
		else
		{	
			$id = $this->currencies_model->_add_currency();		
			
			$this->session->set_flashdata('success', $this->lang->line('item_added_successfully'));
			
			redirect(admin_url() . 'currencies/view_currencies');
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function delete_currency()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->currencies_model->_delete_currency((int)($this->uri->segment(4))))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . 'currencies/view_currencies/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
		}
		
		redirect(admin_url() . 'currencies/view_currencies');
	}
	
	// ------------------------------------------------------------------------
	
	function view_currencies()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['currencies'] = $this->currencies_model->_get_currencies($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'currencies', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column']);
		
		load_admin_tpl('admin', 'tpl_adm_manage_currencies', $data);	

	
	}
	
	// ------------------------------------------------------------------------
	
	function set_default()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$code = $this->uri->segment(4);
		
		$curr_data = $this->currencies_model->_get_currency_details($code, 'code');
		
		$this->currencies_model->_convert_currencies($curr_data);
		
		$this->db_validation_model->_update_db_settings(array('sts_site_default_currency' => $code));
		
		redirect(admin_url() . 'currencies/view_currencies/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
	}
	
	// ------------------------------------------------------------------------
	
	function run_update()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$msg = $this->currencies_model->_update_currencies();
		
		echo $msg;
	}
	
	// ------------------------------------------------------------------------
	
	function update_currencies()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		load_admin_tpl('admin', 'tpl_adm_update_currencies', $data, false, false);
	}
	
	// ------------------------------------------------------------------------
	
	function update_currency()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		if ($this->_check_currency() == false)
		{	
			$this->validation->id = (int)$this->uri->segment(4);
			
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
				
			$m = $this->currencies_model->_get_currency_details((int)$this->uri->segment(4));
			
			if (!empty($m))
			{	
				foreach ($m as $k => $v)
				{
					$this->validation->$k = $v;
				}
			}
			else
			{
				redirect(admin_url() . 'currencies/view_currencies');
				exit();
			}
							
			load_admin_tpl('admin', 'tpl_adm_manage_currency', $data);
		}
		else
		{	
			if ($this->currencies_model->_update_currency((int)$this->uri->segment(4)))
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
	
	function _check_currency()
	{		
		$rules['title'] = 'trim|required|min_length[2]|max_length[50]';
		$rules['code'] = 'trim|required|exact_length[3]';
		$rules['symbol_left'] = 'trim|max_length[15]';
		$rules['symbol_right'] = 'trim|max_length[15]';
		$rules['decimal_point'] = 'trim|max_length[15]';
		$rules['thousands_point'] = 'trim|max_length[15]';
		$rules['decimal_places'] = 'trim|numeric';
		$rules['value'] = 'trim|required|numeric';
		
		
		$this->validation->set_rules($rules);

		$fields['title'] = $this->lang->line('currency_name');
		$fields['code'] = $this->lang->line('code');
		$fields['symbol_left'] = $this->lang->line('symbol_left');
		$fields['symbol_right'] = $this->lang->line('symbol_right');
		$fields['decimal_point'] = $this->lang->line('decimal_point');
		$fields['thousands_point'] = $this->lang->line('thousands_point');
		$fields['decimal_places'] = $this->lang->line('decimal_places');
		$fields['value'] = $this->lang->line('amount');
		
		
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