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
| FILENAME - reps.php
| -------------------------------------------------------------------------     
| 
| This controller file is used to generate replication pages
|
*/

class Reps extends Public_Controller {

	var $username;
	var $program_id;
	var $replication_page;
	
	function __construct()
	{
		parent::__construct();
		//load css body style
		$this->config->set_item('css_body', 'jroxReps');
		
		//load required models
		$this->load->model('init_model', 'init');
		$this->load->helper('country_helper');
		
		if ($this->uri->segment(1) != REPLICATION_ROUTE && strlen(REPLICATION_ROUTE) < 1)
		{			
			$userdata = explode('_', $this->uri->segment(1));
			$this->replication_page = $this->uri->segment(2);
		}
		else
		{
			$userdata = explode('_', $this->uri->segment(2));
			$this->replication_page = $this->uri->segment(3);
		}
		
		$this->program_id = !empty($userdata[1]) ? $userdata[1] : 1;
		$this->username = $userdata[0];
		
	}
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect();
	}

	// ------------------------------------------------------------------------
	
	function id()
	{
		if ($this->config->item('sts_affiliate_enable_replication') == 0) { redirect_301(); exit(); }
		
		if ($this->config->item('sts_affiliate_enable_replication_cache'))
		{
			$this->output->cache((int)$this->config->item('sts_affiliate_enable_replication_cache'));
		}
		
		$this->init->_set_default_program($this->program_id, false, true);
		
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
		
		$data['page_title'] = $this->config->item('sts_site_name');
		
		//run referral tracking functions
		$aff_data = $this->aff->_validate_user($this->username);
		
		if ($aff_data)
		{
			$cookie_name = $this->config->item('aff_cookie_name');
		 	$code = $this->aff->_generate_traffic_code();
			$cid = $aff_data['mid'] . '-' . $this->program_id . '-' . $aff_data['username'] . '-' . $code;
			
			if ($cookie = set_tracking_cookie($cid))
			{
				//set the session data
				$this->session->set_userdata($cookie, serialize($aff_data));
				$this->session->set_userdata('jrox_site_referral_regular', $cookie);
			}
		}

		if (empty($aff_data)) 
		{ 
			redirect_301(); 
			exit(); 
		}
		else
		{
			foreach ($aff_data as $key => $val)
			{
				switch ($key)
				{
					case 'billing_country':
						$data['replication_' . $key] = _get_country_name($val, 'country_name');
					break;
					
					default:
						$data['replication_' . $key] = $val;
					break;
				}
			}
			
			$data['replication_member_id'] = $data['replication_mid'];
			
			//get sponsor info
			
			if ($this->config->item('sts_affiliate_link_type') == 'replicated_site')
			{
				//insert the tracking data
				$sdata = array('date'	=> _generate_timestamp(),
							'member_id'	=> $aff_data['mid'],
							'program_id' => $this->program_id,
							'tracking_code' => $code,
							'tool_type'	=>	'replication',
							'tool_id'	=>	'',
							'referrer'	=>	$this->agent->referrer(),
							'ip_address'	=>	$this->input->ip_address(),
							'user_agent'	=>	$this->agent->agent_string(),
							'os'			=>	$this->agent->platform(),
							'browser'		=>	$this->agent->browser(),
							'isp'			=> gethostbyaddr($this->input->ip_address()),
						);
				$this->aff->_insert_affiliate_traffic($sdata);
			}
		}
		
		//format the affiliate data
		//check for image
		$data['replication_image'] = base_url() . 'images/misc/sponsor.png';
		
		if (!empty($aff_data['facebook_id']))
		{
			$data['replication_image_small'] = 'http://graph.facebook.com/' . $aff_data['facebook_id'] . '/picture';	
			$data['replication_image'] = 'http://graph.facebook.com/' . $aff_data['facebook_id'] . '/picture/?type=large';	
		}
		if (!empty($aff_data['photo_file_name']))
		{
			if ($aff_data['image_resized'] == 1)
			{
				$filename = $aff_data['raw_name'] . '_jrox' . $aff_data['file_ext'];
			}
			else
			{
				$filename = $aff_data['photo_file_name'];
			}
			
			$data['replication_image'] = base_url() . 'images/' . $this->config->item('images_members_dir') . '/' . $filename;
		}
		
		$data['replication_safe_email'] = safe_mailto($aff_data['primary_email'], $this->lang->line('replication_contact_me') , 'class="jroxReplicationSafeMail" id="jroxReplicationSafeMail"');
		
		//get the affiliate link
		$data['replication_affiliate_link'] = _get_aff_link($aff_data['username']);
		$data['replication_url'] = _public_url() . REPLICATION_ROUTE . '/' . $aff_data['username'];
		
		//assign the sponsor data
		if (!empty($aff_data['sponsor_id']))
		{
			$sponsor_data = $this->aff->_validate_user($aff_data['sponsor_id'], true);
			
			if (!empty($sponsor_data))
			{
				foreach ($sponsor_data as $k => $v)
				{
					if ($k != 'password')
					{
						$data['sponsor_replication_' . $k] = $v;
					}
				}
			}
		}
		
		if ($this->replication_page == false)
		{
			$this->replication_page = 'page1';
		}
		
		$show_header = $this->config->item('sts_affiliate_replication_enable_header_footer') == 1 ? true : false;
		
		$this->parser->_JROX_load_view('replication/' . $this->replication_page, 'none', $data, $show_header, $show_header);
	}
}

?>