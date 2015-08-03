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
| FILENAME - module_affiliate_marketing_invisilinks.php
| -------------------------------------------------------------------------     
|
*/

class Module_Affiliate_Marketing_invisilinks extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('modules/module_affiliate_marketing_invisilinks_model', 'invisilinks_model');
		
		$this->config->set_item('menu', 'a');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect($this->uri->uri_string() . '/view');
	}
	
	// ------------------------------------------------------------------------
	
	function view()
	{ 
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = 'manage_invisilinks';
		
		$data['tools'] = $this->invisilinks_model->_get_invisilinks($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);
		
		$data['totals'] = $this->db_validation_model->_get_count('affiliate_invisilinks');
		
		$data['sort'] = array();
		
		for ($i=1; $i<=$data['totals'];$i++)
		{
			$data['sort'][$i] = $i;	
		}
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'affiliate_invisilinks', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column']);
		
		load_admin_tpl('modules', 'tpl_adm_manage_affiliate_marketing_invisilinks', $data);	
	}
	
	// ------------------------------------------------------------------------
	
	function add()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = 'add_invisilink';
			
		if ($this->_check_invisilink() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string;	
			}
			
			$programs = $this->programs_model->_get_all_programs();
			
			$data['programs'] = format_array($programs, 'program_id', 'program_name');
				
			load_admin_tpl('modules', 'tpl_adm_manage_affiliate_marketing_invisilink', $data);		
		}
		else
		{	
			$id = $this->invisilinks_model->_add_invisilink($_POST);		
			
			$this->session->set_flashdata('success', $this->lang->line('item_added_successfully'));

			redirect(modules_url()  . strtolower( __CLASS__) . '/edit/' . $id);
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function delete()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		if ($this->aff->_delete_tool('invisilink', (int)($this->uri->segment(4)), 'affiliate_invisilinks'))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(modules_url()  . strtolower( __CLASS__) . '/view/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
		}
		
		redirect(modules_url()  . strtolower( __CLASS__) . '/view');
	}
	
	// ------------------------------------------------------------------------
	
	function edit()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		$this->validation->id = (int)$this->uri->segment(4);
		
		$data['page_title'] = 'update_invisilink';
		
		if ($this->_check_invisilink() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string;	
			}
			else
			{
				$m = $this->invisilinks_model->_get_invisilink_details((int)$this->uri->segment(4));
				
				if (!empty($m))
				{	
					foreach ($m as $k => $v)
					{
						$this->validation->$k = $v;
						
						if ($k == 'member_id')
						{
							$sponsor_array = $this->db_validation_model->_get_details('members', 'username', 'member_id', $v);
						
							$this->validation->$k =  $sponsor_array[0]['username'];
						}
					}
				}
				else
				{
					redirect(admin_url());
				}
			}
			
			$programs = $this->programs_model->_get_all_programs();
			
			$data['programs'] = format_array($programs, 'program_id', 'program_name');
			
			load_admin_tpl('modules', 'tpl_adm_manage_affiliate_marketing_invisilink', $data);
		}
		else
		{	
			$id = $this->invisilinks_model->_edit_invisilink($this->validation->id, $_POST);		
				
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			redirect($this->uri->uri_string());
			
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function update()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = 'update_module_options';
		
		if ($this->_check_invisilink_options() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] =  $this->validation->error_string;	
			}
			
			$m = $this->aff->_get_affiliate_marketing_details((int)$this->uri->segment(4));
			
			if (!empty($m))
			{	
				foreach ($m as $k => $v)
				{
					$this->validation->$k = $v;
				}
				
				$data['sts_config'] = array();
				
				foreach ($m['sts_config'] as  $v)
				{
					$this->validation->$v['settings_key'] = $v['settings_value'];
	
					array_push($data['sts_config'], $v);
				}
			}
			else
			{
				redirect();
			}
			
			load_admin_tpl('modules', 'tpl_adm_manage_affiliate_marketing_options', $data);
		}
		else
		{	
			$data = $this->modules_model->_update_options($_POST);	
			
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			$url = !empty($_POST['redirect']) ? $_POST['redirect'] : $this->uri->uri_string();
			
			redirect($url);
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function sort_order()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		if ($this->_check_sort_order() == true)
		{	
			$this->aff->_change_sort_order('affiliate_invisilinks');

			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
		}
		else
		{
			$this->session->set_flashdata('error', $this->validation->error_string);
		}
		
		redirect(modules_url()  . strtolower( __CLASS__) . '/view');
	}
	
	// ------------------------------------------------------------------------
	
	
	function change_status()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->db_validation_model->_change_status_field('affiliate_invisilinks', 'id', (int)$this->uri->segment(4), 'status'))
		{
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(modules_url()  . strtolower( __CLASS__) . '/view/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
		}
		
		redirect(modules_url()  . strtolower( __CLASS__) . '/view');
	}
	

	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	
	// ------------------------------------------------------------------------	
	
	function _check_invisilink_options()
	{
		$rules['module_affiliate_marketing_invisilinks_alert_email'] = 'trim|valid_email';
		
		$this->validation->set_rules($rules);
		
		$fields['module_affiliate_marketing_invisilinks_alert_email'] = $this->lang->line('email');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_member_owner()
	{
		if ($this->db_validation_model->_validate_field('members', 'username', $this->validation->member_id))
		{	
			return true;
		}
		
		$this->validation->set_message('_check_member_owner', $this->lang->line('invalid_member_username'));
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_invisilink_add()
	{
		if (!empty($_POST['invisilink_url']))
		{
			preg_match('@^(?:http://)?([^/]+)@i', $_POST['invisilink_url'], $matches);

			$this->validation->invisilink_url = $matches[1];
		}
		
		if ($this->db_validation_model->_validate_field('affiliate_invisilinks', 'invisilink_url', $this->validation->invisilink_url))
		{
			$this->validation->set_message('_check_invisilink_add', $this->lang->line('domain_name_taken'));
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_invisilink_update()
	{
		if (!empty($_POST['invisilink_url']))
		{
			preg_match('@^(?:http://)?([^/]+)@i', $_POST['invisilink_url'], $matches);

			$this->validation->invisilink_url = $matches[1];
		}
		
		if ($this->db_validation_model->_validate_field('affiliate_invisilinks', 'invisilink_url', $this->validation->invisilink_url, 'id', (int)$this->uri->segment(4)))
		{
			$this->validation->set_message('_check_invisilink_update', $this->lang->line('domain_name_taken'));
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_invisilink($type = '')
	{
		$rules['status'] = 'trim|required|integer';
		
		if ($type == 'add')
		{
			$rules['invisilink_url'] = 'trim|required|prep_url|callback__check_invisilink_add';
		}
		else
		{
			$rules['invisilink_url'] = 'trim|required|prep_url|callback__check_invisilink_update';
		}
		
		$rules['member_id'] = 'trim|required|strtolower|alpha_numeric|callback__check_member_owner';	
		
		$rules['notes'] = 'trim';
		
		$this->validation->set_rules($rules);

		$fields['status'] = $this->lang->line('status');
		$fields['invisilink_url'] = $this->lang->line('invisilink_url');
		$fields['member_id'] = $this->lang->line('username');
		
		$fields['notes'] = $this->lang->line('notes');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_link($link = '')
	{
		if (!empty($link))
		{
			preg_match('@^(?:http://)?([^/]+)@i', $link, $matches);
		
			$link = $matches[0];
		}
		
		return $link;
	}
	
	// ------------------------------------------------------------------------
	
	function _update_invisilink_image($type = '', $id = '', $file_name = '')
	{
		
		if ($type == 'update')
		{
			$this->db->where('id', $id);
			
			$query = $this->db->get('affiliate_invisilinks');
			
			$row = $query->result_array();
			
			if ($row[0]['invisilink_file_name'] != $file_name)
			{	
				@unlink('./images/' . $this->config->item('images_invisilinks_dir') . '/' . $row[0]['invisilink_file_name']);
		
			}	
		}
		
		$sdata['config'] = array(
							'table'	=>	'affiliate_invisilinks',
							'key'	=>	'id',
							'value' 	=>	$id,
							);
		
		$sdata['fields'] = array (
								'invisilink_file_name'	=> 	$file_name
								);			
		
		if ($this->uploads_model->_update_image_db($sdata))
		{
			return true;
		}
		
		return false;
	}
	
	
	// ------------------------------------------------------------------------	
	
	function _check_sort_order()
	{
		$data = $this->db_validation_model->_clean_data($_POST);
	
		foreach ($data as $k => $v)
		{
			if (strstr($k, "tool") == true) 
			{
				$rules[$k] = 'trim|required|numeric';
				
				$opt = explode('-', $k);
				
				$fields[$k] = $this->lang->line('invisilink_id'). ' ' .end($opt) ;
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
}
?>