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
| FILENAME - tracking.php
| -------------------------------------------------------------------------     
|
*/

class Tracking extends Member_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('tracking_model', 'tracking');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect(site_url('members/tracking/view'));
	} 
	
	// ------------------------------------------------------------------------
	
	function view()
	{
		
		$this->init->_set_default_program($this->session->userdata('m_signup_link'));
		
		$data = $this->security_model->_load_config('members', __CLASS__,  __FUNCTION__);

		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);

		$data['languages'] = $sdata['languages'];
		
		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		$data['page_title'] = $this->lang->line('ad_tracking');

		$sdata = $this->tracking->_get_user_trackers($this->session->userdata('userid'), MEMBERS_TRACKING_PER_PAGE, $data['offset'], $data['sort_column'], $data['sort_order'], $data['num_options']);
		
		if (!empty($sdata['tracking']))
		{
			$data['rows'] = $sdata['tracking'];
		}

		$pagination = $this->db_validation_model->_set_pagination($data['uri'], 'tracking', MEMBERS_TRACKING_PER_PAGE, 4, $data['sort_order'], $data['sort_column'],  $sdata['total_rows'], '', '', 'public');
		
		$data['pagination_rows'] = $pagination['rows'];
		
		$data['num_pages'] = $pagination['num_pages'];

		$this->parser->_JROX_load_view('tpl_members_tracking', 'members', $data);
	}
	
	// ------------------------------------------------------------------------
	
	function add()
	{
		$this->init->_set_default_program($this->session->userdata('m_signup_link'));
		
		$data = $this->security_model->_load_config('members', __CLASS__,  __FUNCTION__);

		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		$data['languages'] = $sdata['languages'];
		
		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		$data['page_title'] = $this->lang->line('add_tracker');
		$data['page_header'] = 'add_tracker';
		$data['side_header'] = 'what_is_tracker';
		
		if ($this->_check_tracker() == false)
		{
			if (!empty($_POST))
			{
				$data['error' ] = $this->validation->error_string;	
			}
			
			$this->parser->_JROX_load_view('tpl_members_manage_tracking', 'members', $data);
		}
		else
		{
			$id = $this->tracking->_add_tracking($this->session->userdata('userid'));	
			
			$this->session->set_flashdata('success', $this->lang->line('add_tracker_success'));
			
			redirect(site_url('members') . '/tracking/edit/' . $id);
		}
	}
	
	// ------------------------------------------------------------------------
	
	function delete()
	{
		$this->init->_set_default_program($this->session->userdata('m_signup_link'));

		$data = $this->security_model->_load_config('members', __CLASS__,  __FUNCTION__);

		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		$this->tracking->_delete_tracking((int)$this->uri->segment(4), $this->session->userdata('m_member_id'));
		
		$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));
			
		redirect(site_url('members') . '/tracking/view');
	}
	
	// ------------------------------------------------------------------------
	
	function edit()
	{
		$this->init->_set_default_program($this->session->userdata('m_signup_link'));
		
		$data = $this->security_model->_load_config('members', __CLASS__,  __FUNCTION__);

		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);

		$data['languages'] = $sdata['languages'];
		
		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		$data['page_title'] = $this->lang->line('edit_tracker');
		$data['page_header'] = 'edit_tracker';
		$data['side_header'] = 'tracker_stats';

		$data['id'] = (int)$this->uri->segment(4);
		
		if ($this->_check_tracker() == false)
		{
			if (!empty($_POST))
			{
				$data['error' ] =  $this->validation->error_string;	
			}
			else
			{
				$sdata = $this->tracking->_get_user_tracking_details($data['id'], $this->session->userdata('userid'));
				
				foreach ($sdata as $k => $v)
				{
					$this->validation->$k = $v;
				}
			}
			
			$m = date('m');
			$y = date('Y');
			
			$data['total_clicks'] = $this->tracking->_get_tracking_stats($data['id'], 'clicks');
			$data['total_comms'] = format_amounts($this->tracking->_get_tracking_stats($data['id'], 'comms'), $data['num_options']);
			$data['clicks_month'] = $this->tracking->_get_tracking_stats($data['id'], 'clicks', $m, $y);
			$data['comms_month'] = format_amounts($this->tracking->_get_tracking_stats($data['id'], 'comms', $m, $y), $data['num_options']);
			
			$this->parser->_JROX_load_view('tpl_members_manage_tracking', 'members', $data);
		}
		else
		{
			$data = $this->tracking->_update_tracking((int)$this->uri->segment(4), $this->session->userdata('userid'));	
			
			$this->session->set_flashdata('success', $this->lang->line('update_tracker_success'));
			
			redirect(site_url('members') . '/tracking/edit/' . (int)$this->uri->segment(4));
		}
	}
	
	// ------------------------------------------------------------------------	
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/

	// ------------------------------------------------------------------------	
	
	function _check_tracker()
	{	
		$rules['name'] = 'trim|required|min_length[3]|max_length[255]';
		$rules['url'] = 'trim|required|prep_url';
		$rules['cost'] = 'trim|numeric';
		$rules['cost_type'] = 'trim';
		$rules['recur'] = 'trim';
		
		$this->validation->set_rules($rules);

		$fields['name'] = $this->lang->line('name');
		$fields['url'] = $this->lang->line('url');
		$fields['cost'] =  $this->lang->line('cost');
		$fields['cost_type'] = $this->lang->line('cost_type');
		$fields['recur'] = $this->lang->line('recur');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
	}
	
}
?>