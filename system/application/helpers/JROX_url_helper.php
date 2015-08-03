<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| COPYRIGHT NOTICE                                                     
| Copyright 2007 - 2015 JROX Technologies, Inc.  All Rights Reserved.   
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
| FILENAME: JROX_url_helper.php
| -------------------------------------------------------------------------     
| 
| This file extends the url_helper
|
*/

// ------------------------------------------------------------------------

function redirect_301($uri = '', $url = false, $se = true)
{
	if ($se == true)
	{
		header("HTTP/1.1 301 Moved Permanently");
	}
	
	$a = $url == true ? $uri : site_url($uri);
	
	header("Location: ".$a);	
}

// ------------------------------------------------------------------------

function _encode_return($text = '', $return = 0)
{
	return	json_encode(array(	'result' => $return,
								'msg' => $text ));
}

// ------------------------------------------------------------------------

function jrox_auto_link($url = '', $text = '', $att = '', $target = '_blank')
{
	if (!empty($url))
	{
		$b = !empty($text) ? $text : $url;

		$url = '<a href="' . $url . '" target=' . $target . ' ' . $att . '>' . $b . '</a>';
	}
	
	return $url;
}

// ------------------------------------------------------------------------

function jrox_redirect($uri = '')
{
	$CI = & get_instance();
	
	echo '<div style="text-transform: capitalize; margin: 3em; border: #FFCC00 3px solid; padding: 3em; font-size: 14px; font-weight:bold; color: #C60000; background-color: #FFFF66; font-family: tahoma, arial">		
			<img src="' . base_url() . '/images/misc/warning.png" style="vertical-align:middle; margin-right: 1em;"/>
			your session has expired.  <a href="' . base_url()  . $CI->config->slash_item('index_page') . $uri.'" style="color: #C60000">click here</a> to log back in
		  </div>';
	echo '<script>parent.location.href = \'' . base_url()  . $CI->config->slash_item('index_page') . $uri.'\';</script>';	
}

// ------------------------------------------------------------------------

function _get_session_aff($id = '', $type = '')
{
	$CI = & get_instance();
	
	if (!empty($id))
	{
		$a = $CI->encrypt->decode($id, JROX_COOKIE_ENCRYPT);
		$b = explode('-', $a);
		
		if ($type == 'id')
		{
			return $b[0];
		}
		else
		{
			return $b[1];
		}
	}
	
	return false;
}

// ------------------------------------------------------------------------

function _check_base_subdomain()
{
	$CI = & get_instance();
	
	if ($CI->config->item('base_subdomain_name'))
	{
		return 	$CI->config->item('base_subdomain_name') . '.';
	}
	else
	{
		return '';
	}
}

// ------------------------------------------------------------------------

function _get_aff_link($username ='', $type = '')
{
	$CI = & get_instance();
	
	$program_id = '';
	
	if ($CI->config->item('sts_site_showcase_multiple_programs') == 0)
	{
		$program_id = $CI->config->item('prg_program_id');
	}

	if (empty($type) && $CI->config->item('prg_use_remote_domain_link'))
	{
		//check for a custom URL	
		$link = str_replace('{USERNAME}', $username, $CI->config->item('prg_use_remote_domain_link'));
		$link = str_replace('{PROGRAM_ID}', $program_id, $link);
		
		return $link;
	}
	
	
	//check for remote link	
	if ($CI->config->item('sts_site_showcase_multiple_programs') == 0 && empty($type) && $CI->config->item('prg_use_remote_domain_link'))
	{
		$link = str_replace('{USERNAME}', $username, $CI->config->item('prg_use_remote_domain_link'));
		return $link;
	}
	
	$aff_type = empty($type) ? $CI->config->item('sts_affiliate_link_type') : $type;
	
	$http = $CI->config->item('sts_site_ssl_public_area') == 1 ? 'https://' : 'http://';
	
	if ($CI->config->item('base_folder_path'))
	{
		$base_domain = $CI->config->item('base_domain_name');
	}	
	else
	{
		$base_domain = $CI->config->slash_item('base_domain_name');
	}

	switch ($aff_type)
	{
		case 'replicated_site':

			$link = $http . _check_base_subdomain() . $base_domain . $CI->config->slash_item('base_folder_path') . $CI->config->slash_item('index_page') . _jrox_slash_item(REPLICATION_ROUTE) . $username . _append_pid($program_id);
		
		break;
		
		case 'subdomain':
	
			if ($CI->config->item('sts_site_showcase_multiple_programs') == 0)
			{
				$program_id = '/?pid=' . $program_id;
			}
			
			$link = $http . $username .	'.' . $base_domain . $program_id; 
		break;
		
		case 'regular':
			
			$link = $http . _check_base_subdomain() . $base_domain . $CI->config->slash_item('base_folder_path') . $CI->config->slash_item('index_page') . _jrox_slash_item(AFFILIATE_ROUTE) . $username . _append_pid($program_id);
		
		break;
	}
	
	return $link;
}

