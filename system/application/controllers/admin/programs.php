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
| FILENAME - programs.php
| -------------------------------------------------------------------------     
| 
*/

class Programs extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('groups_model');
		
		$this->load->model('forms_model');
		
		$this->load->model('rewards_model', 'rewards');
		
		$this->load->helper('innovaeditor');
		
		$this->config->set_item('menu', 'o');
	}
	
	// ------------------------------------------------------------------------	
	
	function index()
	{
		redirect(admin_url() . 'programs/view_programs');
	}
	
	// ------------------------------------------------------------------------	
	
	function view_programs()
	{	
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		$data['programs'] = $this->programs_model->_get_programs($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);
		
		$total_rows = $this->db->count_all('programs');
		
		$data['sort'] = array();
		
		for ($i = 1; $i <= $total_rows; $i++)
		{
			$data['sort'][$i] = $i;	
		}
		
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'programs', $this->session->userdata('per_page'), 5, $data['sort_order'], $data['sort_column'], '');
		
		load_admin_tpl('admin', 'tpl_adm_manage_programs', $data);	
	}
	
	// ------------------------------------------------------------------------	
	
	function add_program()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		if ($this->_check_program('add') == false)
		{	
		 	$this->validation->id = (int)$this->uri->segment(4);
		
			$data['editor_path'] =  '<script language="Javascript" type="text/javascript" src="' . base_url() . 'js/scripts/innovaeditor.js"></script>';
		 	
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
				
			$aff_groups = $this->groups_model->_get_all_affiliate_groups();
			
			$data['aff_groups'] = format_array($aff_groups, 'group_id', 'aff_group_name');
			
			$data['levels'] = array();

			for ($i=1;$i<=10; $i++)
			{
				$data['levels'][$i] = $i;
			}
				
			$this->validation->terms_of_service = HTML_Editor('oEdit1', $data['sts_admin_enable_wysiwyg_content'], 'pdf', '', 'terms_of_service', '300');
				
			$this->validation->program_description = HTML_Editor('oEdit2', $data['sts_admin_enable_wysiwyg_content'], 'pdf', '', 'program_description', '300');
							
			$this->validation->privacy_policy = HTML_Editor('oEdit3', $data['sts_admin_enable_wysiwyg_content'], 'pdf', '', 'privacy_policy', '300');
					
			load_admin_tpl('admin', 'tpl_adm_manage_program', $data);
		}
		else
		{	
			$sdata = $this->programs_model->_add_program((int)$this->uri->segment(4));	

			$this->emailing_model->_add_program_email_templates($sdata);

			$this->load->model('settings_model');
			
			$this->settings_model->_add_menu_maker($sdata);

			$this->load->model('settings_model');
			
			$this->settings_model->_update_html_menus($sdata['id']);

			$this->_run_program_modules('program_add', (int)($this->uri->segment(4)));		

			$this->session->set_flashdata('success', $this->lang->line('item_added_successfully'));

			redirect(admin_url() . 'programs/update_program/' . $sdata['id']);
		}		
	}
	
		// ------------------------------------------------------------------------	
	
	function update_programs()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		if ($this->_check_sort_order() == true)
		{	
			$this->programs_model->_change_sort_order($_POST);

			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
		}
		else
		{
			$this->session->set_flashdata('error',$this->validation->error_string);
		}
		
		redirect($this->input->post('redirect'));
	}
	
	// ------------------------------------------------------------------------	
	
	function delete_program()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$sdata = $this->programs_model->_get_program_details((int)($this->uri->segment(4)));
									  
		if ($this->programs_model->_delete_program((int)($this->uri->segment(4))))
		{
			$this->_run_program_modules('program_delete', $sdata);
			
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url()  . strtolower( __CLASS__) . '/view_programs/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
		}
		
		redirect(admin_url() . 'programs/view_programs');
	}
	
	// ------------------------------------------------------------------------	
	
	function update_program()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		if ($this->_check_program('update') == false)
		{
			$this->validation->id = (int)$this->uri->segment(4);
			
			$data['editor_path'] =  '<script language="Javascript" type="text/javascript" src="' . base_url() . 'js/scripts/innovaeditor.js"></script>';
			
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
			
			$aff_groups = $this->groups_model->_get_all_affiliate_groups();
					
			$data['aff_groups'] = format_array($aff_groups, 'group_id', 'aff_group_name');	

			$data['levels'] = array();

			for ($i=1;$i<=10; $i++)
			{
				$data['levels'][$i] = $i;
			}
			
			if (empty($_POST))
			{
				$m = $this->programs_model->_get_program_details((int)$this->uri->segment(4));
				
				if (!empty($m))
				{	
					foreach ($m['program_data'] as $k => $v)
					{
						$this->validation->$k = $v;
						
						switch ($k)
						{							
							case 'modified_by':
								
								$this->validation->$k = check_string_value($v, 'admin_user');
							
							break;
							
							case 'last_modified':
								
								$this->validation->$k = check_string_value($v, 'date');
																
							break;
							
							case 'program_description';
							
							$this->validation->$k = HTML_Editor('oEdit1', $data['sts_admin_enable_wysiwyg_content'], 'basic', $v, $k, '400');
							
							break;
							
							case 'terms_of_service';
							
							$this->validation->$k = HTML_Editor('oEdit2', $data['sts_admin_enable_wysiwyg_content'], 'basic', $v, $k, '400');
							
							break;
							
							case 'privacy_policy';
								
								$this->validation->$k = HTML_Editor('oEdit3', $data['sts_admin_enable_wysiwyg_content'], 'basic', $v, $k, '400');
																
							break;

						}
					}
				
					$data['photos'] = $m['photos'];

					if (!empty($m['photos']))
					{
						$this->validation->program_photo_delete = true;
						
						foreach($m['photos'][0] as $k => $v)
						{
							$this->validation->$k = $v;	
						}
						
						$this->validation->program_photo = base_url() . 'images/' .  $data['images_programs_dir'] . '/' . $this->validation->photo_file_name;

						$this->validation->program_photo_class = _check_thickbox();
						$this->validation->program_photo_delete = true;
					}
					else
					{
						$this->validation->program_photo = base_url() . '/images/misc/default.jpg';
						$this->validation->program_photo_delete = false;
					}

				}
				else
				{
					redirect(admin_url() . 'programs/view_programs');
					exit();
				}	
			}
							
			$data['page_title'] = $this->lang->line('manage_program') . ' - ' . $this->validation->program_name;

			load_admin_tpl('admin', 'tpl_adm_manage_program', $data);
			
		}
		else
		{	
			$data = $this->programs_model->_update_program((int)$this->uri->segment(4));	
			
			//run add product modules
			$this->_run_program_modules('program_update', (int)($this->uri->segment(4)));		

			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));

			redirect($this->uri->uri_string());
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function update_status()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		if ($this->db_validation_model->_change_status_field('programs', 'program_id', (int)$this->uri->segment(4), 'program_status'))
		{
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
				
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url()  . strtolower( __CLASS__) . '/view_programs/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
			else
			{
				redirect(admin_url() . 'programs/view_programs');
			}
		}	
	}
	
	// ------------------------------------------------------------------------	

	function delete_logo()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		@unlink('./images/programs/' . $this->uri->segment(4));
		
		$update = array('program_logo' => '');
		$this->db->where('program_logo', $this->uri->segment(4));
		$query = $this->db->update('programs', $update);
		
		if ($query)
		{
			$this->session->set_flashdata('success', $this->lang->line('image_deleted_successfully'));
		}
		
		redirect(admin_url() . 'themes/view_themes#logo');		
	}
	
	// ------------------------------------------------------------------------

	function delete_photo()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$this->load->model('uploads_model');
		
		$data['config'] = array(
								'type'	=>	'delete',
								'table'	=>	'programs_photos',
								'key'	=>	'raw_name',
								'value' 	=>	$this->uri->segment(4),
								'id'	=>	$this->uri->segment(5)
								);
			
		$data['fields'] = array (
								'program_id'	=> 	$this->uri->segment(5)
								);		
		
		if ($this->uploads_model->_delete_photo('programs', $data))
		{	
			$this->session->set_flashdata('success', $this->lang->line('image_deleted_successfully'));
		}
		else
		{
			$this->session->set_flashdata('error', $this->lang->line('could_not_delete_image'));
		}
		
		redirect(admin_url() . 'programs/update_program/' . $this->uri->segment(5) . '#fragment-5');	
		
	}
	
	// ------------------------------------------------------------------------	
	
	function upload_logo()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$this->validation->id =  $this->uri->segment(4,1);
		
		if (empty($_POST))
		{
			$m = $this->programs_model->_get_program_basic('program_id', $this->validation->id, 'program_logo');
			
			foreach ($m as $k => $v)
			{
				$this->validation->$k = $v;
			}
			
			load_admin_tpl('admin', 'tpl_adm_manage_program_logo', $data);
		}
		else
		{
			$this->load->model('uploads_model');
	
			$this->db->where('program_id', $this->input->post('program_id'));
			$query = $this->db->get('programs');
			$row = $query->row_array();
	
			$ldata['logo'] = !empty($row[0]['program_logo']) ? $row[0]['program_logo'] : '';
			
			if (!empty($_FILES) && $_FILES['userfile']['error'] != 4)
			{
				$ldata['image_data'] = $this->_upload_image($this->input->post('program_id'));
				
				if (empty($ldata['image_data']['success']))
				{
					$this->session->set_flashdata('error', $ldata['image_data']['msg']);
				}
				else
				{
					$this->_check_logo_delete($ldata);	
				
					$this->session->set_flashdata('success', $this->lang->line('image_uploaded_successfully'));
				}
			}
		}
		
		redirect(admin_url() . 'themes/view_themes#logo');		
	}
	
	// ------------------------------------------------------------------------	
	
	function upload_photo()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$this->load->model('uploads_model');
		
		$data = $this->uploads_model->_upload_photo('programs', $this->config->item('sts_site_image_auto_resize'));
	
		if ($data['success'])
		{
			$program_id = (int)($this->uri->segment(4));
		
			$program_data = $this->db_validation_model->_get_details('programs_photos', '', 'program_id', $program_id);
		
			if (empty($program_data))
			{
				$insert_data = array (
										'program_id'	=> 	$program_id,
										'photo_file_name' => $data['info']['file_name'], 
										'raw_name'	=> $data['info']['raw_name'],
										'file_ext'	=> $data['info']['file_ext'],
										'file_type'	=> $data['info']['file_type'],
										'original_file_name' => $data['info']['orig_name'],
										'image_resized'	=>	$this->config->item('sts_site_image_auto_resize')
									);	
				
				$this->uploads_model->_insert_image_db($insert_data, 'programs_photos');
			}
			else
			{				
				$data['config'] = array(
											'table'	=>	'programs_photos',
											'key'	=>	'program_id',
											'value' 	=>	$program_id
										);
				
				$data['fields'] = array (
											'photo_file_name' => $data['info']['file_name'], 
											'raw_name'	=> $data['info']['raw_name'],
											'file_ext'	=> $data['info']['file_ext'],
											'file_type'	=> $data['info']['file_type'],
											'original_file_name' => $data['info']['orig_name'],
											'image_resized'	=>	$this->config->item('sts_site_image_auto_resize')
										);			
				
				$this->uploads_model->_update_image_db($data);
				
				if ($program_data[0]['photo_file_name'])
				{
					@unlink('./images/' . $this->config->item('images_programs_dir') . '/' . $program_data[0]['photo_file_name']);
					
					if ($this->config->item('sts_affiliate_image_auto_resize') == 1)
					{
						@unlink('./images/' . $this->config->item('images_programs_dir') . '/' . $program_data[0]['raw_name'] . '_jrox' . $program_data[0]['file_ext']);
					}
				}
			}
			
			$this->session->set_flashdata('success', $this->lang->line('image_uploaded_successfully'));

			redirect(admin_url() . 'programs/update_program/' . $program_id );		
		}
		else
		{
			$this->session->set_flashdata('error', $data['msg']);
			
			redirect(admin_url() . 'programs/update_program/' . (int)($this->input->post('program_id')));		
		}	
	}
	
	// ------------------------------------------------------------------------	
	
	function remote_link()
	{
		/*
		| ---------------------------------------------
		| download the track.php file for the program
		| ---------------------------------------------
		*/
		
		//set data array
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$mdata = $this->programs_model->_get_program_details((int)$this->uri->segment(4));
		
		if (!empty($mdata))
		{
			$this->load->helper('download');
			$this->load->helper('file');
			
			$location = PUBPATH . '/docs/track2.php';
	
			$sdata = read_file($location);
			
			$sdata = str_replace('{auto_secret_id}', $this->config->item('sts_auto_login_secret'), $sdata);
			$sdata = str_replace('{base_url}', base_url(), $sdata);
			$sdata = str_replace('{program_cookie_name}', $mdata['program_data']['program_cookie_name'], $sdata);
			$sdata = str_replace('{remote_domain_name}', $mdata['program_data']['remote_domain_name'], $sdata);
			$sdata = str_replace('{url_redirect}', _public_url() . 'refer/id/', $sdata);
			$sdata = str_replace('{program_id}', (int)$this->uri->segment(4), $sdata);
			$sdata = str_replace('{cookie_timer}', $this->config->item('sts_affiliate_cookie_timer'), $sdata);

			force_download('track.php', $sdata); 
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function update_fields()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$this->forms_model->_update_program_form_fields((int)$this->uri->segment(4));
		
		$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));	
		
		redirect(admin_url() . 'programs/form_fields/1');		
	}
	
	// ------------------------------------------------------------------------	
	
	function form_fields()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['program_id'] = (int)$this->uri->segment(4, 1);
					
		$data['form_fields'] = $this->forms_model->_get_program_form_fields($data['program_id']);
		
		unset($data['form_fields']['program_id']);
		
		if (file_exists(APPPATH . 'language/' . $this->config->item('sts_site_default_language') . '/common_lang.php'))
		{
			include_once(APPPATH . 'language/' . $this->config->item('sts_site_default_language') . '/common_lang.php');
		
			$data['language_fields'] = $lang;
		}
		
		foreach ($data['form_fields'] as $k => $v)
		{

			$this->validation->$k = $v;
				
		}
		
		load_admin_tpl('admin', 'tpl_adm_manage_program_form_fields', $data);
	}

	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	
	// ------------------------------------------------------------------------
	
	function _check_logo_delete($ldata = '')
	{
		
		//check if logo's do not match, then delete the old one
		if (!empty($ldata['image_data']['info']['file_name']))
		{
			if ($ldata['logo'] != $ldata['image_data']['info']['file_name'])
			{
				@unlink('./images/' . $this->config->item('images_programs_dir') . '/' . $ldata['logo']);
			}
		}
		
		//check if the field is blank then delete
		if (empty($_POST['program_logo']))
		{
			if (!empty($ldata['logo']))
			{
				@unlink('./images/' . $this->config->item('images_programs_dir') . '/' . $ldata['logo']);
			}		
		}

		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_sort_order()
	{
		$data = $this->db_validation_model->_clean_data($_POST);

		foreach ($data as $k => $v)
		{
			if (strstr($k, "program") == true) 
			{
				$rules[$k] = 'trim|required|numeric';
				$fields[$k] = $k ;
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
	
	// ------------------------------------------------------------------------	
	
	
	function _check_program_signup_link_add()
	{
		if ($this->db_validation_model->_validate_field('programs', 'signup_link', $this->validation->signup_link))
		{
			$this->validation->set_message('_check_program_signup_link_add', $this->lang->line('program_signup_link_taken'));
			return false;
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_program_signup_link_update()
	{
		if ($this->db_validation_model->_validate_field('programs', 'signup_link', $this->validation->signup_link, 'program_id', $this->uri->segment(4)))
		{
			$this->validation->set_message('_check_program_signup_link_update', $this->lang->line('program_signup_link_taken'));
			return false;
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_program_cookie_name()
	{
		if ($this->db_validation_model->_validate_field('programs', 'program_cookie_name', $this->validation->program_cookie_name, 'program_id', $this->validation->id))
		{
			$this->validation->set_message('_check_program_cookie_name', $this->lang->line('program_cookie_name_taken'));
			return false;
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_program($type = 'add')
	{
	
		//set form validation rules
		$rules['program_status'] = 'trim|required|integer';
		$rules['program_name'] = 'trim|required|min_length[3]|max_length[255]';
		$rules['program_description'] = 'trim';
		$rules['sort_order'] = 'trim|numeric';
		$rules['enable_pay_per_click'] = 'trim|integer';
		$rules['enable_cpm'] = 'trim|integer';
		$rules['cpm_unique_ip'] = 'trim';
		$rules['enable_pay_per_action'] = 'trim|integer';
		$rules['ppc_interval'] = 'trim|numeric';
		$rules['commission_levels'] = 'trim|required|integer';
		$rules['commission_frequency'] = 'trim';
		$rules['new_commission_option'] = 'trim|required';
		$rules['auto_approve_commissions'] = 'trim|integer';
		$rules['url_redirect'] = 'trim|required|prep_url';
		$rules['last_modified'] = 'trim';
		$rules['modified_by'] = 'trim';
		$rules['terms_of_service'] = 'trim'; 
		$rules['privacy_policy'] = 'trim'; 
		$rules['hidden_program'] = 'trim';
		$rules['group_id'] =  'trim|required';
        $rules['postback_url'] = 'trim|strtolower';

        if ($type == 'add')
        {
            $rules['signup_link'] =  'trim|required|min_length[3]|max_length[25]|callback__check_program_signup_link_add';
        }
        else
        {
            $rules['signup_link'] =  'trim|required|callback__check_program_signup_link_update|min_length[3]|max_length[25]';
        }

		$this->validation->set_rules($rules);

		//repopulate form

		$fields['program_status'] = $this->lang->line('program_status');
		$fields['program_name'] = $this->lang->line('program_name');
		$fields['program_description'] = $this->lang->line('program_description');
		$fields['sort_order'] = $this->lang->line('sort_order');
		$fields['enable_pay_per_click'] = $this->lang->line('enable_pay_per_click');
		$fields['enable_cpm'] = $this->lang->line('enable_pay_per_sale');
		$fields['cpm_unique_ip'] = $this->lang->line('cpm_unique_ip');
		$fields['enable_pay_per_action'] = $this->lang->line('enable_pay_per_action');
		$fields['ppc_interval'] = $this->lang->line('ppc_interval');
		$fields['commission_levels'] = $this->lang->line('commission_levels');
		$fields['commission_frequency'] = $this->lang->line('commission_frequency');
		$fields['new_commission_option'] = $this->lang->line('new_commission_option');
		
		$fields['auto_approve_commissions'] = $this->lang->line('auto_approve_commissions');
		$fields['url_redirect'] = $this->lang->line('landing_page_url');
		
		$fields['enable_custom_login'] = $this->lang->line('enable_custom_login');
		$fields['url_redirect_login'] = $this->lang->line('url_redirect_login');
		$fields['enable_custom_signup'] = $this->lang->line('enable_custom_signup');
		$fields['url_redirect_signup'] = $this->lang->line('url_redirect_signup');
		
		$fields['default_theme'] = $this->lang->line('default_theme');
		$fields['default_home_page'] = $this->lang->line('default_home_page');
		$fields['terms_of_service'] = $this->lang->line('terms_of_service');
		$fields['privacy_policy'] = $this->lang->line('privacy_policy');
		$fields['hidden_program'] = $this->lang->line('hidden_program');
		$fields['group_id'] = $this->lang->line('group_id');
		$fields['signup_link'] = $this->lang->line('signup_link');
		$fields['require_trans_id'] = $this->lang->line('require_trans_id');
		$fields['last_modified'] = $this->lang->line('last_modified');
		$fields['modified_by'] = $this->lang->line('modified_by');
		$fields['commission_levels_restrict_view'] = $this->lang->line('commission_levels_restrict_view');
		$fields['enable_affiliate_signup_bonus'] = $this->lang->line('enable_affiliate_signup_bonus');
		$fields['affiliate_signup_bonus_amount'] = $this->lang->line('affiliate_signup_bonus_amount');
		$fields['enable_referral_bonus'] = $this->lang->line('enable_referral_bonus');
		$fields['referral_bonus_amount'] = $this->lang->line('referral_bonus_amount');
		$fields['program_cookie_name'] = $this->lang->line('program_cookie_name');
		$fields['use_remote_domain_link'] = $this->lang->line('use_remote_domain_link');
		$fields['remote_domain_name'] = $this->lang->line('remote_domain_name');
        $fields['postback_url'] = $this->lang->line('postback_url');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;

	}
	
	// ------------------------------------------------------------------------	
	
	function _run_program_modules($type = '', $data = '')
	{
		//load members model
		$this->load->model('modules_model');
		
		//run modules
		$sdata = $this->modules_model->_run_modules($type, 'admin');
		
		if (!empty($sdata))
		{
			//load the api config
			$this->config->load('api');
			
			//load models
			if ($this->config->item('module_api_' . $type . '_models'))
			{
				$api_models = explode(',', $this->config->item('module_api_' . $type . '_models'));
				
				foreach ($api_models as $api)
				{
					$this->load->model(trim($api . '_model'), $api . '_api');
				}
			}
			
			//load helpers
			if ($this->config->item('module_api_' . $type . '_helpers'))
			{
				$helpers = explode(',', $this->config->item('module_api_' . $type . '_helpers'));
				
				foreach ($helpers as $helper)
				{
					$this->load->helper(trim($helper));
				}
			}
			
			for ($i=0; $i<count($sdata); $i++)
			{
				
				$module_model = 'modules/module_' . $sdata[$i]['module_type'] . '_' . $sdata[$i]['module_file_name'] . '_model';
				
				$this->load->model($module_model, $sdata[$i]['module_file_name']);
				
				$function = '_run_module_admin_' . $sdata[$i]['module_file_name'];
				
				$this->$sdata[$i]['module_file_name']->$function('admin', $data);	
			}
		}
	}
	
	// ------------------------------------------------------------------------
	
	function _upload_image($id = '')
	{		
		$this->load->model('uploads_model');
		
		//upload the photo
		$data = $this->uploads_model->_upload_photo('programs', 0);
		
		//add photo to db
		if (!empty($data['success']))
		{
			//add logo
			$sql = array('program_logo'	=> 	$data['info']['file_name']);

			$this->db->where('program_id', $id);

			if (!$this->db->update('programs', $sql))
			{
				return false;
			}
		}
		
		return $data;
		
	}
}
?>