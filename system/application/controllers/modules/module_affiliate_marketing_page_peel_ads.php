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
| FILENAME - module_affiliate_marketing_page_peel_ads.php
| -------------------------------------------------------------------------     
| 
*/

class Module_Affiliate_Marketing_Page_Peel_Ads extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('modules/module_affiliate_marketing_page_peel_ads_model', 'page_peel_ads_model');	
		
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

		$data['page_title'] = 'view_page_peel_ads';

		$data['tools'] = $this->page_peel_ads_model->_get_page_peel_ads($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);
		
		$data['totals'] = $this->db_validation_model->_get_count('affiliate_page_peel_ads');
		
		$data['sort'] = array();
		
		for ($i=1; $i<=$data['totals'];$i++)
		{
			$data['sort'][$i] = $i;	
		}
			
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'affiliate_page_peel_ads', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column']);
		
		load_admin_tpl('modules', 'tpl_adm_manage_affiliate_marketing_page_peel_ads', $data);
	}
	
	// ------------------------------------------------------------------------
	
	function add()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		$data['page_title'] = 'add_page_peel_ad';
		
		if ($this->_check_page_peel_ad() == false)
		{				
			if (!empty($_POST))
			{				
				$data['error'] =  $this->validation->error_string;
			}				
		}
		else
		{	
			if (empty($data['error']))
			{
				$id = $this->page_peel_ads_model->_add_page_peel_ad();		
				
				$this->load->model('uploads_model');
				
				if (!empty($_FILES))
				{
					foreach ($_FILES as $k => $v)
					{
						if (!empty($v['name']))
						{
							$image_data = $this->uploads_model->_upload_photo('banners', '0' , $k);
						
							if ($image_data['success'])
							{
								$size = $k == 'userfile1' ? 'page_peel_ad_small_image' : 'page_peel_ad_large_image';
								
								$this->_update_banner_image('add', $id, $image_data['info']['file_name'], $size);
							}
							else
							{
								$this->session->set_flashdata('error', $data['msg']);
							}
						}
					}
					
					if (!empty($image_data['success']))
					{
						$this->session->set_flashdata('success', $this->lang->line('item_added_successfully'));
					}
				}	
	
				redirect(modules_url() . strtolower( __CLASS__) . '/edit/' . $id);
			}
		}		
		
		$programs = $this->programs_model->_get_all_programs();

		$data['programs'] = format_array($programs, 'program_id', 'program_name');

		load_admin_tpl('modules', 'tpl_adm_manage_affiliate_marketing_page_peel_ad', $data);
	}
	
	// ------------------------------------------------------------------------
	
	function delete()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		if ($this->aff->_delete_tool('page_peel_ad', (int)($this->uri->segment(4)), 'affiliate_page_peel_ads'))
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
	
	function generate_js_code()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$mdata = $this->page_peel_ads_model->_get_page_peel_ad_details((int)$this->uri->segment(4));
		
		$a = $this->page_peel_ads_model->_generate_page_peel_js($mdata);	
	
		echo $a;
	}
	
	// ------------------------------------------------------------------------
	
	function view_ad()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = $this->lang->line('view_page_peel_ad');
		
		$data['id'] = (int)$this->uri->segment(4);

		load_form('admin', 'tpl_adm_page_peel_ads_form_window', $data);
	}
	
	// ------------------------------------------------------------------------
	
	function edit()
	{

		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		$data['page_title'] = 'update_page_peel_ad';
		
		if ($this->_check_page_peel_ad() == false)
		{		
			if (empty($_POST))
			{
				$mdata = $this->page_peel_ads_model->_get_page_peel_ad_details((int)$this->uri->segment(4));
				
				if (!empty($mdata))
				{	
					foreach ($mdata as $key => $value)
					{
						$this->validation->$key = $value;
					}
					
					$data['page_peel_ad'] = $mdata;
				}
				else
				{
					redirect(admin_url());
				}
			
			}
			else
			{
				$data['error'] =  $this->validation->error_string;
			}
				
		}
		else
		{	
			if (empty($data['error']))
			{
				$id = $this->page_peel_ads_model->_edit_page_peel_ad((int)$this->uri->segment(4));	
				
				$this->load->model('uploads_model');
				
				if (!empty($_FILES))
				{
					foreach ($_FILES as $k => $v)
					{
						if (!empty($v['name']))
						{
							$image_data = $this->uploads_model->_upload_photo('banners', '0' , $k);
						
							if ($image_data['success'])
							{
								$size = $k == 'userfile1' ? 'page_peel_ad_small_image' : 'page_peel_ad_large_image';
								
								$this->_update_banner_image('add', $id, $image_data['info']['file_name'], $size);
							}
							else
							{
								$this->session->set_flashdata('error', $data['msg']);
							}
						}
					}
	
				}	

				$this->session->set_flashdata('success', $this->lang->line('update_page_peel_ad_success'));
			
				redirect(modules_url() . 'module_affiliate_marketing_page_peel_ads/edit/' .  (int)$this->uri->segment(4));
			}
		}	
		
		$programs = $this->programs_model->_get_all_programs();
		
		$data['programs'] = format_array($programs, 'program_id', 'program_name');

		load_admin_tpl('modules', 'tpl_adm_manage_affiliate_marketing_page_peel_ad', $data);
	}
	
	// ------------------------------------------------------------------------
	
	function update()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = 'page_peel_ad_options';
		
		if ($this->_check_page_peel_ad_options() == false)
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
			$this->aff->_change_sort_order('affiliate_page_peel_ads');

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
		
		if ($this->db_validation_model->_change_status_field('affiliate_page_peel_ads', 'id', (int)$this->uri->segment(4), 'status'))
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
	
	function _check_page_peel_ad_options()
	{
		return false;	
	}
	
	// ------------------------------------------------------------------------
	
	function _update_banner_image($type = '', $id = '', $file_name = '', $image = 'page_peel_ad_small_image')
	{
		
		if ($type == 'update')
		{
			$this->db->where('id', $id);
			
			$query = $this->db->get('affiliate_page_peel_ads');
			
			$row = $query->result_array();
			
			if ($row[0][$image] != $file_name)
			{				
				@unlink('./images/' . $this->config->item('images_banners_dir') . '/' . $row[0][$image]);
		
			}	
		}
		
		$sdata['config'] = array(
							'table'	=>	'affiliate_page_peel_ads',
							'key'	=>	'id',
							'value' 	=>	$id,
							);
		
		$sdata['fields'] = array ($image => $file_name);			
		
		if ($this->uploads_model->_update_image_db($sdata))
		{
			return true;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_page_peel_ad()
	{
		$rules['status'] = 'trim|required|integer';
		$rules['page_peel_ad_name'] = 'trim|required|min_length[5]|max_length[50]';
		$rules['page_peel_ad_small_image'] = 'trim';
		$rules['page_peel_ad_large_image'] = 'trim';
		$rules['enable_redirect'] = 'trim|integer';
		$rules['program_id'] = 'trim|required|integer';
		
		if ($this->input->post('enable_redirect') == 1)
		{
			$rules['redirect_custom_url'] = 'trim|required|prep_url';
		}
		
		$rules['notes'] = 'trim';
		
		$this->validation->set_rules($rules);

		$fields['status'] = $this->lang->line('status');
		$fields['page_peel_ad_name'] = $this->lang->line('page_peel_ad_name');
		$fields['page_peel_ad_small_image'] = $this->lang->line('page_peel_ad_small_image');
		$fields['page_peel_ad_large_image'] = $this->lang->line('page_peel_ad_large_image');
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
				
				$fields[$k] = $this->lang->line('page_peel_ad_id'). ' ' .end($opt) ;
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