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
| FILENAME - Module_Affiliate_Payment_coinbase_Payment.php
| -------------------------------------------------------------------------     
|
*/
class Module_Affiliate_Payment_Coinbase_Payment extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('modules/' . strtolower(__CLASS__) . '_model', 'module_payments');
		
		$this->load->model('affiliate_payments_model', 'aff_payments');
		
		$this->load->library('convert');
		
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
		
		$data['module_name'] = 'coinbase_payment';
		$data['payment_id'] = 'coinbase_id';
		$data['exclude_minimum'] = $data['module_affiliate_payment_coinbase_payment_exclude_minimum'];
		
		if ($data['module_affiliate_payment_coinbase_payment_use_date_range'] == 1)
		{
			$start = $data['module_affiliate_payment_coinbase_payment_start_date'];
			$end = $data['module_affiliate_payment_coinbase_payment_end_date'];
		}
		
		require_once(APPPATH.'/libraries/coinbase/Coinbase.php');
		
		$_API_KEY = $this->encrypt->decode($this->config->item('module_affiliate_payment_coinbase_payment_api_key'));
		$_API_SECRET = $this->encrypt->decode($this->config->item('module_affiliate_payment_coinbase_payment_api_secret'));
			
		//$coinbase = Coinbase::withApiKey($_API_KEY, $_API_SECRET); 
		
		//$data['coinbase_balance'] = $coinbase->getBalance() . " BTC";
			
		$row = $this->module_payments->_get_affiliate_payments($data['module_affiliate_payment_coinbase_payment_total_rows'], $data['offset'], $data['sort_column'], $data['sort_order'], $start, $end);
		
		$data['affiliate_payments'] = $row['payments'];
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], '',  $this->config->item('module_affiliate_payment_coinbase_payment_total_rows'), 4, $data['sort_order'], $data['sort_column'], $row['total_rows'], $data['where_column'], $data['show_where_value']);
		
		load_admin_tpl('modules', 'tpl_adm_manage_affiliate_payment_coinbase_payment', $data);	
	}
	
	// ------------------------------------------------------------------------
	
	function direct_pay()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->uri->segment(4))
		{
			$sdata = explode('-', base64_decode($this->convert->HexToAscii($this->uri->segment(4))));
			 
			require_once(APPPATH.'/libraries/coinbase/Coinbase.php');

			$_API_KEY = $this->encrypt->decode($this->config->item('module_affiliate_payment_coinbase_payment_api_key'));
			$_API_SECRET = $this->encrypt->decode($this->config->item('module_affiliate_payment_coinbase_payment_api_secret'));
			
			$coinbase = Coinbase::withApiKey($_API_KEY, $_API_SECRET); 
			
			$note = $this->config->item('module_affiliate_payment_coinbase_payment_payment_details');
			$fee =  $this->config->item('module_affiliate_payment_coinbase_payment_user_fee');
			$currency =  $this->config->item('module_affiliate_payment_coinbase_payment_currency');
			
			$response = $coinbase->sendMoney($sdata[1], $sdata[2], $note, $fee, $currency);
			
			//$response->success =  true;
			//$response->transaction_id = 'TEST';
			
			if ($response->success == 'true')
			{
				$note .= "\n\n" . ' Coinbase ' . $response->transaction_id; 
				
				$sdata = $this->aff_payments->_mark_commissions_paid($sdata[0], $sdata[2], $note);
				
				$this->session->set_flashdata('success', $response->transaction->id . ' ' .$this->lang->line('payment_sent_successfully'));
				
				redirect(modules_url() . strtolower(__CLASS__) . '/select_users/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
		
			}
			else
			{
				$this->session->set_flashdata('error', $this->lang->line('invalid_payment'));	
			}
		}
		
		redirect(modules_url() . strtolower(__CLASS__) . '/select_users');
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
				$sdata = $this->aff_payments->_mark_commissions_paid($mid, $amount, $data['module_affiliate_payment_coinbase_payment_payment_details']);
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
						
						$v['affiliate_note'] =  $data['module_affiliate_payment_coinbase_payment_payment_details'];
						
						$this->emailing_model->_send_template_email('member', $v, 'member_affiliate_payment_sent_template', true); //queue it!
					}	
				}
			}
			
			$this->session->set_flashdata('success', $this->lang->line('commissions_marked_as_paid_successfully'));
		}
		
		redirect(modules_url() . strtolower(__CLASS__) . '/select_users');
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
					if ($v['settings_function'] == 'encrypt')
					{
						$this->validation->$v['settings_key'] = $this->encrypt->decode($v['settings_value']);
					
					}
					else
					{
						$this->validation->$v['settings_key'] = $v['settings_value'];
					}		
					
					array_push($data['sts_config'], $v);
				}
			}
			else
			{
				redirect();
			}
			
			load_admin_tpl('modules', 'tpl_adm_manage_affiliate_payment_coinbase_payment_options', $data);
		}
		else
		{		
			$_POST['module_affiliate_payment_coinbase_payment_start_date'] = _save_date($_POST['module_affiliate_payment_coinbase_payment_start_date'], false, 'min');
			
			$_POST['module_affiliate_payment_coinbase_payment_end_date'] = _save_date($_POST['module_affiliate_payment_coinbase_payment_end_date'], false, 'max');
			
			$_POST['module_affiliate_payment_coinbase_payment_api_key'] =  $this->encrypt->encode($_POST['module_affiliate_payment_coinbase_payment_api_key']);
			
			$_POST['module_affiliate_payment_coinbase_payment_api_secret'] =  $this->encrypt->encode($_POST['module_affiliate_payment_coinbase_payment_api_secret']);
			
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
		$rules['module_affiliate_payment_coinbase_payment_use_date_range'] = 'trim';
		$rules['module_affiliate_payment_coinbase_payment_exclude_minimum'] = 'trim';
		$rules['module_affiliate_payment_coinbase_payment_payment_details'] = 'trim';
		$rules['module_affiliate_payment_coinbase_payment_total_rows'] = 'trim|required|integer';
		$rules['module_affiliate_payment_coinbase_payment_currency'] = 'trim|required';
		$rules['module_affiliate_payment_coinbase_payment_user_fee'] = 'trim|numeric';
		$rules['module_affiliate_payment_coinbase_payment_api_key'] = 'trim|required';
		$rules['module_affiliate_payment_coinbase_payment_api_secret'] = 'trim|required';
		
		if ($this->input->post('module_affiliate_payment_coinbase_payment_use_date_range') == 1)
		{
			$rules['module_affiliate_payment_coinbase_payment_start_date'] = 'trim|required';
			$rules['module_affiliate_payment_coinbase_payment_end_date'] = 'trim|required';
		}
		else
		{
			$rules['module_affiliate_payment_coinbase_payment_start_date'] = 'trim';
			$rules['module_affiliate_payment_coinbase_payment_end_date'] = 'trim';	
		}
		
		$this->validation->set_rules($rules);
		
		$fields['module_affiliate_payment_coinbase_payment_start_date'] = $this->lang->line('start_date');
		$fields['module_affiliate_payment_coinbase_payment_end_date'] = $this->lang->line('end_date');
		$fields['module_affiliate_payment_coinbase_payment_use_date_range'] = $this->lang->line('use_date_range');
		$fields['module_affiliate_payment_coinbase_payment_exclude_minimum'] = $this->lang->line('exclude_minimum_amount');
		$fields['module_affiliate_payment_coinbase_payment_payment_details'] = $this->lang->line('payment_note');
		$fields['module_affiliate_payment_coinbase_payment_total_rows'] = $this->lang->line('total_rows');
		$fields['module_affiliate_payment_coinbase_payment_currency'] = $this->lang->line('currency_code');
		$fields['module_affiliate_payment_coinbase_payment_user_fee'] = $this->lang->line('user_fee');
		$fields['module_affiliate_payment_coinbase_payment_api_key'] = $this->lang->line('api_key');
		$fields['module_affiliate_payment_coinbase_payment_api_secret'] = $this->lang->line('api_secret');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
	}
}
?>