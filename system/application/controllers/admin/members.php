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
| FILENAME - members.php
| -------------------------------------------------------------------------     
|
*/

class Members extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('downline_model', 'downline');	
		
		$this->config->set_item('menu', 'm');
		
	}
	
	// ------------------------------------------------------------------------	
	
	function index()
	{
		redirect(admin_url() . strtolower(__CLASS__) . '/view_members');
	}
	
	// ------------------------------------------------------------------------	
	
	function login_member()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$this->load->model('init_model');	
		
		$row = $this->members_model->_get_member_basic((int)$this->uri->segment(4));
		
		$user= $this->encrypt->encode($row['username'], md5($this->config->item('encryption_key')));
		
		$pass = $this->encrypt->encode($row['password'], md5($this->config->item('encryption_key')));
		
		$this->init_model->_set_default_program(1, true);

		if ($this->login_model->_check_admin_login($user, $pass) == false)
		{
			$this->security_model->_auto_block_ip('block', $this->input->ip_address());	
			
			redirect(ERROR_404_ROUTE);
		}
		else
		{		
			$this->security_model->_auto_block_ip('remove', $this->input->ip_address());	
			
			redirect_301(site_url('members'), true, true);
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function add_member()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		$this->load->helper('country');		
		
		$data['country_options'] = _load_countries_dropdown($countries = _load_countries_array());
		
		if (file_exists(APPPATH . 'language/' . $this->config->item('sts_site_default_language') . '/common_custom_lang.php'))
		{
			include_once(APPPATH . 'language/' . $this->config->item('sts_site_default_language') . '/common_custom_lang.php');
		
			if (!empty($lang))
			{
				$data['language_fields'] = $lang;
			}
		}
		elseif (file_exists(APPPATH . 'language/' . $this->config->item('sts_site_default_language') . '/common_lang.php'))
		{
			include_once(APPPATH . 'language/' . $this->config->item('sts_site_default_language') . '/common_lang.php');
		
			$data['language_fields'] = $lang;
		}
		
		$email_array = array(
								'form_type'	=>	'multiple-dropdown',
								'form_name'	=>	'email_mailing_lists',
								'form_selected' 	=>	'',
								'row_fields'	=> 'mailing_list_id, mailing_list_name',
								'form_options'	=> 'class="form-control"',
								'id'	=>	'mailing_list_id',
								'name'	=>	'mailing_list_name',
								'selected' => 'mailing_list_id'
							);
		
		$this->validation->email_mailing_lists = $this->_get_member_form_tables($email_array);
		
		$programs_array = array(
								'form_type'	=>	'multiple-dropdown',
								'form_name'	=>	'programs',
								'form_selected' 	=> '',
								'row_fields'	=> 'program_id, program_name',
								'form_options'	=> 'class="form-control"',
								'id'	=>	'program_id',
								'name'	=>	'program_name',
								'selected' => 'program_id'
							);
		
		$this->validation->programs = $this->_get_member_form_tables($programs_array);

		$aff_group_array = array(
									'form_type'	=>	'dropdown',
									'form_name'	=>	'affiliate_groups',
									'form_selected' 	=>	'',
									'row_fields'	=> 'group_id, aff_group_name',
									'form_options'	=> 'class="form-control"',
									'id'	=>	'group_id',
									'name'	=>	'aff_group_name',
									'selected' => 'group_id'
								);
		
		$this->validation->affiliate_groups = $this->_get_member_form_tables($aff_group_array);
		
		if ($this->_check_member('add') == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}

			load_admin_tpl('admin', 'tpl_adm_add_member', $data);
		}
		else
		{	
			$data = $this->members_model->_add_member($_POST);	
			
			$this->_run_member_modules('account_add', $data['member_id']);
			
			if ($this->input->post('send_welcome_email') == 1)
			{
				$data['password'] = $this->input->post('password', true);			
			
				$this->_send_member_email($data, 'member_login_details_template');
			}
			
			$this->session->set_flashdata('success', $this->lang->line('affiliate_added_successfully'));
			
			redirect(admin_url() . strtolower(__CLASS__) . '/update_member/' . $data['member_id'] . '#edit');
		}		
	}
	
	// ------------------------------------------------------------------------	
	
	function delete_member()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$sdata = $this->members_model->_get_member_details((int)($this->uri->segment(4)));
		
		if ($this->members_model->_delete_member((int)($this->uri->segment(4))))
		{
			$this->_run_member_modules('account_delete', $sdata);
			
			$this->session->set_flashdata('success', $this->lang->line('member_deleted_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . strtolower(__CLASS__) . '/view_members/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
			}
		}
		
		redirect(admin_url() . 'members/view_members');
	}
	
	// ------------------------------------------------------------------------

	function delete_photo()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$this->load->model('uploads_model');
				
		$data['config'] = array(
								'type'	=>	'delete',
								'table'	=>	'members_photos',
								'key'	=>	'raw_name',
								'value' 	=>	$this->uri->segment(4),
								'id'	=>	$this->uri->segment(5)
								);
			
		$data['fields'] = array ( 'member_id'	=> 	$this->uri->segment(5));		
		
		$this->uploads_model->_delete_photo('members', $data);
		
		if ($this->members_model->_delete_member_photo($this->uri->segment(4), $this->uri->segment(5)))
		{	
			$this->session->set_flashdata('success', $this->lang->line('image_deleted_successfully'));
		}
		else
		{
			$this->session->set_flashdata('error', $this->lang->line('could_not_delete_image'));
		}
		
		redirect(admin_url() . strtolower(__CLASS__) . '/update_member/' . $this->uri->segment(5));	
		
	}
	
	// ------------------------------------------------------------------------
	
	function view_members()
	{	

		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$template = $this->session->userdata('view_members_page') == 'table' ? 'tpl_adm_manage_members2' : ADMIN_DEFAULT_MEMBERS_PAGE_VIEW;
		
		if (!empty($data['where_column']) && $data['where_column'] == 'search')
		{
			$this->load->library('convert');
			
			$data['where_value'] = base64_decode($this->convert->HexToAscii($data['where_value'])); 
			
			$template = 'tpl_adm_manage_members2';
		}
		
		$data['members'] = $this->members_model->_get_members($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order'], $data['where_column'], $data['where_value']);
		
		$data['total_rows'] = '';
		
		if (!empty($data['where_column']) && !empty($data['where_value']))
		{
			if ($data['where_column'] == 'search')
			{
				$this->db->like('fname', $data['where_value']);
				$this->db->or_like('lname', $data['where_value']); 
				$this->db->or_like('company', $data['where_value']); 
				$this->db->or_like('username', $data['where_value']); 
				$this->db->or_like('primary_email', $data['where_value']); 
				$this->db->from('members');
				
				$data['total_rows'] = $this->db->count_all_results();

				$data['filter_category'] = $this->lang->line('search');
				$data['filter_name'] = str_replace('_', ' ', $data['where_value']);
			}
			elseif ($data['where_column'] == 'programs')
			{
				$type = '';
				$filter = $this->db_validation_model->_get_details('programs', 'program_name', 'program_id', $data['where_value']);
				$data['filter_category'] = $this->lang->line('program_members');
				$data['filter_name'] = $filter[0]['program_name'];
				
				$data['total_rows'] = $this->db_validation_model->_get_count('members_programs', 'WHERE program_id =\'' . $data['where_value'] . '\'');
			}
			else
			{
				switch ($data['where_column'])
				{
					case 'affiliate_group':
						$type = 'affiliate';
						$filter = $this->db_validation_model->_get_details('affiliate_groups', 'aff_group_name', 'group_id', $data['where_value']);
						$data['filter_category'] = $this->lang->line('affiliate_group');
						$data['filter_name'] = $filter[0]['aff_group_name'];
					break;
					
					default:
						$type = '';
						$data['filter_category'] = '';
						$data['filter_name'] = '';
					break;
				}
					
				$data['total_rows'] = $this->db_validation_model->_get_count('members_groups', 'WHERE group_id =\'' . $data['where_value'] . '\'');
			}
		}
		else
		{
			$data['total_rows']= $this->db->count_all('members');
		}
		
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'members', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column'], $data['total_rows'], $data['where_column'], $data['where_value']);
		
		load_admin_tpl('admin', $template, $data);	

	}
	
	// ------------------------------------------------------------------------	
	
	function view_member()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$id = (int)$this->uri->segment(5);
		
		if ($this->uri->segment(4) == 'next');
		
		$mid = $this->members_model->_get_next_member($id, $this->uri->segment(4));
		
		redirect(admin_url() . strtolower(__CLASS__) . '/update_member/' . $mid);
		
	}
	
	// ------------------------------------------------------------------------	
	
	function update_member()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		$this->load->helper('country');		
		
		$data['country_options'] = _load_countries_dropdown($countries = _load_countries_array());
		
		if (file_exists(APPPATH . 'language/' . $this->config->item('sts_site_default_language') . '/common_lang.php'))
		{
			include_once(APPPATH . 'language/' . $this->config->item('sts_site_default_language') . '/common_lang.php');
		
			$data['language_fields'] = $lang;
		}
		
		if (file_exists(APPPATH . 'language/' . $this->config->item('sts_site_default_language') . '/common_custom_lang.php'))
		{
			include_once(APPPATH . 'language/' . $this->config->item('sts_site_default_language') . '/common_custom_lang.php');
		
			if (!empty($lang))
			{
				$data['language_fields'] = $lang;
			}
		}

		$this->validation->member_id = $this->uri->segment(4);
		
		if ($this->_check_member('update') == false)
		{					
			if (empty($_POST))
			{
				if ($mdata = $this->members_model->_get_member_details((int)($this->validation->member_id)))
				{
					$email_array = array(
											'form_type'	=>	'multiple-dropdown',
											'form_name'	=>	'email_mailing_lists',
											'form_selected' 	=>	$mdata['mailing_lists'],
											'row_fields'	=> 'mailing_list_id, mailing_list_name',
											'form_options'	=> 'class="form-control"',
											'id'	=>	'mailing_list_id',
											'name'	=>	'mailing_list_name',
											'selected' => 'mailing_list_id'
										);
					
					$this->validation->email_mailing_lists = $this->_get_member_form_tables($email_array);

					$programs_array = array(
											'form_type'	=>	'multiple-dropdown',
											'form_name'	=>	'programs',
											'form_selected' 	=>	$mdata['programs'],
											'row_fields'	=> 'program_id, program_name',
											'form_options'	=> 'class="form-control"',
											'id'	=>	'program_id',
											'name'	=>	'program_name',
											'selected' => 'program_id'
										);
					
					$this->validation->programs = $this->_get_member_form_tables($programs_array);
					
					$aff_group_array = array(
												'form_type'	=>	'dropdown',
												'form_name'	=>	'affiliate_groups',
												'form_selected' 	=>	$mdata['affiliate_groups'],
												'row_fields'	=> 'group_id, aff_group_name',
												'form_options'	=> 'class="form-control"',
												'id'	=>	'group_id',
												'name'	=>	'aff_group_name',
												'selected' => 'group_id'
											);
					
					$this->validation->affiliate_groups = $this->_get_member_form_tables($aff_group_array, true);

					if ($this->config->item('sts_admin_enable_member_graphs'))
					{
						$this->load->model('dashboard_model', 'dash');
						
						$days = date('t', _generate_timestamp()) - (date('t',_generate_timestamp()) - date('d',_generate_timestamp()));
						
						$data['total_clicks'] = $this->dash->_total_user_clicks((int)($this->validation->member_id));
						
						$data['total_referrals'] = $this->dash->_total_user_referrals((int)($this->validation->member_id));
						
						$data['total_comm'] = $this->dash->_total_user_comms((int)($this->validation->member_id)); 
						
						$data['month_signups'] = $this->dash->_total_signups(date('m', _generate_timestamp()), date('Y', _generate_timestamp()), (int)($this->validation->member_id));
						
						$data['month_clicks'] = $this->dash->_total_clicks(date('m', _generate_timestamp()), date('Y', _generate_timestamp()), (int)($this->validation->member_id));
						
						$data['month_clicks_avg'] = $data['month_clicks'] / $days;		
						
						$data['month_referrals'] = $this->dash->_total_user_referrals((int)($this->validation->member_id), date('m', _generate_timestamp()), date('Y', _generate_timestamp()));
						
						$data['month_comm'] = $this->dash->_total_commissions(date('m', _generate_timestamp()), date('Y', _generate_timestamp()), (int)($this->validation->member_id));
						
						$data['month_comm_avg'] = $data['month_comm'] / $days;
			
						$data['total_payments'] = $this->dash->_total_user_payments((int)($this->validation->member_id));

						$last_payment = $this->dash->_get_last_payment((int)($this->validation->member_id));
						
						if (empty($last_payment['payment_date']))
						{
							$data['last_payment'] = $this->lang->line('none');	
						}
						else
						{
							$data['last_payment'] = _show_date($last_payment['payment_date']) . ' - ' . format_amounts($last_payment['payment_amount'], $data['num_options']);	
						}
					}
					
					foreach ($mdata['member_data'] as $key => $value)
					{
						$this->validation->$key = $value;
						$this->validation->password = '';
						
						switch ($key)
						{							
							case 'last_login_date':
							case 'signup_date':
							case 'updated_on':
								
								$this->validation->$key = check_string_value($value, 'date');

							break;
							
							case 'updated_by':
								
								$this->validation->$key = check_string_value($value, 'admin_user');
							
							break;
							
							case 'last_login_ip':
								
								$this->validation->last_login_ip = check_string_value($value);
								
							break;
						}
					}
					
					if (!empty($mdata['photos']))
					{ 
						foreach($mdata['photos'][0] as $key => $value)
						{
							$this->validation->$key = $value;
						} 
						
						$photo = $this->validation->photo_file_name;
						
						$this->validation->member_photo_url = base_url() . 'images/' .  $data['images_members_dir'] . '/' . $photo;
						$this->validation->member_photo_delete = true;	
					}
					elseif (!empty($mdata['member_data']['facebook_id']))
					{
						$this->validation->member_photo_url = _check_ssl() . 'graph.facebook.com/' . $mdata['member_data']['facebook_id'] . '/picture?type=large';
						$this->validation->member_photo_delete = false;
						$this->validation->member_photo_url_resize = 0;
					}
					else
					{
						$this->validation->member_photo_url = base_url() . 'themes/admin/' . $data['sts_admin_layout_theme'] .'/img/profile.png';
						$this->validation->member_photo_delete = false;
						$this->validation->member_photo_url_resize = 0;
					}	

					load_admin_tpl('admin', 'tpl_adm_manage_member', $data);
				}
				else 
				{
					redirect(admin_url() . strtolower(__CLASS__) . '/add_member');
					exit();
				}
			}
			else
			{				
				echo '<div class="alert alert-danger animated shake capitalize hover-msg">' . $this->validation->error_string . '</div>';
				exit();
			}		
		}
		else
		{	
			//update the member
			$data = $this->members_model->_update_member((int)($this->uri->segment(4)), true);		
			
			//run update account modules
			$this->_run_member_modules('account_update', $data);
			
			
			echo '<div class="alert alert-success animated shake capitalize hover-msg">' .  $this->lang->line('system_updated_successfully') . '</div>';	
			exit();
			/*
			//set flash data
			$this->session->set_flashdata('success', $this->lang->line('update_member_success'));
			
			//ajax message
			echo '<div class="success" onclick="location.href=\'' . admin_url().'members/update_member/' . $data['member_id'] . '\';" style="cursor:pointer;">' . $this->lang->line('update_member_success') .'</div>';			
			//redirect to admin details form
			echo '<script>window.location = "' . admin_url().'members/update_member/' . $data['member_id'] . '"</script>';
			*/
		}		
		
	}

	// ------------------------------------------------------------------------	
	
	function update_members()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$this->load->model('mailing_lists_model');
		
		if ($this->input->post('change-status') == 'addmailinglist')
		{
			$this->mailing_lists_model->_mass_add_mailing_list($this->input->post('user'), $this->input->post('mailing_list'));
		}
		elseif ($this->input->post('change-status') == 'removemailinglist')
		{
			$this->mailing_lists_model->_mass_remove_mailing_list($this->input->post('user'), $this->input->post('mailing_list'));
		}
		else
		{
			if ($this->input->post('user') AND count($this->input->post('user')) > 0)
			{
				$this->members_model->_change_status($this->input->post('user'), $this->input->post('change-status'));
			}
		}

		$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
		
		redirect($this->input->post('redirect'));
	}
	
	// ------------------------------------------------------------------------
	
	function send_welcome_email()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		$mdata = $this->members_model->_get_member_basic((int)$this->uri->segment(4));	
		
		$mdata['password'] = random_string('alnum', 8);

		$this->members_model->_update_member_password(array('member_id' => (int)($this->uri->segment(4)), 'password' => $mdata['password']));	
		
		if ($this->_send_member_email($mdata, 'member_login_details_template'))
		{
			$this->session->set_flashdata('success', $this->lang->line('email_sent_successfully'));
		}
		
		redirect(admin_url() . strtolower(__CLASS__) . '/update_member/' . (int)$this->uri->segment(4));
	}
	
	
	// ------------------------------------------------------------------------
	
	function update_status()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		$mdata = $this->members_model->_update_status((int)$this->uri->segment(4));

		if ($mdata)
		{	
			if ($mdata != 'inactive')
			{
				if ($this->config->item('sts_sec_require_admin_approval') == '1' && empty($mdata['last_login_date']))
				{
					$this->_send_member_email($mdata, 'member_affiliate_marketing_approval_template');
				}
			}
			
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . strtolower(__CLASS__) . '/view_members/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
			}
			elseif ($this->uri->segment(5) == 3)
			{
				redirect(admin_url());
			}
			else
			{
				redirect(admin_url() . strtolower(__CLASS__) . '/update_member/' . (int)$this->uri->segment(4));
			}
		}	
	}
	
	
	// ------------------------------------------------------------------------	
	
	function upload_photo()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$this->load->model('uploads_model');

		$data = $this->uploads_model->_upload_photo('members', $this->config->item('sts_affiliate_image_auto_resize'));
		
		if ($data['success'])
		{
			$member_id = (int)($this->input->post('member_id'));

			$member_data = $this->db_validation_model->_get_details('members_photos', '', 'member_id', $member_id);
		
			if (empty($member_data))
			{
				$insert_data = array (
										'member_id'	=> 	$member_id,
										'photo_file_name' => $data['info']['file_name'], 
										'raw_name'	=> $data['info']['raw_name'],
										'file_ext'	=> $data['info']['file_ext'],
										'file_type'	=> $data['info']['file_type'],
										'original_file_name' => $data['info']['orig_name'],
										'image_resized'	=>	$this->config->item('sts_affiliate_image_auto_resize')
									);	
				
				$this->uploads_model->_insert_image_db($insert_data, 'members_photos');
			}
			else
			{				
				$data['config'] = array(
											'table'	=>	'members_photos',
											'key'	=>	'member_id',
											'value' 	=>	$member_id
										);
				
				$data['fields'] = array (
											'photo_file_name' => $data['info']['file_name'], 
											'raw_name'	=> $data['info']['raw_name'],
											'file_ext'	=> $data['info']['file_ext'],
											'file_type'	=> $data['info']['file_type'],
											'original_file_name' => $data['info']['orig_name'],
											'image_resized'	=>	$this->config->item('sts_affiliate_image_auto_resize')
										);			
				
				$this->uploads_model->_update_image_db($data);
				
				if ($member_data[0]['photo_file_name'])
				{
					@unlink('./images/' . $this->config->item('images_members_dir') . '/' . $member_data[0]['photo_file_name']);
					
					if ($this->config->item('sts_affiliate_image_auto_resize') == 1)
					{
						@unlink('./images/' . $this->config->item('images_members_dir') . '/' . $member_data[0]['raw_name'] . '_jrox' . $member_data[0]['file_ext']);
					}
				}
			}
			
			$this->session->set_flashdata('success', $this->lang->line('image_uploaded_successfully'));

			redirect(admin_url() . 'members/update_member/' . $member_id . '#fragment-6');		
		}
		else
		{
			$this->session->set_flashdata('error', $data['msg']);
			
			redirect(admin_url() . 'members/update_member/' . (int)($this->input->post('member_id')) . '#fragment-6');		
		}	
	}

	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/

	// ------------------------------------------------------------------------	
	
	function _run_member_modules($type = '', $data = '')
	{
		$this->load->model('modules_model');
		
		$sdata = $this->modules_model->_run_modules($type, 'admin');
		
		if (!empty($sdata))
		{
			$this->config->load('api');
			
			if ($this->config->item('module_api_' . $type . '_models'))
			{
				$api_models = explode(',', $this->config->item('module_api_' . $type . '_models'));
				
				foreach ($api_models as $api)
				{
					$this->load->model(trim($api . '_model'), $api . '_api');
				}
			}
			
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
				
				$function = '_run_module_' . $sdata[$i]['module_file_name'];
				
				$this->$sdata[$i]['module_file_name']->$function('admin', $data);	
			}
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_member_credit()
	{		
		$rules['amount'] = 'trim|required|numeric|callback__check_credit_amount';
		$rules['notes'] = 'trim|required|min_length[5]';
		
		$this->validation->set_rules($rules);

		$fields['amount'] = $this->lang->line('amount');
		$fields['notes'] = $this->lang->line('notes');
			
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;	
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_member($type = '', $data = '')
	{	
		$rules['fname'] = 'trim|required|min_length[2]|max_length[50]';
		$rules['lname'] = 'trim';
		
		if ($type == 'add')
		{
			$rules['facebook_id'] = 'trim|max_length[255]|callback__check_fb_add';
			$rules['username'] = 'trim|required|strtolower|min_length['.$this->config->item('member_min_username_length').']|max_length[20]|alpha_numeric|callback__check_username_add';
			$rules['primary_email'] = 'trim|required|strtolower|valid_email|callback__check_email_add';
			$rules['password'] = 'trim|required|min_length[6]|max_length[20]|matches[passconf]';
			$rules['passconf'] = 'trim|required';
			
			$rules['sponsor'] = 'trim|strtolower|min_length['.$this->config->item('member_min_username_length').']|max_length[20]|alpha_numeric|callback__check_sponsor_add';
			$rules['paypal_id'] = 'trim|strtolower|valid_email|max_length[255]|callback__check_paypal_id_add';
			$rules['moneybookers_id'] = 'trim|strtolower|max_length[255]|callback__check_moneybookers_id_add';
			$rules['payza_id'] = 'trim|strtolower|max_length[255]|callback__check_payza_id_add';
			$rules['custom_id'] = 'trim|strtolower|max_length[255]|callback__check_custom_id_add';
			$rules['coinbase_id'] = 'trim|strtolower|max_length[255]|callback__check_coinbase_id_add';
		}
		else 
		{
			$rules['facebook_id'] = 'trim|max_length[255]|callback__check_fb_update';
			$rules['username'] = 'trim|required|strtolower|min_length['.$this->config->item('member_min_username_length').']|max_length[20]|alpha_numeric|callback__check_username_update';
			$rules['primary_email'] = 'trim|required|strtolower|valid_email|callback__check_email_update';
			
			$rules['sponsor'] = 'trim|strtolower|min_length['.$this->config->item('member_min_username_length').']|max_length[20]|alpha_numeric|callback__check_sponsor_update';
			$rules['paypal_id'] = 'trim|strtolower|valid_email|max_length[255]|callback__check_paypal_id_update';
			$rules['safepay_id'] = 'trim|strtolower|max_length[255]|callback__check_safepay_id_update';
			$rules['moneybookers_id'] = 'trim|strtolower|max_length[255]|callback__check_moneybookers_id_update';
			$rules['payza_id'] = 'trim|strtolower|max_length[255]|callback__check_payza_id_update';
			$rules['custom_id'] = 'trim|strtolower|max_length[255]|callback__check_custom_id_update';
			$rules['coinbase_id'] = 'trim|strtolower|max_length[255]|callback__check_coinbase_id_update';
			
			if ($this->input->post('password'))
			{
				$rules['password'] = 'trim|required|min_length[6]|max_length[20]|matches[passconf]';
				$rules['passconf'] = 'trim|required';
			}
		}
		
		
		$rules['twitter_id'] = 'trim|max_length[255]';
		$rules['myspace_id'] = 'trim|max_length[255]';
		$rules['linkedin_id'] = 'trim|max_length[255]';
		$rules['billing_address_1'] =  'trim|max_length[255]';
		$rules['billing_address_2'] =  'trim|max_length[255]';
		$rules['billing_city'] = 'trim|max_length[255]';
		$rules['billing_state'] = 'trim|max_length[255]';
		$rules['billing_country'] = 'trim|integer';
		$rules['billing_postal_code'] = 'trim|max_length[255]';
		$rules['login_status'] = 'trim|integer';
		$fields['view_hidden_programs'] = 'trim|integer';
		$rules['home_phone'] = 'trim|max_length[100]';
		$rules['work_phone'] = 'trim|max_length[100]';
		$rules['mobile_phone'] = 'trim|max_length[100]';
		$rules['fax'] = 'trim|max_length[100]';
		

		
		$rules['payment_name'] = 'trim|max_length[100]';
		$rules['payment_address_1'] = 'trim|max_length[255]';
		$rules['payment_address_2'] = 'trim|max_length[255]';
		$rules['payment_city'] = 'trim|max_length[255]';
		$rules['payment_state'] = 'trim|max_length[255]';
		$rules['payment_country'] = 'trim|integer';
		$rules['payment_postal_code'] = 'trim|max_length[255]';
		
		$rules['enable_custom_url'] = 'integer';
		
		if ($this->input->post('enable_custom_url') == 1)
		{
			$rules['custom_url_link'] = 'trim|prep_url|required|max_length[255]';
		}
		else
		{
			$rules['custom_url_link'] = 'trim|prep_url|max_length[255]';
		}
		$rules['alert_new_commission'] = 'integer';
		$rules['alert_downline_signup'] = 'integer';
		$rules['alert_payment_sent'] = 'integer';
		$rules['allow_downline_view'] = 'integer';
		$rules['allow_downline_email'] = 'integer';
		$rules['payment_preference_amount'] = 'numeric';
		$rules['website'] = 'trim|prep_url|max_length[255]';
		$rules['bank_transfer'] = 'trim';	

		for($i=1; $i<=20;$i++)
		{
			$rules['program_custom_field_'.$i] = 'trim';
		}
	
		$this->validation->set_rules($rules);

		$fields['status'] = $this->lang->line('status');
		$fields['fname'] = $this->lang->line('first_name');
		$fields['lname'] = $this->lang->line('last_name');
		$fields['company'] = $this->lang->line('company');
		$fields['username'] = $this->lang->line('username');
		$fields['password'] = $this->lang->line('password');
		$fields['passconf'] = $this->lang->line('confirm_password');
		$fields['primary_email'] = $this->lang->line('primary_email');
		$fields['billing_address_1'] = $this->lang->line('billing_address_1');
		$fields['billing_address_2'] = $this->lang->line('billing_address_2');
		$fields['billing_city'] = $this->lang->line('billing_city');
		$fields['billing_state'] = $this->lang->line('billing_state');
		$fields['billing_country'] = $this->lang->line('billing_country');
		$fields['billing_postal_code'] = $this->lang->line('billing_postal_code');
		$fields['home_phone'] = $this->lang->line('home_phone');
		$fields['work_phone'] = $this->lang->line('work_phone');
		$fields['mobile_phone'] = $this->lang->line('mobile_phone');	
		$fields['fax'] = $this->lang->line('fax');	
		$fields['login_status'] = $this->lang->line('login_status');
		$fields['view_hidden_programs'] = $this->lang->line('view_hidden_programs');
		$fields['send_welcome_email'] = $this->lang->line('send_welcome_email');
		$fields['payment_name'] = $this->lang->line('payment_name');
		$fields['payment_address_1'] = $this->lang->line('payment_address_1');
		$fields['payment_address_2'] = $this->lang->line('payment_address_2');
		$fields['payment_city'] = $this->lang->line('payment_city');
		$fields['payment_state'] = $this->lang->line('payment_state');
		$fields['payment_country'] = $this->lang->line('payment_country');
		$fields['payment_postal_code'] = $this->lang->line('payment_postal_code');
		$fields['enable_affiliate_marketing'] = $this->lang->line('enable_affiliate_marketing');
		$fields['enable_custom_url'] = $this->lang->line('enable_custom_url');
		$fields['custom_url_link'] = $this->lang->line('custom_url_link');
		$fields['alert_new_commission'] = $this->lang->line('alert_new_commission');
		$fields['alert_downline_signup'] = $this->lang->line('alert_downline_signup');
		$fields['alert_payment_sent'] = $this->lang->line('alert_payment_sent');
		$fields['allow_downline_view'] = $this->lang->line('allow_downline_view');
		$fields['allow_downline_email'] = $this->lang->line('allow_downline_email');
		$fields['payment_preference_amount'] = $this->lang->line('payment_preference_amount');
		$fields['sponsor'] = $this->lang->line('sponsor');
		$fields['website'] = $this->lang->line('website');
		$fields['paypal_id'] = $this->lang->line('paypal_id');
		$fields['moneybookers_id'] = $this->lang->line('moneybookers_id');
		
		$fields['facebook_id'] = $this->lang->line('facebook_id');
		$fields['twitter_id'] = $this->lang->line('twitter_id');
		$fields['myspace_id'] = $this->lang->line('myspace_id');
		$fields['linkedin_id'] = $this->lang->line('linkedin_id');
		
		$fields['payza_id'] = $this->lang->line('payza_id');
		$fields['coinbase_id'] = $this->lang->line('coinbase_id');
		$fields['custom_id'] = $this->lang->line('custom_id');
		$fields['bank_transfer'] = $this->lang->line('bank_transfer');
		
		for($i=1; $i<=20;$i++)
		{
			$fields['program_custom_field_'.$i] = $this->lang->line('program_custom_field_' . $i);
		}

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
		if ($this->db_validation_model->_validate_field('members', 'primary_email', $this->validation->primary_email))
		{
			$this->validation->set_message('_check_email_add', $this->lang->line('member_email_taken'));
			return false;
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_email_update()
	{
		if ($this->db_validation_model->_validate_field('members', 'primary_email', $this->validation->primary_email, 'member_id', $this->validation->member_id))
		{
			$this->validation->set_message('_check_email_update', $this->lang->line('member_email_taken'));
			return false;
		}
	}
	
	
	// ------------------------------------------------------------------------	
	
	
	function _check_fb_add()
	{
		if ($this->db_validation_model->_validate_field('members', 'facebook_id', $this->validation->facebook_id))
		{
			$this->validation->set_message('_check_fb_add', $this->lang->line('facebook_id_taken'));
			return false;
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_fb_update()
	{
		if ($this->db_validation_model->_validate_field('members', 'facebook_id', $this->validation->facebook_id, 'member_id', $this->validation->member_id))
		{
			$this->validation->set_message('_check_fb_update', $this->lang->line('facebook_id_taken'));
			return false;
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_sponsor_add()
	{
		if ($this->db_validation_model->_validate_field('members', 'username', $this->validation->sponsor))
		{	
			return true;
		}
		
		$this->validation->set_message('_check_sponsor_add', $this->lang->line('invalid_sponsor'));
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_sponsor_update()
	{
		if ($this->db_validation_model->_validate_field('members', 'username', $this->validation->sponsor, 'username', $this->validation->username))
		{
			return true;
		}
		
		$this->validation->set_message('_check_sponsor_update', $this->lang->line('invalid_sponsor'));
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_username_add()
	{
		if ($this->db_validation_model->_validate_field('members', 'username', $this->validation->username))
		{
			$this->validation->set_message('_check_username_add', $this->lang->line('member_username_taken'));
			return false;
		}
		elseif (check_subdomain($this->validation->username))
		{
			$this->validation->set_message('_check_username_add', $this->lang->line('member_username_taken'));
			return false;
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_username_update()
	{
		if (check_subdomain($this->validation->username))
		{
			$this->validation->set_message('_check_username_update', $this->lang->line('member_username_taken'));
			return false;
		}
		
		if ($this->db_validation_model->_validate_field('members', 'username', $this->validation->username, 'member_id', $this->validation->member_id))
		{
			$this->validation->set_message('_check_username_update', $this->lang->line('member_username_taken'));
			return false;
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_custom_ids($type = '', $id = '', $add = '', $callback = '', $lang = '')
	{
		if ($add == 'add')
		{		
			if ($this->db_validation_model->_validate_field('members', $type, $id))
			{
				$this->validation->set_message($callback, $this->lang->line($lang));
				return false;
			}
		}
		else
		{
			
			if ($this->db_validation_model->_validate_field('members', $type, $id, 'member_id', $this->validation->member_id))
			{
				$this->validation->set_message($callback, $this->lang->line($lang));
				return false;
			}
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_paypal_id_add()
	{
		return $this->_check_custom_ids('paypal_id', $this->validation->paypal_id, 'add', '_check_paypal_id_add', 'paypal_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_paypal_id_update()
	{
		return  $this->_check_custom_ids('paypal_id', $this->validation->paypal_id, 'update', '_check_paypal_id_update', 'paypal_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_moneybookers_id_add()
	{
		return  $this->_check_custom_ids('moneybookers_id', $this->validation->moneybookers_id, 'add', '_check_moneybookers_id_add', 'moneybookers_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_moneybookers_id_update()
	{
		return $this->_check_custom_ids('moneybookers_id', $this->validation->moneybookers_id, 'update', '_check_moneybookers_id_update', 'moneybookers_id_taken');
	}	
	
	function _check_payza_id_add()
	{
		return  $this->_check_custom_ids('payza_id', $this->validation->payza_id, 'add', '_check_payza_id_add', 'payza_id_taken');
	}	
	
	// ------------------------------------------------------------------------	
	
	function _check_payza_id_update()
	{
		return $this->_check_custom_ids('payza_id', $this->validation->payza_id, 'update', '_check_payza_id_update', 'payza_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_custom_id_add()
	{
		return $this->_check_custom_ids('custom_id', $this->validation->custom_id, 'add', '_check_custom_id_add', 'custom_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_custom_id_update()
	{
		return $this->_check_custom_ids('custom_id', $this->validation->custom_id, 'update', '_check_custom_id_update', 'custom_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_coinbase_id_add()
	{
		return $this->_check_custom_ids('', $this->validation->coinbase_id, 'add', '_check_coinbase_id_add', 'coinbase_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_coinbase_id_update()
	{
		return $this->_check_custom_ids('coinbase_id', $this->validation->coinbase_id, 'update', '_check_coinbase_id_update', 'coinbase_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_member_form_tables($options = array(), $default = false)
	{		
		$form_options_array = $this->db_validation_model->_get_details($options['form_name'], $options['row_fields']);
		
		if ($default == true)
		{
			$form[0] = array('group_id' => '',
							 'aff_group_name' => $this->lang->line('none'));
			$form_options_array = array_merge($form, $form_options_array);
		}
		
		$result = _generate_form_dropdown(	$options['form_type'], 
											$options['form_name'], 
											$form_options_array, 
											$options['form_selected'], 
											$options['form_options'], 
											$options['id'], 
											$options['name'],
											$options['selected']);

		return $result;
	}
	
	// ------------------------------------------------------------------------	
	
	function _send_member_email($row = '', $template = '')
	{
		$this->load->model('emailing_model');
		
		if ($this->emailing_model->_send_template_email('admin', $row, $template))
		{
			log_message('info', 'Member Login Details Sent to ' . $row['primary_email']);
		}
		else
		{
			show_error($this->lang->line('could_not_send_email') . '. ' . $this->lang->line('check_email_settings'));
				
			log_message('error', 'Could not send email to ' . $row['primary_email'] . '. Check email settings.');
		}
		
		return true;
	}
}
?>