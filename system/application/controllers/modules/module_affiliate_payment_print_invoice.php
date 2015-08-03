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
| FILENAME - module_affiliate_payment_print_invoice.php
| -------------------------------------------------------------------------     
|
*/
class Module_Affiliate_Payment_Print_Invoice extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('modules/' . strtolower(__CLASS__) . '_model', 'module_payments');
		
		$this->load->model('affiliate_payments_model', 'aff_payments');
		
		$this->load->helper('country');
		
		$this->config->set_item('menu', 'p');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect($this->uri->uri_string() . '/view/');
	}
	
	// ------------------------------------------------------------------------
	
	function select_users()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$start = '';
		$end = '';
		
		$data['module_name'] = 'print_invoice';
		$data['payment_id'] = 'print_invoice_id';
		$data['exclude_minimum'] = $data['module_affiliate_payment_print_invoice_exclude_minimum'];
		
		if ($data['module_affiliate_payment_print_invoice_use_date_range'] == 1)
		{
			$start = $data['module_affiliate_payment_print_invoice_start_date'];
			$end = $data['module_affiliate_payment_print_invoice_end_date'];
		}
		
		$row = $this->module_payments->_get_affiliate_payments($data['module_affiliate_payment_print_invoice_total_rows'], $data['offset'], $data['sort_column'], $data['sort_order'], $start, $end);
		
		$data['affiliate_payments'] = $row['payments'];
		
		load_admin_tpl('modules', 'tpl_adm_manage_affiliate_payment_print_invoice', $data);	
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
				$sdata = $this->aff_payments->_mark_commissions_paid($mid, $amount, $data['module_affiliate_payment_print_invoice_payment_details']);
			}
			
			if ($this->config->item('sts_affiliate_alert_payment_sent') == 1)
			{				
				foreach ($this->input->post('user') as $mid => $amount)
				{
					$member = $this->db_validation_model->_get_details('members', '*', 'member_id', $mid);
					
					if ($member[0]['alert_payment_sent'] != '0') 
					{
						$v = $member[0];
						
						$v['payment_amount'] = format_amounts($amount, $data['num_options']);
						
						$v['affiliate_note'] =  $data['module_affiliate_payment_print_invoice_payment_details'];
						
						$this->emailing_model->_send_template_email('member', $v, 'member_affiliate_payment_sent_template', true); //queue it!
					}	
				}
			}
			
			$this->session->set_flashdata('success', $this->lang->line('commissions_marked_as_paid_successfully'));

			redirect(modules_url() . strtolower(__CLASS__) . '/select_users');
		}
		else
		{							
			$members = array();
			
			foreach ($this->input->post('user') as $mid => $amount)
			{
				
				$member = $this->db_validation_model->_get_details('members', '*', 'member_id', $mid);
		
				$v = $member[0];

				$v['payment_amount'] = format_amounts($amount, $data['num_options'], true);
				
				$v['affiliate_note'] = $data['module_affiliate_payment_print_invoice_payment_details'];
				
				$v['num_options'] =  $data['num_options'];
				
				array_push($members, $v);
			}
			
			$this->module_payments->_generate_payments($members);
		}
	}
	
	// ------------------------------------------------------------------------
	
	function update()
	{ 
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->_check_options() == false)
		{	
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
					
			$m = $this->modules_model->_get_module_options((int)$this->uri->segment(4), 'affiliate_payment');
			
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
			
			load_admin_tpl('modules', 'tpl_adm_manage_affiliate_payment_print_invoice_options', $data);
		}
		else
		{		
			$_POST['module_affiliate_payment_print_invoice_start_date'] = _save_date($_POST['module_affiliate_payment_print_invoice_start_date'], false, 'min');
			
			$_POST['module_affiliate_payment_print_invoice_end_date'] = _save_date($_POST['module_affiliate_payment_print_invoice_end_date'], false, 'max');
			
			$data = $this->modules_model->_update_options($_POST);
			
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			$url = !empty($_POST['redirect']) ? $_POST['redirect'] : modules_url() .  strtolower(__CLASS__) . '/select_users';
			
			redirect($url);
		}
	}
	
	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	
	// ------------------------------------------------------------------------	
	
	function _check_users()
	{
		if(!empty($_POST['user']))
		{
			return true;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_options()
	{
		$rules['module_affiliate_payment_print_invoice_use_date_range'] = 'trim';
		$rules['module_affiliate_payment_print_invoice_exclude_minimum'] = 'trim';
		$rules['module_affiliate_payment_print_invoice_payment_details'] = 'trim';
		$rules['module_affiliate_payment_print_invoice_total_rows'] = 'trim|required|integer';
		
		if ($this->input->post('module_affiliate_payment_print_invoice_use_date_range') == 1)
		{
			$rules['module_affiliate_payment_print_invoice_start_date'] = 'trim|required';
			$rules['module_affiliate_payment_print_invoice_end_date'] = 'trim|required';
		}
		else
		{
			$rules['module_affiliate_payment_print_invoice_start_date'] = 'trim';
			$rules['module_affiliate_payment_print_invoice_end_date'] = 'trim';	
		}
		
		$this->validation->set_rules($rules);
		
		$fields['module_affiliate_payment_print_invoice_start_date'] = $this->lang->line('start_date');
		$fields['module_affiliate_payment_print_invoice_end_date'] = $this->lang->line('end_date');
		$fields['module_affiliate_payment_print_invoice_use_date_range'] = $this->lang->line('use_date_range');
		$fields['module_affiliate_payment_print_invoice_exclude_minimum'] = $this->lang->line('exclude_minimum_amount');
		$fields['module_affiliate_payment_print_invoice_payment_details'] = $this->lang->line('payment_note');
		$fields['module_affiliate_payment_print_invoice_total_rows'] = $this->lang->line('total_rows');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
	}
}
?>