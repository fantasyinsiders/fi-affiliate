<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Script Limits
|--------------------------------------------------------------------------
*/

$config['script_time_limit'] = '0';

/*
|--------------------------------------------------------------------------
| Folder and License Path
|--------------------------------------------------------------------------
*/
$config['base_license_domain_name'] = 'jemtest.com';
$config['base_physical_path'] = '/var/www/html/jam3';
$config['base_folder_path'] = '/jam3';
$config['base_domain_name'] = 'jemtest.com';

$config['base_subdomain_name'] = 'fedora';

$config['base_mobile_subdomain'] = 'm';
$config['base_url']	= 'http://' . $config['base_subdomain_name'] . '.' . $config['base_domain_name'] . $config['base_folder_path'];
$config['base_SSL_url'] = 'https://' . $config['base_subdomain_name'] . '.' . $config['base_domain_name'] . $config['base_folder_path'];

$config['auto_check_current_version'] = '1';

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
*/

$config['aff_cookie_name']	= 'jamcom';
$config['cookie_prefix']	= '';
$config['cookie_domain']	= '.' .  $config['base_domain_name'];
$config['ssl_cookie_domain']	= '.' . $config['base_domain_name'];
$config['cookie_path']		= '/';
$config['tracking_cookie_name']	= 'jamtracker';
$config['p3p_header'] = 'P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"';

/*
|--------------------------------------------------------------------------
| Facebook Connect Permissions
|--------------------------------------------------------------------------
*/

$config['facebook_permissions'] = 'email,publish_stream'; //email,publish_stream,create_event,rsvp_event,offline_access
$config['facebook_deny_url'] = $config['base_url'] . '/login';

/*
|--------------------------------------------------------------------------
| Pagination Links
|--------------------------------------------------------------------------
*/

$config['admin_pagination_links'] = 2;
$config['member_pagination_links'] = 4;

/*
|--------------------------------------------------------------------------
| Images Path and Settings
|--------------------------------------------------------------------------
*/
$config['images_admins_dir'] = 'admins';
$config['images_programs_dir'] = 'programs';
$config['images_members_dir'] = 'members';
$config['images_banners_dir'] = 'banners';
$config['images_maintain_ratio'] = true;
$config['images_quality'] = '100%';
$config['member_marketing_tool_ext'] = 'png';
$config['pragma_header_cache_control'] = false;
$config['disable_db_autosorting'] = false;

/*
|--------------------------------------------------------------------------
| URL Settings
|--------------------------------------------------------------------------
*/

$config['jrox_url_separator'] = 'dash'; //dash or underscore only
$config['tracker_unique_referrals_only'] = false;
$config['jrox_custom_affiliate_url'] = ''; //set {USERNAME} for the affiliate URL: http://www.domain.com/{USERNAME}

/*
|--------------------------------------------------------------------------
| Admin Settings
|--------------------------------------------------------------------------
*/

$config['admin_login_username_field'] = 'username7296';
$config['admin_login_password_field'] = 'password4029';

/*
|--------------------------------------------------------------------------
| Member Settings
|--------------------------------------------------------------------------
*/

$config['member_min_username_length'] = '6';
$config['members_password_function'] = 'sha1'; //md5, sha1, or mcrypt
$config['member_mass_email_throttle'] = '0';
$config['member_url_logout_redirect'] = '';
$config['member_disable_registration_email'] = false;
$config['member_list_append_unsubscribe'] = true;
$config['member_enable_group_change_registration'] = true;
$config['member_add_to_default_list_on_registration'] = '1';
$config['member_admin_default_downline_view'] = '10';

/*
|--------------------------------------------------------------------------
| Content Settings
|--------------------------------------------------------------------------
*/
$config['content_enable_javascript_code'] = true;

/*
|--------------------------------------------------------------------------
| Links and Customization
|--------------------------------------------------------------------------
*/
$config['customizer_license_ordering_url'] = 'http://jam.jrox.com/pricing/'; //order button
$config['customizer_admin_area_help_url'] = 'http://jam.jrox.com/kb/'; //admin help button
$config['customizer_admin_area_forum_url'] = 'http://community.jrox.com/';
$config['customizer_admin_area_docs_url'] = 'http://jam.jrox.com/kb/';
$config['customizer_admin_area_videos_url'] = 'http://community.jrox.com/videos/jam';
$config['customizer_admin_area_quick_start_url'] = 'http://jam.jrox.com/kb/';
$config['customizer_member_area_help_url'] = 'http://jam.jrox.com/docs/member_docs/'; //members help button

/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
*/
$config['index_page'] = 'index.php';
$config['admin_index_page'] = 'index.php';

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
| 'AUTO'			Default - auto detects
| 'PATH_INFO'		Uses the PATH_INFO
| 'QUERY_STRING'	Uses the QUERY_STRING
| 'REQUEST_URI'		Uses the REQUEST_URI
| 'ORIG_PATH_INFO'	Uses the ORIG_PATH_INFO
|
*/
$config['uri_protocol']	= 'AUTO';

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
*/
$config['language']	= 'english';

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
*/
$config['charset'] = 'UTF-8'; //ISO-8859-1 UTF-8

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
*/
$config['log_threshold'] = '0';

/*
|--------------------------------------------------------------------------
| Error Logging Directory Path
|--------------------------------------------------------------------------
*/
$config['log_path'] = '';

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
*/
$config['log_date_format'] = 'Y-m-d H:i:s';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
*/
$config['cache_path'] = '';

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
*/

//CHANGING THIS WILL INVALIDATE CURRENT ENCRYPTED DATA!
$config['encryption_key'] = '35ed2186d830a590a31f10d140b5eb1b';

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
*/
$config['sess_cookie_name']			= 'jrox_session8928';
$config['sess_expiration']			= 60 * 60 * 24 * 7;
$config['sess_expiration_pub']		= 60 * 60 * 24 * 7;

$config['sess_encrypt_cookie']		= FALSE;
$config['sess_use_database']			= TRUE;
$config['sess_table_name']			= 'sessions';
$config['sess_match_ip']				= TRUE;
$config['sess_match_useragent']		= TRUE;
$config['sess_time_to_update'] 		= 300;

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
*/
$config['compress_output'] = FALSE;

/*
|--------------------------------------------------------------------------
| Host IP Lookup URL
|--------------------------------------------------------------------------
*/
$config['enable_geo_location_api'] = false;
$config['geo_location_api_url'] = 'http://ipinfo.io';

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
*/
$config['time_reference'] = 'gmt';

//design_disable_dropdown_js

/*
|--------------------------------------------------------------------------
| Do Not Edit Anything Below.  It May Cause Your Site To Be Unstable
|--------------------------------------------------------------------------
*/
$config['subclass_prefix'] = 'JROX_';
$config['enable_hooks'] = TRUE;
$config['permitted_uri_chars'] = '@+=\a-z 0-9~%.:_-';
$config['enable_query_strings'] = FALSE;
$config['directory_trigger'] = 'd';
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['rewrite_short_tags'] = FALSE;
$config['global_xss_filtering'] = FALSE;
$config['url_suffix'] = '';
?>