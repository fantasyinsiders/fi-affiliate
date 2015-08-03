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
| FILENAME - search.php
| -------------------------------------------------------------------------     
| 
*/
class Search extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->library('convert');
		
		$this->load->model('search_model');
	}
	
	// ------------------------------------------------------------------------	
	
	function index()
	{
		redirect(admin_url());
	}
	
	// ------------------------------------------------------------------------	
	
	function general()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($_POST['search_term'] == 'enter search here')
		{
			redirect(admin_url());
		}
		elseif (empty($_POST['search_term']))
		{
			redirect(admin_url());
		}
		else
		{
			switch ($_POST['table'])
			{
				 
				case 'commissions':
				
					redirect(admin_url() . 'commissions/view_commissions/0/0/0/search/' .  $this->convert->AsciiToHex(base64_encode($_POST['search_term'])));
				
				break;
				
				case 'members':
				
					redirect(admin_url() . 'members/view_members/0/0/0/search/' .  $this->convert->AsciiToHex(base64_encode($_POST['search_term'])));
					
				break;

			}
		}
		
	}
	
	// ------------------------------------------------------------------------	
	
	function advanced()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = $this->lang->line('advanced_search');
		
		if (!empty($_POST))
		{
			if ($this->input->post('search_term', true))
			{				
				
				$data['post_data'] = $_POST;
				
				if ($this->input->post('result'))
				{
					if (!empty($_POST['search_fields_commissions']))
					{
						$data['post_data']['search_fields_commissions'] = unserialize(base64_decode($_POST['search_fields_commissions']));
					}
					
					if (!empty($_POST['search_fields_members']))
					{
						$data['post_data']['search_fields_members'] = unserialize(base64_decode($_POST['search_fields_members']));
					}
				}
				else
				{
					$data['post_data']['result'] = true;
				}
				
				$rows = $this->search_model->_advanced_search($this->input->post('table'), $data['post_data'], $data['offset']);
				
				$data['rows'] = $rows['rows'];
				
				$data['total_rows'] = $rows['total_rows'];
				
				$data['post_data']['next_offset'] = $data['offset'] + $this->input->post('rows');
				
				$data['post_data']['previous_offset'] = $data['offset'] - $this->input->post('rows');
				
				load_admin_tpl('admin', 'tpl_adm_search_results_' . $this->input->post('table'), $data);	
			}
			else
			{
				show_error($this->lang->line('no_search_term'));	
			}
		}
		else
		{
			$query = $this->db->query('SHOW columns FROM ' . $this->db->dbprefix('commissions'));
			
			$data['comm_columns'] = array();
			
			$invalid = array(	'recur', 'recurring_comm', 'parent_id', 'performance_paid'
								);
								
			foreach ($query->result_array() as $v)
			{
				if (!in_array($v['Field'], $invalid))
				{
					$data['comm_columns'][$v['Field']] = $this->lang->line($v['Field']);	
				}
			}
			
			$query = $this->db->query('SHOW columns FROM ' . $this->db->dbprefix('members'));
			
			$data['mem_columns'] = array();
			
			$invalid = array(	'password', 'last_login_date', 'confirm_id', 'sponsor_id', 'original_sponsor_id', 'performance_paid', 'updated_by', 'updated_on', 'signup_date',
									'alert_downline_signup', 'alert_new_commission', 'alert_payment_sent', 'allow_downline_view', 'allow_downline_email',
									'view_hidden_programs', 'enable_custom_url', 'billing_country,', 'payment_country', 'login_status',
								);
								
			foreach ($query->result_array() as $v)
			{	
				if (!in_array($v['Field'], $invalid))
				{
					$data['mem_columns'][$v['Field']] = $this->lang->line($v['Field']);	
				}
			}
			
			load_admin_tpl('admin', 'tpl_adm_advanced_search', $data);
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function ajax_members() 
	{		
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		$data['page_title'] = $this->lang->line('search');
		
		
		$q = xss_clean(strtolower(($this->uri->segment(4))));
		
		if (!$q) return;
		
		$this->db->select('username');
		$this->db->limit('10');
		$this->db->like('username',  $q); 
		
		$sponsors = $this->db->get('members');
		if ($sponsors->num_rows() > 0)
		{
			$sp = $sponsors->result_array();
			
			foreach ($sp as $v)
			{
				echo $v['username']."\n";
			}
		}
		
	}
	
	// ------------------------------------------------------------------------	
	
	function ajax_mailing_list() 
	{		
		//set data array
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		//set page title
		$data['page_title'] = $this->lang->line('search');
		
		
		$q = xss_clean(strtolower(($this->uri->segment(4))));
		
		if (!$q) return;
		
		$this->db->select('mailing_list_name');
		$this->db->limit('5');
		$this->db->like('mailing_list_name',  $q, 'after'); 
		
		$sponsors = $this->db->get('email_mailing_lists');
		if ($sponsors->num_rows() > 0)
		{
			$sp = $sponsors->result_array();
			
			foreach ($sp as $v)
			{
				echo $v['mailing_list_name']."\n";
			}
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function ajax_members2() 
	{		
		//set data array
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		//set page title
		$data['page_title'] = $this->lang->line('search');
		
		
		$q = xss_clean(strtolower(($this->uri->segment(4))));
		
		if (!$q) return;

		$this->db->limit('25');
		$this->db->like('content_title',  $q , 'after'); 
		
		$sponsors = $this->db->get('content_articles');
		if ($sponsors->num_rows() > 0)
		{
			$sp = $sponsors->result_array();
			
			foreach ($sp as $v)
			{
				echo '<h2>' . $v['content_title']."</h2><br />" . $v['content_body'];
			}
		}
		
	}
	
	// ------------------------------------------------------------------------	
}
?>