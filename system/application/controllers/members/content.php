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
| FILENAME - content.php
| -------------------------------------------------------------------------     
| 
| This controller file is used to generate member only content pages
|
*/

class Content extends Member_Controller {

	function __construct()
	{
		parent::__construct();
		$this->config->set_item('css_body', 'jroxMembersContent');
		
		//load required models
		$this->load->model('content_model', 'content');
		
		//create pagination links		
		$this->config->set_item('uri', $this->config->slash_item('base_folder_path') . $this->config->slash_item('site_index_page') . $this->uri->segment(1));
		$this->config->set_item('uri_string', str_replace('/', ':', trim_slashes($this->uri->uri_string())));	
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(site_url('members/content/view'));
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
		
		$data['page_title'] = $this->lang->line('content');
		
		$sdata = $this->content->_get_members_content($data['prg_program_id'],  MEMBERS_CONTENT_PER_PAGE, $data['offset'], $data['sort_column'], $data['sort_order']);
		
		$data['rows'] = array();
		
		if (!empty($sdata['articles']))
		{
			foreach ($sdata['articles'] as $v)
			{
				if (!empty($v['drip_date']))
				{
					//check for drip content
					$days = $v['drip_date'] * 60 * 60 * 24;
					$now = _generate_timestamp() - $this->session->userdata('m_signup_date');
					
					if ($days > $now) continue;	
				}
				
				array_push($data['rows'], $v);
			}
		}

		$pagination = $this->db_validation_model->_set_pagination($data['uri'], 'content_articles', MEMBERS_CONTENT_PER_PAGE, 4, $data['sort_order'], $data['sort_column'],  $sdata['total_rows'], '', '', 'public');
		
		$data['pagination_rows'] = $pagination['rows'];
		
		$data['num_pages'] = $pagination['num_pages'];

		//load the template
		$this->parser->_JROX_load_view('tpl_members_content', 'members', $data);
	}
	
	// ------------------------------------------------------------------------
	
	function article()
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
		 
		//get the article
		$row = $this->content->_get_article_details((int)$this->uri->segment(4));

		if (empty($row)) redirect(); 

		$data['page_title'] = $row['content_title'];
		
		//check for drip
		if (!empty($row['drip_date']))
		{
			//check for drip content
			$days = $row['drip_date'] * 60 * 60 * 24;
			$now = _generate_timestamp() - $this->session->userdata('m_signup_date');
			
			if ($days > $now) show_error('invalid access');	
		}
		
		//check for permissions
		if ($row['enable_affiliate_group_permissions'] == 1)
		{
			$show_content = false;
			$row['show_comments'] = 0;
			//check for membership cookie
			if ($this->session->userdata('m_affiliate_group'))
			{
				//get affiliate groups
				$groups = $this->content->_get_affiliate_groups($row['article_id']);
				
				if (!empty($groups))
				{
					foreach ($groups as $g)
					{
						if ($this->session->userdata('m_affiliate_group') == $g['group_id']) 
						{ 
							$show_content = true;
						}
					}
				}
				
				if ($show_content == false) show_error('invalid access');
			}
		}
		
		foreach ($row as $k => $v)
		{	
			if ($k == 'content_body')
			{
				$v = $this->init->_parse_member_data(html_entity_decode($v, ENT_QUOTES, $this->config->item('charset')));
			}
			
			$data[$k] = $v;
		}
		
		//load the template
		$this->parser->_JROX_load_view('tpl_members_content_article', 'members', $data);
			
	}
	
	
}

?>