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
| FILENAME - refer.php
| -------------------------------------------------------------------------     
| 
| This controller file is for affiliate tracking
|
*/

class Refer extends Public_Controller {
	
	var $seg;
	var $program_id;

	function __construct()
	{ 
		parent::__construct();
		//set the header
		header($this->config->item('p3p_header'));
		
		if ($this->uri->segment(1) == 'refer' && $this->uri->segment(2) == 'id')
		{
			$userdata = explode('_', $this->uri->segment(3));
			$this->config->set_item('jrox_site_referral', $userdata[0]);
		}
		elseif ($this->uri->segment(1) != AFF_TOOLS_ROUTE && strlen(AFFILIATE_ROUTE) < 1)
		{			
			//check if there is a program ID appended to the username
			$userdata = explode('_', $this->uri->segment(1));
			$this->config->set_item('jrox_site_referral', $userdata[0]);
		}
		else
		{
			//check if there is a program ID appended to the username
			$userdata = explode('_', $this->uri->segment(2));
			
			$this->config->set_item('jrox_site_referral', $userdata[0]);
		}
		
		
		$this->program_id = !empty($userdata[1]) ? $userdata[1] : '1';
		
		//load models
		$this->load->model('programs_model');
		$this->load->model('groups_model');
		$this->load->model('commissions_model');
		//load required models
		$this->load->model('init_model', 'init');		
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		redirect_301();	
	}
	
	// ------------------------------------------------------------------------
	
