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
| FILENAME - rewards.php
| -------------------------------------------------------------------------     
| 
*/

class Rewards extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('groups_model');

		$this->load->model('rewards_model', 'rewards');
		
		$this->config->set_item('menu', 'a');
	}
	
	// ------------------------------------------------------------------------	
	
	function index()
	{
		redirect(admin_url() . 'rewards/view_rewards');
	}
	
	// ------------------------------------------------------------------------	
	
	function view_rewards()
	{	
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		$data['rewards'] = $this->rewards->_get_rewards();
		
		$data['sort'] = array();
		
		for ($i=1; $i<=count($data['rewards']);$i++)
		{
			$data['sort'][$i] = $i;	
		}
		
		load_admin_tpl('admin', 'tpl_adm_manage_rewards', $data);
	}
	
	// ------------------------------------------------------------------------	
	
	function add_reward()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$id = (int)$this->uri->segment(4, 1);

		if ($this->_check_reward() == false)
		{		
			if (empty($_POST))
			{
				$aff_groups = $this->groups_model->_get_all_affiliate_groups();
				
				$data['groups'] = format_array($aff_groups, 'group_id', 'aff_group_name');
				
				load_admin_tpl('admin', 'tpl_adm_add_performance_reward', $data);
			}
			else
			{				
				echo '<div class="alert alert-danger animated shake capitalize hover-msg">' . $this->validation->error_string . '</div>';
			}		
		}
		else
		{	
			$data = $this->rewards->_add_reward($id, $_POST);		
			
			echo '<div class="alert alert-success animated bounce capitalize alert-msg hover-msg">' . $this->lang->line('item_added_successfully') . '</div>';			
			exit();	
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function sort_order()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		if ($this->_check_sort_order() == true)
		{	
			$this->rewards->_change_sort_order('performance_rewards');

			echo '<div class="alert alert-success animated bounce capitalize alert-msg hover-msg">' . $this->lang->line('system_updated_successfully') . '</div>';			
			exit();	
		}
		
	}
	
	// ------------------------------------------------------------------------	
	
	function update_reward()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$id = (int)$this->uri->segment(4);

		if ($this->_check_reward() == false)
		{		
			if (empty($_POST))
			{
				$mdata = $this->rewards->_get_reward_details($id);
				
				$aff_groups = $this->groups_model->_get_all_affiliate_groups();
				
				$data['groups'] = format_array($aff_groups, 'group_id', 'aff_group_name');
				
				if (!empty($mdata))
				{	
					foreach ($mdata[0] as $key => $value)
					{
						$this->validation->$key = $value;
					}
					
					load_admin_tpl('admin', 'tpl_adm_manage_performance_reward', $data);
				}
				else
				{
					show_error($this->lang->line('no_reward_found'));
				}
			}
			else
			{				
				echo '<div class="alert alert-danger animated shake capitalize hover-msg">' . $this->validation->error_string . '</div>';
			}		
		}
		else
		{	
			$data = $this->rewards->_update_reward($id, $_POST);		
			
			echo '<div class="alert alert-success animated bounce capitalize alert-msg hover-msg">' . $this->lang->line('system_updated_successfully') . '</div>';			
			exit();	
		}		
	}
	
	// ------------------------------------------------------------------------	
	
	function delete_reward()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$id = (int)$this->uri->segment(4);
		
		if ($this->rewards->_delete_reward($id))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));	
		}
		
		redirect(admin_url() . 'rewards/view_rewards');	
	}
	
	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	
	// ------------------------------------------------------------------------
	
	function _check_reward($type = 'add')
	{
		//set form validation rules
		
		$rules['sale_type'] = 'trim|required';
		$rules['time_limit'] = 'trim|required';
		$rules['greater_than'] = 'trim|required';
		$rules['sale_amount'] = 'trim|required|numeric';
		$rules['action'] = 'trim|required';
		
		if ($this->input->post('action') == 'issue_bonus_commission')
		{
			$rules['bonus_amount'] = 'trim|required|numeric';
		}
		else
		{
			$rules['group_id'] = 'trim|required|integer';
		}
		
		$this->validation->set_rules($rules);

		//repopulate form

		$fields['sale_type'] = $this->lang->line('sale_type');
		$fields['time_limit'] = $this->lang->line('time_limit');
		$fields['greater_than'] = $this->lang->line('greater_than');
		$fields['sale_amount'] = $this->lang->line('total_amount');
		$fields['action'] = $this->lang->line('reward_action');
		$fields['bonus_amount'] = $this->lang->line('bonus_amount');
		$fields['group_id'] = $this->lang->line('group_name');

		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
		
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_sort_order()
	{
		$data = $this->db_validation_model->_clean_data($_POST);
	
		foreach ($data as $k => $v)
		{
			if (strstr($k, "reward") == true) 
			{
				$rules[$k] = 'trim|required|numeric';
				
				$opt = explode('-', $k);
				
				$fields[$k] = $this->lang->line('reward_id'). ' ' .end($opt) ;
			}
		}
		
		$this->validation->set_rules($rules);
		
		//repopulate form
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
	}	
}
?>