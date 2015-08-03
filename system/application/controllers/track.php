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
| FILENAME - track.php
| -------------------------------------------------------------------------     
| 
| This controller file is used for tracking
|
*/

class Track extends Public_Controller {
	
	function __construct()
	{
		parent::__construct();
		//set the header
		header($this->config->item('p3p_header'));
		
		//load css body style
		$this->config->set_item('css_body', 'jroxTrack');
			
		//load required models
		$this->load->model('init_model', 'init');
		$this->load->model('members_model', 'members');
		$this->load->model('tracking_model', 'track');

	}
	
	// ------------------------------------------------------------------------
	
	function id()
	{		
		$this->init->_set_default_program(1, false, true);
		
		//set data array
		$data = $this->security_model->_load_config('public', __CLASS__,  __FUNCTION__);

		//initialize site with required db config
		$sdata = $this->init->_initialize(__CLASS__,  __FUNCTION__);
		
		//set up the languages array
		$data['languages'] = $sdata['languages'];
		
		//set referral info
		foreach ($sdata['aff_cfg'] as $key => $val)
		{
			$data[$key] = $val;
		} 
		
		//get the tracker ID
		$data['id'] = (int)$this->uri->segment(2); 
		$tool_type = $this->uri->segment(3,0); 
		$tool_id = $this->uri->segment(4,0); 
		
		//get tracking data		
		$tracker = $this->track->_get_tracking_details($data['id']);
		
		//set tracking cookie
		if ($cookie = set_tracking_cookie($data['id'], 'tracking_cookie_name'))
		{
			//record tracking click
			$sdata = array(	'date'	=> _generate_timestamp(),
							'member_id'	=> $tracker['member_id'],
							'program_id' => '0',
							'tracking_code' => $this->aff->_generate_traffic_code(),
							'tool_type'	=>	$tool_type,
							'tool_id'	=>	$tool_id,
							'referrer'	=>	$this->agent->referrer(),
							'ip_address'	=>	$this->input->ip_address(),
							'user_agent'	=>	$this->agent->agent_string(),
							'os'			=>	$this->agent->platform(),
							'browser'		=>	$this->agent->browser(),
							'isp'			=> gethostbyaddr($this->input->ip_address()),
							'tracker'		=> $data['id'],
							);
			$this->aff->_insert_affiliate_traffic($sdata);
		}
		
		if (!empty($tracker['member_id']))
		{
			$mem = $this->members->_get_member_basic($tracker['member_id']);
			
			if (!empty($mem))
			{
				$code = $this->aff->_generate_traffic_code();
				$cid = $tracker['member_id'] . '-1-' . $mem['username'] .  '-'. $code;
				$cookie = set_tracking_cookie($cid);
			}
		}
		
		//redirect to page
		if (!empty($tracker['url']))
		{
			redirect_301($tracker['url'], true);	
		}
		else
		{
			redirect();	
		}
	}
	
	// ------------------------------------------------------------------------
	
	function imp()
	{
		//record impressions for banners
		
		$type = $this->uri->segment(3);
		$id = $this->uri->segment(4);
		
		$tool = explode('_', $id);
		$mid = $tool[0];
		$bid = $tool[1];
		
		//load the default language
		$this->lang->load('common', $this->config->item('sts_site_default_language'));
		
		$this->load->model('tracking_model', 'track');
		$this->load->model('commissions_model', 'comms');
		
		//load module model
		$this->load->model('modules/module_affiliate_marketing_banners_model', 'modules');
		
		$data = $this->modules->_get_banner_imp_details($bid);
		
		//check if the program is paying CPM
		if ($data['enable_cpm'] == '1')
		{
			
			//insert the impression data
			$sdata = array(	'date'	=> _generate_timestamp(),
							'member_id'	=> $mid,
							'program_id' => $data['program_id'],
							'tool_type'	=>	'banners',
							'tool_id'	=>	$bid,
							'referrer'	=>	$this->agent->referrer(),
							'ip_address'	=>	$this->input->ip_address(),
							'user_agent'	=>	$this->agent->agent_string(),
							'os'			=>	$this->agent->platform(),
							'browser'		=>	$this->agent->browser(),
							'isp'			=> gethostbyaddr($this->input->ip_address()),
							);
			
			//first check for unique impression
			if ($this->track->_check_impression($sdata, $data['cpm_unique_ip']) == false)
			{
				$total = $this->track->_insert_impression($sdata);
				
				//check for commissions
				if ($total >= '1000' && !empty($data['cpm_amount']))
				{
					//generate a commission
					
					$num_options = get_default_currency($this->config->item('sts_site_default_currency'));
					
					switch ($this->config->item('sts_affiliate_new_commission'))
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
		
					$comm_array = array(
									'member_id' 						=> 			$mid,
									'program_id' 						=> 			$data['program_id'],
									'invoice_id' 						=> 			'0',
									'comm_status' 						=>			$comm_status,
									'approved' 							=>			'0',
									'date' 								=>			_generate_timestamp(),
									'commission_amount'					=>			format_amounts($data['cpm_amount'], $num_options, true),
									'sale_amount' 						=>			'0',
									'commission_level' 					=>			1,
									'referrer' 							=>			'',
									'trans_id' 							=>			$this->lang->line('cpm_commission_generated'),
									'ip_address' 						=>			$this->input->ip_address(),
									'commission_notes' 					=>			print_r($sdata, true),
									'performance_paid' 					=>			'1',
									'email_sent' 						=>			'0',
									'tool_type' 						=>			'banners',
									'tool_id' 							=>			$bid,
									);
				
					$this->comms->_add_commission($comm_array);
					
					//mark impressions as paid
					$this->track->_update_impressions($sdata);
				}
			}
		}
	}
}
?>