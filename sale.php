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
| FILENAME - sale.php
| -------------------------------------------------------------------------     
| 
| This file controls commission triggers for JAM version 1
| copy this file to the root of the JAM folder if you want to use it
|
*/


require_once('JAM.php');

require_once APPPATH . '/config/config.php';

if (!empty($_GET))
{
	$url = '';
	foreach ($_GET as $k => $v)
	{
		$url .= $k . '/' . $v . '/';	
	}
	
	$url .= 'tracking_code/' . $_COOKIE['jamcom'];
}

if (!empty($_GET['amount']))
{
	//header("Location:" . $config['base_url'] . "/sale/" . $url);
	echo file_get_contents($config['base_SSL_url'] . "/sale/" . $url);
}