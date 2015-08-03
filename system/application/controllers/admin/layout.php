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
| FILENAME - layout.php
| -------------------------------------------------------------------------     
| 
*/

class Layout extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('settings_model', 'set');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . 'layout/generate_menu/1');
	}
	
	// ------------------------------------------------------------------------
	
	function manage_sub_menus()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = 'manage_sub_menus';
		
		$data['id'] = (int)$this->uri->segment(4);
		
		$data['program_id'] = $this->uri->segment(5,1);
		
		if (empty($_POST))
		{
							
			$data['subs'] = $this->set->_get_sub_menus($data['id']);
			
			load_admin_tpl('admin', 'tpl_adm_manage_sub_menus', $data);
		}
		else
		{
			if ($this->_check_menu_layout() == false)
			{
				$this->session->set_flashdata('error', $this->lang->line('all_menu_sort_order_numeric'));
				exit();
			}
			else
			{			
				if ($this->set->_update_sub_menus($data['id'], $data['program_id']))
				{

					$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
				}
			}
			
			redirect($this->uri->uri_string());		
		}
	}
	
	// ------------------------------------------------------------------------
	
	function generate_menu()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = 'menu_maker';
		
		$data['program_id'] = (int)$this->uri->segment(4, 1);
		
		$this->load->model('programs_model');
		
		$program_name = $this->programs_model->_get_program_name($data['program_id']);

		if (empty($_POST))
		{
			$config = $this->set->_get_layout_menu($data['program_id']);

			$data['members'] = array();
			
			foreach ($config as  $v)
			{
				array_push($data['members'], $v);
			}
			
			load_admin_tpl('admin', 'tpl_adm_manage_menus', $data);

		}		
		else
		{
			if ($this->_check_menu_layout() == false)
			{
				show_error($this->lang->line('all_menu_sort_order_numeric'));
			}
			else
			{			
				if ($this->set->_update_menus($data['program_id']))
				{
					$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
					
					redirect(admin_url() . 'layout/generate_menu/' . $data['program_id']);		
				}				
			}	
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function update_layout()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->set->_update_settings())
		{
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
		}
		
		redirect(admin_url() . 'themes/view_themes#dash');	
	}
	
	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	
	// ------------------------------------------------------------------------	
	
	function _check_menu_layout()
	{
		$data = $this->db_validation_model->_clean_data($_POST);
	
		foreach ($data as $k => $v)
		{				
			if (substr($k,0,16) == "member_menu_sort")
			{
				if ($k != 'member_menu_sort_order-0')
				{
					if (!is_numeric($v))
					{
						return false;
					}
				}
			}
			
			
		}
		
		return true;
	}
}