	function banners()
	{
		//get banner data
		$id = (int)$this->uri->segment(3);
		$this->db->where('id', $id);
		$query = $this->db->get('affiliate_banners');
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			
			header('Location:'  . base_url() . 'images/banners/' . $row['banner_file_name']);	
		}
	}
	
	// ------------------------------------------------------------------------
	
	function id()
	{		
		//set data array
		$data = $this->security_model->_load_config('public', __CLASS__,  __FUNCTION__);
		
		//check for blocked clicks
		$discard = $this->aff->_block_clicks();
		
		//load the language file
		$this->lang->load('common', $this->config->item('sts_site_default_language'));
		
		$tool_data['program_id'] = $this->program_id;
		
		if ($this->uri->segment(3) == 'tool' && $this->uri->segment(4) != false)
		{
			$tool_type = $this->uri->segment(4, 0);
			$tool_id = (int)$this->uri->segment(5, 0);
				
			//get tool data
			$tool_data = $this->aff->_get_tool_details($tool_type, $tool_id);	
		}
		
		//get program data
		$prog = $this->programs_model->_get_program_basic('program_id', $tool_data['program_id']);
			
		//set the default url to redirect to
		$url_redirect = !empty($prog['url_redirect']) ? $prog['url_redirect'] : base_url();
		
		//run referral tracking functions
		$aff_data = $this->aff->_validate_user($this->config->item('jrox_site_referral'));
		
		if (!empty($aff_data))
		{
			$tool_type = $this->uri->segment(4, 0);
			$tool_id = (int)$this->uri->segment(5, 0);
				
			//check if this is a marketing tool URL
			if ($this->uri->segment(3) == 'tool' && $this->uri->segment(4) != false)
			{
				//get tool data
				$tool_data = $this->aff->_get_tool_details($tool_type, $tool_id);
			}
			
			//check for pay per click
			if (!empty($prog))
			{
				if (!empty($prog['enable_pay_per_click']) && !empty($prog['ppc_interval']))
				{
					//check ppc_interval
					if ($this->aff->_check_ppc($prog, $this->input->ip_address()))
					{
						$discard = true;
					}
				}
				
				if (!empty($prog['url_redirect']))
				{
					$url_redirect = $prog['url_redirect'];		
				}
			}			
			
			if (!empty($tool_data))
			{
				$tool_data['type'] = $tool_type;	
				if (!empty($tool_data['enable_redirect']))
				{
					$url_redirect = $tool_data['redirect_custom_url'];	
				}
			}
				 
			//check if we are overwriting the cookie
			if ($this->config->item('sts_affiliate_ovewrite_existing_cookie') == false && check_tracking_cookie() == true)
			{
				$discard = true;
			}
			
			//record the traffic click
			if (empty($discard))
			{
				//get group amount for ppc
				$group = $this->groups_model->_get_aff_group_details($prog['group_id']);
						
				//add the ppc
				if (!empty($prog['enable_pay_per_click']) && !empty($prog['ppc_interval']))
				{
					$this->commissions_model->_add_ppc($aff_data['mid'], $tool_data, $group['ppc_amount']);	
				}
				
				//set tracking cookie
				$code = $this->aff->_generate_traffic_code();
				$cid = $aff_data['mid'] . '-' . $tool_data['program_id'] . '-' . $aff_data['username'] .  '-'. $code;
				
				if ($cookie = set_tracking_cookie($cid))
				{
					//insert the tracking data 
					$sdata = array(	'date'	=> _generate_timestamp(),
									'member_id'	=> $aff_data['mid'],
									'program_id' => empty($tool_data['program_id']) ? '' : $tool_data['program_id'],
									'tracking_code' => $code,
									'tool_type'	=>	$tool_type,
									'tool_id'	=>	$tool_id,
									'referrer'	=>	$this->agent->referrer(),
									'ip_address'	=>	$this->input->ip_address(),
									'user_agent'	=>	$this->agent->agent_string(),
									'os'			=>	$this->agent->platform(),
									'browser'		=>	$this->agent->browser(),
									'isp'			=> gethostbyaddr($this->input->ip_address()),
									);
					
					if ($this->uri->segment(2) == 'tracker')
					{
						$sdata['tracker'] = $this->uri->segment(3);	
						
						set_tracking_cookie($sdata['tracker'], 'custom_tracker');
					}
					
					if ($this->config->item('enable_geo_location_api') == true)
					{
						$geo = connect_curl($this->config->slash_item('geo_location_api_url') . $this->input->ip_address . '/geo');
		
						if (!empty($geo))
						{
							$geo_data = json_decode($geo);	
							
							$sdata['region'] = !empty($geo_data['region']) ? $geo_data['region'] : '';
							$sdata['country_code'] = !empty($geo_data['country']) ? $geo_data['country'] : '';
						}
					}
					
					$this->aff->_insert_affiliate_traffic($sdata);
				
				
					//set the session data
					$this->session->set_userdata($cookie, serialize($aff_data));
					$this->session->set_userdata('jrox_site_referral_regular', $cookie);
				}
			}
		}
		
		//set page defaults
		$page = $url_redirect;
		
		if ($this->uri->segment(2) == 'signup' || $this->uri->segment(3) == 'signup')
		{
			$page = _public_url() . 'registration/' . $aff_data['username']; 
		}
		elseif ($aff_data['enable_custom_url'] == 1 && !empty($aff_data['custom_url_link']))
		{
			//redirect to the user's custom web page
			$page = $aff_data['custom_url_link'];
		}
		
		if (!empty($aff_data))
		{
			//check for custom redirects
			foreach ($aff_data as $k => $v)
			{
				if ($aff_data != 'password')
				{
					$page = str_replace('{' . $k . '}', $aff_data[$k], $page);
				}
			}
		}
		/*
		if (empty($discard))
		{
			$data['cookie_data'] = $cid;
			$data['cookie_name'] = $this->config->item('aff_cookie_name');
		 	$data['url'] = $page;
			$this->parser->_JROX_load_view('tpl_set_tracking', 'none', $data, false, false);
		}
		*/
		
		$page = str_replace('{cid}', $cid, $page);
		redirect_301($page, true);	//default base url redirect
		
	}
	
	// ------------------------------------------------------------------------
	
	function redirect()
	{
		$cid = true;
		
		if ($this->uri->segment(3) == 'uid')
		{
			$cid = false;	
		}
		
		$ids = explode('_', $this->uri->segment(4));
	
		$aff_data = $this->aff->_validate_user($ids[0], $cid);	
		$pid = 1;
			
		if (count($ids) > 1)
		{
			$pid = $ids[1];
			$tool_id = $ids[3];
			
			switch ($ids[2])
			{
				case 'bid':
					$a = 'banners';
				break;
				
				case 'taid':
					$a = 'text_ads';
				break;
				
				case 'tlid':
					$a = 'text_links';
				break;
				
				case 'haid':
					$a = 'hover_ads';
				break;
				
				case 'eaid':
					$a = 'email_ads';
				break;
				
				case 'aaid':
					$a = 'article_ads';
				break;
			}
		
			$link = _public_url() . AFF_TOOLS_ROUTE . '/' . $aff_data['username'] . '/tool/' . $a . '/' . $tool_id;
		}
		else
		{
			$link = _get_aff_link($aff_data['username']);
		}
		
		//get program data
		$prog = $this->programs_model->_get_program_basic('program_id', $pid);
		
		if (!empty($link))
		{
			if ($this->uri->segment(5) == 'jxURL')
			{
				$code = $this->aff->_generate_traffic_code();
				$cid = $aff_data['mid'] . '-1-' . $aff_data['username'] .  '-'. $code;
				if ($cookie = set_tracking_cookie($cid))
				{
					//insert the tracking data
					$sdata = array(	'date'	=> _generate_timestamp(),
									'member_id'	=> $aff_data['mid'],
									'program_id' => empty($tool_data['program_id']) ? '1' : $tool_data['program_id'],
									'tracking_code' => $code,
									'tool_type'	=>	 empty($a) ? '' : $a,
									'tool_id'	=>	empty($tool_id) ? '' : $tool_id,
									'referrer'	=>	$this->agent->referrer(),
									'ip_address'	=>	$this->input->ip_address(),
									'user_agent'	=>	$this->agent->agent_string(),
									'os'			=>	$this->agent->platform(),
									'browser'		=>	$this->agent->browser(),
									'isp'			=> gethostbyaddr($this->input->ip_address()),
									);
					$this->aff->_insert_affiliate_traffic($sdata);
				
				 
					//set the session data
					$this->session->set_userdata($cookie, serialize($aff_data));
					$this->session->set_userdata('jrox_site_referral_regular', $cookie);
				}
				
				//load convert library
				$this->load->library('convert');
				$url = base64_decode($this->convert->HexToAscii($this->uri->segment(6))); 
				
				$link = urldecode($url);	
			}
			
			//generate aff link
			redirect_301($link, true);
		}
		else
		{
			redirect_301($prog['url_redirect'], true);	
		}
	}
	
	// ------------------------------------------------------------------------
}
?>