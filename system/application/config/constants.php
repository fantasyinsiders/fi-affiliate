<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 					'ab');
define('FOPEN_READ_WRITE_CREATE', 				'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 			'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
|--------------------------------------------------------------------------
| JEM Constants
|--------------------------------------------------------------------------
|
*/

define('CURL_PROXY_REQUIRED', false);
define('CURL_PROXY_TUNNEL_FLAG', false);
define('CURL_PROXY_SERVER_DETAILS', '');
define('CURL_SSL_VERIFYPEER', false);

define('MEMBERS_COMMISSIONS_PER_PAGE', '25');
define('MEMBERS_PAYMENTS_PER_PAGE', '25');
define('MEMBERS_CONTENT_PER_PAGE', '25');
define('MEMBERS_TRAFFIC_PER_PAGE', '25');
define('MEMBERS_DOWNLOADS_PER_PAGE', '25');
define('MEMBERS_PROGRAMS_PER_PAGE', '25');
define('MEMBERS_REPORTS_PER_PAGE', '25');
define('MEMBERS_TRACKING_PER_PAGE', '25');
define('ADMIN_DEFAULT_MEMBERS_PAGE_VIEW', 'tpl_adm_manage_members');
define('ADMIN_SESSION_EXPIRATION_TIMER', '14400000');
define('ALLOW_ZERO_AMOUNT_COMMISSIONS', false);

/* End of file constants.php */
/* Location: ./system/application/config/constants.php */