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
| FILENAME - faq_articles.php
| -------------------------------------------------------------------------     
| 
*/

class Faq_articles extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('faq_model', 'faq');
		
		$this->config->set_item('menu', 'w');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . 'faq_articles/view_faq_articles');
	}
	
	// ------------------------------------------------------------------------
	
	function view_faq_articles()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['faq_articles'] = $this->faq->_get_faq_articles($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'faq_articles', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column']);
		
		load_admin_tpl('admin', 'tpl_adm_manage_faq_articles', $data);	
	}
	
	// ------------------------------------------------------------------------
	
	function add_faq_article()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->_check_faq_article() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
				
			$data['disable_wysiwyg'] = _check_wysiwyg_disable('faq_articles-add_faq_article');		
			
			$options = array(	'instance'	=> 'oEdit1',
								'type' => 'email',
								'content' => '',
								'height'	=> '400',
								'width'	=> '100%',
								'editor_type'	=> 'full',
								'textarea'	=>	'content_body',
								'tags'	=> true,
							);
			$check_editor = _initialize_html_editor($options);
		
			$data['editor'] = $check_editor['editor'];
			
			$data['editor_path'] = $check_editor['editor_path'];

			load_admin_tpl('admin', 'tpl_adm_manage_faq_article', $data);		
		}
		else
		{	
			$id = $this->faq->_add_faq_article();
			
			if (!empty($id))
			{
				$this->session->set_flashdata('success', $this->lang->line('item_added_successfully'));
			}
			
			redirect(admin_url() . 'faq_articles/update_faq_article/'. $id);	
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function update_status()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		if ($this->db_validation_model->_change_status_field('faq_articles', 'article_id', (int)$this->uri->segment(4), 'status'))
		{
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . 'faq_articles/view_faq_articles/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
			}
		}
		
		redirect(admin_url() . 'faq_articles/view_faq_articles');	
	}
	
	// ------------------------------------------------------------------------
	
	function delete_faq_article()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->faq->_delete_faq_article((int)($this->uri->segment(4))))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . 'faq_articles/view_faq_articles/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
			}
		}
		
		redirect(admin_url() . 'faq_articles/view_faq_articles');	
	}
	
	// ------------------------------------------------------------------------
	
	
	function update_faq_article()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		$this->validation->id = (int)$this->uri->segment(4);
		
		if ($this->_check_faq_article() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
					
			if (empty($_POST))
			{
				
				$this->validation->content_body = html_entity_decode($this->validation->content_body);
				
				$m = $this->faq->_get_faq_article_details((int)$this->uri->segment(4));
				
				if (!empty($m))
				{	
					foreach ($m as $k => $v)
					{
						$this->validation->$k = $v;
					}
					
					$data['disable_wysiwyg'] = _check_wysiwyg_disable('faq_articles-update_faq_article-' . $this->uri->segment(4));		
						
					$options = array(	'instance'	=> 'oEdit1',
										'type' => 'email',
										'content' => $this->validation->content_body,
										'height'	=> '400',
										'width'	=> '100%',
										'editor_type'	=> 'Default',
										'textarea'	=>	'content_body',
										'tags'	=> true,
									);
					$check_editor = _initialize_html_editor($options);
				
					$data['editor'] = $check_editor['editor'];
					
					$data['editor_path'] = $check_editor['editor_path'];

				}
				
				load_admin_tpl('admin', 'tpl_adm_manage_faq_article', $data);
			}
	
		}
		else
		{	
			if ($this->faq->_update_faq_article((int)$this->uri->segment(4)))
			{
				$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			}
		}	
		
		redirect($this->uri->uri_string());		
	}
	
	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	// ------------------------------------------------------------------------	
	
	function _check_faq_article()
	{	
		$rules['status'] = 'trim';
		$rules['content_title'] = 'trim|required|min_length[3]|max_length[255]';
		$rules['content_body'] = 'trim|required|min_length[10]';
		$rules['program_id'] = 'trim|integer';
		
		$this->validation->set_rules($rules);

		$fields['status'] = $this->lang->line('status');
		$fields['content_title'] = $this->lang->line('faq_question');
		$fields['content_body'] =  $this->lang->line('content_body');
		$fields['program_id'] = $this->lang->line('program_name');
		
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