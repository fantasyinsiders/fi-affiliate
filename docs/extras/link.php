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
| FILENAME - link.php
| -------------------------------------------------------------------------     
| 
| This controller file is for use with invisilink tracking
|
*/

require_once('JAM.php');
include APPPATH . 'config/config.php';

$url = $config['base_url'] . '/direct_track/index';

if (!empty($_GET))
{
	$data = array_merge($_GET, $_SERVER);
	
	$fields = "";
	foreach( $data as $key => $value ) 
	{
		if (!is_array($value))
		{
			$fields .= "$key=" . urlencode( $value ) . "&";
		}
	}
	
	$ch = curl_init($url);
		
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, '50');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " ));
	$resp = curl_exec($ch); 
	curl_close ($ch);
	
	$jam_cookie = unserialize(base64_decode($resp));
	setcookie(
				$jam_cookie['name'],
				$jam_cookie['value'],
				$jam_cookie['length'],
				$jam_cookie['path'],
				$jam_cookie['domain'],
				0
				);

	//echo 'document.write(\'' . $_GET['ref'] . '\')';
}
?>
