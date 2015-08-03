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
| FILENAME - commissions.php
| -------------------------------------------------------------------------     
|
*/

class Commissions extends Admin_Controller {
	 
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('groups_model', 'groups');
		
		$this->config->set_item('menu', 'c');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . 'commissions/view_commissions');
	}
	
	// ------------------------------------------------------------------------
	
	function view_commissions()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		if (!empty($data['where_column2']))		
		{			
			if ($data['where_column2'] == 'payment_id' && !empty($data['where_value2']))
			{
				$data['page_title'] = $this->lang->line('commissions_associated_to_payment_id') . ' ' . $data['where_value2'];
			}
			elseif ($data['where_column2'] == 'member_id' && !empty($data['where_value2']))
			{
				$member = $this->db_validation_model->_get_details('members', 'fname, lname', 'member_id', $data['where_value2']);
				
				$data['page_title'] = $this->lang->line('view_commissions_for') . ' ' . $member[0]['fname'] . ' ' . $member[0]['lname'];
			}
		}

		if ($data['where_column'] == 'search')
		{
			$this->load->library('convert');
			
			$data['where_value'] = base64_decode($this->convert->HexToAscii($data['where_value'])); 
		}
		
		$data['commissions'] = $this->comm->_get_commissions($this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order'], $data['where_column'], $data['where_value'], $data['where_column2'], $data['where_value2']);
		
		if (!empty($data['where_column']) && $data['where_column'] == 'search')
		{
			$this->db->join('members', 'commissions.member_id = members.member_id', 'left');
			$this->db->like('commissions.trans_id', $data['where_value']);
			$this->db->or_like('commissions.commission_amount', $data['where_value']); 
			$this->db->or_like('commissions.sale_amount', $data['where_value']);
			$this->db->or_like('commissions.invoice_id', $data['where_value']);
			$this->db->or_like('members.fname', $data['where_value']);
			$this->db->or_like('commissions.comm_id', $data['where_value']);
			$this->db->or_like('members.lname', $data['where_value']);
			$this->db->or_like('members.username', $data['where_value']); 
			$this->db->from('commissions');
			
			$total_rows = $this->db->count_all_results();

			$data['filter_category'] = $this->lang->line('search');
			$data['filter_name'] = str_replace('_', ' ', $data['where_value']);
		}
		else
		{
			$sql = '';
			if (!empty($data['where_column']) && !empty($data['where_value']) && empty($data['where_column2']) && empty($data['where_value2']))
			{
				$sql = 'WHERE ' . $data['where_column'] . ' = \'' . $data['where_value'] . '\'';	
			}
			elseif (!empty($data['where_column']) && !empty($data['where_value']) && !empty($data['where_column2']) && !empty($data['where_value2']))
			{
				$sql = 'WHERE ' . $data['where_column'] . ' = \'' . $data['where_value'] . '\'	
							AND ' . $data['where_column2'] . ' = \'' . $data['where_value2'] . '\'';
			}
			if (empty($data['where_column']) && empty($data['where_value']) && !empty($data['where_column2']) && !empty($data['where_value2']))
			{
				$sql = 'WHERE ' . $data['where_column2'] . ' = \'' . $data['where_value2'] . '\'';	
			}
			
			$total_rows = $this->db_validation_model->_get_count('commissions', $sql);
		}
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'commissions', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column'], $total_rows, $data['where_column'], $data['show_where_value'], 'admin', $data['where_column2'], $data['where_value2']);

		load_admin_tpl('admin', 'tpl_adm_manage_commissions', $data);	
		
	}
	
	// ------------------------------------------------------------------------
	
	function update_status()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		if ($this->db_validation_model->_change_status_field('commissions', 'comm_id', (int)$this->uri->segment(4), 'approved'))
		{
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
				
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . 'commissions/view_commissions/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
		}	
		
		redirect(admin_url() . 'commissions/view_commissions');
	}
	
	// ------------------------------------------------------------------------	
	
	function update_commissions()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->input->post('commission') AND count($this->input->post('commission')) > 0)
		{
			$this->comm->_change_status($this->input->post('commission'), $this->input->post('change-status'));
			
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
		}
		
		redirect($this->input->post('redirect'));
	}
	
	// ------------------------------------------------------------------------
	
	function add_commission()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
				
		if ($this->_check_commission('add') == false)
		{	
		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
			else
			{
				$this->validation->date = _format_date($this->validation->date, $data['format_date2']);
			}
				
			$programs = $this->programs_model->_get_all_programs();
			
			$data['programs'] = format_array($programs, 'program_id', 'program_name');
			
			for ($i = 1; $i<=10; $i++)
			{
				$data['commission_levels'][$i] = $i;
			}
			
			load_admin_tpl('admin', 'tpl_adm_manage_commission', $data);
						
				
		}
		else
		{	
			$pg = $this->programs_model->_get_program_details($this->input->post('program_id'));
	
			if (empty($pg)) show_error('program_not_found');
			
			$program_info = $pg['program_data'];

			if (!empty($_POST['referring_affiliate']))
			{
				$member = $this->db_validation_model->_get_details('members', '*', 'username', $this->input->post('referring_affiliate'));
			}
			else
			{
				show_error($this->lang->line('no_member_found'));
			}	
			
			$data['date'] = _save_date($this->input->post('date'));
			
			$data['date_paid'] = '';
			if (!empty($_POST['date_paid']))
			{
				$data['date_paid'] = _save_date($this->input->post('date_paid'));
			}
			
			$data['recur'] = '';
			if (!empty($_POST['recur']))
			{
				$data['recur'] = _save_date($this->input->post('recur'));
			}
					
			
			$level = $this->input->post('commission_level');
			
			$data['commission_amount'] = $this->input->post('commission_amount');

			if ($this->input->post('use_program_defaults') == '0')
			{
				$group = $this->members_model->_get_member_group_data($member[0]['member_id']);
				
				if (empty($group))
				{
					$group = $this->groups->_get_aff_group_details($pg['program_data']['group_id']);	
				}
				
				if ($group['commission_type'] == 'percent')
				{
					
					$data['commission_amount'] = $this->input->post('sale_amount') * $group['commission_level_' . $level];
				}
				else
				{
					$data['commission_amount'] = $group['commission_level_' . $level];
				}
			}

			$email_sent = $this->input->post('send_email_alert') == 1 ? 1 : 0;
			
			foreach ($_POST as $k => $v)
			{
				$insert[$k] = $v;	
			}
				
			$insert['member_id'] = $member[0]['member_id'];
			$insert['date'] = $data['date'];
			$insert['commission_amount'] = $data['commission_amount'];
			$insert['date_paid'] = $data['date_paid'];
			$insert['recur'] = $data['recur'];
			$insert['performance_paid'] = '0';
			$insert['commission_level'] = (int)$level;
			$insert['ip_address'] = $this->input->ip_address();
			
			switch ($program_info['new_commission_option'])
			{
				case 'no_pending':
				case 'alert_pending':
					
					$comm_status = 'pending';
					
				break;
				
				case 'no_unpaid':
				case 'alert_unpaid':
				
					$comm_status = 'unpaid';
					
				break;
			}
		
			if ($this->input->post('credit_upline') == 0) 
			{
				
				$data = $this->comm->_add_commission($insert);	

				if ($this->input->post('send_email_alert') == 1)
				{
					if ($member[0]['alert_new_commission'] != '0')
					{
						$v = $member[0];
						$v['commission_amount'] = format_amounts($data['commission_amount'], $num_options);
						
						$this->emailing_model->_send_template_email('member', $v, 'member_affiliate_commission_generated_template', $this->input->post('program_id')); //send it!
					}
				}
			}
			else
			{
				
				$members = $this->downline->_get_upline($member[0]['member_id'], $program_info['commission_levels']);
                krsort($members);

				foreach ($members as $k => $v)
				{
					if ($v['status'] == 1) 
					{
						$level = $k;

						$insert['commission_amount'] = $this->input->post('commission_amount');
                        $insert['sale_amount'] =  $this->input->post('sale_amount');

						if ($this->input->post('use_program_defaults') == '0')
						{
							if (empty($v['commission_level_' . $k]))
                            {
                                $v['commission_type'] = $group['commission_type'];
                                $v['commission_level_' . $k]  = $group['commission_level_' . $k];
                            }

                            $insert['commission_amount'] = $this->comm->_calculate_commission($v['commission_type'], $level, $v['commission_level_' . $k],  $insert['sale_amount']);
							
						}

						$insert['member_id'] = $v['member_id'];
						$insert['commission_level'] = $level;
                        if ($level > 1)
                        {
                            $insert['sale_amount'] = '0';
                        }

						$data = $this->comm->_add_commission($insert);	

						if ($this->input->post('send_email_alert') == 1)
						{
							if ($v['alert_new_commission'] != '0')
							{
								$v['commission_amount'] = format_amounts($insert['commission_amount'], $num_options);
								$this->emailing_model->_send_template_email('member', $v, 'member_affiliate_commission_generated_template', $this->input->post('program_id'));
							}
						}
					}
				}
			}

			$this->session->set_flashdata('success', $this->lang->line('item_added_successfully'));	
			
			redirect(admin_url() . 'commissions/update_commission/' . $data['comm_id']);
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function delete_commission()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->comm->_delete_commission((int)($this->uri->segment(4))))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));	
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . 'commissions/view_commissions/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
			}
		}
		
		redirect(admin_url() . 'commissions/view_commissions/');
	}
	
	// ------------------------------------------------------------------------
	
	function mark_all_approved()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$this->comm->_mark_all_approved();
		
		$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));	
	
		redirect(admin_url() . 'affiliate_payments/view_payment_options');
	}
	
	// ------------------------------------------------------------------------
	
	
	function update_commission()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->_check_commission('update') == false)
		{	
			$this->validation->id = (int)$this->uri->segment(4);
			
			$programs = $this->programs_model->_get_all_programs();
		
			$data['programs'] = format_array($programs, 'program_id', 'program_name');	
		
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string ;
			}
			
			$m = $this->comm->_get_commission_details((int)$this->uri->segment(4));

			for ($i = 1; $i<=10; $i++)
			{
				$data['commission_levels'][$i] = $i;
			}
			
			if (!empty($m))
			{	
				foreach ($m as $k => $v)
				{
					$this->validation->$k = $v;
					
					if ($k == 'username') $this->validation->referring_affiliate = $m['username'];
				}
	
				$this->validation->date = _format_date($this->validation->date, $data['format_date2']);
				
				if (!empty($m['date_paid']))
				{
					$this->validation->date_paid =  _format_date($this->validation->date_paid, $data['format_date2']);
				}
				
				if (!empty($m['recur']))
				{
					$this->validation->recur =  _format_date($this->validation->recur, $data['format_date2']);
				}

				load_admin_tpl('admin', 'tpl_adm_manage_commission', $data);
			}
			else
			{
				redirect(admin_url() . 'commissions/view_commissions');
				exit();
			}
		}
		else
		{	
			$data = $this->comm->_update_commission((int)$this->uri->segment(4));	
			
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
	
	function _check_commission($type = '')
	{		
		$rules['comm_status'] = 'trim|required';
		$rules['approved'] = 'trim|required';
		$rules['date'] = 'trim|required';
		$rules['date_paid'] = 'trim';
		$rules['program_id'] = 'trim|required|integer';
		$rules['customer_name'] = 'trim';
		$rules['invoice_id'] = 'trim|integer';
		$rules['payment_id'] = 'trim|integer';
		$rules['action_commission_id'] = 'trim|integer';
		$rules['tracking_id'] = 'trim|integer';
		$rules['order_id'] = 'trim';
		
		if ($this->input->post('use_group_defaults') == 0)
		{
			$rules['commission_amount'] = 'trim|numeric';
		}
		else
		{
			$rules['commission_amount'] = 'trim|required|numeric';
		}
		
		$rules['sale_amount'] = 'trim|required|numeric';
		$rules['referring_affiliate'] = 'trim|required|callback__check_username';
		
		if ($type == 'add')
		{
			$rules['commission_level'] = 'trim|required|integer';
		}
		
		$rules['referrer'] = 'trim|prep_url';
		$rules['trans_id'] = 'trim|max_length[255]';
		$rules['commission_notes'] = 'trim';
		$rules['credit_upline'] = 'trim';
		$rules['send_email_alert'] = 'trim';
		$rules['use_program_defaults'] = 'trim';
		$rules['recurring_comm'] = 'trim';
		
		if ($this->input->post('recurring_comm') == 1)
		{
			$rules['recur'] = 'trim|required';	
		}
		
		for ($i=1; $i<=20; $i++) 
		{
		 $rules['custom_commission_field_'. $i] = 'trim';
		}  
		
		$this->validation->set_rules($rules);

		$fields['comm_status'] = $this->lang->line('status');
		$fields['approved'] = $this->lang->line('approved');
		$fields['date'] = $this->lang->line('date_created');
		$fields['date_paid'] = $this->lang->line('date_paid');
		$fields['program_id'] = $this->lang->line('program_name');
		$fields['customer_name'] = $this->lang->line('customer_name');
		$fields['invoice_id'] = $this->lang->line('invoice_id');
		$fields['action_commission_id'] = $this->lang->line('action_commission_id');
		$fields['tracking_id'] = $this->lang->line('tracking_id');
		$fields['payment_id'] = $this->lang->line('payment_id');
		$fields['order_id'] = $this->lang->line('order_id');
		
		$fields['sale_amount'] = $this->lang->line('sale_amount');
		$fields['commission_amount'] = $this->lang->line('commission_amount');
		$fields['referring_affiliate'] = $this->lang->line('referring_affiliate');
		$fields['commission_level'] = $this->lang->line('commission_level');
		$fields['referrer'] = $this->lang->line('referrer');
		$fields['trans_id'] = $this->lang->line('transaction_id');
		$fields['commission_notes'] = $this->lang->line('commission_notes');
		$fields['credit_upline'] = $this->lang->line('credit_upline');
		$fields['send_email_alert'] = $this->lang->line('send_email_alert');
		$fields['use_program_defaults'] = $this->lang->line('use_group_defaults');
		$fields['recurring_comm'] = $this->lang->line('recurring_commission');
		$fields['recur'] = $this->lang->line('next_recurring_date');
		
		for ($i=1; $i<=20; $i++) 
		{
		 $fields['custom_commission_field_' . $i] = $this->lang->line('custom_commission_field_' . $i);
		}  
		 
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
		
	}
	
	// ------------------------------------------------------------------------
	
	function _check_username()
	{
		if ($this->db_validation_model->_validate_field('members', 'username', $this->validation->referring_affiliate) == false)
		{
			$this->validation->set_message('_check_username', $this->lang->line('invalid_referring_affiliate'));
			
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	
}
?>