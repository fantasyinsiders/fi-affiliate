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
| FILENAME - replication.php
| -------------------------------------------------------------------------     |
*/

class Replication extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->helper('directory');
		
		$this->config->set_item('menu', 'w');
	}
		
	// ------------------------------------------------------------------------
	
	function download_file()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		$this->load->helper('download');
		$this->load->helper('file');
		
		$file = APPPATH . 'views/main/replication/' . $this->uri->segment(4);
		
		$data = read_file($file);
	
		$name = basename($file);
		
		force_download($name, $data); 	
	}
	
	// ------------------------------------------------------------------------
	
	function delete_file()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if (@unlink(APPPATH . 'views/main/replication/' . $this->uri->segment(4)))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));
		}
		else
		{
			$this->session->set_flashdata('error', $this->lang->line('could_not_delete_file'));
		}
		
		redirect(admin_url() . 'replication');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = 'replication';
		
		$map = directory_map(APPPATH . '/views/main/replication', TRUE);
		
		$data['files'] = array();
		
		foreach ($map as $v)
		{
			$ext = end(explode('.', $v));
		
			if ($v != 'index.php' && $ext == 'php')
			{				
				array_push($data['files'], $v);	
			}
		}
		
		load_admin_tpl('admin', 'tpl_adm_manage_replication', $data);	
	}
	
	// ------------------------------------------------------------------------
	
	function upload_file()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if (!empty($_FILES) && $_FILES['userfile']['error'] != 4)
		{
			$sconfig['upload_path'] = APPPATH . '/views/main/replication/';
			$sconfig['allowed_types'] = 'php';
			$sconfig['max_size']	= $this->config->item('sts_support_max_upload_size');
			$sconfig['encrypt_name'] = false;
			$sconfig['remove_spaces'] = true;
			
			$this->load->model('uploads_model');
			
			$sdata = $this->uploads_model->_upload_file('userfile', $sconfig);
			
			if ($sdata['success'])
			{				
				$this->session->set_flashdata('success', $this->lang->line('file_uploaded_successfully'));
			}
			else
			{
				$this->session->set_flashdata('error', $this->lang->line('could_not_upload_file'));	
			}
		}
		
		redirect(admin_url() . 'replication');	
	}
	
	// ------------------------------------------------------------------------	
	
}
?>