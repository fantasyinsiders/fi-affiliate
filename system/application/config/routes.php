<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| 	www.your-site.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved 
| routes must come before any wildcard or regular expression routes.
|
*/

//ADMIN LOGIN ROUTE
define('ADMIN_LOGIN_ROUTE', 'admin_login');
$route[ADMIN_LOGIN_ROUTE] = "admin_login";

//ADMIN ROUTING
define('ADMIN_ROUTE', 'admin'); 
$route[ADMIN_ROUTE] = ADMIN_ROUTE.'/dashboard';

//AFFILIATE ROUTING
define('AFFILIATE_ROUTE', '');
$route[AFFILIATE_ROUTE.'/:any'] = "refer/id/$1";

//AFFILIATE TOOLS ROUTING
define('AFF_TOOLS_ROUTE', 'aff_tools');
$route[AFF_TOOLS_ROUTE.'/:any'] = "refer/id/$1";

//REPLICATION ROUTING
define('REPLICATION_ROUTE', 'reps');
$route[REPLICATION_ROUTE.'/:any'] = "reps/id/$1";

define('PROFILE_ROUTE', 'profiles');
$route[PROFILE_ROUTE.'/([a-z0-9A-Z]+)'] = "profiles/users/$1";

//CONTENT ROUTING
define('CONTENT_ROUTE', 'content');

//CONTENT CATEGORIES ROUTING
define('CONTENT_CATEGORIES_ROUTE', 'content_categories');
$route[CONTENT_CATEGORIES_ROUTE.'/:any'] = "content_categories/$1";

//FAQ ROUTING
define('FAQ_ROUTE', 'faq');
$route[FAQ_ROUTE. '/:any'] = "faq/index/$1";

//QR CODE
$route['qr/:any'] = "qr/index/$1";

//TERMS OF SERVICE
define('TOS_ROUTE', 'tos');
$route[TOS_ROUTE] = "content/index/";
$route[TOS_ROUTE. '/:any'] = "content/index/$1";

//PRIVACY POLICY
define('PRIVACY_ROUTE', 'privacy');
$route[PRIVACY_ROUTE] = "content/index/";
$route[PRIVACY_ROUTE. '/:any'] = "content/index/$1";

//FAQ CATEGORIES
define('FAQ_CATEGORIES_ROUTE', 'faq_categories');
$route[FAQ_CATEGORIES_ROUTE.'/:any'] = "faq_categories/$1";

//URI ROUTING OPTION
define('AFF_ROUTE_OPTION', 'regular'); //change to regular or replication

//PROGRAMS ROUTE
define('PROGRAM_ROUTE', 'program');
$route[PROGRAM_ROUTE] = "program/id/$1";
$route[PROGRAM_ROUTE. '/:any'] = "program/id/$1";

//ACCESS ROUTE
define('ACCESS_ROUTE', 'access');
$route[ACCESS_ROUTE. '/:any'] = "access/index/$1";

$route['switch_language/:any'] = "access/switch_language/$1";

//REGISTRATION ROUTE
$route['registration/:any'] = "registration/index/$1";

//LOGIN
$route['login/:any'] = "login/index/$1";

//RESET PASSWORD
$route['reset_password/:any'] = "reset_password/index/$1";

//ROTATOR ROUTING
$route['rotator/:any'] = "rotator/rotate/$1/$2";

//MEMBERS ROUTING
define('MEMBERS_ROUTE', 'members');
$route[MEMBERS_ROUTE] = 'members/dashboard';


//LOGOUT
$route[MEMBERS_ROUTE .'/logout/:any'] = MEMBERS_ROUTE . "/logout/index/$1";

//MEMBERS CONTENT ROUTE
define('MEMBERS_CONTENT_ROUTE', 'members');
$route[MEMBERS_CONTENT_ROUTE] = 'members/dashboard';

//404 ERROR ROUTING
define('ERROR_404_ROUTE', 'error');

//REPORT ROUTE
$route[MEMBERS_ROUTE . '/report/([a-z0-9A-Z_]+)/([0-9]+)/([0-9]+)'] = "modules/$1/generate/$2/$3";
$route[MEMBERS_ROUTE . '/report'] = 'members/reports';

$route[ADMIN_ROUTE . '/report/([a-z0-9A-Z_]+)/([0-9]+)/([0-9]+)'] = "modules/$1/generate/$2/$3";

//TRACKER
define('TRACK_ROUTE', 'go');
$route[TRACK_ROUTE.'/:any'] = "track/id/$1";

//SALE ROUTE
define('SALE_ROUTE', 'sale');
$route[SALE_ROUTE.'/:any'] = "sale/index/$1";

//MOBILE ROUTE
$route['m'] = "mobile/index/$1";
$route['m/:any'] = "mobile/index/$1";

$route['vac/:any'] =  ADMIN_LOGIN_ROUTE . "/reset_access/verify/$1";



//DEFAULT ROUTES
$route['default_controller'] = "program";

$route['scaffolding_trigger'] = "dixlaerox2882jd92u8d43qadf82dd9aj2jd9a2j23jd8s";

if (file_exists(APPPATH . 'config/custom_routes.php'))
{
	include APPPATH . 'config/custom_routes.php';	
}
?>