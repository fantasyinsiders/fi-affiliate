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
| FILENAME - js.php
| -------------------------------------------------------------------------     
| 
| This controller file is used for javascript calls
|
*/

class Js extends Public_Controller {

	function __construct()
	{
		parent::__construct();
		//load css body style
		$this->config->set_item('css_body', 'jroxHome');
		
		//load required models
		$this->load->model('init_model', 'init');		
	}
	
	// ------------------------------------------------------------------------
	
	function type()
	{		
		switch ($this->uri->segment(3))
		{
			case 'page_peel_ads':
		
				//load required model
				$this->load->model('modules/module_affiliate_marketing_page_peel_ads_model', 'page_peel_ads_model');
		
				$user = $this->aff->_validate_user($this->uri->segment(5));
				
				if (!empty($user))
				{
					$link = _public_url() .  _jrox_slash_item(AFF_TOOLS_ROUTE) . $user['username'] . '/tool/' . $this->uri->segment(3) . '/' . $this->uri->segment(4);
					
					$mdata = $this->page_peel_ads_model->_get_page_peel_ad_details((int)$this->uri->segment(4));
			
					$a = $this->page_peel_ads_model->_generate_page_peel_js($mdata, true, $link);	
				
					echo $a;
				}
				
			break;
		}
	}
	
	// ------------------------------------------------------------------------
	
	function track()
	{
		/*
		|---------------------------------------
		| show tracking code in a hidden form field
		|----------------------------------------
		*/
		
		$cookie_name = $this->config->item('aff_cookie_name');
				
		if (!empty($_COOKIE[$cookie_name]))
		{
			if ($this->uri->segment(3))
			{
				$col = $this->uri->segment(3);
				
				echo 'document.write(\'<input type="hidden" name="' . xss_clean($col) . '" value="' . $_COOKIE[$cookie_name] . '">\')';	
			}
		}
	}
	
	
	// ------------------------------------------------------------------------
	
	function custom_tracker()
	{
		/*
		|---------------------------------------
		| show custom tracker code
		|----------------------------------------
		*/
				
		if (!empty($_COOKIE['custom_tracker']))
		{
			echo xss_clean($_COOKIE['custom_tracker']);
		}
	}
	
	// ------------------------------------------------------------------------
	
	function show()
	{
		/*
		|---------------------------------------
		| show affiliate fields using javascript
		|----------------------------------------
		*/
		
		if ($this->config->item('sts_affiliate_allow_javascript_info') == '0') { die(); }

		if ($this->uri->segment(5))
		{
			$c = explode('-', $this->uri->segment(5));
		}
		else
		{
			$c = retrieve_cookie_data();
		}
		
		if (!empty($c)) 
		{
			$user = $this->aff->_validate_user($c[0], true);
			$col = $this->uri->segment(3);
			
			if (!empty($user[$col]))
			{
				if ($this->uri->segment(4) == 'field')
				{
					echo 'document.write(\'<input type="hidden" name="' . xss_clean($col) . '" value="' . xss_clean($user[$col]) . '">\')';
				}
				else
				{
					echo 'document.write(\'' . xss_clean($user[$col]) . '\')';
				}
			}
		}
	}
	
	// ------------------------------------------------------------------------
	
	function remote()
	{
		/*
		|----------------------------------------------------
		| set tracking cookie using remote links
		|----------------------------------------------------
		*/
		
		$ref = $this->input->post('ref', true);
		
		$program_id = $this->uri->segment(3,1);
		
		if ($ref)
		{
			$url = parse_url($ref);
			
			if (!empty($url['path']))
			{
				$user = str_replace('/','',$url['path']);
			}
		}	
		elseif ($this->uri->segment(5) == 'username')
		{
			$user = $this->uri->segment(4);
		
			if ($this->uri->segment(6) != $this->config->item('sts_auto_login_secret')) exit();
		}
		
		if (!empty($user))
		{
			$aff_data = $this->aff->_validate_user($user);
			
			if (!empty($aff_data))
			{
				//set tracking cookie
				$code = $this->aff->_generate_traffic_code();
				$cid = $aff_data['mid'] . '-' . $program_id . '-' . $aff_data['username'] .  '-'. $code;
				//insert the tracking data
				$sdata = array(	'date'	=> _generate_timestamp(),
								'member_id'	=> $aff_data['mid'],
								'program_id' => $program_id,
								'tracking_code' => $code,
								'tool_type'	=>	'',
								'tool_id'	=>	'',
								'referrer'	=>	$this->input->post('ref'),
								'ip_address'	=>	$_SERVER['REMOTE_ADDR'],
								'user_agent'	=>	$this->agent->agent_string(),
								'os'			=>	$this->agent->platform(),
								'browser'		=>	$this->agent->browser(),
								'isp'			=> gethostbyaddr($_SERVER['REMOTE_ADDR']),
								);
				$this->aff->_insert_affiliate_traffic($sdata);
			
				$this->sess_cookie = $this->config->item('cookie_prefix').$this->config->item('aff_cookie_name');
	
				$c = array(
						  'name' => $this->sess_cookie,
						  'value' => $cid,
						  'length' => (int)$this->config->item('sts_affiliate_cookie_timer'),
						  'path'	=> $this->config->item('cookie_path'), 
						  'domain'	=> $url['host'], 
						  );	
				
				echo json_encode($c);	
			}
		}
	}
	
	// ------------------------------------------------------------------------
	
	function user()
	{
		/*
		|----------------------------------------------------
		| show affiliate info based on username
		|----------------------------------------------------
		*/
		
		if ($this->uri->segment(3))
		{
			$user = $this->aff->_validate_user($this->uri->segment(3));
			
			//show default field
			$field = $this->uri->segment(4,'username');
			
			if ($field != 'password')
			{
				if ($field == 'photo_file_name')
				{
					echo 'document.write(\'<img src="' . base_url() . '/images/members/' . xss_clean($user[$field]) . '" border="0" class="memberPhoto"/>\')';
				}
				else
				{
					if ($this->uri->segment(5) == 'php')
					{
						echo  xss_clean($user[$field]);
					}
					else
					{
						echo 'document.write(\'' . xss_clean($user[$field]) . '\')';
					}
				}
			}
		}
	}
	
	// ------------------------------------------------------------------------
	
	function sub()
	{
		
		/*
		|----------------------------------------------------
		| set tracking cookie using subdomain
		|----------------------------------------------------
		*/
		
		if ($this->uri->segment(4))
		{
			$sub = $this->uri->segment(4);
		}
		else
		{
			preg_match("/^([^\.]+)\./",$_SERVER['HTTP_HOST'],$args);
			$sub = $args[1];
		}
		
		$pid = $this->uri->segment(3, 1);
		$sub = xss_clean($sub);
		
		if ($this->aff->_check_subdomain($sub)) 
		{
			$aff_data = $this->aff->_validate_user($sub);
				
			if (!empty($aff_data))
			{
				//set tracking cookie
				$code = $this->aff->_generate_traffic_code();
				$cid = $aff_data['mid'] . '-' . $pid . '-' . $aff_data['username'] .  '-'. $code;
				
				if ($cookie = set_tracking_cookie($cid))
				{
					//insert the tracking data
					$sdata = array(	'date'	=> _generate_timestamp(),
									'member_id'	=> $aff_data['mid'],
									'program_id' => empty($pid) ? '' : $pid,
									'tracking_code' => $code,
									'tool_type'	=>	'dynamic',
									'tool_id'	=>	0,
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
			}	
		}
	}
}
?>