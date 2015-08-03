<?php
if (!empty($_GET['u']))
{
	$c = file_get_contents('{base_url}js/remote/{program_id}/' . $_GET['u'] . '/username/{auto_secret_id}');	
	$cid = json_decode($c);
	$cookie_expires = 1 * 60 * 60 * 24 * {cookie_timer};
	$cookie_domain = '.{remote_domain_name}'; //DON'T FORGET THE . (dot) before the domain
	setcookie('{program_cookie_name}', $cid['value'], time()+$cookie_expires,"/", $cookie_domain);
}

//header("HTTP/1.1 301 Moved Permanently");
header("Location:{url_redirect}");

?>
