<?php
#####################################################################################
## JROX.COM Affiliate Manager - admin/index.php file
## Version 1.6.2
##      
## Author: 			Ryan Roxas(ryan@jrox.com)              
## Homepage:	 	http://jam.jrox.com
## Bug Reports: 	http://jam.jrox.com/bugzilla/
## Release Notes:	docs/READ_ME.txt
#######################################################################################

#######################################################################################
## COPYRIGHT NOTICE                                                     
## Copyright 2007 JROX Technologies, Inc.  All Rights Reserved.       
##                                                                        
## This script may be only used and modified in accordance to the license      
## agreement attached (license.txt) except where expressly noted within      
## commented areas of the code body. This copyright notice and the  
## comments above and below must remain intact at all times.  By using this 
## code you agree to indemnify JROX Technologies, Inc, its corporate agents   
## and affiliates from any liability that might arise from its use.                                                        
##                                                                           
## Selling the code for this program without prior written consent is       
## expressly forbidden and in violation of Domestic and International 
## copyright laws.  		                                           
#######################################################################################

#######################################################################################
## This file is the main logic file for admin login and password reset
#######################################################################################

require '../includes/common.php';
require_once '../includes/sessions.php';
require_once '../includes/setlang.php';

$id = '';

Db_Connect();

if (empty($_GET['bid']))
{
$query = mysql_query("SELECT *, DATE_FORMAT(date_added,'%a %b %d %Y %h:%i %p') AS date_added, DATE_FORMAT(date_modified,'%a %b %d %Y %h:%i %p') AS date_modified FROM jx_banners ORDER BY bid ASC") or die(cmysql_error(__LINE__, __FILE__));
	
	if ($query)
	{
		
		while ($row = mysql_fetch_assoc($query))
		{
			if ($row['store_in_db'] == '1')
			{
				//print_r($row);
				echo '<div style="float:left; padding: 8px"><img src="/affiliates/admin/banner_export.php?bid=' . $row['bid'].'" />';
				echo '<br />';
				echo $row['bid'] . ' = ' . $row['banner_file_name'] . '<br />
				</div>';
			}
		}
	}

}
else
{
	$query = mysql_query("SELECT *, DATE_FORMAT(date_added,'%a %b %d %Y %h:%i %p') AS date_added, DATE_FORMAT(date_modified,'%a %b %d %Y %h:%i %p') AS date_modified FROM jx_banners WHERE bid = " . $_GET['bid']) or die(cmysql_error(__LINE__, __FILE__));
	
	if ($query)
	{
		
		$row = mysql_fetch_assoc($query);
		if ($row['store_in_db'] == '1')
		{
		//print_r($row); exit();
			header("Content-type: " . $row['banner_file_type']);
			header('Content-disposition: inline;filename="' . $row['banner_file_name'] . '"'); 	
			echo $row['banner_file_bin_data'];
		}
		
	}
	
}
?>
