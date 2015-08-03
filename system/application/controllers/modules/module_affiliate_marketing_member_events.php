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
| FILENAME - module_affiliate_marketing_member_events.php
| -------------------------------------------------------------------------     
|
*/

class Module_Affiliate_Marketing_Member_Events extends Admin_Controller {
	
	var $m;
	var $y;
	var $d;
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('groups_model');

		$this->load->model('modules/module_affiliate_marketing_member_events_model', 'member_events_model');
		
		$this->config->set_item('menu', 'a');
		
		$this->y = $this->uri->segment(4, date('Y'));
		
		$this->m = $this->uri->segment(5, date('m'));
		
		$this->d = $this->uri->segment(6, date('d'));
		
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect($this->uri->uri_string() . '/view/');
	}
	
	// ------------------------------------------------------------------------
	
	function view()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = 'view_member_events';
		
		
		$data['month'] = $this->m;
		
		$data['year'] = $this->y;
		
		$data['day'] = $this->d;
		
		$aff_groups = $this->groups_model->_get_all_affiliate_groups();
		
		$data['aff_groups'] = format_array($aff_groups, 'group_id', 'aff_group_name', true);
					
		$data['hour'] = array();
		
		for ($h=1; $h<=12; $h++)
		{
			$i = date('h', mktime($h,0,0, date('m'), date('d'), date('Y')));
			
			$data['hour'][$i] = $i;	
		}
		
		$j = '0';
		
		$data['min'] = array();
		
		while($j <= '55')
		{
			if ($j < 10) { $j = '0'.$j; }
			
			$data['min'][$j] = $j;	
			
			$j = $j + 5;
		}

		$data['ampm'] = array( 'am' => 'AM', 'pm' => 'PM');
		
		$data['recurrence_type'] = array( 'day' => $this->lang->line('day_s'), 'week' => $this->lang->line('week_s'), 'month' => $this->lang->line('month_s'), 'year' => $this->lang->line('year_s'));
		
		$prefs = array (
			   'day_type' => 'short',
               'show_next_prev'  => TRUE,
               'next_prev_url'   => modules_url() . 'module_affiliate_marketing_member_events/view/'
             );

		
		$prefs['template'] = '

   {table_open}<table border="0" cellpadding="4" cellspacing="1" width="100%" class="calendar">{/table_open}

   {heading_row_start}<tr>{/heading_row_start}

   {heading_previous_cell}<th class="text-center"><h3><a href="{previous_url}"" class="btn btn-lg btn-default"><i class="fa fa-chevron-left"></i></a></h3></th>{/heading_previous_cell}
   {heading_title_cell}<th colspan="{colspan}" class="text-center"><h1>{heading}</h1></th>{/heading_title_cell}
   {heading_next_cell}<th class="text-center"><h3><a href="{next_url}" class="btn btn-lg btn-default"><i class="fa fa-chevron-right"></i></a></h3></th>{/heading_next_cell}

   {heading_row_end}</tr>{/heading_row_end}

   {week_row_start}<tr>{/week_row_start}
   {week_day_cell}<td class="week"><strong>{week_day}</strong></td>{/week_day_cell}
   {week_row_end}</tr>{/week_row_end}

   {cal_row_start}<tr>{/cal_row_start}
   {cal_cell_start}<td>{/cal_cell_start}

   {cal_cell_content}<div class="events"><div class="day" onclick="ViewEvents(\'{day}\')">{day}<i class="fa fa-check-circle-o fa-2x"></i></div>{/cal_cell_content}
   {cal_cell_content_today}<div class="highlight day" onclick="ViewEvents(\'{day}\')">{day}<i class="fa fa-check-circle-o fa-2x"></i></div>{/cal_cell_content_today}

   {cal_cell_no_content}<div class="day">{day}</div>{/cal_cell_no_content}
   {cal_cell_no_content_today}<div class="highlight day">{day}</div>{/cal_cell_no_content_today}

   {cal_cell_blank}&nbsp;{/cal_cell_blank}

   {cal_cell_end}</td>{/cal_cell_end}
   {cal_row_end}</tr>{/cal_row_end}

   {table_close}</table>{/table_close}';

		$this->load->library('calendar', $prefs);
		
		$row = $this->member_events_model->_get_member_events($this->m, $this->y);
		
		$sdata = array();
		
		if (!empty($row))
		{
			$sdata = array();
			
			foreach ($row[0] as $k => $v)
			{
				if ($k != 'id') 
				{	
					if (!empty($v)) { $sdata[$k] = $v;}
				}
			}
		}
		
		$data['calendar'] = $this->calendar->generate($this->y, $this->m, $sdata);
		
		load_admin_tpl('modules', 'tpl_adm_manage_affiliate_marketing_member_events', $data);	
	}
	
	// ------------------------------------------------------------------------
	
	function add()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		$data['page_title'] = 'add_event';
		
		$data['month'] = $this->m;
		
		$data['year'] = $this->y;
		
		$data['day'] = $this->d;
		
		if ($this->_check_member_event() == false)
		{	
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string;	
			}
			else
			{
				$this->validation->date = date($data['format_date2'], _generate_timestamp());
			}

			$aff_groups = $this->groups_model->_get_all_affiliate_groups();
			
			$data['aff_groups'] = format_array($aff_groups, 'group_id', 'aff_group_name', true);

			$data['hour'] = array();
			
			for ($h=1; $h<=12; $h++)
			{
				$i = date('h', mktime($h,0,0, date('m'), date('d'), date('Y')));
				
				$data['hour'][$i] = $i;	
			}
			
			$j = '0';
			
			$data['min'] = array();
			
			while($j <= '55')
			{
				if ($j < 10) { $j = '0'.$j; }
				
				$data['min'][$j] = $j;	
				
				$j = $j + 5;
			}
	
			$data['ampm'] = array( 'am' => 'AM', 'pm' => 'PM');
			
			$data['recurrence_type'] = array( 'day' => $this->lang->line('day_s'), 'week' => $this->lang->line('week_s'), 'month' => $this->lang->line('month_s'), 'year' => $this->lang->line('year_s'));
			
			$data['options'] = array();
			
			for( $i=1; $i<=30; $i++)
			{
				$data['options'][$i] = $i;	
			}
			
			load_admin_tpl('modules', 'tpl_adm_manage_affiliate_marketing_member_event', $data);
		}
		else
		{	
			$id = $this->member_events_model->_add_member_event($_POST);		
			
			$this->session->set_flashdata('success', $this->lang->line('item_added_successfully'));		

			redirect( modules_url() . strtolower(__CLASS__) . '/view/' . $id);	
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function delete()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		if ($this->aff->_delete_tool('member_event', (int)($this->uri->segment(4)), 'affiliate_member_events'))
		{
			$this->session->set_flashdata('success', $this->lang->line('item_deleted_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{				
				
				redirect(modules_url() . strtolower(__CLASS__) . '/view/' . $this->uri->segment(8,0) . '/' . $this->uri->segment(6,0) . '/' . $this->uri->segment(7,0));
			}
		}
		
		redirect( modules_url() . strtolower(__CLASS__) . '/view/');
	}
	
	// ------------------------------------------------------------------------
	
	function edit()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);

		$data['page_title'] = 'manage_event';
		
		$y = $this->uri->segment(7, date('Y'));
		
		$m = $this->uri->segment(5, date('m'));
		
		$d = $this->uri->segment(6, date('d'));
		
		$data['month'] = $m;
		
		$data['year'] = $y;
		
		$data['day'] = $d;
		
		if ($this->_check_member_event('edit') == false)
		{	
			if (!empty($_POST))
			{
				$data['error'] = $this->validation->error_string;	
			}
			else
			{
				$mdata = $this->member_events_model->_get_member_event_details((int)$this->uri->segment(4));
				
				$data['id'] = (int)$this->uri->segment(4);
		
				if (!empty($mdata))
				{	
					foreach ($mdata as $k => $v)
					{
						$this->validation->$k = $v;
						
						switch ($k)
						{
							case 'start_time':
								
								$time = explode(':', date("g:i:a", strtotime($v)));
								$this->validation->start_hour = $time[0];
								$this->validation->start_min = $time[1];
								$this->validation->start_ampm = $time[2];

							break;
							
							case 'end_time':
								
								$time = explode(':', date("g:i:a", strtotime($v)));
								$this->validation->end_hour = $time[0];
								$this->validation->end_min = $time[1];
								$this->validation->end_ampm = $time[2];

							break;
							
							case 'date':
							
								$this->validation->$k = date('m/d/Y', $v);
								
							break;
							
							case 'member_id':

								if (!empty($v))
								{	
									$sponsor_array = $this->db_validation_model->_get_details('members', 'username', 'member_id', $v);
									$this->validation->$k =  $sponsor_array[0]['username'];
								}
								else
								{
									$this->validation->$k = '';	
								}
													
							break;
						}
					}
					
					$data['member_event'] = $m;
				}
				else
				{
					redirect();
				}
				
				$aff_groups = $this->groups_model->_get_all_affiliate_groups();
				
				$data['aff_groups'] = format_array($aff_groups, 'group_id', 'aff_group_name', true);

				$data['hour'] = array();
				
				for ($h=1; $h<=12; $h++)
				{
					$i = date('h', mktime($h,0,0, date('m'), date('d'), date('Y')));
					
					$data['hour'][$i] = $i;	
				}
				
				$j = '0';
				
				$data['min'] = array();
				
				while($j <= '55')
				{
					if ($j < 10) { $j = '0'.$j; }
					
					$data['min'][$j] = $j;	
					
					$j = $j + 5;
				}
		
				$data['ampm'] = array( 'am' => 'AM', 'pm' => 'PM');
				
				$data['recurrence_type'] = array( 'day' => $this->lang->line('day_s'), 'week' => $this->lang->line('week_s'), 'month' => $this->lang->line('month_s'), 'year' => $this->lang->line('year_s'));

			}
			
			load_admin_tpl('modules', 'tpl_adm_manage_affiliate_marketing_member_event', $data);
		}
		else
		{	
			$id = $this->member_events_model->_edit_member_event((int)$this->uri->segment(4), $_POST);		
			
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			redirect($this->uri->uri_string());			
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function update()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['page_title'] = 'update_module_options';
		
		if ($this->_check_member_event_options() == false)
		{		
			if (!empty($_POST))
			{
				$data['error'] =  $this->validation->error_string;	
			}
			
			$m = $this->aff->_get_affiliate_marketing_details((int)$this->uri->segment(4));
			
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
			
			load_admin_tpl('modules', 'tpl_adm_manage_affiliate_marketing_options', $data);
		}
		else
		{	
			$data = $this->modules_model->_update_options($_POST);	
			
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			$url = !empty($_POST['redirect']) ? $_POST['redirect'] : $this->uri->uri_string();
			
			redirect($url);
		}		
	}
	
	// ------------------------------------------------------------------------
	
	function events()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
	
		$data['month'] = $this->uri->segment(4, date('m'));
		
		$data['day'] = $this->uri->segment(5, date('d'));
		
		$data['year'] = $this->uri->segment(6, date('Y'));
		
		$data['current_day'] = date('M d Y', mktime(12,0,0, $data['month'], $data['day'], $data['year']));
		
		$data['events'] = $this->member_events_model->_get_daily_events($data['month'], $data['day'], $data['year']);
					
		load_form('modules', 'tpl_adm_manage_affiliate_marketing_member_event_details', $data);	
	}
	
	// ------------------------------------------------------------------------
	
	function inactive_events()
	{

		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		$data['events'] = $this->member_events_model->_get_inactive_events();
		
		$data['month'] = $this->uri->segment(4, date('m'));
		
		$data['day'] = $this->uri->segment(5, date('d'));
		
		$data['year'] = $this->uri->segment(6, date('Y'));
		
		$data['current_day'] = date('M d Y', mktime(12,0,0, $data['month'], $data['day'], $data['year']));

		load_form('modules', 'tpl_adm_manage_affiliate_marketing_member_inactive_events', $data);		
	}
	
	// ------------------------------------------------------------------------
	
	
	function change_status()
	{
		$data = $this->security_model->_load_config('admin', __CLASS__,  __FUNCTION__);
		
		if ($this->db_validation_model->_change_status_field('affiliate_member_events', 'id', (int)$this->uri->segment(4), 'status'))
		{
			$this->session->set_flashdata('success', $this->lang->line('system_updated_successfully'));
			
			if ($this->uri->segment(5) == 2)
			{
				redirect(modules_url()  . strtolower( __CLASS__) . '/events/' .  $this->uri->segment(6,date('m')) . '/' . $this->uri->segment(7, date('d')) . '/' . $this->uri->segment(8,date('Y')));
				exit();
			}
		}
		
		redirect(modules_url()  . strtolower( __CLASS__) . '/view');
	}
	

	// ------------------------------------------------------------------------
	
	/*
	| -------------------------------------------------------------------------
	| supporting functions - these are used to support the main functions above
	| -------------------------------------------------------------------------
	*/
	
	// ------------------------------------------------------------------------	
	
	function _check_member_event_options()
	{
		$rules['module_affiliate_marketing_member_events_alert_email'] = 'trim|valid_email';
		
		$this->validation->set_rules($rules);
		
		$fields['module_affiliate_marketing_member_events_alert_email'] = $this->lang->line('email');
		
		$this->validation->set_fields($fields);
			
		if ($this->validation->run() == FALSE)	
		{
			return false;
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_member_owner()
	{
		if ($this->db_validation_model->_validate_field('members', 'username', $this->validation->member_id))
		{	
			return true;
		}
		
		$this->validation->set_message('_check_member_owner', $this->lang->line('invalid_member_username'));
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _check_member_event($type = 'add')
	{
		$rules['date'] = 'trim|required';
		$rules['status'] = 'trim|integer';
		$rules['start_hour'] = 'trim|required';
		$rules['start_min'] = 'trim|required';
		$rules['start_ampm'] = 'trim|required';
		$rules['end_hour'] = 'trim|required';
		$rules['end_min'] = 'trim|required';
		$rules['end_ampm'] = 'trim|required';
		
		if (empty($_POST['member_id']))
		{
			$rules['member_id'] = 'trim|strtolower|alpha_numeric|callback__check_member_owner';	
		}
		if ($type == 'add')
		{
			$rules['recur_in_days'] = 'trim|required|integer';
		}
		
		$rules['member_event_title'] = 'trim|required';
		$rules['member_event_location'] = 'trim';
		$rules['member_event_description'] = 'trim|required';
		$rules['restrict_group'] = 'trim|integer';
		
		$this->validation->set_rules($rules);

		$fields['date'] = $this->lang->line('date');
		$fields['status'] = $this->lang->line('status');
		$fields['start_hour'] = $this->lang->line('start_hour');
		$fields['start_min'] = $this->lang->line('start_min');
		$fields['start_ampm'] = $this->lang->line('start_ampm');
		$fields['end_hour'] = $this->lang->line('end_hour');
		$fields['end_min'] = $this->lang->line('end_min');
		$fields['end_ampm'] = $this->lang->line('end_ampm');
		$fields['recur_in_days'] = $this->lang->line('recur_in_days');
		$fields['member_event_title'] = $this->lang->line('member_event_title');
		$fields['member_event_location'] = $this->lang->line('member_event_location');
		$fields['member_event_description'] = $this->lang->line('member_event_description');
		$fields['restrict_group'] = $this->lang->line('group');
		
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
			if (strstr($k, "tool") == true) 
			{
				$rules[$k] = 'trim|required|numeric';
				
				$opt = explode('-', $k);
				
				$fields[$k] = $this->lang->line('member_event_id'). ' ' .end($opt) ;
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
}
?>