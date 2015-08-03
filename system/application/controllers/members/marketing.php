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
| FILENAME - marketing.php
| -------------------------------------------------------------------------     
| 
| This controller file handles marketing tools
|
*/

class Marketing extends Member_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('modules_model', 'modules');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(site_url('members/marketing/view'));
	}
	
	// ------------------------------------------------------------------------
	
	function view()
	{
		$this->init->_set_default_program($this->session->userdata('m_signup_link'));
		
		$data = $this->security_model->_load_config('members', __CLASS__,  __FUNCTION__);

		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);

		$data['languages'] = $sdata['languages'];

		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		$data['page_title'] = $this->lang->line('marketing_tools');

		$data['tools'] = $this->aff->_get_user_tools();

		$this->parser->_JROX_load_view('tpl_members_tools', 'members', $data);
	}
	
	// ------------------------------------------------------------------------
	
	function add()
	{
		$this->init->_set_default_program($this->session->userdata('m_signup_link'));
		
		$data = $this->security_model->_load_config('members', __CLASS__,  __FUNCTION__);

		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);

		$data['languages'] = $sdata['languages'];

		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		$this->load->model('modules/module_affiliate_marketing_' . $this->uri->segment(4) . '_model', 'tools');

		$mdata = $this->tools->_initialize($this->uri->segment(5), 'add');
		
		foreach ($mdata as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		$data['page_title'] = $this->lang->line($data['page_header']);
		$data['module_file_name'] = $this->uri->segment(4);
		$data['module_id'] = $this->uri->segment(5);
		
		
		if ($this->tools->$mdata['check_add_function']($_POST) == false)
		{
			if (!empty($_POST))
			{
				$data['error' ] =  $this->validation->error_string;	
			}

			//load the template
			$this->parser->_JROX_load_view($mdata['template'], 'members', $data);
		}
		else
		{
			$id = $this->tools->$mdata['add_function']($_POST, $this->session->userdata('userid'));
			if (!empty($id))
			{
				$this->session->set_flashdata('success', $this->lang->line('add_tool_success'));
			
				redirect_301(site_url('members') . '/marketing/edit/' . $this->uri->segment(4) . '/' . $id . '/' . $data['module_id'], true, false);
				
				//check if we have to do something (post add)
				
				if ($mdata['post_add_function'])
				{
					$this->tools->$mdata['post_add_function']($_POST, $id);	
				}
			}
			else
			{
				show_error($this->lang->line('could_not_add_tool'));	
			}
		}
		
	}
	
	// ------------------------------------------------------------------------
	
	function edit()
	{
		$this->init->_set_default_program($this->session->userdata('m_signup_link'));
		
		//set data array
		$data = $this->security_model->_load_config('members', __CLASS__,  __FUNCTION__);

		//initialize site with required db config
		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		//set up the languages array
		$data['languages'] = $sdata['languages'];
		
		//set referral info
		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		$data['id'] = (int)$this->uri->segment(5);
		$data['module_id'] = $this->uri->segment(6);
		
		//load the model
		$this->load->model('modules/module_affiliate_marketing_' . $this->uri->segment(4) . '_model', 'tools');
		
		$mdata = $this->tools->_initialize($this->uri->segment(6), 'edit', $data['id']);
		foreach ($mdata as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		$data['page_title'] = $this->lang->line($data['page_header']);
		$data['module_file_name'] = $this->uri->segment(4);
		
		if ($this->tools->$mdata['check_edit_function']($this->uri->segment(5), $_POST) == false)
		{
			$sdata = $this->tools->_get_tool_details($data['id'], $this->session->userdata('userid'));
				
			foreach ($sdata as $k => $v)
			{
				$this->validation->$k = $v;
			}
			
			if (!empty($_POST))
			{
				$data['error' ] = $this->validation->error_string;	
				
				foreach ($sdata as $k => $v)
				{
					$this->validation->$k = $v;
				}
			}
			
			$this->parser->_JROX_load_view($mdata['template'], 'members', $data);
		}
		else
		{
			$this->tools->$mdata['edit_function']($_POST, $this->uri->segment(5), $this->session->userdata('userid'));
			
			$this->session->set_flashdata('success', $this->lang->line('update_tool_success'));
			
			redirect_301(site_url('members') . '/marketing/edit/' . $this->uri->segment(4) . '/' . $this->uri->segment(5) . '/' . $this->uri->segment(6), true, false);
		}
	}
	
	// ------------------------------------------------------------------------
	
	function deactivate()
	{
		$this->init->_set_default_program($this->session->userdata('m_signup_link'));
		
		//set data array
		$data = $this->security_model->_load_config('members', __CLASS__,  __FUNCTION__);

		//initialize site with required db config
		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		//set up the languages array
		$data['languages'] = $sdata['languages'];
		
		//set referral info
		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		$data['id'] = (int)$this->uri->segment(5);
		
		//load the model
		$this->load->model('modules/module_affiliate_marketing_' . $this->uri->segment(4) . '_model', 'tools');
		
		//initialize the edit tools
		$mdata = $this->tools->_initialize('edit', $data['id']);
		foreach ($mdata as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		if ($this->tools->_deactivate($data['id'], $this->session->userdata('userid')))
		{
			$this->session->set_flashdata('success', $this->lang->line('deactivate_tool_success'));
		}
		
		redirect_301(site_url('members') . '/marketing/edit/' . $this->uri->segment(4) . '/' . $this->uri->segment(5), true, false);		
	}
	
	// ------------------------------------------------------------------------
	
	function preview()
	{
		$this->init->_set_default_program($this->session->userdata('m_signup_link'));

		$data = $this->security_model->_load_config('members', __CLASS__,  __FUNCTION__);

		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);

		$data['languages'] = $sdata['languages'];

		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		$data['page_title'] = $this->lang->line('preview');
		$data['module_file_name'] = $this->uri->segment(4);

		$this->load->model('modules/module_affiliate_marketing_' . $this->uri->segment(4) . '_model', 'tools');
		
		$sdata = $this->tools->_preview_code($this->uri->segment(5));
		
		$data['username'] = $this->session->userdata('m_username');
		$data['preview_title'] = $sdata['s_title'];
		$data['preview_code'] = $sdata['tool_code'];
		$data['id'] = $sdata['id'];

		echo $this->parser->_JROX_parse($sdata['template'], APPPATH . 'views/members', $data, true, true);
	}
	
	// ------------------------------------------------------------------------
	
	function print_file()
	{
		$this->init->_set_default_program($this->session->userdata('m_signup_link'));

		$data = $this->security_model->_load_config('members', __CLASS__,  __FUNCTION__);

		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);

		$data['languages'] = $sdata['languages'];

		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		$data['page_title'] = $this->lang->line('preview');
		$data['module_file_name'] = $this->uri->segment(4);

		$this->load->model('modules/module_affiliate_marketing_' . $this->uri->segment(4) . '_model', 'tools');
		
		$this->tools->_print_code($this->uri->segment(5));
	}
	
	// ------------------------------------------------------------------------
	
	function download()
	{
		$this->load->model('modules/module_affiliate_marketing_' . $this->uri->segment(4) . '_model', 'tools');
		
		$sdata = $this->tools->_download_tool($this->uri->segment(5));
	}
	
	// ------------------------------------------------------------------------
	
	function tools()
	{
		$this->init->_set_default_program($this->session->userdata('m_signup_link'));
		
		$data = $this->security_model->_load_config('members', __CLASS__,  __FUNCTION__);

		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		$data['languages'] = $sdata['languages'];

		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		$data['module_id'] = (int)$this->uri->segment(8);

		$module = $this->modules->_get_module_details($data['module_id']);

		$data['page_title'] = $module['module_name'];
		
		$data['module_file_name'] = $module['module_file_name'];
		
		$data['program_id'] =  $this->config->item('sts_site_showcase_multiple_programs') == 1 ? $this->uri->segment(10, '0') : $this->config->item('prg_program_id');
		
		$this->load->model('modules/module_affiliate_marketing_' . $data['module_file_name'] . '_model', 'tools');
		
		$sdata = $this->tools->_generate_tools($data['program_id'], $data['sts_affiliate_tools_per_page'], $data['offset'], $data['sort_column'], $data['sort_order'], $this->session->userdata('userid'), $data['num_options']);

		foreach ($sdata as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		$data['tools'] = $this->aff->_get_program_tools();
		
		if (!empty($sdata['use_pagination']))
		{
			$pagination = $this->db_validation_model->_set_pagination($data['uri'], $data['module_file_name'], $data['sts_affiliate_tools_per_page'], 4, $data['sort_order'], $data['sort_column'],  $sdata['row_count'], $data['where_column'], $data['show_where_value'], 'public', $data['where_column2'], $data['where_value2']);
		
			$data['pagination_rows'] = $pagination['rows'];
				
			$data['num_pages'] = $pagination['num_pages'];
		}
		
		
		
		$this->parser->_JROX_load_view($sdata['template'], 'members', $data);
	}
	
	// ------------------------------------------------------------------------
	
	function _generate_rotator_link($id = '', $type = '')
	{
		return _public_url() . 'rotator/' . $type . '/' . $id;
	}
}

?>