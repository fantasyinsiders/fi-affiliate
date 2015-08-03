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
| FILENAME - admin_login.php
| -------------------------------------------------------------------------     
|
*/

class Admin_Login extends Public_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->library('user_agent');

		$this->lang->load('adm_main', $this->config->item('sts_admin_default_language'));
		
		$this->config->set_item('jrox_module_type', 'admin_login');	
		
		if (is_writable(APPPATH . 'config/config.php')) 
		{	
			@chmod(APPPATH . 'config/config.php', 0644);
		}
		
		if (is_writable(APPPATH . 'config/database.php')) 
		{	
			@chmod(APPPATH . 'config/database.php', 0444);
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function index()
	{
		$this->security_model->_check_ip_restriction();
		
		$this->security_model->_check_ssl_page('admin', __CLASS__, __FUNCTION__);
		
		if ($this->config->item('sts_site_ssl_admin_area') == 1)
		{
			$this->config->set_item('base_url', $this->config->item('base_SSL_url'));
		}

		$this->config->config['page_title'] = $this->lang->line('login');
		
		$data = $this->config->config;
			
		$data['check_browser'] = $this->security_model->_check_browser();
		
		
		if ($this->uri->segment(3) == 'timer_expired')
		{
			$data['timer_expired'] = $this->lang->line('timer_expired');
		}
		
		if ($this->uri->segment(4))
		{
			$data['page_redirect'] = $this->uri->segment(4);	
		}
		
		$this->db->where('status', '1');
		$query = $this->db->get('languages');
		
		if ($query->num_rows() > 1)
		{
			$lang = $query->result_array();
			
			$data['languages'] = format_array($lang, 'language_id', 'name');
		}
		
		load_admin_tpl('admin', 'tpl_adm_login', $data, false, false);
		
	}
	
	// ------------------------------------------------------------------------	
	
	function reset_confirmation()
	{
		$this->security_model->_check_ip_restriction();
		
		$this->security_model->_check_ssl_page('admin', __CLASS__, __FUNCTION__);
		
		if ($this->config->item('sts_site_ssl_admin_area') == 1)
		{
			$this->config->set_item('base_url', $this->config->item('base_SSL_url'));
		}
		
		$this->config->config['page_title'] = $this->lang->line('reset_password');
		
		$data = $this->config->config;
		
		if ($this->uri->segment(3))
		{
			$data['confirm_id'] = $this->uri->segment(3);
			
			if ($this->login_model->_check_reset_confirmation($data['confirm_id']) == true)
			{
				if ($this->_reset_pass_confirm() == false)
				{
					if (!empty($_POST))
					{
						$data['error'] = $this->validation->error_string;
					}
				}
				else
				{
					$this->login_model->_update_admin_pass($_POST);
					
					$data['success'] = $this->lang->line('password_reset_successfully');	
				}
			}
			else
			{
				redirect();		
			}
			
			load_admin_tpl('admin', 'tpl_adm_reset_password_confirmation', $data, false, false);	
		}
		else
		{
			redirect();	
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function reset_password()
	{		
		$this->security_model->_check_ip_restriction();
		
		$this->security_model->_check_ssl_page('admin', __CLASS__, __FUNCTION__);
		
		if ($this->config->item('sts_site_ssl_admin_area') == 1)
		{
			$this->config->set_item('base_url', $this->config->item('base_SSL_url'));
		}
		
		$this->config->config['page_title'] = $this->lang->line('reset_password');

		$rules['email'] = "trim|required|valid_email|callback__check_reset_password";
		
		$this->validation->set_rules($rules);

		$fields['email'] = $this->lang->line('email');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)
		{
			$this->security_model->_auto_block_ip('block', $this->input->ip_address());	
			
			load_admin_tpl('admin', 'tpl_adm_reset_password', $this->config->config, false, false);
		}
		else
		{
			$this->session->set_flashdata('success', $this->lang->line('reset_password_sent'));
			
			$this->security_model->_auto_block_ip('remove', $this->input->ip_address());	
			
			redirect('admin_login/reset_password');
		}		
	}
	
	// ------------------------------------------------------------------------	
	
	function reset_access()
	{

		$this->config->config['page_title'] = $this->lang->line('reset_access');
			
		if ($this->uri->segment(1) == 'vac')
		{
			$v = xss_clean($this->uri->segment(2));
			
			if ($this->login_model->_verify_reset_access($v) == true)
			{
				$this->security_model->_auto_block_ip('remove', $this->input->ip_address());	
				
				redirect( ADMIN_LOGIN_ROUTE );	
			}
		}
		else
		{
			if (!empty($_POST))
			{
				$email = $this->input->post('email', true);
				$row = $this->login_model->_check_reset_access(trim(strtolower($email)));
			
				if (!empty($row))
				{
					$id = strtoupper(md5(time() . $row['apassword']));
					$this->db->where('admin_id', (int)$row['admin_id']);
					$this->db->update('admin_users', array('confirm_id' => $id));
					
					$body = "Reset Access Email\n\n";
					$body .= "Click To Reset Access To " . $this->config->item('sts_site_name') . "\n\n";
					$body .= base_url() . 'vac/' . $id;
					
					//format emails
					$sdata['email_template_html'] = 'text';
					$sdata['email_template_from_email'] =  $this->config->item('sts_sec_admin_failed_login_email');
					$sdata['email_template_from_name'] = $this->config->item('sts_site_name');
					$sdata['email_template_subject'] =$this->lang->line('reset_access');
					$sdata['email_template_body_text'] = $body;
					$sdata['primary_email'] = $row['primary_email'];
					$sdata['fname'] = $row['fname'];
				
					if ($this->emailing_model->_send_email('admin', $sdata))
					{
						//set flash data
						$this->session->set_flashdata('success', $this->lang->line('reset_access_sent'));
						
						//redirect to admin reset password
						$this->security_model->_auto_block_ip('remove', $this->input->ip_address());	
						redirect( ADMIN_LOGIN_ROUTE .'/reset_access');
					}
					else
					{
						show_error($this->lang->line('could_not_send_email'));
					}
				}	
				else	
				{
					//send to reset password form
					$this->security_model->_auto_block_ip('block', $this->input->ip_address());	
					show_error($this->lang->line('invalid_email'));
				}	
			}
		}
		
		load_admin_tpl('admin', 'tpl_adm_reset_access', $this->config->config, false, false);
	}
	
	// ------------------------------------------------------------------------	
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	
	// ------------------------------------------------------------------------	
	
	function _check_reset_password($email)
	{
		$this->validation->set_message('_check_reset_password', $this->lang->line('invalid_email_address'));
		
		$row = $this->login_model->_check_reset('admin_users', $email);

		if (!$row)
		{
			return false;
		}
		else 
		{
			$this->load->model('emailing_model');
		
			if ($this->emailing_model->_send_template_email('admin', $row, 'admin_reset_password_template'))
			{
				log_message('info', 'Admin Password Email Sent to ' . $email);
			
				return true;
			}
			else 
			{
				log_message('error', 'Could not send admin email to ' . $email . '. Check email settings.');
				
				show_error($this->lang->line('could_not_send_email') . '. ' . $this->lang->line('check_email_settings'));
				
				return false;
				
			}
		}
	}

	// ------------------------------------------------------------------------	
	
	function _reset_pass_confirm()
	{
		$rules['cpass'] = 'trim|required|min_length[6]|max_length[20]|matches[cpassconf]';
		$rules['cpassconf'] = 'trim|required';
		
		$this->validation->set_rules($rules);

		$fields['cpass'] = $this->lang->line('password');
		$fields['cpassconf'] = $this->lang->line('confirm_password');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;	
	}
}

?>