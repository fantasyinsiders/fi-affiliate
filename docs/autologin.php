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
| FILENAME - autologin.php
| -------------------------------------------------------------------------     
| 
| This is an example file on how to use the automation API in JAM.
|
*/

$url = 'http://www.yourdomain.com/affiliates'; //NO TRAILING SLASH 
$access_key = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; 
$access_id = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
$aff_cookie = 'jamcom';

switch ($_GET['type'])
{
	// ------------------------------------------------------------------------
	
	case 'register':
		
		$sdata = array(
					   'access_key' => $access_key,
					   'access_id' => $access_id,
					   
					   'email' => 'automation@jrox.com',
					   'fname' => 'automation',
					   //'affiliate_group' => '2',
					   //'password' => 'dc724af18fbdd4e59189f5fe768a5f8311527050',
					   //'encrypted' => true
					   'program_id' => '2',
					   'sponsor' => !empty($_COOKIE[$aff_cookie]) ? $_COOKIE[$aff_cookie] : '',
					   );
		
		$fields = "";
		foreach( $sdata as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";
		
		$resp = connect_curl($_GET['type'], $fields);
		
		$result = json_decode( $resp);
		
		echo $result->msg;
		
	break;
	
	// ------------------------------------------------------------------------
	
	case 'activate':
		
		$sdata = array(
					   'access_key' => $access_key,
					   'access_id' => $access_id,
					   'email' => 'automation@test.com',
					   );
		
		$fields = "";
		foreach( $sdata as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";
		
		$resp = connect_curl($_GET['type'], $fields);
		
		$result = json_decode( $resp);
		
		echo $result->msg;
		
	break;
	
	// ------------------------------------------------------------------------
	
	case 'deactivate':
		
		$sdata = array(
					   'access_key' => $access_key,
					   'access_id' => $access_id,
					   'email' => 'automation@test.com',
					   );
		
		$fields = "";
		foreach( $sdata as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";
		
		$resp = connect_curl($_GET['type'], $fields);
		
		$result = json_decode( $resp);
		
		echo $result->msg;
		
	break;
	
	// ------------------------------------------------------------------------
	
	case 'update_group':
	
		$sdata = array(
					   'access_key' => $access_key,
					   'access_id' => $access_id,
					   'id' => 'automation@test.com',
					   'field' => 'primary_email',
					   'group_id' => '1',
					   );
		
		$fields = "";
		foreach( $sdata as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";
		
		$resp = connect_curl($_GET['type'], $fields);
		
		$result = json_decode( $resp);
		
		echo $result->msg;
	
	break;
	
	// ------------------------------------------------------------------------
	
	case 'update_program':
	
		$sdata = array(
					   'access_key' => $access_key,
					   'access_id' => $access_id,
					   'id' => 'automation@test.com',
					   'field' => 'primary_email',
					   'program_id' => '1',
					   );
		
		$fields = "";
		foreach( $sdata as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";
		
		$resp = connect_curl($_GET['type'], $fields);
		
		$result = json_decode( $resp);
		
		echo $result->msg;
	
	break;
	
	// ------------------------------------------------------------------------
	
	case 'update_commission':
	
		$sdata = array(
					   'access_key' => $access_key,
					   'access_id' => $access_id,
					   'trans_id' => 'XXXXXXXXXXXX', //the unique transaction ID
					   'key' => 'trans_id',
					   'status' => 'pending', //set to unpaid or pending
					   );
		
		$fields = "";
		foreach( $sdata as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";
		
		$resp = connect_curl($_GET['type'], $fields);
		
		$result = json_decode( $resp);
		
		echo $result->msg;
	
	break;
	
	// ------------------------------------------------------------------------
	
	case 'delete':
		
		$sdata = array(
					   'access_key' => $access_key,
					   'access_id' => $access_id,
					   'email' => 'automation@test.com',
					   );
		
		$fields = "";
		foreach( $sdata as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";
		
		$resp = connect_curl($_GET['type'], $fields);
		
		$result = json_decode( $resp);
		
		echo $result->msg;
		
	break;
	
	// ------------------------------------------------------------------------
	
	case 'logout':
		
		$sdata = array(
					   'access_key' => $access_key,
					   'access_id' => $access_id,
					   'session' => $_COOKIE['jrox_sessionsss'],
					   );
		
		$fields = "";
		foreach( $sdata as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";
		
		$resp = connect_curl($_GET['type'], $fields);
		
		$result = json_decode( $resp);
		
		echo $result->msg;
		
	break;
	
	// ------------------------------------------------------------------------
	
	case 'set_tracking_cookie':
	
		$sdata = array(
					   'access_key' => $access_key,
					   'access_id' => $access_id,
					   'ip' => $_SERVER['REMOTE_ADDR'],
					   'user_agent' => $_SERVER['HTTP_USER_AGENT'],
					   'subdomain' => $_GET['affiliate'], //affiliate username
					   );
		
		$fields = "";
		foreach( $sdata as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";
			
		$resp = connect_curl($_GET['type'], $fields);
		
		$result = json_decode( $resp);

		$jam_cookie = $result->msg;
			
		if (setcookie(
					$jam_cookie->name,
					$jam_cookie->value,
					$jam_cookie->expire,
					$jam_cookie->path,
					$jam_cookie->domain,
					0
					))
		{
			echo 'SUCCESS: tracking_cookie_set';	
		}
		
	
	break;
	
	// ------------------------------------------------------------------------
	
	case 'login':
		$sdata = array(
					   'access_key' => $access_key,
					   'access_id' => $access_id,
					   'ip' => $_SERVER['REMOTE_ADDR'],
					   'user_agent' => $_SERVER['HTTP_USER_AGENT'],
					   'email' => 'automation@test.com',
					   //'password' => 'testing',
					   //'password' => 'dc724af18fbdd4e59189f5fe768a5f8311527050',
					   'bypass_pwd' => 1,
					   'encrypted' => false,
					   );
		
		$fields = "";
		foreach( $sdata as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";
			
		$resp = connect_curl($_GET['type'], $fields);
		
		$result = json_decode( $resp);

		$jam_cookie = $result->msg;
			
		if (setcookie(
					$jam_cookie->name,
					$jam_cookie->value,
					$jam_cookie->expire,
					$jam_cookie->path,
					$jam_cookie->domain,
					0
					))
		{
			echo 'SUCCESS: User logged in';	
		}
		
	break;
}

// ------------------------------------------------------------------------

function connect_curl($type = '', $fields = '')
{
	global $url;
	
	$ch = curl_init($url . '/automate/' . $type);
	
	curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, '50');

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
	curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
	$resp = curl_exec($ch); //execute post and get results
	curl_close ($ch);
	return $resp;		
}
?>
    