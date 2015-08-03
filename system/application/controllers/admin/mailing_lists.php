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
| FILENAME - mailing_lists.php
| -------------------------------------------------------------------------     
|
*/

class Mailing_Lists extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('mailing_lists_model');
		
		$this->config->set_item('menu', 'e');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . 'mailing_lists/view_mailing_lists');
	}
	
	// ------------------------------------------------------------------------
	
	function add_mailing_list()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$this->validation->mailing_list_id = '';
		
		if ($this->_check_mailing_list() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
			
			load_admin_tpl('admin', 'tpl_adm_manage_mailing_list', $data);
		}
		else
		{	
			if ($this->mailing_lists_model->_add_mailing_list($_POST))
			{
				$this->session->set_flashdata('success', $this->lang->line('item_added_successfully'));
			}
			
			redirect(admin_url() . 'mailing_lists/view_mailing_lists');
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function delete_mailing_list()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->mailing_lists_model->_delete_mailing_list((int)($this->uri->segment(4))))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . 'mailing_lists/view_mailing_lists/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
		}
		
		redirect(admin_url() . 'mailing_lists/view_mailing_lists');
	}
	
	// ------------------------------------------------------------------------
	
	function view_list_members()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['mailing_list_id'] = (int)($this->uri->segment(8));
		
		$details = $this->db_validation_model->_get_details('email_mailing_lists', '', 'mailing_list_id', $data['mailing_list_id']);
		
		$name = $details[0]['mailing_list_name'];
		
		$data['page_title'] = $this->lang->line('view_list_members'). ' - ' . $name;

		$data['list_members'] = $this->mailing_lists_model->_get_list_members($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order'], $data['mailing_list_id']);	
		
		$total_follow_ups = $this->db_validation_model->_get_details('email_follow_ups', 'sequence, follow_up_id, follow_up_name', 'mailing_list_id', $data['mailing_list_id'], 'sequence', 'ASC');
		
		$data['total_follow_ups'] = array('1' => '1');
		
		if (!empty($total_follow_ups))
		{
			$j = 0; 
			for ($i=1; $i<=count($total_follow_ups); $i++)
			{
				$data['total_follow_ups'][$i] = $i . ' - ' . limit_chars($total_follow_ups[$j]['follow_up_name'],25);
				$j++;
			}
		}
		
		$total_rows = $this->db_validation_model->_get_count('email_mailing_list_members', 'WHERE mailing_list_id=\'' .  $data['mailing_list_id'] . '\'');

		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'email_mailing_lists', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column'], $total_rows, $data['where_column'], $data['show_where_value'], 'admin', $data['where_column2'], $data['where_value2']);
		
		load_admin_tpl('admin', 'tpl_adm_manage_list_members', $data);	
	}
	
	// ------------------------------------------------------------------------
	
	function view_mailing_lists()
	{

		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if (!empty($_POST['mailing_lists']))
		{
			$this->load->library('convert');
			
			$lists = $this->convert->AsciiToHex(base64_encode(implode(',',$_POST['mailing_lists'])));
		
			redirect(admin_url() . 'email_send/mailing_list/' . $lists);
		}
		else
		{
			$data['template_options'] = $this->mailing_lists_model->_get_custom_templates();
			
			$data['mailing_lists'] = $this->mailing_lists_model->_get_mailing_lists($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);
			
			$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'email_mailing_lists', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column']);
			
			load_admin_tpl('admin', 'tpl_adm_manage_mailing_lists', $data);
		}
	}
	
	// ------------------------------------------------------------------------
	
	function update_mailing_list()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		if ($this->_check_mailing_list() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
			
			if (empty($_POST))
			{
				$m = $this->mailing_lists_model->_get_mailing_list_details((int)$this->uri->segment(4));
				
				if (!empty($m))
				{	
					foreach ($m as $k => $v)
					{
						$this->validation->$k = $v;
					}
				}
				else
				{
					redirect(admin_url() . 'mailing_lists/view_mailing_lists');
					exit();
				}
			}
			
			load_admin_tpl('admin', 'tpl_adm_manage_mailing_list', $data);
		}
		else
		{	
			if ($this->mailing_lists_model->_update_mailing_list((int)$this->uri->segment(4), $_POST))
			{
				$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			}
			
			redirect(admin_url() . 'mailing_lists/view_mailing_lists');
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function update_list_members()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['mailing_list_id'] = (int)($this->uri->segment(4));
	
		if ($this->_check_sequence() == true)
		{	
			$this->mailing_lists_model->_change_follow_up_sequence($data['mailing_list_id']);
		
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
		}
		else
		{
			$this->session->set_flashdata('error', $this->validation->error_string);
		}
		
		redirect($this->input->post('redirect'));
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
	
	function _check_mailing_list()
	{		
		$rules['mailing_list_name'] = 'trim|required|min_length[2]|max_length[50]';

		$this->validation->set_rules($rules);

		$fields['mailing_list_name'] = $this->lang->line('mailing_list_name');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
		
	}
}
?>