// ------------------------------------------------------------------------

function _get_aff_program_link($p = array(), $username = '')
{
	$link =  _get_aff_link($username . '_' . $p['program_id'], 'regular');
	
	if (!empty($p['use_remote_domain_link']))
	{
		//check for a custom URL	
		$link = str_replace('{USERNAME}', $username, $p['use_remote_domain_link']);
		$link = str_replace('{PROGRAM_ID}', $p['program_id'], $link);
	}
	
	return $link;
}

// ------------------------------------------------------------------------

function check_subdomain($sub = '')
{
	$CI =& get_instance();
	
	$subs = explode(',', $CI->config->item('sts_affiliate_restrict_subdomains'));
	
	foreach ($subs as $v)
	{
		if (trim($v) == trim($sub))
		{
			return true;
		}
	}
	
	return false;
}

// ------------------------------------------------------------------------

function base_url($path = '', $base_only = false)
{
	
	$CI =& get_instance();
	$url = $CI->config->slash_item('base_url');
	
	switch ($path)
	{
		case 'members':
		case 'login':
		
			if ($_SERVER['SERVER_PORT'] == '443')
			{
				$url = $CI->config->slash_item('base_SSL_url');	
			}
			
		break;
		
		case 'js':
		
			return str_replace('http:', '', $CI->config->slash_item('base_url'));	
			
		break;
		
		default:
		
			if ($_SERVER['SERVER_PORT'] == '443')
			{
				$url = $CI->config->slash_item('base_SSL_url');		
			}
			
		break;
	}
	
	if (!empty($path))
	{	
		if ($base_only == false)
		{
			$url .= $path . '/';
		}
	}
		
	return $url;
}

// ------------------------------------------------------------------------

function path_url()
{
	$CI =& get_instance();
	return $CI->config->slash_item('base_url');
}

// ------------------------------------------------------------------------

function site_url2()
{
	$CI =& get_instance();
	$a = base_url() . $CI->config->slash_item('index_page');
	
	return $a;
}

// ------------------------------------------------------------------------

function admin_url()
{
	$CI =& get_instance();
	
	if ($CI->config->item('admin_index_page'))
	{
		return base_url() . $CI->config->item('admin_index_page') . '/' .  ADMIN_ROUTE . '/';
	}
	else
	{	
		return base_url() .  ADMIN_ROUTE . '/';
	}
}

// ------------------------------------------------------------------------

function modules_url()
{
	$CI =& get_instance();
	if ($CI->config->item('admin_index_page'))
	{
		return base_url() . $CI->config->item('admin_index_page') . '/modules/';
	}
	else
	{	
		return base_url().'modules/';
	}
	
}

// ------------------------------------------------------------------------

function _append_pid($item = '')
{
	if ($item != '' && substr($item, 0) != '_')
	{	
		$item = '_' . $item;
	}

	return $item;		
}

// ------------------------------------------------------------------------

function _jrox_slash_item($item = '')
{
	if ($item != '' && substr($item, -1) != '/')
	{	
		$item .= '/';
	}

	return $item;	
}

// ------------------------------------------------------------------------

function _public_url($index = true)
{
	$CI =& get_instance();
	
	$index_page = ''; 
	if ($index == true)
	{
		$index_page = $CI->config->slash_item('index_page');
	}
	
	if ($CI->config->item('base_folder_path'))
	{
		$url = _check_base_subdomain() . $CI->config->item('base_domain_name') . $CI->config->slash_item('base_folder_path') . $index_page;
	}
	else
	{
		$url = _check_base_subdomain() . $CI->config->slash_item('base_domain_name') . $CI->config->slash_item('base_folder_path') . $CI->config->slash_item('index_page');
	}
	
	
	if ($CI->config->item('sts_site_ssl_public_area') == 1)
	{
		return  'https://' . $url;
	}
	else
	{	
		return  'http://' . $url;
	}
}

