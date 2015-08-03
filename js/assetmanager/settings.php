<?php
require dirname(dirname(dirname(__FILE__))) . '/JAM.php';
require dirname(dirname(dirname(__FILE__))) . '/system/application/config/config.php';
require dirname(dirname(dirname(__FILE__))) . '/system/application/config/database.php';

//get db values
$jem_sql = mysql_connect($db['default']['hostname'], $db['default']['username'], $db['default']['password']);
$jem_connect = mysql_select_db($db['default']['database'], $jem_sql);

//check for perms
$jrox_sess_cookie = $config['sess_cookie_name'];
if (empty($_COOKIE[$jrox_sess_cookie]))
{
	exit();
}
else
{
	$check = mysql_query("SELECT * FROM " . $db['default']['dbprefix'] . "sessions 
							WHERE session_id = '" . $_COOKIE[$jrox_sess_cookie] . "'")
							or die(mysql_error());
	
	if (mysql_num_rows($check) < 1)
	{
		exit();
	}
	else
	{
		$check_row = mysql_fetch_assoc($check);
		
		if (!empty($check_row['session_data'])) 
		{
			$jem_sess = unserialize($check_row['session_data']);
			if (empty($jem_sess['adminid'])) exit();
		}
		else
		{
			exit();
		}
	}
	$AllowedTypes = 'jpg|jpeg|gif|png|txt|pdf|zip';
	
	//get values
	$jem_query = mysql_query("SELECT settings_key, settings_value 
								FROM " . $db['default']['dbprefix'] . "settings 
								WHERE settings_key = 'sts_image_max_photo_size' 
								OR settings_key = 'sts_site_upload_photo_types'") 
								or die(mysql_error());

	if (mysql_num_rows($jem_query) > 0)
	{
		while ($row = mysql_fetch_assoc($jem_query))
		{
			if ($row['settings_key'] == 'sts_image_max_photo_size')
			{
				$MaxFileSize = $row['settings_value'] * 1000;
			}
			elseif ($row['settings_key'] == 'sts_site_upload_photo_types')
			{
				$AllowedTypes = $row['settings_value'];
			} 
		}
	}
	
	$bReturnAbsolute=true;
	
	$sBaseVirtual0 = $config['base_folder_path'] . "/images";  //Assuming that the path is http://yourserver/Editor/assets/ ("Relative to Root" Path is required)
	$sBase0 = $config['base_physical_path'] . "/images"; //The real path
	//$sBase0="/home/yourserver/web/Editor/assets"; //example for Unix server
	$sName0="images";


define("WEBSITEROOT_LOCALPATH", $_SERVER['DOCUMENT_ROOT']);
//define("UPLOAD_FILE_TYPES", "jpg|jpeg|gif|png|txt|pdf|zip");
define('UPLOAD_FILE_TYPES', $AllowedTypes);

}
?>