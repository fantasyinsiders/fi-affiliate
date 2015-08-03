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
| This controller file shows any programs made by the user
|
*/

class Programs extends Member_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->config->set_item('css_body', 'jroxMembersPrograms');
		
		$this->load->model('programs_model', 'programs');
		
		//create pagination links		
		$this->config->set_item('uri', $this->config->slash_item('base_folder_path') . $this->config->slash_item('site_index_page') . $this->uri->segment(1));
		$this->config->set_item('uri_string', str_replace('/', ':', trim_slashes($this->uri->uri_string())));
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(site_url('members/programs/view'));
	}
	
	// ------------------------------------------------------------------------
	
	function login()
	{
		if ($this->config->item('sts_site_showcase_multiple_programs') == 1) {	redirect(); }
		
		//check for user access
		if ($this->members_model->_check_member_program((int)$this->uri->segment(4), 'program_id') == false)  { redirect; }
		
		$this->load->model('programs_model');
		
		$prog = $this->programs_model->_get_program_basic('program_id' , (int)$this->uri->segment(4));
		
		if (!empty($prog))
		{
			$this->session->set_userdata('m_signup_link', $prog['signup_link']);
			$this->session->set_userdata('m_program_id', $prog['program_id']);
			$this->session->set_userdata('m_program_name', $prog['program_name']);
		}
		
		redirect_301(site_url('members') . '/programs/view', true,false);
	}
	
	// ------------------------------------------------------------------------
	
	function view()
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
		
		$data['page_title'] = $this->lang->line('programs');
		
		if ($this->config->item('sts_site_showcase_multiple_programs') == 0)
		{
			$sdata = $this->programs->_get_registered_programs($this->session->userdata('userid'),  MEMBERS_PROGRAMS_PER_PAGE, $data['offset'], $data['sort_column'], $data['sort_order']);
		}
		else
		{
			$sdata = $this->programs->_get_user_programs($this->session->userdata('userid'),  MEMBERS_PROGRAMS_PER_PAGE, $data['offset'], $data['sort_column'], $data['sort_order']);
		}
		
		if (!empty($sdata['programs']))
		{
			$data['rows'] = $sdata['programs'];
		}

		$pagination = $this->db_validation_model->_set_pagination($data['uri'], 'programs', MEMBERS_PROGRAMS_PER_PAGE, 4, $data['sort_order'], $data['sort_column'],  $sdata['total_rows'], '', '', 'public');
		
		$data['pagination_rows'] = $pagination['rows'];
		
		$data['num_pages'] = $pagination['num_pages'];
		
		if ($this->config->item('sts_site_showcase_multiple_programs') == 0)
		{
			//load the template
			$this->parser->_JROX_load_view('tpl_members_registered_programs', 'members', $data);
		}
		else
		{
			//load the template
			$this->parser->_JROX_load_view('tpl_members_programs', 'members', $data);
		}
	}
}

?>