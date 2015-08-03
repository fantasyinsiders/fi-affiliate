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
| FILENAME - module_affiliate_marketing_viral_videos.php
| -------------------------------------------------------------------------     
|
*/

class Module_Affiliate_Marketing_viral_videos extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('modules/module_affiliate_marketing_viral_videos_model', 'viral_videos_model');
		
		$this->config->set_item('menu', 'a');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect($this->uri->uri_string() . '/view/');
	}
	
	// ------------------------------------------------------------------------
	
	function view()
	{ 
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = 'manage_viral_videos';
		
		$data['tools'] = $this->viral_videos_model->_get_viral_videos($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);
		
		$data['totals'] = $this->db_validation_model->_get_count('affiliate_viral_videos');
		
		$data['sort'] = array();
		
		for ($i=1; $i<=$data['totals'];$i++)
		{
			$data['sort'][$i] = $i;	
		}
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'affiliate_viral_videos', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column']);
		
		load_admin_tpl('modules', 'tpl_adm_manage_affiliate_marketing_viral_videos', $data);	
	}
	
	// ------------------------------------------------------------------------
	
	function add()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = 'add_viral_video';
			
		if ($this->_check_viral_video() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string;	
			}
			
			$programs = $this->programs_model->_get_all_programs();
			
			$data['programs'] = format_array($programs, 'program_id', 'program_name');
			
			load_admin_tpl('modules', 'tpl_adm_manage_affiliate_marketing_viral_video', $data);		
		}
		else
		{	
			$id = $this->viral_videos_model->_add_viral_video($_POST);		
			
			$this->session->set_flashdata('success', $this->lang->line('item_added_successfully'));

			redirect(modules_url()  . strtolower( __CLASS__) . '/edit/' . $id);
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function delete()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		if ($this->aff->_delete_tool('viral_video', (int)($this->uri->segment(4)), 'affiliate_viral_videos'))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(modules_url()  . strtolower( __CLASS__) . '/view/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
		}
		
		redirect(modules_url()  . strtolower( __CLASS__) . '/view');
	}
	
	// ------------------------------------------------------------------------
	
	function edit()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		$this->validation->id = (int)$this->uri->segment(4);
		
		$data['page_title'] = 'update_viral_video';
		
		if ($this->_check_viral_video() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string;	
			}
			else
			{
				$data['viral_video'] = $this->viral_videos_model->_get_viral_video_details((int)$this->uri->segment(4));
				
				if (!empty($data['viral_video']))
				{	
					foreach ($data['viral_video'] as $k => $v)
					{
						$this->validation->$k = $v;
					}
				}
				else
				{
					redirect(admin_url());
				}
				
				
			}
			
			$programs = $this->programs_model->_get_all_programs();
			
			$data['programs'] = format_array($programs, 'program_id', 'program_name');
			
			load_admin_tpl('modules', 'tpl_adm_manage_affiliate_marketing_viral_video', $data);
		}
		else
		{	
			$id = $this->viral_videos_model->_edit_viral_video($this->validation->id, $_POST);		
				
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			redirect($this->uri->uri_string());
			
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function update()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = 'update_module_options';
		
		if ($this->_check_viral_video_options() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] =  $this->validation->error_string;	
			}
			
			$m = $this->aff->_get_affiliate_marketing_details((int)$this->uri->segment(4));
			
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
			}
			else
			{
				redirect();
			}
			
			load_admin_tpl('modules', 'tpl_adm_manage_affiliate_marketing_options', $data);
		}
		else
		{	
			$data = $this->modules_model->_update_options($_POST);	
			
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			$url = !empty($_POST['redirect']) ? $_POST['redirect'] : $this->uri->uri_string();
			
			redirect($url);
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function sort_order()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		if ($this->_check_sort_order() == true)
		{	
			$this->aff->_change_sort_order('affiliate_viral_videos');

			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
		}
		else
		{
			$this->session->set_flashdata('error', $this->validation->error_string);
		}
		
		redirect(modules_url()  . strtolower( __CLASS__) . '/view');
	}
	
	// ------------------------------------------------------------------------
	
	
	function change_status()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->db_validation_model->_change_status_field('affiliate_viral_videos', 'id', (int)$this->uri->segment(4), 'status'))
		{
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(modules_url()  . strtolower( __CLASS__) . '/view/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
		}
		
		redirect(modules_url()  . strtolower( __CLASS__) . '/view');
	}

	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	
	// ------------------------------------------------------------------------	
	
	function _check_viral_video_options()
	{
		$rules['module_affiliate_marketing_viral_videos_default_viral_video_width'] = 'trim|required|numeric';
		
		$this->validation->set_rules($rules);

		$fields['module_affiliate_marketing_viral_videos_default_viral_video_width'] = $this->lang->line('module_affiliate_marketing_viral_videos_default_viral_video_width');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_viral_video()
	{
		$rules['status'] = 'trim|required|integer';
		$rules['name'] = 'trim|required|min_length[5]|max_length[50]';
		$rules['viral_video_link'] = 'trim|required';
		$rules['enable_redirect'] = 'trim|integer';
		$rules['program_id'] = 'trim|required|integer';
		
		if ($this->input->post('enable_redirect') == 1)
		{
			$rules['redirect_custom_url'] = 'trim|required|prep_url';
		}
		
		$rules['notes'] = 'trim';
		
		$this->validation->set_rules($rules);

		//repopulate form

		$fields['status'] = $this->lang->line('status');
		$fields['name'] = $this->lang->line('viral_video_name');
		$fields['viral_video_link'] = $this->lang->line('viral_video_link');
		$fields['enable_redirect'] = $this->lang->line('enable_redirect');
		$fields['redirect_custom_url'] = $this->lang->line('redirect_custom_url');
		$fields['program_id'] = $this->lang->line('program');

		$fields['notes'] = $this->lang->line('notes');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
	}
	
	
	
	// ------------------------------------------------------------------------	
	
	function _check_sort_order()
	{
		$data = $this->db_validation_model->_clean_data($_POST);
	
		foreach ($data as $k => $v)
		{
			if (strstr($k, "tool") == true) 
			{
				$rules[$k] = 'trim|required|numeric';
				
				$opt = explode('-', $k);
				
				$fields[$k] = $this->lang->line('viral_video_id'). ' ' .end($opt) ;
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
}
?>