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
| FILENAME - module_affiliate_marketing_banners.php
| -------------------------------------------------------------------------     
|
*/

class Module_Affiliate_Marketing_Banners extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('modules/module_affiliate_marketing_banners_model', 'banners_model');
		
		$this->load->model('uploads_model');		
		
		$this->config->set_item('menu', 'a');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect($this->uri->uri_string() . '/view');
	}
	
	// ------------------------------------------------------------------------
	
	function view()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = 'manage_banners';
		
		$data['tools'] = $this->banners_model->_get_banners($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);
		
		$data['totals'] = $this->db_validation_model->_get_count('affiliate_banners');
		
		$data['sort'] = array();
		
		for ($i=1; $i<=$data['totals'];$i++)
		{
			$data['sort'][$i] = $i;	
		}
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'affiliate_banners', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column']);
		
		load_admin_tpl('modules', 'tpl_adm_manage_affiliate_marketing_banners', $data);	
	}
	
	// ------------------------------------------------------------------------
	
	function add()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		$data['page_title'] = 'add_banner';

		if ($this->_check_banner() == false)
		{					
			if (!empty($_POST))
			{				
				$data['error'] =  $this->validation->error_string;
			}			
		}
		else
		{	
			if ($this->input->post('use_external_image') == 0)
			{
				if (!empty($_FILES) && $_FILES['userfile']['error'] != 4)
				{
					$image_data = $this->_upload_image($data);
					
					if (empty($image_data['success']))
					{
						$data['error'] =  $image_data['msg'];
					}
				}
				else
				{
					$data['error'] = $this->lang->line('banner_file_required');
				}
			}
			
			if (empty($data['error']))
			{
				$id = $this->banners_model->_add_banner($_POST);		
				
				if ($this->input->post('use_external_image') == 0)
				{
					$this->_update_banner_image('add', $id, $image_data['info']['file_name']);
				}

				$this->session->set_flashdata('success', $this->lang->line('item_added_successfully'));
				
				redirect(modules_url() . strtolower(__CLASS__) . '/edit/' . $id);
			}
		}	
		
		$programs = $this->programs_model->_get_all_programs();
		
		$data['programs'] = format_array($programs, 'program_id', 'program_name');
		
		load_admin_tpl('modules', 'tpl_adm_manage_affiliate_marketing_banner', $data);
	}
	
	// ------------------------------------------------------------------------
	
	function delete()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->banners_model->_delete_banner((int)($this->uri->segment(4))))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(modules_url()  . strtolower( __CLASS__) . '/view/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
		}
		
		redirect(modules_url() . strtolower(__CLASS__) . '/view');
	}
	
	// ------------------------------------------------------------------------
	
	function edit()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		$data['page_title'] = 'update_banner';
		
		$this->validation->id = (int)$this->uri->segment(4);
		
		if ($this->_check_banner() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string;
			}	
			else
			{
				$m = $this->banners_model->_get_banner_details((int)$this->uri->segment(4));
				
				if (!empty($m))
				{	
					foreach ($m as $k => $v)
					{
						$this->validation->$k = $v;
					}
				}
				else
				{
					redirect();
				}
			}
		}
		else
		{				
			if (!empty($_FILES) && $_FILES['userfile']['error'] != 4)
			{
				$image_data = $this->_upload_image($data);
				
				if (empty($image_data['success']))
				{
					$data['error'] =  $image_data['msg'];
				}
			}
			else
			{				
				if ($this->input->post('use_external_image') == 0 && !$this->input->post('banner_file_name'))
				{
					$data['error'] = $this->lang->line('banner_file_required');	
				}
			}
			
			if (empty($data['error']))
			{
				$id = $this->banners_model->_edit_banner((int)$this->uri->segment(4), $_POST);		
				
				if (!empty($image_data))
				{
					$this->_update_banner_image('update', $id, $image_data['info']['file_name']);
				}
				
				if ($this->input->post('use_external_image') == 1)
				{
					$this->_update_banner_image('update', $id, $this->input->post('banner_file_name'));
				}
				
				$this->session->set_flashdata('success', $this->lang->line('update_banner_success'));
					
				redirect($this->uri->uri_string());
			}
		}		
		
		$programs = $this->programs_model->_get_all_programs();
			
		$data['programs'] = format_array($programs, 'program_id', 'program_name');
			
		load_admin_tpl('modules', 'tpl_adm_manage_affiliate_marketing_banner', $data);
	}
	
	// ------------------------------------------------------------------------
	
	function update()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = 'update_module_options';

		if ($this->_check_banner_options() == false)
		{	
			if (!empty($_POST))
			{
				if (!empty($_POST['redirect']))
				{
					$this->session->set_flashdata('error', $this->validation->error_string);
					
					redirect($this->input->post('redirect'));
				}
				
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
			$this->aff->_change_sort_order('affiliate_banners');

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
		
		if ($this->db_validation_model->_change_status_field('affiliate_banners', 'id', (int)$this->uri->segment(4), 'status'))
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
	
	function _check_banner_options()
	{
		$rules['module_affiliate_marketing_banners_file_types'] = 'trim|required';
		
		$this->validation->set_rules($rules);

		//repopulate form

		$fields['module_affiliate_marketing_banners_file_types'] = $this->lang->line('module_affiliate_marketing_banners_file_types');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_banner_height()
	{
		if ($this->validation->banner_height < 1)
		{
			$this->validation->set_message('_check_banner_height', $this->lang->line('invalid_banner_height'));
			return false;
		}	
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_banner_width()
	{
		if ($this->validation->banner_width < 1)
		{
			$this->validation->set_message('_check_banner_width', $this->lang->line('invalid_banner_width'));
			return false;
		}	
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_banner()
	{
		$rules['status'] = 'trim|required|integer';
		$rules['name'] = 'trim|required|min_length[5]|max_length[50]';
		$rules['rotator_group'] = 'trim|integer';
		$rules['enable_redirect'] = 'trim|integer';
		$rules['program_id'] = 'trim|required|integer';
		
		if ($this->input->post('enable_redirect') == 1)
		{
			$rules['redirect_custom_url'] = 'trim|required|prep_url';
		}
		
		$rules['banner_height'] = 'trim|required|numeric|callback__check_banner_height';
		$rules['banner_width'] = 'trim|required|numeric|callback__check_banner_width';
		$rules['use_external_image'] = 'trim|integer';
		
		if ($this->input->post('use_external_image') == 1)
		{
			$rules['banner_file_name'] = 'trim|required|prep_url';
		}
		
		$rules['notes'] = 'trim';
		
		$this->validation->set_rules($rules);

		//repopulate form

		$fields['status'] = $this->lang->line('status');
		$fields['name'] = $this->lang->line('banner_name');
		$fields['rotator_group'] = $this->lang->line('rotator_group');
		$fields['enable_redirect'] = $this->lang->line('enable_redirect');
		$fields['redirect_custom_url'] = $this->lang->line('redirect_custom_url');
		$fields['banner_height'] = $this->lang->line('banner_height');
		$fields['banner_width'] = $this->lang->line('banner_width');
		$fields['use_external_image'] = $this->lang->line('use_external_image');
		$fields['banner_file_name'] = $this->lang->line('banner_file_name');
		$fields['notes'] = $this->lang->line('notes');
		$fields['program_id'] = $this->lang->line('program');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _update_banner_image($type = '', $id = '', $file_name = '')
	{
		
		if ($type == 'update')
		{
			//get the data first
			$this->db->where('id', $id);
			$query = $this->db->get('affiliate_banners');
			
			$row = $query->result_array();
			
			if ($row[0]['banner_file_name'] != $file_name)
			{
				//delet the old banner
				
				@unlink('./images/' . $this->config->item('images_banners_dir') . '/' . $row[0]['banner_file_name']);
		
			}	
		}
		
		//add the image to the banner
		$sdata['config'] = array(
							'table'	=>	'affiliate_banners',
							'key'	=>	'id',
							'value' 	=>	$id,
							);
		
		$sdata['fields'] = array (
								'banner_file_name'	=> 	$file_name
								);			
		
		if ($this->uploads_model->_update_image_db($sdata))
		{
			return true;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	function _upload_image()
	{		
		
		//upload the photo
		$data = $this->uploads_model->_upload_photo('banners', '0', 'userfile', $this->config->item('module_affiliate_marketing_banners_file_types'));
		
		return $data;
		
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
				
				$fields[$k] = $this->lang->line('banner_id'). ' ' .end($opt) ;
			}
		}
		
		$this->validation->set_rules($rules);
		
		//repopulate form
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
	}
}
?>