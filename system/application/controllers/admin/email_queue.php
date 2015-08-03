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
| FILENAME - email_queue.php
| -------------------------------------------------------------------------     
| 
*/

class Email_Queue extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->config->set_item('menu', 'e');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . 'email_queue/view_email_queue');
	}
	
	// ------------------------------------------------------------------------
	
	function view_email_queue()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['email_queue'] = $this->emailing_model->_get_email_queue($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);

		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'email_queue', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column']);
		
		load_admin_tpl('admin', 'tpl_adm_manage_email_queue', $data);	
	}
	
	// ------------------------------------------------------------------------
	
	function send_emails()
	{		
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$msg = $this->emailing_model->_flush_queue('admin', 'now');
		
		echo '<div class="success">' . $msg . '</div>';
	} 
	
	// ------------------------------------------------------------------------
	
	function delete_queue()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		$this->db->query('TRUNCATE  `' . $this->db->dbprefix('email_queue') . '`');
		
		redirect(admin_url() . 'email_queue/view_email_queue');
	}
	
	// ------------------------------------------------------------------------
	
	function flush_queue()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$row = $this->emailing_model->_flush_queue('admin', 'now');
		
		$data['percent_done'] = '100';
		 
		if(!empty($row['total_left']))
		{
			$data['percent_done'] = $row['total_sent'] * 100 / ($row['total_left'] + $this->config->item('sts_email_limit_mass_mailing'));
			$data['msg'] = $this->lang->line('please_wait');
			$data['do_send'] = true;	 
		}
		else
		{
			$data['msg'] = $this->lang->line('emails_sent_successfully');	
		}

		load_admin_tpl('admin', 'tpl_adm_flush_email_queue', $data);
	}
	
	// ------------------------------------------------------------------------
	
	function update_email_queue()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		if ($this->input->post('email') AND count($this->input->post('email')) > 0)
		{
			if ($this->emailing_model->_update_email_queue($this->input->post('email')))
			{
				$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			}
		}
			
		redirect(admin_url() . 'email_queue/view_email_queue');
	}
	
	// ------------------------------------------------------------------------
	
	function delete_email()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->emailing_model->_delete_queue((int)$this->uri->segment(4,0)))
		{ 
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));	
		}
		
		redirect(admin_url() . 'email_queue/view_email_queue/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
	}
	
	// ------------------------------------------------------------------------
}
?>