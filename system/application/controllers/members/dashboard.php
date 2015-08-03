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
| FILENAME - dashboard.php
| -------------------------------------------------------------------------     
| 
| This file controls the member logout
|
*/
class Dashboard extends Member_Controller {

	function __construct()
	{
		parent::__construct();
		//load css body style
		$this->config->set_item('css_body', 'jroxHome');
		
		//load required models
		$this->load->model('init_model', 'init');	
		$this->load->model('dashboard_model', 'dash');
		$this->load->model('content_model', 'content');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		$this->init->_set_default_program($this->session->userdata('m_signup_link'));
		
		//set data array
		$data = $this->security_model->_load_config('members', __CLASS__,  __FUNCTION__);

		//initialize site with required db config
		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		//set up the languages array
		$data['languages'] = $sdata['languages'];
		
		//set referral info
		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		//set the meta tags
		$data['page_title'] = $data['prg_program_name'] . ' - ' . $this->lang->line('dashboard');
		
		$days = date('t') - (date('t') - date('d'));
		
		//get latest members
		$data['latest_referrals'] = $this->dash->_latest_members((int)$this->session->userdata('userid'));
		
		//get latest commissions
		$data['latest_commissions'] = $this->dash->_latest_commissions((int)$this->session->userdata('userid'));
		
		//get total clicks
		$data['total_clicks'] = $this->dash->_total_user_clicks((int)$this->session->userdata('userid'));
		
		//get total comms
		$data['total_referrals'] = $this->dash->_total_user_referrals((int)$this->session->userdata('userid'));
		
		//get total comms
		$data['total_comm'] = $this->dash->_total_user_comms((int)$this->session->userdata('userid'));
		
		//get total signups for current month
		$data['month_signups'] = $this->dash->_total_signups(date('m', _generate_timestamp()), date('Y', _generate_timestamp()), (int)$this->session->userdata('userid'));
		
		//get total clicks for current month
		$data['month_clicks'] = $this->dash->_total_clicks(date('m', _generate_timestamp()), date('Y', _generate_timestamp()), (int)$this->session->userdata('userid'));
		
		$data['month_clicks_avg'] = $data['month_clicks'] / $days;		
		
		//get total commissions for current month
		$data['month_comm'] = $this->dash->_total_commissions(date('m', _generate_timestamp()), date('Y', _generate_timestamp()), (int)$this->session->userdata('userid'));
		
		$data['month_comm_avg'] = $data['month_comm'] / $days;
		
		//get content
		//sts_content_members_dashboard_num_articles
		$data['articles'] = $this->content->_get_dashboard_content($data['prg_program_id']);
		
		$data['calendar'] = $this->_check_calendar();
			
		$this->parser->_JROX_load_view($data['layout_theme_members_default_dashboard_template'], 'members', $data);
	}
	
	// ------------------------------------------------------------------------
	
	function _check_calendar()
	{		
		if ($this->modules_model->_check_module_install('affiliate_marketing', 'member_events', true))
		{
			if ($this->config->item('sts_content_members_dashboard_calendar') == 1)
			{
				
				$prefs['template'] = '
		
			   {table_open}<table border="0" cellpadding="0" cellspacing="1" width="100%" class="smallcalendar">{/table_open}
			
			   {heading_row_start}<tr>{/heading_row_start}

			   {heading_previous_cell}<th class="dashboardHeader"><a href="{previous_url}" style="font-size:13px">&lt;&lt;</a></th>{/heading_previous_cell}
			   {heading_title_cell}<th colspan="{colspan}" class="dashboardHeader"><h4 class="eventsCalendarSmall">{heading}</h4></th>{/heading_title_cell}
			   {heading_next_cell}<th class="dashboardHeader"><a href="{next_url}" style="font-size:13px">&gt;&gt;</a></th>{/heading_next_cell}
			
			   {heading_row_end}</tr>{/heading_row_end}
			
			   {week_row_start}<tr>{/week_row_start}
			   {week_day_cell}<td class="smallweek">{week_day}</td>{/week_day_cell}
			   {week_row_end}</tr>{/week_row_end}
			
			   {cal_row_start}<tr>{/cal_row_start}
			   {cal_cell_start}<td>{/cal_cell_start}
			
			   {cal_cell_content}<div class="smallevents"><div class="smallday"><a href="' . site_url('members') . '/marketing/tools/0/0/0/module_id/38/0/0/' . date('Y') . '/' . date('m') .  '/{day}">{day}</a></div>{/cal_cell_content}
			   {cal_cell_content_today}<div class="smallhighlight smallday"><a href="' . site_url('members') . '/marketing/tools/0/0/0/module_id/38/0/0/' . date('Y') . '/' . date('m') . '/{day}">{day}</a></div>{/cal_cell_content_today}
			
			   {cal_cell_no_content}<div class="smallday"><a href="' . site_url('members') . '/marketing/tools/0/0/0/module_id/38/0/0/' . date('Y') . '/' . date('m') . '/{day}">{day}</a></div>{/cal_cell_no_content}
			   {cal_cell_no_content_today}<div class="smallhighlight smallday"><a href="' .  site_url('members') . '/marketing/tools/0/0/0/module_id/38/0/0/' . date('Y') . '/' . date('m') . '/{day}">{day}</a></div>{/cal_cell_no_content_today}
			
			   {cal_cell_blank}&nbsp;{/cal_cell_blank}
			
			   {cal_cell_end}</td>{/cal_cell_end}
			   {cal_row_end}</tr>{/cal_row_end}
			
			   {table_close}</table>{/table_close}';
			   
				$y = $this->uri->segment(4, date('Y'));
				$m = $this->uri->segment(5, date('m'));
				
				$this->load->library('calendar', $prefs);
				
				//load required model
				$this->load->model('modules/module_affiliate_marketing_member_events_model', 'member_events_model');
				$row = $this->member_events_model->_get_member_events($m, $y);
				
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
				
				return $this->calendar->generate($y, $m, $sdata);
			}
		}
		return false;
	}
}
?>