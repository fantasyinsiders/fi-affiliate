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
| FILENAME - email templates.php
| -------------------------------------------------------------------------     
| 
*/

 
class Email_Templates extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->config->set_item('menu', 'e');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . 'email_templates/view_email_templates');
	}
	
	// ------------------------------------------------------------------------
	
	function view_email_templates()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$program_name = $this->programs_model->_get_program_name(1);

		$data['templates'] = $this->emailing_model->_get_program_email_templates('1');
			
		load_admin_tpl('admin', 'tpl_adm_manage_email_templates', $data);
	}
	
	// ------------------------------------------------------------------------
	
	function update_email_template()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$this->validation->id = $this->uri->segment(4);
		
		if ($this->_check_email_template() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
			
			if (empty($_POST))
			{
				$m = $this->emailing_model->_get_email_template_details((int)$this->uri->segment(4));
				
				if (!empty($m))
				{	
					foreach ($m as $k => $v)
					{
						$this->validation->$k = $v;
					}
					
					
				}
				else
				{
					redirect(admin_url());
					exit();
				}				
			}
			
			$data['program_name'] = $this->programs_model->_get_program_name($this->validation->program_id);

			$options = array(	'instance'	=> 'oEdit1',
								'type' => 'email',
								'content' => $this->validation->email_template_body_html,
								'height'	=> '400',
								'width'	=> '100%',
								'editor_type'	=> 'basic',
								'textarea'	=>	'email_template_body_html',
								'tags'	=> true,
							);
			$check_editor = _initialize_html_editor($options);
		
			$data['editor'] = $check_editor['editor'];
			$data['editor_path'] = $check_editor['editor_path']; 
					
			load_admin_tpl('admin', 'tpl_adm_manage_email_template', $data);		
		}
		else
		{	
			if ($this->emailing_model->_update_email_template((int)$this->uri->segment(4)))
			{				
				$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			}
			
			redirect($this->uri->uri_string());		
		}		
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_email_template()
	{		
		$rules['email_template_html'] = 'trim|required|min_length[2]|max_length[50]';
		$rules['email_template_subject'] = 'trim|required|min_length[2]|max_length[50]';
		$rules['email_template_type'] = 'trim';
		
		if ($this->input->post('email_template_type') == 'custom')
		{
			$rules['email_template_name'] = 'trim|required|min_length[5]|max_length[50]';
		}
		
		if ($this->input->post('email_template_html') == 'text')
		{
			$rules['email_template_body_text'] = 'trim|required|min_length[25]';
		}
		
		if ($this->input->post('email_template_html') == 'html')
		{
			$rules['email_template_body_html'] = 'trim|required|min_length[25]';
		}
		
		$rules['email_template_from_name'] = 'trim|required|min_length[3]|max_length[50]';
		$rules['email_template_from_email'] = 'trim|required|valid_email';
		$rules['email_template_cc'] = 'trim|valid_emails';
		$rules['email_template_bcc'] = 'trim|valid_emails';
		
		$this->validation->set_rules($rules);

		$fields['email_template_html'] = $this->lang->line('email_template_type');
		$fields['email_template_subject'] = $this->lang->line('subject');
		$fields['email_template_type'] = $this->lang->line('email_template_type');
		$fields['email_template_name'] = $this->lang->line('email_template_name');
		$fields['email_template_body_text'] = $this->lang->line('text_template');
		$fields['email_template_body_html'] = $this->lang->line('html_template');
		$fields['email_template_from_name'] = $this->lang->line('from_name');
		$fields['email_template_from_email'] = $this->lang->line('from_email');
		$fields['email_template_cc'] = $this->lang->line('cc');
		$fields['email_template_bcc'] = $this->lang->line('bcc');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
		
	}
}
?>