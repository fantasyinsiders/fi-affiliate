<?php
/*
| -------------------------------------------------------------------------
| COPYRIGHT NOTICE                                                     
| Copyright 2007 - 2013 JROX Technologies, Inc.  All Rights Reserved.  
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
| FILENAME - jrox.php
| -------------------------------------------------------------------------     
| 
| This file controls redirection for JAM version 1
| copy this file to the root of the JAM folder if you want to use it
|
*/

$use_automation = false;

if ($use_automation == true)
{
	$affiliate_url = 'http://www.yourdomain.com/jam2'; //NO TRAILING SLASH 
	$redirect_url = 'http://www.domain.com';
	$access_key = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; 
	$access_id = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
	
	$sdata = array(
						   'access_key' => $access_key,
						   'access_id' => $access_id,
						   'member_id' => $_GET['id'],
						   //subdomain => $_GET['uid'],
						   );
			
	$fields = "";
	foreach( $sdata as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";
		
	$ch = curl_init($url . '/automate/set_tracking_cookie');
	
	curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, '50');
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
	curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
	$resp = curl_exec($ch); //execute post and get results
	curl_close ($ch);
		
	$jam_cookie = unserialize(base64_decode($resp));
			
	if (setcookie(
				$jam_cookie['name'],
				$jam_cookie['value'],
				$jam_cookie['length'],
				$jam_cookie['path'],
				$jam_cookie['domain'],
				0
				))
	{
		//echo 'SUCCESS: User logged in';	
	}
	
	
	if (!empty($_GET['jxURL']))
	{
		$redirect_url = $_GET['jxURL'];
	}
	
	header("Location:" . $redirect_url);
	exit();

}
else
{
	require_once('JAM.php');
	
	require_once APPPATH . '/config/config.php';
	
	if (!empty($_GET))
	{
		$url = '';
		foreach ($_GET as $k => $v)
		{
			if ($k == 'jxURL')
			{
				require_once APPPATH . '/libraries/Convert.php';
				$convert = new Convert();
				$v = $convert->AsciiToHex(base64_encode($v));
			}
	
			$url .= $k . '/' . $v . '/';	
		
		}
	}
	
	if (!empty($_GET['rep']))
	{	
		header("Location:" . $config['base_url'] . "/reps/" . str_replace('.php', '', $_GET['rep']));
	}
	else
	{
		header("Location:" . $config['base_url'] . "/refer/redirect/" . $url);
		//die($config['base_url'] . "/refer/redirect/" . $url);
	}
}
?>