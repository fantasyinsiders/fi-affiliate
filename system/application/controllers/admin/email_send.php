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
| FILENAME - email_send.php
| -------------------------------------------------------------------------     
| 
*/

class Email_Send extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('mailing_lists_model');
		
		$this->config->set_item('menu', 'e');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url());
	}
	
	// ------------------------------------------------------------------------
	
	function member()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		$data['page_title'] = $this->lang->line('send_email');
		
		$data['module_name'] = 'email_send-member';
		
		if ($this->_check_email('member') == false)
		{		
			if (empty($_POST))
			{
				if ($this->uri->segment(4) > 0)
				{
					$q = $this->db_validation_model->_get_details('members', 'primary_email', 'member_id', $this->uri->segment(4));
					
					$this->validation->recipient_email = $q[0]['primary_email'];
				}
				
				if ($this->uri->segment(5) == 'template')
				{
					$template = $this->db_validation_model->_get_details('email_templates', '*', 'id', (int)$this->uri->segment(6));
					
					$this->validation->sender_name = $template[0]['email_template_from_name'];
					$this->validation->sender_email = $template[0]['email_template_from_email'];
					$this->validation->subject = $template[0]['email_template_subject'];
					$this->validation->text_body = $template[0]['email_template_body_text'];
					$this->validation->html_body = $template[0]['email_template_body_html'];
					$this->validation->email_type = $template[0]['email_template_html'];
				}
				
				$fdate = explode(':', $this->config->item('sts_admin_date_format'));
				$data['curdate'] = $fdate[1];
				$this->validation->send_date = date($fdate[1], _generate_timestamp()); 
				
				
				
				$templates = $this->mailing_lists_model->_get_custom_templates();
					
				$data['template_options'] = array(0);
				$key = array(0);
				$value = array($this->lang->line('load_custom_template'));
				
				if (!empty($templates))
				{
					foreach ($templates as $k => $v)
					{
						if ($k > 0)
						{
							$new = admin_url(). '/email_send/member/' . (int)$this->uri->segment(4) . '/template/' . $k;
							array_push($key, $new);
							array_push($value, $v);
						}	
					}	
					
					$data['template_options'] = combine_array($key, $value);
				}	
				else
				{
					$data['template_options'] = array('0' => $this->lang->line('load_custom_template'));
				}
			
			
			$options = array(	'instance'	=> 'oEdit1',
									'type' => 'email',
									'content' => $this->validation->html_body,
									'height'	=> '400',
									'width'	=> '100%',
									'editor_type'	=> 'description',
									'textarea'	=>	'html_body',
									'tags'	=> true,
								);
			$check_editor = _initialize_html_editor($options);
		
			$data['editor'] = $check_editor['editor'];
			
			$data['editor_path'] = $check_editor['editor_path'];
			
			load_admin_tpl('admin', 'tpl_adm_email_send', $data);
			
			}
			else
			{
				echo '<div class="alert alert-danger animated shake capitalize alert-msg hover-msg">' . $this->validation->error_string. '</div>';
			}
			
		}
		else
		{	
			$sdata = $this->db_validation_model->_clean_data($_POST);

			$member = $this->db_validation_model->_get_details('members', '*', 'member_id', (int)$this->uri->segment(4));
			
			$data = $this->emailing_model->_format_email($sdata, 'member', $member[0]);
			
			if (isset($_POST['queue-email']))
			{
				if ($this->emailing_model->_queue_email('admin', $data))
				{
					echo '<div class="alert alert-success animated bounce capitalize alert-msg hover-msg">' . $this->lang->line('email_queued_successfully') . '</div>';
					exit();
				}
			}
			else
			{
				if ($this->emailing_model->_send_email('admin', $data))
				{
					echo '<div class="alert alert-success animated bounce capitalize alert-msg hover-msg">' . $this->lang->line('email_sent_successfully') . '</div>';
					exit();
				}
			}
		}
	}
	
	// ------------------------------------------------------------------------
	
	function send_mass_email()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = $this->lang->line('send_email');
		
		if ($this->_check_mass_email() == false)
		{		
			show_error($this->validation->error_string);
		}
		else
		{
			$data['submit_url'] = admin_url().'email_send/send_mass_email/' . $this->input->post('offset');
			
			$sdata = $this->db_validation_model->_clean_data($_POST);
			
			$sdata['html_body'] = html_entity_decode($_POST['html_body']);
			
			$row = $this->emailing_model->_get_list_users($sdata['list_ids'], $this->config->item('sts_email_limit_mass_mailing'), $this->input->post('offset'));
			
			$data['offset'] = !empty($row['offset']) ? $row['offset'] : '0';
			
			$data['percent_done'] = '100';
			
			if(!empty($row['total']))
			{
				$data['percent_done'] = $data['offset'] * 100 / $row['total'];
			}
			
			if (!empty($row['users']))
			{
				if ($this->emailing_model->_queue_mass_email('admin', $sdata, $row['users']))
				{
					$data['msg'] =  $this->lang->line('please_wait');
					$data['do_send'] = true;	 
				}
			}
			else
			{
				$data['msg'] = $this->lang->line('email_queued_successfully');
			}

			load_admin_tpl('admin', 'tpl_adm_queue_email', $data);
		}
	}
	
	// ------------------------------------------------------------------------
	
	function mailing_list()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['module_name'] = 'email_send-mailing_list';
		
		if ($this->uri->segment(4))
		{
			$this->load->library('convert');
			
			$data['list_ids'] = base64_decode($this->convert->HexToAscii($this->uri->segment(4))); 
		}
		if (empty($data['list_ids']))
		{
			show_error($this->lang->line('no_lists_selected'));
		}
		
		$lists = explode(',', $data['list_ids']);	
				
		$data['lists'] = $this->emailing_model->_get_mailing_lists($lists);
		
		if ($this->_check_mass_email() == false)
		{
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string;
			}

			if (empty($_POST))
			{
				if (!empty($_POST['email_template']))
				{
					$template = $this->db_validation_model->_get_details('email_templates', '*', 'id', (int)$this->input->post('email_template'));
					
					$this->validation->sender_name = $template[0]['email_template_from_name'];
					$this->validation->sender_email = $template[0]['email_template_from_email'];
					$this->validation->subject = $template[0]['email_template_subject'];
					$this->validation->text_body = $template[0]['email_template_body_text'];
					$this->validation->html_body = $template[0]['email_template_body_html'];
					$this->validation->email_type = $template[0]['email_template_html'];
					$this->validation->cc = $template[0]['email_template_cc'];
					$this->validation->bcc = $template[0]['email_template_bcc'];
				}
				else
				{
				
					$this->validation->sender_name = $data['sts_site_name'];
					$this->validation->sender_email = $data['sts_site_email'];
					$this->validation->subject = '';
					$this->validation->text_body = '';
					$this->validation->html_body = '';
					$this->validation->email_type = '';
					$this->validation->cc = '';
					$this->validation->bcc = '';
				}
			}
			
			$this->validation->send_date = _format_date(_generate_timestamp(), $data['format_date2']);
		}
		else
		{
			if (!empty($_POST['send_draft']))
			{
				$sdata = $this->db_validation_model->_clean_data($_POST);
			
				$sdata['primary_email'] = $this->input->post('send_draft');
				$sdata['fname'] = $this->input->post('send_draft');
				$sdata['email_template_from_name'] = empty($data['sender_name']) ? $this->config->item('sts_site_name') : $sdata['sender_name'];
				$sdata['email_template_from_email'] = empty($data['sender_email']) ? $this->config->item('sts_site_email') : $sdata['sender_email'];
				$sdata['email_template_cc'] = !empty($data['cc']) ? $sdata['cc'] : '';
				$sdata['email_template_bcc'] = !empty($data['bcc']) ? $sdata['bcc'] : '';
				$sdata['email_template_subject'] = $sdata['subject'];
				$sdata['email_template_html'] = $sdata['email_type'];
				$sdata['email_template_body_html'] = $sdata['html_body'];
				$sdata['email_template_body_text'] = $sdata['text_body'];
				
				$this->emailing_model->_send_email('admin', $sdata);
				
				$data['success'] = $this->lang->line('email_sent_successfully');	
			}
			else
			{
				$data['send_mass_mail'] = true;	
			}
		}
		
		$options = array(	'instance'	=> 'oEdit1',
							'type' => 'email',
							'content' => $this->validation->html_body,
							'height'	=> '400',
							'width'	=> '100%',
							'editor_type'	=> 'description',
							'textarea'	=>	'html_body',
							'tags'	=> true,
							);
		$check_editor = _initialize_html_editor($options);
	
		$data['editor'] = $check_editor['editor'];
	
		$data['editor_path'] = $check_editor['editor_path'];
			
		load_admin_tpl('admin', 'tpl_adm_manage_mass_email_send', $data);
	}
	
	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/

	// ------------------------------------------------------------------------	
	
	function _check_mass_email()
	{
		$rules['list_ids'] = 'trim|required';
		$rules['subject'] = 'trim|required';
		
		if ($this->input->post('email_type') == 'text')
		{
			$rules['text_body'] = 'trim|required|min_length[25]';
		}
		else
		{
			$rules['html_body'] = 'trim|required|min_length[25]';
		}
		
		$rules['cc'] = 'trim|valid_emails';
		$rules['bcc'] = 'trim|valid_emails';
		$rules['email_type'] = 'trim|required';
		$rules['send_date'] = 'trim';
		
		$rules['sender_name'] = 'trim|required';
		$rules['sender_email'] = 'trim|required|valid_email';
		
		$this->validation->set_rules($rules);
		
		$fields['list_ids'] = $this->lang->line('list_ids');
		$fields['subject'] = $this->lang->line('subject');
		$fields['text_body'] = $this->lang->line('text_body');
		$fields['html_body'] = $this->lang->line('html_body');
		
		$fields['email_type'] = $this->lang->line('email_type');
		$fields['recipient_email'] = $this->lang->line('from_email');
		$fields['recipient_name'] = $this->lang->line('from_name');
		
		$fields['cc'] = $this->lang->line('cc');
		$fields['bcc'] = $this->lang->line('bcc');
		$fields['email_type'] = $this->lang->line('email_type');
		$fields['send_date'] = $this->lang->line('send_date');
		$fields['sender_name'] = $this->lang->line('from_name');
		$fields['sender_email'] = $this->lang->line('from_email');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_email($type = '')
	{
		$rules['subject'] = 'trim|required|min_length[5]|max_length[255]';
		$rules['recipient_email'] = 'trim|required|valid_emails';
		$rules['html_body'] = 'trim|required|min_length[20]';
		$rules['send_date'] = 'trim';
	
		$this->validation->set_rules($rules);

		$fields['subject'] = $this->lang->line('subject');
		$fields['text_body'] = $this->lang->line('content_text');
		$fields['html_body'] = $this->lang->line('html_body');
		$fields['recipient_email'] = $this->lang->line('email');
		$fields['send_date'] = $this->lang->line('send_date');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
		
	}
}
?>