// ------------------------------------------------------------------------

function referer_redirect($type = '', $url = '', $sep = ':')
{
	$referer = '';
	
	if ($type == 'admin')
	{
		return str_replace($sep, '/', $url);
	}
	
	if (!empty($url))
	{
		$uri = explode($sep, $url);
		
		foreach ($uri as $v)
		{
			$referer .= '/'.$v;
		}
	}
	else
	{
		$referer = ADMIN_ROUTE;
	}
	
	return $referer;
}

// ------------------------------------------------------------------------

function format_curl_data($data = array(), $type = 'array')
{
    if ($type == 'json')
    {
         return json_encode($data);
    }

    $fields = "";
    foreach( $data as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";

    return $fields;
}

// ------------------------------------------------------------------------

function connect_curl($url = '', $use_post = false, $vars = '', $return_data = 1, $timeout = 5, $ssl_verify_peer = false, $path = false)
{
	if (!empty($path))
	{ 
		$fp = fopen($path, 'w');
	 
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_FILE, $fp);
	 
		$data = curl_exec($ch);
	 
		curl_close($ch);
		fclose($fp);
	}
	else
	{
		$ch = curl_init($url);
		
		curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, $return_data); // Returns response data instead of TRUE(1)
		
		if ($use_post == true)
		{
			curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $vars, "& " )); // use HTTP POST to send form data
		}
		
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, CURL_SSL_VERIFYPEER); // uncomment this line if you get no gateway response. ###
		
		if (CURL_PROXY_REQUIRED == true) {
			curl_setopt ($ch, CURLOPT_HTTPPROXYTUNNEL, CURL_PROXY_TUNNEL_FLAG);
			curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
			curl_setopt ($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
		}
		
		$resp = curl_exec($ch); //execute post and get results
		curl_close ($ch);
		
		return $resp;
	}
}

// ------------------------------------------------------------------------

function _generate_js_url($type = 'admin')
{
	if ($type == 'admin')
	{	
		echo '<script language="JavaScript" type="text/javascript" src="' . base_url() . '/js/js_admin.php"></script>';
	}
}

// ------------------------------------------------------------------------

function _generate_header_cache()
{	

	header("Cache-control: private"); // Fix for IE
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0', FALSE);
	header('Pragma: no-cache');

}

// ------------------------------------------------------------------------

function anchor_popup($uri = '', $title = '', $attributes = FALSE, $css = 'btn btn-default', $style = '')
	{	
		$title = (string) $title;
	
		$site_url = ( ! preg_match('!^\w+://! i', $uri)) ? site_url($uri) : $uri;
	
		if ($title == '')
		{
			$title = $site_url;
		}
	
		
	
		if ( ! is_array($attributes))
		{
			$attributes = array();
		}
		
		foreach (array('width' => '500', 'height' => '400', 'scrollbars' => 'yes', 'status' => 'yes', 'resizable' => 'yes', 'screenx' => '0', 'screeny' => '0', ) as $key => $val)
		{
			$atts[$key] = ( ! isset($attributes[$key])) ? $val : $attributes[$key];
		}

		return "<a href='javascript:void(0);' onclick=\"window.open('".$site_url."', '_blank', '"._parse_attributes($atts, TRUE)."');\" class='" . $css . "' style='" . $style . "'>".$title."</a>";
	}

// ------------------------------------------------------------------------

function _format_url($type = '', $id = '', $title = '')
{
	$CI = & get_instance();
	
	$u = _check_base_subdomain() . $CI->config->item('base_domain_name') . $CI->config->item('base_folder_path');
	
	if ($CI->config->item('sts_site_ssl_public_area') == 1)
	{
		$url = 'https://' . $u;
	}
	else
	{
		$url = 'http://' . $u;
	}
	
	$a = $url . '/' . $type . '/' . $id . '/' . url_title($title, $CI->config->item('jrox_url_separator'));
	
	return $a;
}

function _format_zip($zip = '')
{
	$zip = str_replace(' ', '', $zip);
	$zip2 = explode('-', $zip);
	$a = $zip2[0];
		
	return $a;
}
?>