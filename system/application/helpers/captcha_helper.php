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
| FILENAME: captcha_helper.php
| -------------------------------------------------------------------------     
| 
*/

// ------------------------------------------------------------------------

function _generate_captcha($config = 'sts_sec_enable_captcha')
{
	$CI =& get_instance();
	
	$data = '';
	
	if ($CI->config->item('sts_sec_enable_captcha') == 1)
	{
		require_once(APPPATH.'/libraries/recaptchalib.php');
	
		// Get a key from http://recaptcha.net/api/getkey
		$publickey = $CI->config->item('sts_sec_recaptcha_public_key');
		
		# the response from reCAPTCHA
		$resp = '';
		# the error code from reCAPTCHA, if any
		$error = '';
		
		$ssl = $_SERVER['SERVER_PORT'] == '443' ? true : false;
		
		$data = recaptcha_get_html($publickey, $error, $ssl);
	}
	
	return $data;
}

// ------------------------------------------------------------------------	

function _verify_recaptcha($challenge = '', $response = '', $key = '')
{
	$CI =& get_instance();
	
	require_once(APPPATH.'/libraries/recaptchalib.php');

	$resp = recaptcha_check_answer ($key,
									$CI->input->ip_address(),
									$challenge,
									$response);
		
	if ($resp->is_valid) 
	{
		return true;
	}
	
	return false;
}

?>
