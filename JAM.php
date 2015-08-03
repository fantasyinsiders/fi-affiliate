<?php
define('APP_VERSION', '3.0.42');
define('APP_REVISION_NUMBER', '04093042');

$system_path = 'system';
$application_folder = 'application';

// Set the current directory correctly for CLI requests
if (defined('STDIN'))
{
	chdir(dirname(__FILE__));
}

if (realpath($system_path) !== FALSE)
{
	$system_path = realpath($system_path).'/';
}
 
// ensure there's a trailing slash
$system_path = rtrim($system_path, '/').'/';

// Is the system path correct?
if ( ! is_dir($system_path))
{
	//exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME));
}

/*
* -------------------------------------------------------------------
*  Now that we know the path, set the main path constants
* -------------------------------------------------------------------
*/
// The name of THIS file
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

// The PHP file extension
// this global constant is deprecated.
define('EXT', '.php');

// Path to the system folder
define('BASEPATH', str_replace("\\", "/", $system_path));

// Path to the front controller (this file)
define('FCPATH', str_replace(SELF, '', __FILE__));

// Name of the "system folder"
define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));


// The path to the "application" folder
if (is_dir($application_folder))
{
	define('APPPATH', $application_folder.'/');
}
else
{
	if ( ! is_dir(BASEPATH.$application_folder.'/'))
	{
		//exit("Your application folder path does not appear to be set correctly. Please open the following file and correct this: ".SELF);
	}

	define('APPPATH', BASEPATH.$application_folder.'/');
}

define('PUBPATH',realpath(dirname(__FILE__)));

include_once(APPPATH . 'config/debug.php');

if (defined('ENVIRONMENT') && ENVIRONMENT == 'development')
{
    error_reporting(E_ALL);
}
else
{
    error_reporting(0);
}

if ($_SERVER['HTTP_HOST'])
{
	preg_match("/^([^\.]+)./",$_SERVER['HTTP_HOST'],$args);
	if ($args[1] != 'www' && $args)
	{
		//define('CURRENT_SUBDOMAIN', $args[1]);
	}
}
?>