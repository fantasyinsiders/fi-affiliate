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
| FILENAME - affiliate_groups.php
| -------------------------------------------------------------------------     
| 
*/

class Affiliate_Groups extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('groups_model', 'groups');
		
		$this->config->set_item('menu', 'm');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . strtolower( __CLASS__) . '/view_groups');
	}
	
	// ------------------------------------------------------------------------
	
	function view_groups()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['groups'] =$this->groups->_get_affiliate_groups($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);
		
		$data['totals'] = $this->db_validation_model->_get_count('affiliate_groups');
		
		$data['tiers'] = array();
		
		for ($i=1; $i<=$data['totals'];$i++)
		{
			$data['tiers'][$i] = $i;	
		}
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'affiliate_groups', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column']);
		
		load_admin_tpl('admin', 'tpl_adm_manage_affiliate_groups', $data);
	}
	
	// ------------------------------------------------------------------------
	
	function add_group()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		$this->load->model('mailing_lists_model');
		
		$lists = $this->mailing_lists_model->_get_all_mailing_lists();
		
		$data['lists'] = format_array($lists, 'mailing_list_id', 'mailing_list_name', true);
		
		$this->validation->mailing_list_id = '';
		 
		if ($this->_check_group('add') == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string;
			}
			
			load_admin_tpl('admin', 'tpl_adm_manage_affiliate_group', $data);
		
		}
		else
		{	
			$id =$this->groups->_add_affiliate_group($_POST);		
			
			$this->session->set_flashdata('success', $this->lang->line('item_added_successfully'));
						
			redirect(admin_url() . strtolower( __CLASS__) . '/update_group/' . $id);
		}
	}
	
	// ------------------------------------------------------------------------
	
	function delete_group()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->groups->_delete_aff_group((int)($this->uri->segment(4))))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . strtolower( __CLASS__) . '/view_groups/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
		}
		
		redirect(admin_url() . strtolower( __CLASS__) . '/view_groups');
	}
	
	// ------------------------------------------------------------------------
	
	function update_group()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$this->load->model('mailing_lists_model');
		
		$lists = $this->mailing_lists_model->_get_all_mailing_lists();
		
		$data['lists'] = format_array($lists, 'mailing_list_id', 'mailing_list_name', true);
		
		$this->validation->group_id = (int)$this->uri->segment(4);
		
		if ($this->_check_group('update') == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
			else
			{
				$m = $this->groups->_get_aff_group_details((int)$this->uri->segment(4));
				
				if (!empty($m))
				{	
					foreach ($m as $k => $v)
					{
						$this->validation->$k = $v;
					}
				}
				else
				{
					redirect(admin_url() . strtolower( __CLASS__) . '/view_groups');
					exit();
				}			
			}
			
			load_admin_tpl('admin', 'tpl_adm_manage_affiliate_group', $data);	
		}
		else
		{				
			$data =$this->groups->_update_affiliate_group((int)$this->uri->segment(4), $_POST);	
			
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
						
			redirect($this->uri->uri_string());
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function update_groups()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->_check_tiers() == true)
		{	
			$this->groups->_change_aff_group_status();
			
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
		}
		else
		{
			$this->session->set_flashdata('error', $this->validation->error_string);
		}
		
		redirect(admin_url() . strtolower( __CLASS__) . '/view_groups');
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
			if (strstr($k, "group") == true) 
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
	
	function _check_group_code_update()
	{
		if ($this->db_validation_model->_validate_field('affiliate_groups', 'aff_group_code', $this->validation->aff_group_code, 'group_id', $this->validation->group_id))
		{
			$this->validation->set_message('_check_group_code_update', $this->lang->line('group_code_taken'));
			
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_group_code_add()
	{
		if ($this->db_validation_model->_validate_field('affiliate_groups', 'aff_group_code', $this->validation->aff_group_code))
		{
			$this->validation->set_message('_check_group_code_add', $this->lang->line('group_code_taken'));
			
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_group($type = '')
	{		
		$rules['aff_group_name'] = 'trim|required|min_length[2]|max_length[50]';
		
		if ($type == 'add')
		{
			$rules['aff_group_code'] = 'trim|required|min_length[4]|max_length[25]|strip_tags|callback__check_group_code_add';
		}
		else
		{
			$rules['aff_group_code'] = 'trim|required|min_length[4]|max_length[25]|strip_tags|callback__check_group_code_update';	
		}
		
		$rules['aff_group_description'] = 'trim|max_length[255]';
		$rules['commission_type'] = 'trim|required';
		$rules['tier'] = 'trim|numeric';
		$rules['ppc_amount'] = 'trim|numeric';
		$rules['cpm_amount'] = 'trim|numeric';
	
		for ($i=1;$i<=10;$i++)
		{
			$level = 'commission_level_' . $i;
			
			$rules[$level] = 'trim|numeric';	
		}	
		
		$this->validation->set_rules($rules);

		$fields['aff_group_name'] = $this->lang->line('group_name');
		$fields['aff_group_code'] = $this->lang->line('group_code');
		$fields['aff_group_description'] = $this->lang->line('group_description');
		$fields['commission_type'] = $this->lang->line('commission_type');
		$fields['tier'] = $this->lang->line('tier');
		$fields['ppc_amount'] = $this->lang->line('ppc_amount');
		$fields['cpm_amount'] = $this->lang->line('cpm_amount');
	
		for ($k=1;$k<=10;$k++)
		{
			$level = 'commission_level_' . $k;
			$fields[$level] = $this->lang->line($level);
		}	
		
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