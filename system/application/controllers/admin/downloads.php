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
| FILENAME - downloads.php
| -------------------------------------------------------------------------     
| 
*/

class Downloads extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('downloads_model');
		
		$this->load->model('groups_model');		
		
		$this->config->set_item('menu', 'w');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . 'downloads/view_downloads');
	}
	
	// ------------------------------------------------------------------------
	
	function add_download()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$this->load->model('uploads_model');
		
		if ($this->_check_download() == false)
		{		
			$programs = $this->programs_model->_get_all_programs();
			
			$data['programs'] = format_array($programs, 'program_id', 'program_name');
			
			$aff_groups = $this->groups_model->_get_all_affiliate_groups();
			
			$data['aff_groups'] = format_array($aff_groups, 'group_id', 'aff_group_name', true);
		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
			
			load_admin_tpl('admin', 'tpl_adm_manage_download', $data);
		}
		else
		{	
			$id = $this->downloads_model->_add_download($_POST);	
			
			if (!empty($_FILES))
			{
				$i = 1;
				
				foreach ($_FILES as $k => $v)
				{
					if (!empty($v['name']))
					{
						$max_size = str_replace('M', '', $this->config->item('sts_site_upload_max_filesize')) * 1024;
						
						$config = array(	'upload_path' => $this->config->item('sts_site_download_file_path'),
											'allowed_types' => $this->config->item('sts_site_download_allowed_file_types'),
											'max_size'	=> $max_size,
											'encrypt_name' => false,
											'remove_spaces' => true,
										);
						
						$data = $this->uploads_model->_upload_file($k, $config);
						
						if (!empty($data['success']))
						{
							$sdata['download_location_' . $i] = $data['info']['full_path'];
							
							$this->db->where('id', $id);
					
							$this->db->update('downloads', $sdata);
						}		
					}
					
					$i++;
				}
			}				
			
			$msg = !empty($data['success']) ? $this->lang->line('item_added_successfully') : $data['msg'];

			$this->session->set_flashdata('success', $msg);
						
			redirect(admin_url() . 'downloads/update_download/' . $id);	
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function delete_download()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->downloads_model->_delete_download((int)($this->uri->segment(4))))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . 'downloads/view_downloads/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
			
			redirect(admin_url() . 'downloads/view_downloads');
		}
	}
	
	// ------------------------------------------------------------------------
	
	function view_downloads()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['downloads'] = $this->downloads_model->_get_downloads($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'downloads', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column']);
		
		load_admin_tpl('admin', 'tpl_adm_manage_downloads', $data);
	}
	
	// ------------------------------------------------------------------------
	
	function update_status()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		if ($this->db_validation_model->_change_status_field('downloads', 'id', (int)$this->uri->segment(4), 'status'))
		{
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . 'downloads/view_downloads/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
			
			redirect(admin_url() . 'downloads/view_downloads');
		}	
	}
	
	// ------------------------------------------------------------------------
	
	
	function update_download()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$this->validation->download_id = $this->uri->segment(4);
		
		$this->load->model('uploads_model');
			
		if ($this->_check_download() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
			
			$programs = $this->programs_model->_get_all_programs();
			
			$data['programs'] = format_array($programs, 'program_id', 'program_name');
			
			$aff_groups = $this->groups_model->_get_all_affiliate_groups();
			
			$data['aff_groups'] = format_array($aff_groups, 'group_id', 'aff_group_name', true);
			
			if (empty($_POST))
			{
				$m = $this->downloads_model->_get_download_details((int)$this->uri->segment(4));
				
				if (!empty($m))
				{	
					foreach ($m as $k => $v)
					{
						$this->validation->$k = $v;
					}
				}
				else
				{
					redirect(admin_url() . 'downloads/view_downloads');
					exit();
				}
							
			}

			load_admin_tpl('admin', 'tpl_adm_manage_download', $data);
		}
		else
		{		
			$data = $this->downloads_model->_update_download((int)$this->uri->segment(4), $_POST);	
			
			if (!empty($_FILES))
			{				
				$i = 1;
			
				foreach ($_FILES as $k => $v)
				{
					if (!empty($v['name']))
					{
						$max_size = str_replace('M', '', $this->config->item('sts_site_upload_max_filesize')) * 1024;
						
						$config = array(	'upload_path' => $this->config->item('sts_site_download_file_path'),
											'allowed_types' => $this->config->item('sts_site_download_allowed_file_types'),
											'max_size'	=> $max_size,
											'encrypt_name' => false,
											'remove_spaces' => true,
										);
						
						$data = $this->uploads_model->_upload_file($k, $config);
						
						if (!empty($data['success']))
						{
							$sdata['download_location_' . $i] = $data['info']['full_path'];
							
							$this->db->where('id', (int)$this->uri->segment(4));
							
							$this->db->update('downloads', $sdata);		
						}
		
					}
					
					$i++;
				}
			}				
			
			
			$msg = !empty($data['success']) ?  $this->lang->line('system_updated_successfully') : $data['msg'];
			
			$this->session->set_flashdata('success', $msg);
					
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
	
	function _check_download()
	{	
		$rules['download_name'] = 'trim|required|min_length[2]|max_length[255]';
		$rules['program_id'] = 'trim|required|integer';
		$rules['group_id'] = 'trim|required|integer';
		$rules['description'] = 'trim|strip_tags';
		$rules['status'] = 'trim|integer';
		
		for ($i=1; $i<=10; $i++)
		{
			$rules['download_location' . $i] = 'trim|max_length[255]';	
		}
		
		$this->validation->set_rules($rules);

		$fields['download_name'] = $this->lang->line('download_name');
		$fields['program_id'] = $this->lang->line('program_id');
		$fields['group_id'] = $this->lang->line('group_id');
		$fields['description'] = $this->lang->line('description');
		$fields['status'] = $this->lang->line('status');
		
		for ($i=1; $i<=10; $i++)
		{
			$fields['download_location_' . $i] = $this->lang->line('download_location_' . $i);
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