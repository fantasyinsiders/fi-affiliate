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
| FILENAME - affiliate_payments.php
| -------------------------------------------------------------------------     
| 
*/

class Affiliate_Payments extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('affiliate_payments_model', 'aff_payments');
		
		$this->load->helper('country');
		
		$this->config->set_item('menu', 'p');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . strtolower( __CLASS__) . '/view_affiliate_payments');
	}
	
	// ------------------------------------------------------------------------
	
	function view_affiliate_payments()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);		
	
		if ($this->uri->segment(4) && $this->uri->segment(5))
		{
			$member = $this->db_validation_model->_get_details('members', 'fname, lname', 'member_id', $this->uri->segment(5));
			
			$data['page_title'] = $this->lang->line('view_payments_to') . ' ' . $member[0]['fname'] . ' ' . $member[0]['lname'];
		}
		
		$data['affiliate_payments'] = $this->aff_payments->_get_payment_history($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order'], $data['where_column'], $data['where_value']);
		
		if (!empty($data['where_column']) && !empty($data['where_value']))
		{

			$sql = 'WHERE ' . $data['where_column'] .' = \'' . $data['where_value'] . '\'';	
			
			$data['total_rows'] = $this->db_validation_model->_get_count('affiliate_payments', $sql);
		}
		else
		{
			$data['total_rows'] = $this->db_validation_model->_get_count('affiliate_payments');
		}
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'affiliate_payments', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column'], $data['total_rows'], $data['where_column'], $data['where_value']);
		
		load_admin_tpl('admin', 'tpl_adm_manage_affiliate_payments', $data);	
	}
	
	// ------------------------------------------------------------------------
	
	function generate_affiliate_payments()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if (empty($_POST['user']))
		{
			show_error($this->lang->line('no_members_selected'));
		}
		
		if ($this->input->post('payment_type') == 'mark_as_paid')
		{
			foreach ($this->input->post('user') as $mid => $amount)
			{		
				$sdata = $this->aff_payments->_mark_commissions_paid($mid, $amount, $this->input->post('affiliate_note'));
			}
			
			if ($this->config->item('sts_affiliate_alert_payment_sent') == 1)
			{
				$this->load->model('emailing_model');
				
				foreach ($this->input->post('user') as $mid => $amount)
				{
					$member = $this->db_validation_model->_get_details('members', '*', 'member_id', $mid);
					
					if ($member[0]['alert_payment_sent'] != '0') 
					{
						$v = $member[0];
						
						$v['payment_amount'] = format_amounts($amount, $num_options);
						
						$v['affiliate_note'] = $this->input->post('affiliate_note');
						
						$this->emailing_model->_send_template_email('member', $v, 'member_affiliate_payment_sent_template', true); //queue it!
					}	
				}
			}
			
			$this->session->set_flashdata('success', $this->lang->line('commissions_marked_as_paid_successfully'));

			redirect(modules_url() . 'module_affiliate_payment_' . $this->input->post('module') . '/select_users');
		}
		else
		{
			$vpath = 'modules/module_affiliate_payment_' . $this->input->post('payment_type') . '_model';
			
			if (file_exists(APPPATH . 'models/' . $vpath.'.php'))
			{				
				$this->load->model($vpath, 'payment_model');
				
				$members = array();
				
				foreach ($this->input->post('user') as $mid => $amount)
				{
					
					$member = $this->db_validation_model->_get_details('members', '*', 'member_id', $mid);
			
					$v = $member[0];

					$v['payment_amount'] = format_amounts($amount, $data['num_options'], true);
					
					$v['affiliate_note'] = $this->input->post('affiliate_note');
					
					$v['num_options'] =  $data['num_options'];
					
					array_push($members, $v);
				}
				
				$this->payment_model->_generate_payments($members);
			}
		}
	}
	
	// ------------------------------------------------------------------------
	
	function view_payment_options()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['modules'] = $this->aff_payments->_get_affiliate_payment_options($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);

		$data['total'] = $this->db_validation_model->_get_count('modules', 'WHERE module_type = \'affiliate_payment\'');
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'modules', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column'], $data['total']);
		
		load_admin_tpl('admin', 'tpl_adm_select_payments', $data);	
	}
	
	// ------------------------------------------------------------------------
	
	function make_affiliate_payments()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		$row = $this->aff_payments->_get_affiliate_payments($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);
		
		$data['payment_options'] = $this->aff_payments->_get_payment_options();
		
		$data['affiliate_payments'] = $row['payments'];
		
		$data['total_rows'] = $row['total_rows'];	
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'affiliate_payments', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column'], $data['total_rows'], $data['where_column'], $data['where_value']);
		
		load_admin_tpl('admin', 'tpl_adm_make_affiliate_payments', $data);	

	}
	
	// ------------------------------------------------------------------------
	
	function delete_affiliate_payment()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->aff_payments->_delete_payment((int)($this->uri->segment(4))))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . strtolower( __CLASS__) . '/view_affiliate_payments/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
		}
		
		redirect(admin_url() . strtolower( __CLASS__) . '/view_affiliate_payments');
	}
	
	// ------------------------------------------------------------------------
	
	
	function update_affiliate_payment()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->_check_affiliate_payment() == false)
		{	
			$data['payment_options'] = $this->aff_payments->_get_payment_options();
			 
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}	
			else
			{
				$m = $this->aff_payments->_get_affiliate_payment_details((int)$this->uri->segment(4));
				
				if (!empty($m))
				{	
					foreach ($m as $k => $v)
					{
						$this->validation->$k = $v;
					}
					
					$this->validation->payment_date =  date($data['format_date2'], $this->validation->payment_date);
					
				}
				else
				{
					redirect(admin_url() . strtolower( __CLASS__) . '/view_affiliate_payments');
					
					exit();
				}
			}
			
			load_admin_tpl('admin', 'tpl_adm_manage_affiliate_payment', $data);	
		}
		else
		{	
			
			$data = $this->aff_payments->_update_payment((int)$this->uri->segment(4));	
			
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
				
			redirect($this->uri->uri_string());				
			
		}		
	}
	
	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	
	// ------------------------------------------------------------------------
	
	function _check_username()
	{
		if ($this->db_validation_model->_validate_field('members', 'username', $this->validation->username) == false)
		{
			$this->validation->set_message('_check_username', $this->lang->line('invalid_referring_affiliate'));
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	function _check_affiliate_payment()
	{
		$rules['payment_date'] = 'trim|required';
		$rules['payment_amount'] = 'trim|required|numeric';
		
		$rules['username'] = 'trim|required|callback__check_username';
		$rules['payment_details'] = 'trim';
		
		$this->validation->set_rules($rules);
		
		$fields['payment_date'] = $this->lang->line('date');
		$fields['payment_amount'] = $this->lang->line('payment_amount');
		
		$fields['username'] = $this->lang->line('username');
		$fields['payment_details'] = $this->lang->line('payment_details');
		
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