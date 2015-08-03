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
| FILENAME - tracking_log.php
| -------------------------------------------------------------------------     
|
*/

class Tracking_Log extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();
	
		$this->load->model('tracking_log_model');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(admin_url() . 'tracking_log/view_log');
	}

	// ------------------------------------------------------------------------
	
	function delete_log()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->tracking_log_model->_delete_log((int)($this->uri->segment(4))))
		{	
			$this->session->set_flashdata('success', $this->lang->line('language_deleted_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(admin_url() . 'tracking_log/view_logs/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0) . '/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(9,0) . '/' . $this->uri->segment(10,0));
				exit();
			}
		}
		
		redirect(admin_url() . 'tracking_log/view_logs');	
	}
	
	// ------------------------------------------------------------------------
	
	function view_logs()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$member = $this->db_validation_model->_get_details('members', 'fname, lname', 'member_id', $this->uri->segment(8));
				
		$data['sub_title'] = $this->lang->line('manage_tracking_log_for') . ' ' . $member[0]['fname'] . ' ' . $member[0]['lname'];
		
		$this->validation->id = (int)$this->uri->segment(8);
		
		$data['logs'] = $this->tracking_log_model->_get_tracking_logs((int)$this->uri->segment(8), $this->session->userdata('per_page'), $data['offset'], $data['sort_column'], $data['sort_order']);
		
		$data['pagination'] = $this->db_validation_model->_set_pagination($data['uri'], 'tracking_log', $this->session->userdata('per_page'), 4, $data['sort_order'], $data['sort_column']);
		
		load_admin_tpl('admin', 'tpl_adm_manage_tracking_logs', $data);		
	}
	
	// ------------------------------------------------------------------------	
	
	function update_logs()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->input->post('id') AND count($this->input->post('id')) > 0)
		{
			$this->tracking_log_model->_change_status($this->input->post('id'));
		}
		
		$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
		
		redirect($this->input->post('redirect'));
	}
	
	// ------------------------------------------------------------------------
	
	function update_log()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		//set page title
		$data['page_title'] = $this->lang->line('update_affiliate_log');
		$data['submit_url'] = admin_url().'tracking_log/update_log';
		$data['add_link'] = 'update_affiliate_log';
		$data['update_link'] = 'tracking_log';
		$data['logs_table'] = 'tracking_log-table';
		
		$this->validation->log_id_redirect = '<input type="hidden" id="log_id" name="log_id" value="' . (int)$this->uri->segment(4) . '" />';
		$this->validation->log_id = $this->uri->segment(4);
		
		$this->load->model('mailing_lists_model');
		$lists = $this->mailing_lists_model->_get_all_mailing_lists();
		$data['lists'] = format_array($lists, 'mailing_list_id', 'mailing_list_name', true);
		
		if ($this->_check_log() == false)
		{		
			if (empty($_POST))
			{
				$mdata = $this->tracking_log_model->_get_log_details((int)$this->uri->segment(4));
				
				if (!empty($mdata))
				{	
					foreach ($mdata[0] as $key => $value)
					{
						$this->validation->$key = $value;
					}
					
					load_admin_tpl('admin', 'tpl_adm_manage_affiliate_log', $data);
				}
				else
				{
					//load add member page
					redirect(admin_url() . 'tracking_log/view_logs');
					exit();
				}
							
			}
			else
			{				
				echo '<div class="error" id="error-messages">' . $this->validation->error_string . '</div>';
			}		
		}
		else
		{				
			$data = $this->tracking_log_model->_update_affiliate_log((int)$this->uri->segment(4));	

			echo '<div class="success" id="success">' .  $this->lang->line('update_log_success') . '</div>';	
			exit();
		}		
	}
	
	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	
	// ------------------------------------------------------------------------	
	
	function _check_log()
	{		
		$rules['log_name'] = 'trim|required|min_length[2]|max_length[50]';
		$rules['log_description'] = 'trim|max_length[255]';
		$rules['commission_type'] = 'trim|required';
		$rules['tier'] = 'trim|numeric';
		$rules['ppc_amount'] = 'trim|numeric';
		$rules['cpm_amount'] = 'trim|numeric';
	
		for ($i=1;$i<=10;$i++)
		{
			$level = 'commission_level_' . $i;
			
			$rules[$level] = 'trim|numeric';	
		}	
		
		$this->validation->set_rules($rules);

		$fields['log_name'] = $this->lang->line('log_name');
		$fields['log_description'] = $this->lang->line('log_description');
		$fields['commission_type'] = $this->lang->line('commission_type');
		$fields['tier'] = $this->lang->line('tier');
		$fields['ppc_amount'] = $this->lang->line('ppc_amount');
		$fields['cpm_amount'] = $this->lang->line('cpm_amount');
	
		for ($k=1;$k<=10;$k++)
		{
			$level = 'commission_level_' . $k;
			$fields[$level] = $this->lang->line($level);
		}	
		
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