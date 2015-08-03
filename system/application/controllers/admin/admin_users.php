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
| FILENAME - admin_users.php
| -------------------------------------------------------------------------     
|
*/

class Admin_Users extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();
	
		$this->load->model('admins_model');
		
		$this->config->set_item('menu', 'm');
	}
	
	// ------------------------------------------------------------------------	
	
	function index()
	{ 
		redirect(admin_url() . strtolower( __CLASS__) . '/view_admins');
	}
	
	// ------------------------------------------------------------------------	
	
	function view_admins()
	{

		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		$data['admins'] = $this->admins_model->_get_admins($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'admin_users', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column']);

		load_admin_tpl('admin', 'tpl_adm_manage_admins', $data);
	}
	
	// ------------------------------------------------------------------------	
	
	function add_admin()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->_check_admin('add') == false)
		{		
			$this->validation->admin_id = '';
			
			$this->validation->admin_photo_url = base_url() . 'themes/admin/' . $data['sts_admin_layout_theme'] .'/img/avatar/5.jpg';
			
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}

			$this->validation->form_permissions = $this->_get_admin_permissions();

			load_admin_tpl('admin', 'tpl_adm_manage_admin', $data);

		}
		else
		{	
			$id = $this->admins_model->_add_admin($_POST);		
			
			$this->session->set_flashdata('success', $this->lang->line('admin_added_successfully'));
				
			redirect(admin_url() . strtolower( __CLASS__) . '/update_admin/' . $id);	
		}		
	}
	
	// ------------------------------------------------------------------------	
	
	function delete_admin()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		if ($this->uri->segment(4) > 1)
		{
			if ($this->admins_model->_delete_admin((int)($this->uri->segment(4))))
			{
				$this->session->set_flashdata('success', $this->lang->line('admin_deleted_successfully'));		
			}
		}
		else
		{
			$this->session->set_flashdata('success', $this->lang->line('cannot_delete_superadmin'));
		}

		if ($this->uri->segment(5) == 2)
		{
			redirect(admin_url() . '/admin_users/view_admins/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
			exit();
		}
		
		redirect(admin_url() . strtolower( __CLASS__) . '/view_admins');
	}
	
	// ------------------------------------------------------------------------	
	
	function delete_photo()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->admins_model->_delete_admin_photo($this->uri->segment(5)))
		{	
			$this->session->set_flashdata('success', $this->lang->line('image_deleted_successfully'));
		}
		else
		{
			$this->session->set_flashdata('error', $this->lang->line('could_not_delete_image'));
		}
		
		redirect(admin_url() . strtolower( __CLASS__) . '/update_admin/' . $this->uri->segment(5));	
	}
	
	// ------------------------------------------------------------------------	
	
	function update_admin()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$this->validation->admin_id = (int)$this->uri->segment(4);
		
		if ($this->_check_admin('update') == false)
		{
			$perm_array = array();
					
			if ((int)$this->uri->segment(4))
			{
				if (!empty($_POST))
				{
					$data['error'] = $this->validation->error_string ;
				}
				else
				{
					$admin_data = $this->admins_model->_get_admin_details((int)($this->uri->segment(4)));
					
					foreach ($admin_data as $key => $value)
					{
						$this->validation->$key = $value;
						
						$this->validation->apassword = '';
						
						if ($key == 'permissions')
						{
							if (!empty($value))
							{
								$perm_array = explode(',', $value);
							}
						}
					}	
				}
				
				$this->validation->form_permissions = $this->_get_admin_permissions($perm_array);
				
				load_admin_tpl('admin', 'tpl_adm_manage_admin', $data);
			}
		}
		else
		{
			$id = $this->admins_model->_update_admin((int)$this->uri->segment(4), $_POST);
			
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			redirect($this->uri->uri_string());		
		}	
	}
	
	// ------------------------------------------------------------------------	
	
	function update_admins()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->input->post('user') AND count($this->input->post('user')) > 0)
		{
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			$this->admins_model->_change_status($this->input->post('user'), $this->input->post('change-status'));
		}

		$url = referer_redirect('admin',$this->input->post('redirect'));
		
		redirect($url);
	}
	
	// ------------------------------------------------------------------------	
	
	function upload_photo()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$this->load->model('uploads_model');
		
		$data = $this->uploads_model->_upload_photo('admin', $this->config->item('sts_admin_image_resize'));
		
		if ($data['success'])
		{
			$admin_data = $this->admins_model->_get_admin_details((int)($this->input->post('admin_id')));
			
			$data['config'] = array(
								'table'	=>	'admin_users',
								'key'	=>	'admin_id',
								'value' 	=>	(int)$this->uri->segment(4),
								);
			
			$data['fields'] = array (
									'admin_photo'	=> 	$data['info']['file_name']
									);			
			
			$this->uploads_model->_update_image_db($data);
			
			if ($admin_data['admin_photo'])
			{
				@unlink('./images/' . $this->config->item('images_admins_dir') . '/' . $admin_data['admin_photo']);
			}
			
			$this->session->set_flashdata('success', $this->lang->line('image_uploaded_successfully'));	
		}
		else
		{
			$this->session->set_flashdata('error', $data['msg']);
		}	
		
		redirect(admin_url() . strtolower( __CLASS__) . '/update_admin/' . (int)($this->uri->segment(4)));
	}
	
	// ------------------------------------------------------------------------	
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/

	// ------------------------------------------------------------------------	
	
	function _check_admin($type = '')
	{
		$rules['fname'] = 'trim|required|min_length[2]|max_length[50]';
		$rules['lname'] = 'trim|required|min_length[2]|max_length[50]';
		
		if ($type == 'add')
		{
			$rules['username'] = 'trim|required|min_length[6]|max_length[20]|alpha_numeric|callback__check_username_add';
			$rules['primary_email'] = 'trim|required|valid_email|callback__check_email_add';
			$rules['apassword'] = 'trim|required|min_length[6]|max_length[30]|matches[passconf]';
			$rules['passconf'] = 'trim|required';
			
		}
		else
		{
			$rules['username'] = 'trim|required|min_length[6]|max_length[20]|alpha_numeric|callback__check_username_update';
			$rules['primary_email'] = 'trim|required|valid_email|callback__check_email_update';

			if ($this->input->post('apassword'))
			{
				$rules['apassword'] = 'trim|required|min_length[6]|max_length[20]|matches[passconf]';
				$rules['passconf'] = 'trim|required';
			}
		}

		$rules['show_assigned_tickets_only'] = 'trim';
		$rules['alert_affiliate_signup'] = 'trim|required|integer';
		$rules['alert_affiliate_commission'] = 'trim|required|integer';
		$this->validation->set_rules($rules);

		$fields['fname'] = $this->lang->line('first_name');
		$fields['status'] = $this->lang->line('status');
		$fields['lname'] = $this->lang->line('last_name');
		$fields['username'] = $this->lang->line('username');
		$fields['apassword'] = $this->lang->line('password');
		$fields['passconf'] = $this->lang->line('confirm_password');
		$fields['primary_email'] = $this->lang->line('primary_email');
		$fields['admin_photo'] = $this->lang->line('admin_photo');
		$fields['rows_per_page'] = $this->lang->line('rows_per_page');
		$fields['show_assigned_tickets_only'] = $this->lang->line('show_assigned_tickets_only');
		$fields['alert_affiliate_signup'] = $this->lang->line('alert_affiliate_signup');
		$fields['alert_affiliate_commission'] = $this->lang->line('alert_affiliate_commission');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_email_add()
	{
		if ($this->db_validation_model->_validate_field('admin_users', 'primary_email', $this->validation->primary_email))
		{
			$this->validation->set_message('_check_email_add', $this->lang->line('admin_email_taken'));
			return false;
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_email_update()
	{
		if ($this->db_validation_model->_validate_field('admin_users', 'primary_email', $this->validation->primary_email, 'admin_id', $this->validation->admin_id))
		{
			$this->validation->set_message('_check_email_update', $this->lang->line('admin_email_taken'));
			return false;
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_username_add()
	{
		if ($this->db_validation_model->_validate_field('admin_users', 'username', $this->validation->username))
		{
			$this->validation->set_message('_check_username_add', $this->lang->line('admin_username_taken'));
			return false;
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_username_update()
	{
		if ($this->db_validation_model->_validate_field('admin_users', 'username', $this->validation->username, 'admin_id', $this->validation->admin_id))
		{
			$this->validation->set_message('_check_username_update', $this->lang->line('admin_username_taken'));
			return false;
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_admin_permissions($selected = '')
	{		 
		$options = array(
						'admin_users/add_admin' => $this->lang->line('add_administrator'),
						'admin_users/delete_admin' => $this->lang->line('delete_administrator'),
						'admin_users/update_admin' => $this->lang->line('update_administrator'),
						'admin_users/view_admins' => $this->lang->line('view_administrators'),
						
						'affiliate_groups/add_group' => $this->lang->line('add_affiliate_group'),
						'affiliate_groups/delete_group' => $this->lang->line('delete_affiliate_group'),
						'affiliate_groups/update_group' => $this->lang->line('update_affiliate_group'),
						'affiliate_groups/view_groups' => $this->lang->line('view_affiliate_groups'),
						'affiliate_marketing' => $this->lang->line('affiliate_marketing'),
						
						'affiliate_payments/delete_affiliate_payment' => $this->lang->line('delete_affiliate_payment'),
						'affiliate_payments/make_affiliate_payments' => $this->lang->line('make_affiliate_payments'),
						'affiliate_payments/update_affiliate_payment' => $this->lang->line('update_affiliate_payment'),
						'affiliate_payments/view_affiliate_payments' => $this->lang->line('view_affiliate_payments'),
						
						'commissions/add_commission' => $this->lang->line('add_commission'),
						'commissions/delete_commission' => $this->lang->line('delete_commission'),
						'commissions/update_commission' => $this->lang->line('update_commission'),
						'commissions/view_commissions' => $this->lang->line('view_commissions'),
						
						'content_articles/add_content_article' => $this->lang->line('add_content_article'),
						'content_articles/delete_content_article' => $this->lang->line('delete_content_article'),
						'content_articles/update_content_article' => $this->lang->line('update_content_article'),
						'content_articles/view_content_articles' => $this->lang->line('view_content_articles'),

						'currencies/view_currencies' => $this->lang->line('manage_currencies'),
						
						'downline/view_downline' => $this->lang->line('view_downline'),
						'email_queue/view_email_queue' => $this->lang->line('view_email_queue'),
						
						'programs/add_program' => $this->lang->line('programs'),
						'programs/delete_program' => $this->lang->line('delete_program'),
						'programs/update_program' => $this->lang->line('update_program'),
						'programs/view_programs' => $this->lang->line('view_programs'),
						'programs/form_fields' => $this->lang->line('manage_forms'),
						
						'faq_articles/add_faq_article' => $this->lang->line('add_faq_article'),
						'faq_articles/delete_faq_article' => $this->lang->line('delete_faq_article'),
						'faq_articles/update_faq_article' => $this->lang->line('update_faq_article'),
						'faq_articles/view_faq_articles' => $this->lang->line('view_faq_articles'),
						
						
						
						'import_export/view_import_export' => $this->lang->line('manage_import_export'),
						
						'languages/view_languages' => $this->lang->line('manage_languages'),

						'mailing_lists/add_mailing_list' => $this->lang->line('add_mailing_list'),
						'mailing_lists/delete_mailing_list' => $this->lang->line('delete_mailing_list'),
						'mailing_lists/update_mailing_list' => $this->lang->line('update_mailing_list'),
						'mailing_lists/view_mailing_lists' => $this->lang->line('view_mailing_lists'),
						
						'members/add_member' => $this->lang->line('add_member'),
						'members/delete_member' => $this->lang->line('delete_member'),
						'members/update_member' => $this->lang->line('update_member'),
						'members/update_members' => $this->lang->line('update_members'),
						'members/view_members' => $this->lang->line('view_members'),
						'modules/view_modules' => $this->lang->line('manage_modules'),
						
						'reports/view_reports' => $this->lang->line('view_reports'),
						'settings' => $this->lang->line('manage_settings'),
						
						);
		
		asort($options);
		return form_dropdown('permissions[]', $options, $selected, 'multiple="multiple" id="admin-permissions" class="form-control capitalize permissions-area" style="min-height: 140px;"');
	}
	
	// ------------------------------------------------------------------------	
}
?>