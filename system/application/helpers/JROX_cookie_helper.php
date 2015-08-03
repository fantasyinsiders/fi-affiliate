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
| FILENAME: JROX_cookie_helper.php
| -------------------------------------------------------------------------     
| 
| This file extends the cookie_helper
|
*/

// ------------------------------------------------------------------------

function set_tracking_cookie($id = '', $cookie = 'aff_cookie_name', $encrypt = false)
{
	$CI = & get_instance();
	
	$cookie_value = $id;	
	
	if ($encrypt == true)
	{
		$cookie_value = $CI->encrypt->encode($id, JROX_COOKIE_ENCRYPT);
	}
	
	$cookie_name = $cookie != 'aff_cookie_name' ? $cookie : $CI->config->item($cookie);
	
	$cookie = array('name'   => $cookie_name,
					 'value'  => $cookie_value,
					 'expire' => 60 * 60 * 24 * $CI->config->item('sts_affiliate_cookie_timer')
				   );
	
	
	//set the affiliate cookie
	set_cookie($cookie);
	
	return $cookie_value;
}

// ------------------------------------------------------------------------

function _generate_cookie_name()
{
	$CI = & get_instance();
	
	$total = $CI->db->count_all('programs') + 1;
	
	return $CI->config->item('aff_cookie_name') . $total;
}

// ------------------------------------------------------------------------

function set_membership_cookies($array = '')
{
	$CI = & get_instance();
	
	$cookie = array('name'   => 'jrox_login',
					 'value'  => 'yes',
					 'expire' => $CI->config->item('sess_expiration_pub'),
				   );
	
	//set the login cookie
	set_cookie($cookie);
	
	//set each membership cookie now
	foreach ($array as $row)
	{
		$mcookie = array('name'   => 'jrox_group_login_'.$row,
						'value'  => 'yes',
						'expire' => $CI->config->item('sess_expiration_pub'),
				   );
	
		//set the membershop cookie
		set_cookie($mcookie);
	}	
	
	//set the session
	$CI->session->set_userdata('jrox_current_memberships', $array);

	return true;
}

// ------------------------------------------------------------------------

function check_tracking_cookie()
{
	$CI = & get_instance();
	$cookie_name = $CI->config->item('aff_cookie_name');
				
	if (empty($_COOKIE[$cookie_name]))
	{
		return false;
	}
	
	return true;
}

// ------------------------------------------------------------------------

function retrieve_cookie_data()
{
	$CI = & get_instance();
	$cookie_name = $CI->config->item('aff_cookie_name');
				
	if (!empty($_COOKIE[$cookie_name]))
	{
		$data = explode('-', $_COOKIE[$cookie_name]);
	
		return $data;
	}
	
	return false;
}

?>