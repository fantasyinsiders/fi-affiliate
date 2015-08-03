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
| FILENAME - follow_ups.php
| -------------------------------------------------------------------------     
| 
*/

class follow_ups extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('follow_ups_model');
		
		$this->load->model('mailing_lists_model', 'lists');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . 'mailing_lists/view_mailing_lists');
	}
	
	// ------------------------------------------------------------------------
	
	function view_follow_ups()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['mailing_list_id'] = (int)($this->uri->segment(8));
		
		$details = $this->lists->_get_mailing_list_details($data['mailing_list_id']);
		
		if (!empty($details))
		{
			$data['page_title'] = 'manage_follow_ups';
			
			$data['mailing_list_name'] = $details['mailing_list_name'];
			
			$data['follow_ups'] = $this->follow_ups_model->_get_follow_ups($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order'], $data['mailing_list_id']);
	
			$total_rows = $this->db_validation_model->_get_count('email_follow_ups', 'WHERE mailing_list_id=\'' . $data['mailing_list_id'] . '\'');

			$data['sort'] = array();
			
			for ($i=1; $i<=$total_rows;$i++)
			{
				$data['sort'][$i] = $i;	
			}
			
			$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'email_follow_ups', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column'], $total_rows, $data['mailing_list_id']);
			
			load_admin_tpl('admin', 'tpl_adm_manage_follow_ups', $data);	
		}
		else
		{
			redirect();	
		}
	}

	// ------------------------------------------------------------------------
	
	function add_follow_up()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		if ($this->_check_follow_up() == false)
		{		
			$this->validation->mailing_list_id = (int)($this->uri->segment(4));
			
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
			
			if (empty($_POST))
			{
				$this->validation->from_name = $data['sts_site_name'];
				$this->validation->from_email = 'noreply@' . $this->config->item('base_domain_name'); //$data['sts_site_email'];
			}
			
			$options = array(	'instance'	=> 'oEdit1',
									'type' => 'email',
									'content' => $this->validation->html_message,
									'height'	=> '400',
									'width'	=> '100%',
									'editor_type'	=> 'Default',
									'textarea'	=>	'html_message',
									'tags'	=> true,
								);
								
			$check_editor = _initialize_html_editor($options);
		
			$data['editor'] = $check_editor['editor'];
			$data['editor_path'] = $check_editor['editor_path'];
				
			load_admin_tpl('admin', 'tpl_adm_manage_follow_up', $data);
		}
		else
		{	
			$id = $this->follow_ups_model->_add_follow_up();		

			$this->session->set_flashdata('success', $this->lang->line('item_added_successfully'));
			
			redirect(admin_url() . 'follow_ups/update_follow_up/'. $id);	
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function delete_follow_up()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->follow_ups_model->_delete_follow_up((int)($this->uri->segment(4))))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . 'follow_up/view_follow_up/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
			}
		}
		
		redirect(admin_url() . 'follow_ups/view_follow_ups');
	}
	
	// ------------------------------------------------------------------------
	
	
	function update_follow_up()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->_check_follow_up() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
			
			if (empty($_POST))
			{
				$m = $this->follow_ups_model->_get_follow_up_details((int)$this->uri->segment(4));
				
				if (!empty($m))
				{	
					foreach ($m as $k => $v)
					{
						$this->validation->$k = $v;
					}
				}
				else
				{
					redirect(admin_url() . 'follow_ups/view_follow_ups');
					exit();
				}			
			}
			
			//initialize HTML editor
			$options = array(	'instance'	=> 'oEdit1',
								'type' => 'email',
								'content' => $this->validation->html_message,
								'height'	=> '400',
								'width'	=> '100%',
								'editor_type'	=> 'Default',
								'textarea'	=>	'html_message',
								'tags'	=> true,
							);
							
			$check_editor = _initialize_html_editor($options);
		
			$data['editor'] = $check_editor['editor'];
			
			$data['editor_path'] = $check_editor['editor_path'];
					
			load_admin_tpl('admin', 'tpl_adm_manage_follow_up', $data);	
		}
		else
		{	
			if ($this->follow_ups_model->_update_follow_up((int)$this->uri->segment(4)))
			{
				$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			}
			
			redirect($this->uri->uri_string());	
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function update_follow_ups()
	{	
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->_check_sequence() == true)
		{	
			$this->follow_ups_model->_change_follow_up_sequence($_POST);
			
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
		}
		else
		{
			$this->session->set_flashdata('error',  $this->validation->error_string);
		}

		redirect(admin_url() . 'follow_ups/view_follow_ups/0/0/0/mailing_list_id/' . (int)$this->input->post('mailing_list_id'));
	}
	
	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	
	// ------------------------------------------------------------------------	
	
	function _check_sequence()
	{
		$data = $this->db_validation_model->_clean_data($_POST);
	
		foreach ($data as $k => $v)
		{
			if (strstr($k, "sequence") == true) 
			{
				$rules[$k] = 'trim|required|numeric';
				$fields[$k] = $k ;
			}
			
			if (strstr($k, "days") == true) 
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
	
	function _check_follow_up()
	{	
		$rules['follow_up_name'] = 'trim|required|min_length[2]|max_length[50]';
		$rules['from_name'] = 'trim|required|min_length[2]|max_length[50]';
		$rules['from_email'] = 'trim|required|valid_email';
		$rules['email_subject'] = 'trim|required|min_length[2]|max_length[255]';
		
		$rules['email_type'] = 'trim|required';
		
		if ($this->input->post('email_type') == 'text')
		{
			$rules['text_message'] = 'trim|required';
		}
		else
		{
			$rules['html_message'] = 'trim|required|min_length[20]';
		}
		
		if ($this->config->item('function') == 'update_follow_up')
		{
			$rules['follow_up_id'] = 'trim|integer';
			$rules['mailing_list_id'] = 'trim|integer';	
		}
		
		$this->validation->set_rules($rules);

		$fields['follow_up_name'] = $this->lang->line('follow_up_name');
		$fields['from_name'] = $this->lang->line('from_name');
		$fields['from_email'] = $this->lang->line('from_email');
		$fields['email_subject'] = $this->lang->line('subject');
		$fields['email_type'] = $this->lang->line('email_type');
		$fields['html_message'] = $this->lang->line('html_message');
		$fields['text_message'] = $this->lang->line('text_message');
		
		if ($this->config->item('function') == 'update_follow_up')
		{
			$fields['follow_up_id'] = $this->lang->line('follow_up_id');
			$fields['mailing_list_id'] = $this->lang->line('mailing_list_id');
		}
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;	
	}
	
	// ------------------------------------------------------------------------
}
?>