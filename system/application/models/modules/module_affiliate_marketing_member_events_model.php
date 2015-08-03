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
| FILENAME - module_affiliate_marketing_member_events_model.php
| -------------------------------------------------------------------------     
| 
| This model handles the functions for member events
|
*/

class Module_Affiliate_Marketing_Member_Events_Model extends CI_Model {	
	
	function _install_jrox_module($id = '')
	{	
		//check admin session
		$this->security_model->_check_admin_session();
		
		$delete = 'DROP TABLE IF EXISTS ' . $this->db->dbprefix('affiliate_member_events') . ';';
		
		$query = $this->db->query($delete);
		
		$sql = 'CREATE TABLE IF NOT EXISTS `' . $this->db->dbprefix('affiliate_member_events') . '` (
				  `id` int(10) NOT NULL AUTO_INCREMENT,
				  `status` enum(\'0\',\'1\') NOT NULL DEFAULT \'1\',
				  `member_id` int(10) NOT NULL,
				  `date` varchar(25) NOT NULL DEFAULT \'\',
				  `start_time` time NOT NULL DEFAULT \'00:00:00\',
				  `end_time` time NOT NULL DEFAULT \'00:00:00\',
				  `member_event_title` varchar(255) NOT NULL DEFAULT \'\',
				  `member_event_location` text NOT NULL,
				  `member_event_description` text NOT NULL,
				  `restrict_group` int(11) NOT NULL DEFAULT \'0\',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';
		
		$query = $this->db->query($sql);
		
		/*
		//insert required config rows in settings table
		$config = array(
							'settings_key'	=>	'module_affiliate_marketing_member_events_allow_member_post',
							'settings_value'	=>	'0',
							'settings_module'	=>	'affiliate_marketing',
							'settings_type'	=>	'dropdown',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'1',
							'settings_function'	=>	'boolean',
							);
		
		//insert into settings table
		$this->db->insert('settings', $config);
		
		//insert required config rows in settings table
		$config = array(
							'settings_key'	=>	'module_affiliate_marketing_member_events_auto_approve_post',
							'settings_value'	=>	'0',
							'settings_module'	=>	'affiliate_marketing',
							'settings_type'	=>	'dropdown',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'2',
							'settings_function'	=>	'boolean',
							);
		
		//insert into settings table
		$this->db->insert('settings', $config);
		
		//insert required config rows in settings table
		$config = array(
							'settings_key'	=>	'module_affiliate_marketing_member_events_alert_email',
							'settings_value'	=>	$this->config->item('sts_site_email'),
							'settings_module'	=>	'affiliate_marketing',
							'settings_type'	=>	'text',
							'settings_group'	=>	$id,
							'settings_sort_order'	=>	'3',
							'settings_function'	=>	'none',
							);
		
		//insert into settings table
		$this->db->insert('settings', $config);
		*/
		return true;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_inactive_events()
	{
		$this->db->where('status', '0');
		$this->db->order_by('date', 'DESC');
		$query = $this->db->get('affiliate_member_events');
		
		if ($query->num_rows() > 0)
		{
			$a = array();
			foreach ($query->result_array() as $row)
			{
				$pub = explode(':', $row['start_time']);
				$row['start_time'] = date('h:i A',mktime($pub[0], $pub[1], $pub[2], date('m'), date('d'), date('Y')));
				$pub = explode(':', $row['end_time']);
				$row['end_time'] = date('h:i A',mktime($pub[0], $pub[1], $pub[2], date('m'), date('d'), date('Y')));
				
				$row['date'] = _show_date($row['date']);
				
				array_push($a, $row);
			}
			
			return $a;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
	
	function _get_daily_events($m = '', $d = '', $y = '')
	{
		$fdate = explode(':', $this->config->item('sts_admin_date_format'));
				
		$start = mktime(0, 0, 0, $m  , $d, $y);
		$end = mktime(23, 59, 59, $m  , $d, $y);
		
		$sql = 'SELECT * FROM ' . $this->db->dbprefix('affiliate_member_events') . ' 
				  WHERE date >= ' . $start . ' 
				  AND date <= ' . $end . '
				  ORDER BY start_time ASC';
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$a = array();
			foreach ($query->result_array() as $row)
			{
				$pub = explode(':', $row['start_time']);
				$row['start_time'] = date('h:i A',mktime($pub[0], $pub[1], $pub[2], date('m'), date('d'), date('Y')));
				$pub = explode(':', $row['end_time']);
				$row['end_time'] = date('h:i A',mktime($pub[0], $pub[1], $pub[2], date('m'), date('d'), date('Y')));
			
				array_push($a, $row);
			}
			
			return $a;
		}
		else
		{
			return false;
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _add_member_event($post = array(), $id = '')
	{
		$data = $this->db_validation_model->_clean_data($post);
	
		//format the dates
		$fdate = explode(':', $this->config->item('sts_admin_date_format'));
		$recur = $data['recur_in_days'];

		$start_hour = $data['start_hour'];
		$start_min = $data['start_min'];
		$start_ampm = $data['start_ampm'];
		$end_hour = $data['end_hour'];
		$end_min = $data['end_min'];
		$end_ampm = $data['end_ampm'];
	
		unset($data['recur_in_days']);
		unset($data['start_hour']);
		unset($data['start_min']);
		unset($data['start_ampm']);
		unset($data['end_hour']);
		unset($data['end_min']);
		unset($data['end_ampm']);
		
		if (!empty($id)) { $data['member_id'] = $id; }
		
		//format date
		$pub = explode('/', $data['date']);
						
		for ($i=0; $i<$recur;$i++)
		{
			switch ($fdate[0])
			{
				case 'mm/dd/yyyy':
						
					if (!empty($data['date']))
					{	
						$data['date'] = mktime('12',date('i'),date('s'), $pub[0], $pub[1] + $i, $pub[2]);
						$y = $pub[2];
						$m = $pub[0];
						$d = $pub[1];
					}
				break;
				
				case 'dd/mm/yyyy':
						
					if (!empty($data['date']))
					{	
						$data['date'] = mktime('12',date('i'),date('s'), $pub[1], $pub[0] + $i, $pub[2]);
						$y = $pub[2];
						$m = $pub[1];
						$d = $pub[0];
					}
				break;
				
				case 'yyyy/mm/dd':
						
					if (!empty($data['date']))
					{	
						$data['date'] = mktime('12',date('i'),date('s'), $pub[2], $pub[0] + $i, $pub[1]);
						$y = $pub[0];
						$m = $pub[1];
						$d = $pub[2];
					}
				break;
			}

			if ($start_ampm == 'pm') { $start_hour = $start_hour + 12; }
			$data['start_time'] = $start_hour . ':' . $start_min;
		
			if ($end_ampm == 'pm') { $end_hour = $end_hour + 12; }
			$data['end_time'] = $end_hour . ':' . $end_min;
			
			//check if start time is greater than end time
			$start = mktime($start_hour, $start_min,date('s'), $pub[0], $pub[1] + $i, $pub[2]);
			$end = mktime($end_hour, $end_min,date('s'), $pub[0], $pub[1] + $i, $pub[2]); 
			
			if ($end < $start) { $data['end_time'] = $data['start_time']; }
			
			$data['member_event_description'] = _content_filter($data['member_event_description']);
			
			//insert into db
			if (!$this->db->insert('affiliate_member_events', $data))
			{
				show_error($this->lang->line('could_not_add_member_event'));
				
				//log error
				log_message('error', 'Could not insert member_event into member_events table');
				
				return false;
			}
			else
			{
				$id = $this->db->insert_id();
				
				//log success
				log_message('info', 'member_event '. $id . ' inserted into member_events table');
			}
		}

		return $y . '/' . $m . '/' . $d;
	}
	
	// ------------------------------------------------------------------------	
	
	function _edit_member_event($id = '', $post = array())
	{
		
		$data = $this->db_validation_model->_clean_data($post);
	
		//format the dates
		$fdate = explode(':', $this->config->item('sts_admin_date_format'));
		
		$start_hour = $data['start_hour'];
		$start_min = $data['start_min'];
		$start_ampm = $data['start_ampm'];
		$end_hour = $data['end_hour'];
		$end_min = $data['end_min'];
		$end_ampm = $data['end_ampm'];

		unset($data['start_hour']);
		unset($data['start_min']);
		unset($data['start_ampm']);
		unset($data['end_hour']);
		unset($data['end_min']);
		unset($data['end_ampm']);
		
		//filter sponsor
		if (!empty($data['member_id'])) 
		{
			$sponsor_data = $this->db_validation_model->_get_details('members', 'member_id', 'username', $data['member_id']);
			
			$data['member_id'] = !empty($sponsor_data) ? $sponsor_data[0]['member_id'] : 0;
		}
		
		//format date
		$pub = explode('/', $data['date']);
		
		switch ($fdate[0])
		{
			case 'mm/dd/yyyy':
					
				if (!empty($data['date']))
				{	
					$data['date'] = mktime('12',date('i'),date('s'), $pub[0], $pub[1], $pub[2]);
				}
			break;
		}
		
		if ($start_ampm == 'am' && $start_hour == '12') { $start_hour = 0; }
		if ($start_ampm == 'pm' && $start_hour != '12') { $start_hour = $start_hour + 12; }
		$data['start_time'] = $start_hour . ':' . $start_min;
		
		if ($end_ampm == 'am' && $end_hour == '12') { $end_hour = 0; }
		if ($end_ampm == 'pm' && $end_hour != '12') { $end_hour = $end_hour + 12; }
		$data['end_time'] = $end_hour . ':' . $end_min;
		
		//check if start time is greater than end time
		$start = mktime($start_hour, $start_min, date('s'), $pub[0], $pub[1], $pub[2]);
		$end = mktime($end_hour, $end_min,date('s'), $pub[0], $pub[1], $pub[2]); 
		
		if ($end < $start) { $data['end_time'] = $data['start_time']; }
		
		$data['member_event_description'] = _content_filter($data['member_event_description']);
	
		//insert into db
		$this->db->where('id', $id);
		if (!$this->db->update('affiliate_member_events', $data))
		{
			show_error($this->lang->line('could_not_update_member_event'));
			
			//log error
			log_message('error', 'Could not update text link in affiliate_member_events table');
			
			return false;
		}
		else
		{	
			//log success
			log_message('info', 'text link '. $id . ' updated into affiliate_member_events table');
		}
		
		return true;
	}
	
	// ------------------------------------------------------------------------

	function _get_member_event_details($id = '')
	{
		//get the data from member_events table
		$this->db->where('id', $id);
		$query = $this->db->get('affiliate_member_events');
		
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
		{
			return false;
		}
	}
	
	// ------------------------------------------------------------------------	
	
	function _generate_tools()
	{
		
		$y = !$this->uri->segment(11) ?  date('Y') : $this->uri->segment(11);
		$m = $this->uri->segment(12, date('m'));
		$d = $this->uri->segment(13, date('d'));

		$data['month'] =$m;
		$data['cmonth'] =  date('M', mktime(12,0,0, $m, date('d'), date('Y')));
		$data['year'] = $y;
		$data['day'] = $d;
		
		//set the time
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
               'show_next_prev'  => TRUE,
               'next_prev_url'   => site_url('members') . '/marketing/tools/0/0/0/module_id/' . (int)$this->uri->segment(8) . '/0/0/'
             );

		
		$prefs['template'] = '{table_open}<table border="0" cellpadding="0" cellspacing="1" width="100%" class="smallcalendar">{/table_open}
			
			   {heading_row_start}<tr>{/heading_row_start}

			   {heading_previous_cell}<th class="text-center"><a href="{previous_url}" ><i class="fa fa-chevron-left"></i></a></th>{/heading_previous_cell}
			   {heading_title_cell}<th colspan="{colspan}" class="dashboardHeader"><h4 id="calendar" class="eventsCalendarSmall text-center">{heading}</h4></th>{/heading_title_cell}
			   {heading_next_cell}<th class="text-center"><a href="{next_url}"><i class="fa fa-chevron-right"></i></a></th>{/heading_next_cell}
			
			   {heading_row_end}</tr>{/heading_row_end}
			
			   {week_row_start}<tr>{/week_row_start}
			   {week_day_cell}<td class="smallweek">{week_day}</td>{/week_day_cell}
			   {week_row_end}</tr>{/week_row_end}
			
			   {cal_row_start}<tr>{/cal_row_start}
			   {cal_cell_start}<td>{/cal_cell_start}
			
			   {cal_cell_content}<div class="smallevents"><div class="smallday"><a href="' . site_url('members') . '/marketing/tools/0/0/0/module_id/'. $this->uri->segment(8) . '/0/0/' . $y . '/' . $m . '/{day}">{day}</a></div>{/cal_cell_content}
			   {cal_cell_content_today}<div class="smallhighlight smallday"><a href="' . site_url('members') . '/marketing/tools/0/0/0/module_id/'. $this->uri->segment(8) . '/0/0/' . $y . '/' . $m . '/{day}">{day}</a></div>{/cal_cell_content_today}
			
			   {cal_cell_no_content}<div class="smallday">{day}</div>{/cal_cell_no_content}
			   {cal_cell_no_content_today}<div class="smallhighlight smallday">{day}</div>{/cal_cell_no_content_today}
			
			   {cal_cell_blank}&nbsp;{/cal_cell_blank}
			
			   {cal_cell_end}</td>{/cal_cell_end}
			   {cal_row_end}</tr>{/cal_row_end}
			
			   {table_close}</table>{/table_close}';

		$this->load->library('calendar', $prefs);
		
		$row = $this->_get_member_events($m, $y);
		
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
		
		//generate the calendar
		$data['calendar'] = $this->calendar->generate($y, $m, $sdata);
		
		//get the events for the current day
		$events = $this->_get_daily_events($data['month'], $data['day'], $data['year']);
		
		if (!empty($events))
		{
			$data['events'] = array();
			foreach ($events as $v)
			{
				if (!empty($v['restrict_group']))
				{
					if ($this->session->userdata('m_affiliate_group') != $v['restrict_group'])
					{
						continue;	
					}
				}
				
				array_push($data['events'], $v);
			}
		}
		$data['use_pagination'] = false;
		$data['template'] = 'tpl_members_events';
		
		return $data;
	}

	// ------------------------------------------------------------------------	
	
	function _get_member_events($cmonth = '', $cyear = '')
	{
		$fdate = explode(':', $this->config->item('sts_admin_date_format'));
				
		$days = date('t', mktime(0, 0, 0, $cmonth  , '1', $cyear));
		$month = date('M', mktime(0, 0, 0, $cmonth  , '1', $cyear));
		
		$sql = 'SELECT id, ';
		
		for ($i = 1; $i <=$days; $i++)
		{
			//day 7
			$start = mktime(0, 0, 0, $cmonth  , $i, $cyear);
			$end = mktime(23, 59, 59, $cmonth  , $i, $cyear);
			$day = date('j', mktime(12, 0, 0, $cmonth  , $i, $cyear));
		
			$sql .= '(SELECT COUNT(id) 
					  FROM ' . $this->db->dbprefix('affiliate_member_events') . ' 
					  WHERE date >= ' . $start . ' 
					  AND date <= ' . $end . ') as \'' . $day . '\'';
			
			if ($i < $days) $sql .= ', ';
		}
		
		$sql .= ' FROM ' . $this->db->dbprefix('affiliate_member_events') . ' LIMIT 1';
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$row = $query->result_array();
			
			return $row;
		}
		
		return false;
	}
	
	// ------------------------------------------------------------------------	
}
?>