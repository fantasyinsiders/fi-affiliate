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
| FILENAME - direct_track.php
| -------------------------------------------------------------------------     
| 
| This controller file is for tracking invisilinks
|
*/

class Direct_Track extends Public_Controller {

	function __construct()
	{
		parent::__construct();
		//load models
		$this->load->model('programs_model');
	}
	
	// ------------------------------------------------------------------------
	
	function index()
	{
		if ($this->input->post('ref'))
		{
			// get last two segments of host name
			preg_match('@^(?:http://)?([^/]+)@i', $this->input->post('ref'), $matches);
			
			$this->db->like('invisilink_url', $matches[1]);
			$this->db->where('status', '1');
			
			$query = $this->db->get('affiliate_invisilinks');
		
			if ($query->num_rows() > 0)
			{
				$row = $query->row_array();
				
				$aff_data = $this->aff->_validate_user($row['member_id'], true);
				
				//check for program ID
				$program_id = !empty($_POST['pid']) ? (int)$_POST['pid'] : 1;
				
				//get program data
				$prog = $this->programs_model->_get_program_basic('program_id', $program_id);
		
				if (!empty($aff_data))
				{
					$tool_type = $this->uri->segment(4, 0);
					$tool_id = (int)$this->uri->segment(5, 0);
					
					//set tracking cookie
					$code = $this->aff->_generate_traffic_code();
					$cid = $aff_data['mid'] . '-' . $program_id . '-' . $aff_data['username'] .  '-'. $code;
					//insert the tracking data
					$sdata = array(	'date'	=> _generate_timestamp(),
									'member_id'	=> $aff_data['mid'],
									'program_id' => empty($tool_data['program_id']) ? '' : $tool_data['program_id'],
									'tracking_code' => $code,
									'tool_type'	=>	'invisilinks',
									'tool_id'	=>	$row['id'],
									'referrer'	=>	$this->input->post('ref'),
									'ip_address'	=>	$_SERVER['REMOTE_ADDR'],
									'user_agent'	=>	empty($_POST['user_agent']) ? $this->agent->agent_string() : $this->input->post('user_agent', true),
									'os'			=>	$this->agent->platform(),
									'browser'		=>	$this->agent->browser(),
									'isp'			=> gethostbyaddr($_SERVER['REMOTE_ADDR']),
									);
					$this->aff->_insert_affiliate_traffic($sdata);
				
				
					//set the session data
					//$this->session->set_userdata($cookie, serialize($aff_data));
					//$this->session->set_userdata('jrox_site_referral_regular', $cookie);
				
					$this->sess_cookie = $this->config->item('cookie_prefix').$this->config->item('aff_cookie_name');

					$c = array(
							  'name' => $this->sess_cookie,
							  'value' => $cid,
							  'length' => (int)$this->config->item('sts_affiliate_cookie_timer'),
							  'path'	=> $this->config->item('cookie_path'), 
							  'domain'	=> $this->config->item('cookie_domain'), 
							  );	
					
					echo json_encode($c);	
				}
			}
		}	
	}
}
?>