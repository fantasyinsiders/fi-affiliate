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
| FILENAME - email_archive.php
| -------------------------------------------------------------------------     
| 
*/

class Email_Archive extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->config->set_item('menu', 'e');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . 'email_archive/view_email_archive');
	}
	
	// ------------------------------------------------------------------------
	
	function view_email_archive()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		$data['email_archive'] = $this->emailing_model->_get_email_archive($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'email_archive', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column']);
		
		load_admin_tpl('admin', 'tpl_adm_manage_email_archive', $data);	
	}
	
	
	// ------------------------------------------------------------------------
	
	function update_email_archive()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->input->post('email') AND count($this->input->post('email')) > 0)
		{
			if ($this->emailing_model->_update_email_archive($this->input->post('email')))
			{
				$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			}
		}
		
		redirect(admin_url() . 'email_archive/view_email_archive');
	}
	
	// ------------------------------------------------------------------------
	
	function delete_archive()
	{		 
		//set data array
		$data = $this->security_model->_load_config('admin');
		
		$this->db->query('TRUNCATE  `' . $this->db->dbprefix('email_archive') . '`');
		
		redirect(ADMIN_ROUTE . '/email_archive/view_email_archive/');
	}
	
	// ------------------------------------------------------------------------
	
	function delete_email()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->emailing_model->_delete_archive((int)$this->uri->segment(4,0)))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));	
		}
		
		redirect(admin_url() . 'email_archive/view_email_archive/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
	}
	
	// ------------------------------------------------------------------------
}
?>