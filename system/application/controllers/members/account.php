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
| FILENAME - account.php
| -------------------------------------------------------------------------     
| 
| This controller file is used to manage a user's account details
|
*/

class Account extends Member_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->config->set_item('css_body', 'jroxMembersAccount');
		
		//load required models
		$this->load->model('init_model', 'init');	
		$this->load->model('forms_model', 'forms');

		$this->load->helper('country');
		
		//get countries
		$this->country_options = _load_countries_dropdown($countries = _load_countries_array(true));
	}
	 
	// ------------------------------------------------------------------------	
	
	function index()
	{
		$this->init->_set_default_program($this->session->userdata('m_signup_link'));

		$data = $this->security_model->_load_config('members', __CLASS__,  __FUNCTION__);

		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		$data['languages'] = $sdata['languages'];

		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		$data['form_fields'] = array();
		$this->form_fields = $this->forms->_get_registration_fields($data['prg_program_id']);
		
		$this->validation->set_error_delimiters('<label class="error">', '</label>');
		
		$this->validation->member_id = $this->session->userdata('userid');
		
		if ($this->_check_registration_data() == false)
		{	
			$s_billing_country = !$this->input->post('billing_country') ?  $data['sts_site_default_country'] : $this->input->post('billing_country');
			$s_payment_country = !$this->input->post('payment_country') ?  $data['sts_site_default_country'] : $this->input->post('payment_country');
			
			$mem_info = $this->members_model->_get_member_basic($this->session->userdata('userid'));
				
			if (!empty($mem_info))
			{
				//set referral info
				if (empty($_POST))
				{
					foreach ($mem_info as $key => $val)
					{
						$this->validation->$key = $val;
					}
				}
				
				$data['member_photo'] = '';
				
				//check if we are allowing photo uploads
				if ($this->config->item('sts_affiliate_allow_upload_photos') == 1)
				{
					$mem_photos = $this->members_model->_get_member_photos($this->session->userdata('userid'));
					
					if (!empty($mem_photos))
					{
						$data['member_photo'] = base_url('js') . 'images/members/' . $mem_photos[0]['photo_file_name'];
						$data['member_photo_raw_name'] = $mem_photos[0]['raw_name'];
					}
				}
			}	
			else
			{
				$this->session->sess_destroy();
				redirect();
			}
			
			
			//set the default countries
		
			foreach ($this->form_fields as $k => $v)
			{
				if ($k == 'show_tos') continue;
				
				$str = str_replace('enable_', '', $k);
				$required = $v == 1 ? 'required' : '';
				$fdata = array(
								'name'        => 	$str,
								'id'          =>	$str,
								'value'       => 	$this->validation->$str,
								'size'        => 	'50',
								'class'       => 	'form-control ' . $required,
								);
				
				$row = array(
								'required' 			=> 		$v == 1 ? '*' : '',
								'form_description'	=>		$this->lang->line($str),
								'form_name'	 		=>		$str,
							);
				
				switch ($k)
				{
					case 'enable_primary_email':
						
						$fdata['class'] = 'form-control required email';
						$row['form_field'] = form_input($fdata);
						
					break;
					
					case 'enable_billing_country':
						$country = !$this->validation->billing_country ?  $data['sts_site_default_country'] : $this->validation->billing_country;
						$attributes = 'id="' . $str . '" class="form-control"';
						$row['form_field'] = form_dropdown($str, $this->country_options, $country, $attributes);
						
					break;
					case 'enable_payment_country':	
						$country = !$this->validation->payment_country ?  $data['sts_site_default_country'] : $this->validation->payment_country;
						$attributes = 'id="' . $str . '" class="form-control"';
						$row['form_field'] = form_dropdown($str, $this->country_options,  $country, $attributes);
						
					break;
					
					case 'enable_bank_transfer':
						
						$fdata = array(
								'name'        => 	$str,
								'id'          =>	$str,
								'value'       => 	$this->validation->$str,
								'size'        => 	'50',
								'class'       => 	'form-control ' . $required,
								);
						
						$row['form_field'] = form_textarea($fdata);
					
					break;
					
					default:
					
						if ($k == 'enable_password')
						{
							$fdata = array(
								'name'        => 	$str,
								'id'          =>	$str,
								'value'       => 	'',
								'size'        => 	'50',
								'class'       => 	'form-control',
								);
							
							$row['form_field'] = form_password($fdata);
							$row['required'] = '';
						}
						else
						{
							$row['form_field'] = form_input($fdata);
						}
					break;
				}
				
				$error_str = $str . '_error';
				$row['form_field'] .= $this->validation->$error_str; 
				
				array_push($data['form_fields'], $row);
			}	
			
			if ($this->validation->error_string)
			{
				$data['error'] = $this->lang->line('view_form_errors');	
			}
			
			//set the meta tags
			$data['page_title'] = $data['prg_program_name'] . ' - ' . $this->lang->line('account_profile');
			
			$this->parser->_JROX_load_view('tpl_members_account', 'members', $data);
		}
		else
		{
			//update the member
			$userdata = $this->members_model->_update_member($this->session->userdata('userid'));	
			
			$this->session->set_flashdata('success', $this->lang->line('account_updated_successfully'));
			
			redirect(site_url('members/account'));
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function reset_password()
	{
		//set data array
		$data = $this->security_model->_load_config('members');
		
		if ($this->check_reset_password() == false)
		{		
			if ($this->validation->error_string)
			{
				$data['error'] = $this->validation->error_string;
			}
			
			$this->parser->_JROX_load_view('tpl_members_reset_password', 'members', $data);
		}
		else
		{
			//update the member
			$userdata = $this->members_model->_update_password($this->session->userdata('userid'));	
			
			$this->session->set_flashdata('success', $this->lang->line('account_updated_successfully'));
			
			redirect(site_url('members/account'));
		}
		
		
	}
	
	// ------------------------------------------------------------------------	
	
	function delete_photo()
	{
		//set data array
		$data = $this->security_model->_load_config('members');
		
		//check if user is deleting his photo only
		if ($this->session->userdata('userid') != $this->uri->segment(5)) { show_error('invalid_data'); }
		
		
		//initialize site with required db config
		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		$this->load->model('uploads_model');
		
		//delete the photo
		
		$pdata['config'] = array(
								'type'	=>	'delete',
								'table'	=>	'members_photos',
								'key'	=>	'raw_name',
								'value' 	=>	$this->uri->segment(4),
								'id'	=>	$this->uri->segment(5)
								);
			
		$pdata['fields'] = array (
								'member_id'	=> 	$this->uri->segment(5)
								);		
		
		if ($this->uploads_model->_delete_photo('members', $pdata))
		{	
			$this->session->set_flashdata('success', $this->lang->line('image_deleted_successfully'));
		}
		else
		{
			//set flash data
			$this->session->set_flashdata('error', $this->lang->line('could_not_delete_image'));
		}
		
		//redirect back to account area
		redirect(site_url('members/account'));
	}
	
	// ------------------------------------------------------------------------	
	
	function update_photo()
	{
		//set data array
		$data = $this->security_model->_load_config('members');
		
		//initialize site with required db config
		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		$this->load->model('uploads_model');
		
		//upload the photo
		$data = $this->uploads_model->_upload_photo('members', $this->config->item('sts_affiliate_image_auto_resize'));
		
		if ($data['success'])
		{
			$member_id = $this->session->userdata('userid');
		
			//get the admin data first
			$member_data = $this->db_validation_model->_get_details('members_photos', '', 'member_id', $member_id);
		
			if (empty($member_data))
			{
				//insert new photo into members photo table
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
				//update the members photo table with new photo
				
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
				
				//delete any old photo
				if ($member_data[0]['photo_file_name'])
				{
					@unlink('./images/' . $this->config->item('images_members_dir') . '/' . $member_data[0]['photo_file_name']);
					
					//delete thumbs
					if ($this->config->item('sts_affiliate_image_auto_resize') == 1)
					{
						@unlink('./images/' . $this->config->item('images_members_dir') . '/' . $member_data[0]['raw_name'] . '_jrox' . $member_data[0]['file_ext']);
					}
				}
			}
			
			$this->session->set_flashdata('success', $this->lang->line('image_uploaded_successfully'));

			
		}
		else
		{
			//set flash data
			$this->session->set_flashdata('error', $data['msg']);
		}	
		
		//redirect back to account area
		redirect(site_url('members/account'));	
	}
	
	// ------------------------------------------------------------------------	
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	
	// ------------------------------------------------------------------------	
	
	function check_reset_password()
	{
		$rules['password'] = 'trim|required|min_length[6]|max_length[20]|matches[passconf]';
		$rules['passconf'] = 'trim|required';
		
		$this->validation->set_rules($rules);
		
		$fields['password'] = $this->lang->line('password');
		$fields['passconf'] = $this->lang->line('confirm_password');
		
		$this->validation->set_fields($fields);
		
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_registration_data()
	{	
	
		foreach ($this->form_fields as $k => $v)
		{
			$str = str_replace('enable_', '', $k);
		
			switch ($str)
			{
				case 'fname':
				
					$rules[$str] = 'trim|min_length[2]';
					
				break;
				
				case 'primary_email':
					
					$rules[$str] = 'trim|strtolower|valid_email|callback__check_email_update';
					
				break;
				
				case 'password':
				
					$rules[$str] = 'trim|min_length[6]|max_length[25]|alpha_dash';
					
				break;
				
				case 'website':
				
					$rules[$str] = 'trim|prep_url';
				
				break;
				
				case 'show_tos':
					
					$rules[$str] = 'trim';
					
				break;
				
				case 'payment_preference_amount':
				
					$rules[$str] = 'trim|numeric';
				
				break;
				
				case 'facebook_id':
				case 'twitter_id':
				case 'myspace_id':
				case 'linkedin_id':
				case 'paypal_id':
				case 'payza_id':
				case 'custom_id':
				
					$rules[$str] = 'trim|callback__check_' . $str;
				
				break;
				
				default:
					
					$rules[$str] = 'trim';
					
				break;
			}
			
			if ($v == 1 && $str != 'password' && $str != 'username' && $str != 'show_tos') { $rules[$str] .= '|required'; }
				
			$fields[$str] = $this->lang->line($str);
		}
		
		$this->validation->set_rules($rules);
		$this->validation->set_fields($fields);
		
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------		
	
	function _check_email_update()
	{

		if ($this->security_model->_check_free_email_accounts($this->validation->primary_email) == true)
		{
			$this->validation->set_message('_check_email_update', $this->lang->line('free_email_accounts_not_allowed'));
			return false;
		}
		elseif ($this->db_validation_model->_validate_field('members', 'primary_email', $this->validation->primary_email, 'member_id', $this->validation->member_id))
		{
			$this->validation->set_message('_check_email_update', $this->lang->line('member_email_taken'));
			return false;
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_payza_id()
	{
		return $this->_check_custom_ids('payza_id', $this->validation->payza_id, '_check_payza_id', 'payza_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_custom_id()
	{
		return $this->_check_custom_ids('custom_id', $this->validation->custom_id, '_check_custom_id', 'custom_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_facebook_id()
	{
		return $this->_check_custom_ids('facebook_id', $this->validation->facebook_id, '_check_facebook_id', 'facebook_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_linkedin_id()
	{
		return $this->_check_custom_ids('linkedin_id', $this->validation->linkedin_id, '_check_linkedin_id', 'linkedin_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_myspace_id()
	{
		return $this->_check_custom_ids('myspace_id', $this->validation->myspace_id, '_check_myspace_id', 'myspace_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_moneybookers_id()
	{
		return $this->_check_custom_ids('moneybookers_id', $this->validation->moneybookers_id, '_check_moneybookers_id', 'moneybookers_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_twitter_id()
	{
		return $this->_check_custom_ids('twitter_id', $this->validation->twitter_id, '_check_twitter_id', 'twitter_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_paypal_id()
	{
		return $this->_check_custom_ids('paypal_id', $this->validation->paypal_id, '_check_paypal_id', 'paypal_id_taken');
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_custom_ids($type = '', $id = '', $callback = '', $lang = '')
	{
		if (empty($id))
		{
			$this->validation->set_message($callback, $this->lang->line($id) . ' ' .$this->lang->line('required'));
			return false;
		}
		
		if ($this->db_validation_model->_validate_field('members', $type, $id, 'member_id', $this->validation->member_id))
		{
			$this->validation->set_message($callback, $this->lang->line($lang));
			return false;
		}
		return true;
	}
}

?>