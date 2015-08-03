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
| FILENAME - content_articles.php
| -------------------------------------------------------------------------     
| 
*/

class Content_Articles extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('content_model', 'content');
		
		$this->load->model('programs_model', 'programs');
		
		$this->config->set_item('menu', 'w');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . 'content_articles/view_content_articles');
	}
	
	// ------------------------------------------------------------------------
	
	function view_content_articles()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		$data['content_articles'] = $this->content->_get_content_articles($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);
		
		$data['totals'] = $this->db_validation_model->_get_count('content_articles');
		
		$data['sort'] = array();
		
		for ($i=1; $i<=$data['totals'];$i++)
		{
			$data['sort'][$i] = $i;	
		}
		
		$this->load->model('groups_model');
		
		$aff_groups = $this->groups_model->_get_all_affiliate_groups();
		
		$data['affiliate_groups'] = format_array($aff_groups, 'group_id', 'aff_group_name');	
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'content_articles', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column']);
		
		load_admin_tpl('admin', 'tpl_adm_manage_content_articles', $data);	

	}
	
	// ------------------------------------------------------------------------
	
	function add_content_article()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		if ($this->_check_content_article() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
				
				$this->validation->date_published = _save_date($this->validation->date_published);
			}
			
			$this->load->model('groups_model');
			
			$aff_groups = $this->groups_model->_get_all_affiliate_groups();
			
			$data['aff_groups'] = format_array($aff_groups, 'group_id', 'aff_group_name');
			
			$data['article_to_groups'] = '';
			
			$data['disable_wysiwyg'] = _check_wysiwyg_disable('content_articles-add_content_article/');
				
			$options = array(	'instance'	=> 'oEdit1',
								'type' => 'email',
								'content' => $this->validation->content_body,
								'height'	=> '400',
								'width'	=> '100%',
								'editor_type'	=> 'full',
								'textarea'	=>	'content_body',
								'tags'	=> true,
							);
							
			$check_editor = _initialize_html_editor($options);
		
			$data['editor'] = $check_editor['editor'];
			
			$data['editor_path'] = $check_editor['editor_path'];
			
			load_admin_tpl('admin', 'tpl_adm_manage_content_article', $data);	
		}
		else
		{	
			$sdata = $this->content->_add_content_article($_POST);

			$sdata['url'] =  _format_url(CONTENT_ROUTE, $sdata['article_id'], $sdata['content_title']);
			
			if (!empty($sdata['article_id']))
			{
				$this->session->set_flashdata('success', $this->lang->line('item_added_successfully'));
				
				redirect(admin_url() . 'content_articles/update_content_article/'. $sdata['article_id']);	
			}
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function update_status()
	{

		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		if ($this->db_validation_model->_change_status_field('content_articles', 'article_id', (int)$this->uri->segment(4), 'status'))
		{
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
				
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . 'content_articles/view_content_articles/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
		}	
			
		redirect(admin_url() . 'content_articles/view_content_articles');	
	}
	
	// ------------------------------------------------------------------------
	
	function delete_content_article()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->content->_delete_content_article((int)($this->uri->segment(4))))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));
				
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . 'content_articles/view_content_articles/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
		}
		
		redirect(admin_url() . 'content_articles/view_content_articles');
	}
	
	// ------------------------------------------------------------------------
	
	function update_articles()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->input->post('change-status'))
		{			
			if ($this->input->post('change-status') == 'sort')
			{
				if ($this->_check_sort_order() == true)
				{	
					$this->content->_change_sort_order();
					
					$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
				}
				else
				{
					$this->session->set_flashdata('error', $this->validation->error_string);
				}
			}
			else
			{
				if (count($_POST['article']) > 0)
				{
					$this->content->_mass_update_articles($this->input->post('article'), $this->input->post('change-status'));
						   
					//set flash data
					$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
				}
			}
		}
				
		redirect($this->input->post('redirect'));
	}
	
	// ------------------------------------------------------------------------
	
	
	function update_content_article()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		$this->validation->id = (int)$this->uri->segment(4);
		
		if ($this->_check_content_article() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
				
				$this->validation->date_published = _save_date($this->validation->date_published);
			}
			else
			{
				$m = $this->content->_get_content_article_details((int)$this->uri->segment(4));
				
				if (!empty($m))
				{	
					foreach ($m as $k => $v)
					{
						$this->validation->$k = $v;
					}
				}
				else
				{
					redirect();
					exit();
				}			
			}
			
			
			$programs = $this->programs->_get_all_programs();
					
			$data['programs'] = format_array($programs, 'program_id', 'program_name');
		
			$this->load->model('groups_model');
			
			$aff_groups = $this->groups_model->_get_all_affiliate_groups();
			
			$data['aff_groups'] = format_array($aff_groups, 'group_id', 'aff_group_name');
			
			$data['article_to_groups'] = $this->content->_get_affiliate_groups((int)$this->uri->segment(4));
			
			$this->validation->content_body = html_entity_decode($this->validation->content_body);
			
			$u = _format_url(MEMBERS_CONTENT_ROUTE . '/' . CONTENT_ROUTE . '/article', (int)$this->uri->segment(4));
				
			$data['url'] ='<a href="' . $u . '" target="_blank" class="show-url">' . limit_chars($u, 120) . '</a>';					

			$data['disable_wysiwyg'] = _check_wysiwyg_disable('content_articles-update_content_article-' . $this->uri->segment(4));		
			
			$options = array(	'instance'	=> 'oEdit1',
								'type' => 'email',
								'content' => $this->validation->content_body,
								'height'	=> '400',
								'width'	=> '100%',
								'editor_type'	=> 'full',
								'textarea'	=>	'content_body',
								'tags'	=> true,
							);
			$check_editor = _initialize_html_editor($options);
		
			$data['editor'] = $check_editor['editor'];
			$data['editor_path'] = $check_editor['editor_path'];
					
			load_admin_tpl('admin', 'tpl_adm_manage_content_article', $data);
		}
		else
		{	
			$sdata = $this->content->_update_content_article((int)$this->uri->segment(4), $_POST);
			
			if (!empty($sdata))
			{			
				$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
				redirect($this->uri->uri_string());		
				
			}
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function update_wysiwyg()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->uri->segment(4) == 'disable')
		{
			$this->session->set_userdata('admin_disable_wysiwyg', true);	
		}
		else
		{
			$this->session->set_userdata('admin_disable_wysiwyg', false);	
		}
		
		$url = str_replace('-','/', $this->uri->segment(5));
		
		redirect_301(admin_url(). $url, true, false);
	}
	
	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	
	// ------------------------------------------------------------------------	
	
	function _check_sort_order()
	{
		$data = $this->db_validation_model->_clean_data($_POST);
	
		foreach ($data as $k => $v)
		{
			if (strstr($k, "article_id") == true) 
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
	
	function _check_date_published()
	{
		if ($this->validation->date_published)
		{
			$this->validation->set_message('_check_date_published', $this->lang->line('invalid_date_published_format'). ': ' . $this->validation->date_published);
			
			$a = explode('/', $this->validation->date_published);
			if (count($a) != 3)
			{
				return false;
			}
		}
		
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _enable_affiliate_group_permissions()
	{		
		if (empty($_POST['content_permissions']))
		{
			
			$this->validation->set_message('_enable_affiliate_group_permissions', $this->lang->line('affiliate_group_required'));
			return false;
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_content_article()
	{
		$rules['status'] = 'trim';		
		$rules['content_title'] = 'trim|required|min_length[3]|max_length[255]';
		$rules['date_published'] = 'trim|required|callback__check_date_published';
		$rules['content_body'] = 'trim|min_length[10]';
		$rules['program_id'] = 'trim|integer';
		
		if ($this->input->post('drip_date'))
		{
			$rules['drip_date'] = 'trim|integer';
		}
		
		if ($this->input->post('enable_affiliate_group_permissions') == 1)
		{
			$rules['enable_affiliate_group_permissions'] = 'trim|required|callback__enable_affiliate_group_permissions';
		}	
		
		$this->validation->set_rules($rules);

		$fields['status'] = $this->lang->line('status');
		$fields['content_title'] = $this->lang->line('title');
		$fields['content_body'] =  $this->lang->line('content_body');
		$fields['date_published'] = $this->lang->line('publish_date');
		$fields['content_permissions'] = $this->lang->line('restrict_access_to');
		$fields['program_id'] = $this->lang->line('program_name');
		$fields['drip_date'] = $this->lang->line('drip_date');
		$fields['enable_affiliate_group_permissions'] = $this->lang->line('enable_affiliate_group_permissions');
		
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