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
| FILENAME - fb_redir.php
| -------------------------------------------------------------------------     
| 
| This controller file is used for the facebook connect redirects
|
*/

require_once 'JAM.php';
require_once APPPATH . '/config/config.php';
if (!empty($_GET['error_reason']) && $_GET['error_reason'] == 'user_denied')
{
	header('Location:' . $config['facebook_deny_url'] . '/' . $_GET['pid']);
}

else
{
	header('Location:' . $config['base_url'] . '/fb/login/' . $_GET['pid']);	
}

exit();
?>