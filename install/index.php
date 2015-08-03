<?php 
#####################################################################################
## JROX.COM Affiliate Manager Installer
## Version 2.0
##      
## Author: 			Ryan Roxas(ryan@jrox.com)              
## Homepage:	 	http://jam.jrox.com
## Bug Reports: 	http://www.jrox.com
## Release Notes:	docs/READ_ME.txt
#######################################################################################

#######################################################################################
## COPYRIGHT NOTICE                                                     
## Copyright 2007 - 2013 JROX Technologies, Inc.  All Rights Reserved.      
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
## JAM Installer 
#######################################################################################

define('JROX_INSTALLER', 1);
define('DEFAULT_THEME', 'default');
//define('ENVIRONMENT', 'development');

require_once ('../JAM.php');

@date_default_timezone_set('GMT');	

if (empty($_GET['step'])) 
{ 
	//check if ioncube version is loaded
	//header('Location:check_ioncube.php'); 
	header('Location:index.php?step=1'); 
}
else
{
$body = '

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Installer</title>
</head>
<style>
@charset "utf-8";
/* CSS Document */

body {
	background:#f4f4f4;
	font: 16px Tahoma, Arial, Helvetica, sans-serif;
	color:#333;
}


.notes {
	border: 1px dotted #666;
	margin: 1em;
	padding: 1em 1em 1em 3px;
	background: #fff; 
}

.postnotes {
	border: 1px dotted #ff3300;
	margin: 1em;
	padding: 1em 1em 1em 3px;
	background: #f4f4f4;  
}

.jroxContainer {
	margin: 1em auto;
	width: 750px;
	border: 4px solid #EBEBEB;
	background: #fff;
	-moz-box-shadow: 0px 16px 18px #999;
	-webkit-box-shadow: 0px 16px 18px #999;
	box-shadow: 0px 16px 18px #999;
	border-radius: 5px;
}

.jroxContainer h1 {
	font-size:24px;
	padding: 12px 1em;
	margin: 0;
	background:#efefef;
}

.jroxContent {
	padding: 2em;
	line-height: 1.6em;
}

.idiv {
	padding: 1em;
	border-bottom: 1px dotted #CCCCCC;
}

.idiv li {
	list-style:square;
	margin-top: 8px;
	list-style-position:outside;
	margin-left: 0;
	padding-left: 0;
}

.strong {
	font-weight:bold;

}

.style1 {
	font-weight: bold;
	font-style: italic;
}

.topmargin {
	margin-top: 1em;
}

.input label {

	text-transform: capitalize;
	display:block;
	margin-bottom: 8px;
}

.error {
	
	border: 1px solid #CC0000;
	padding: 1em;
	color: #CC3300;
	background:#FFD7D7;
	margin: 1em auto;
	font-size: 14px;
	text-transform: capitalize;
}
.input label span {
	font-weight:bold;
}

.textarea {
	width: 600px;
	height: 500px;
	overflow-y: scroll;
	overflow-x: hidden;
	border: 1px dotted #ccc;
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
	padding: 5px;
	background: #fff;
	color: #666;
	text-align:left;
}
.input input {
	font-size: 16px;
	border: 1px solid #CCCCCC;
	padding: 6px 8px;
	-moz-box-shadow: 0px 2px 3px #999;
	-webkit-box-shadow: 0px 2px 3px #999;
	box-shadow: 0px 2px 3px #999;
	border-radius: 2px;
	width: 400px;
	background: #eee;
	color: #666666;
}


.continue {
	display: block;
	border: 1px solid #efefef;
	-moz-box-shadow: 0px 2px 3px #999;
	-webkit-box-shadow: 0px 2px 3px #999;
	box-shadow: 0px 2px 3px #999;
	border-radius: 3px;
	padding: 5px;
	margin: 1em;
	font-size: 18px;
	color: #fff;
	font-weight:bold;
	background:#333;
	width: 260px;
	text-decoration: none;
}

.continue:hover { 
	background: green;
	cursor: pointer;
}
</style>
<body>
<div class="jroxContainer">
<h1>Application Installer</h1>
<div class="jroxContent">';


	switch ($_GET['step'])
	{
		case "1":
		
			$body .= "<form action='index.php' method='get' name='form'><div class='idiv strong'>Step 1 of 2: End-User License Agreement...</div>";
			
			$body .= '<div class="idiv" align="center"><div class="textarea">' . nl2br(read_file('license.txt')) . '</div></div>';
			
			$body .= '<div class="idiv input padding" align="center">
							<label><span><input name="accept" type="checkbox" id="accept" value="1" style="width: 15px; height: 15px; border: 1px solid #666;"/> By checking this box, you agree to the licensing agreement </span></label>
							
							</div>';
			
			$body .= "<div align='center' style='margin-bottom:2em'><input type=hidden name='step' value='2' id='step'/><button class='continue' type='submit'>Click Here To Continue</button></div>";
			
		break;
		
		case "2":
		
			if (!empty($_POST))
			{	
				$check_error = check_data($_POST);
				
				if (empty($check_error['errors']))
				{
					header("Location:index.php?step=done&u=" . $check_error['u'] . "&p=" . $check_error['p'] . "&l=" . $check_error['l'] );
					exit();
				}
			}
			elseif (empty($_GET['accept']))
			{
				header("Location:index.php?step=1");
				exit();
			}
			
			
			
			if (!empty($check_error['errors']))
			{
				$body .= "<div class='idiv error'><p><img src='error.gif' width='16' height='16' align='absmiddle' /> <strong>You have errors in your submission:</strong></p>
				<p>" . $check_error['errors'] . "</p>
				</div>";
			}
			
			$body .= "<form action='index.php?step=2' method='post' name='form'><div class='idiv strong'>Step 2 of 2: Checking System Requirements...</div>";
			//first check for system requirements
			
			//$body .= "<div class='idiv'><img src='warning.png' width='16' height='16' align='absmiddle' /> Make sure that you have uploaded all files in BINARY FORMAT</div>";
			
			if (phpversion() >= "5.3")
			{
				$body .= "<div class='idiv'><img src='tick.png' width='16' height='16' align='absmiddle' /> PHP version " . phpversion() . " installed</div>";
			}
			else 
			{
				$error = true;
				$body .= "<div class='idiv'><img src='error.gif' width='16' height='16' align='absmiddle' /> JAM requires at least PHP version 5.3 or greater.  Your current PHP version is: ". phpversion()."</div>";
			}
			
			if (phpversion() < "5.5")
			{
				if (function_exists('mysql_connect'))
				{
					$body .= "<div class='idiv'><img src='tick.png' width='16' height='16' align='absmiddle' /> MySQL Database " . @mysql_get_server_info() . "  installed</div>";
				}
				else
				{
					$body .= "<div class='idiv'><img src='error.gif' width='16' height='16' align='absmiddle' /> MySQL Database not detected.  Please check to see if MySQL is compiled with PHP</div>";
					$error = true;
				}
			}
			
			if (extension_loaded('curl'))
			{
				$body .= "<div class='idiv'><img src='tick.png' width='16' height='16' align='absmiddle' /> Curl seems to be Installed</div>";
			}
			else 
			{
				$body .= "<div class='idiv'><img src='error.gif' width='16' height='16' align='absmiddle' /> Curl has not been detected. Please install</div>";
				$error = true;
			}
			
			//enter database info if no errors 
			if (empty($error))
			{
				
				$body .= "<div class='idiv strong topmargin'>Enter JAM Installation Code...</div>";
			
				$body .= '<div class="idiv input padding">
							<label><span>JAM Installation Code</span> - you should have received this when downloading</label>
							<input name="jamcode" id="jamcode" type="text"  value="'; if  (empty($_POST['jamcode'])) { $body .= ''; } else { $body .= $_POST['jamcode']; } $body .='"/>
						  </div>';
						  
				$body .= "<div class='idiv strong topmargin'>Enter Database Info...</div>";
			
				$body .= '<div class="idiv input padding">
							<label><span><input name="skip_config" type="checkbox" id="skip_config" value="1" style="height: 15px; width: 15px;"/> If you manually edited the config.php and database.php files already, check this box and skip the database and config info below. </span></label>
							
							</div>';
							
				$body .= '<div class="idiv input padding">
							<label><span>database server</span> - the name of the server where your database is at</label>
							<input name="hostname" id="hostname" type="text"  value="'; if  (empty($_POST['hostname'])) { $body .= 'localhost'; } else { $body .= $_POST['hostname']; } $body .='"/>
						  </div>';
			
				$body .= '<div class="idiv input padding">
							<label><span>database name</span> - the name of your database</label>
							<input name="database" id="database" type="text" value="'; if  (empty($_POST['database'])) { $body .= 'database_name'; } else { $body .= $_POST['database']; } $body .='"/>
							</div>';
				
				$body .= '<div class="idiv input padding">
							<label><span>database username</span> - the username that will connect to your database</label>
							<input name="username" id="username" type="text" value="'; if  (empty($_POST['username'])) { $body .= 'database_user'; } else { $body .= $_POST['username']; } $body .='"/>
							</div>';
				
				$body .= '<div class="idiv input padding">
							<label><span>database password</span> - the password of the database user</label>
							<input name="password" id="password" type="text" value="'; if  (empty($_POST['password'])) { $body .= 'database_pass'; } else { $body .= $_POST['password']; } $body .='"/>
							</div>';
				
				
				//check the file paths
				//get url info
				$current_page = curPageURL();
				
				$page_data = parse_url($current_page);
				
				if (!empty($page_data['host']))
				{
					$domain_array = explode('.', $page_data['host']);
					$subdomain = $domain_array[0];
				}
				
				$subdomain = 'www';
				
				if (!empty($_SERVER["SERVER_NAME"]))
				{
					$domain_name = _format_domain_name($_SERVER['SERVER_NAME']);
				}
				else
				{
					$domain_name = _format_domain_name($current_page);
				}
				
				$system_email = 'affiliates@' . $domain_name;
				
				if (!empty($page_data['path']))
				{
					$folder_path = str_replace('/install/index.php', '', $page_data['path']);
				}
				else
				{
					$folder_path = '';
				}
				//echo '<pre>'; print_r($page_data);
				$body .= "<div class='idiv strong topmargin'>Enter Config File Paths...</div>";
				
				if (file_exists(PUBPATH . '/system/application/controllers/home.php'))
				{
					$body .= '<input name="base_physical_path" id="base_physical_path" type="hidden" value="PUBPATH" />';
				}
				else
				{
					$body .= '<div class="idiv input padding">
								<label><span>base physical path</span> -  the full file path to your installation</label>
								<input name="base_physical_path" id="base_physical_path" type="text" value="'; if  (empty($_POST['base_physical_path'])) { $body .= PUBPATH; } else { $body .= $_POST['base_physical_path']; } $body .='"/>
							  </div>';
				}
				
				$body .= '<div class="idiv input padding">
							<label><span>base folder path</span> - the path to the installation folder (ex. /shop)</label>
							<input name="base_folder_path" id="base_folder_path" type="text" value="'; if  (empty($_POST['base_folder_path'])) { $body .= $folder_path; } else { $body .= $_POST['base_folder_path']; } $body .='"/>
							</div>';
				
				$body .= '<div class="idiv input padding">
							<label><span>base domain name</span> - your domain name (ex. domain.com)</label>
							<input name="base_domain_name" id="base_domain_name" type="text" value="'; if  (empty($_POST['base_domain_name'])) { $body .= $domain_name; } else { $body .= $_POST['base_domain_name']; } $body .='"/>
							</div>';
				
				$body .= '<div class="idiv input padding">
							<label><span>base subdomain name</span> - the subdomain such as www)</label>
							<input name="base_subdomain_name" id="base_subdomain_name" type="text" value="'; if  (empty($_POST['base_subdomain_name'])) { $body .= $subdomain; } else { $body .= $_POST['base_subdomain_name']; } $body .='"/>
							</div>';
				
				$body .= '<div class="idiv input padding">
							<label><span>system email address</span> - email address that system uses to send out email</label>
							<input name="system_email" id="system_email" type="text" value="'; if  (empty($_POST['system_email'])) { $body .= $system_email; } else { $body .= $_POST['system_email']; } $body .='"/>
							</div>';
							
				$body .= '<div class="idiv input padding">
							<label><span><input name="index_php" type="checkbox" id="index_php" value="1" style="height: 15px; width: 15px;"/> If you are having errors with logging in or mod_rewrite, check this box </span></label>
							
							</div>';
							
				//enter admin info
				
				
				$admin_email = 'webmaster@' . $domain_name;
								
				$body .= "<div class='idiv strong topmargin'>Enter Admin Info...</div>";
			
				$body .= '<div class="idiv input padding">
							<label><span>admin first name</span></label>
							<input name="admin_fname" id="admin_fname" type="text" value="'; if  (empty($_POST['admin_fname'])) { $body .= 'admin'; } else { $body .= $_POST['admin_fname']; } $body .='"/>
							</div>';
				
				$body .= '<div class="idiv input padding">
							<label><span>admin last name</span></label>
							<input name="admin_lname" id="admin_lname" type="text" value="'; if  (empty($_POST['admin_lname'])) { $body .= 'user'; } else { $body .= $_POST['admin_lname']; } $body .='"/>
							</div>';			
				
				$body .= '<div class="idiv input padding">
							<label><span>admin email address</span></label>
							<input name="admin_email" id="admin_email" type="text" value="'; if  (empty($_POST['admin_email'])) { $body .= $admin_email; } else { $body .= $_POST['admin_email']; } $body .='"/>
							</div>';
							
				$body .= '<div class="idiv input padding">
							<label><span><input name="send_login" type="checkbox" id="send_login" value="1" style="height: 15px; width: 15px;"/> click to send login details to admin </span></label>
							
							</div>';
				
				$body .= "<div align='center' style='margin-bottom:2em'><button type='submit' class='continue'>Click Here To Continue</button></div></form>";
			}
		
		break;
		
		case "phpinfo":
		
			phpinfo();
		
		break;
		
		case "done":
			
			//now try to at least delete the installer and the sql file
			@unlink ('index.php');
			@unlink ('jam_db.sql');
			@unlink('check_ioncube.php');
			@unlink('error.gif');
			@unlink('ioncube-encoded-file.php');
			@unlink('license.txt');
			@unlink('style.css');
			@unlink('tick.png');
			@unlink('error_log');
			@unlink('warning.png');
			
			//try write to .htaccess file
			
			$body .= "<div class='idiv strong topmargin'>Installation Success!</div>";
			
			$body .= "<div class='idiv'><img src='tick.png' width='16' height='16' align='absmiddle' /> <span class='strong'>Admin Username:</span> " . $_GET['u'] . "</div>";
			
			$body .= "<div class='idiv'><img src='tick.png' width='16' height='16' align='absmiddle' /> <span class='strong'>Admin Password:</span> " . $_GET['p'] . "</div>";
			
			$body .= "<div class='idiv'>
			<p>You can change your username/password in Members &gt; Manage Admins after logging in... </p>
<p><strong style=\"color:#ff3300\">IMPORTANT - Post-Installation Notes:</strong></p>
			<div class=\"postnotes\">
<ul>";
  
  			$body .= '<li>If you click on the login link below and it does not work, Try editing the .htaccess file in your JAM folder and uncomment the RewriteBase rule to your JAM folder</li>';
			
			if (@rmdir ('../install') == false)
			{
				$body .= "<li>Make sure to delete the<strong><em> /install</em></strong> folder for security.</li>";
			}
		  
			//lets check the permissions for the images folder
			if (!is_writable('../images/banners')) 
			{	
				if (@chmod('../images/banners', 0777) == false)
				{
					$body .= "<li>If you want to upload banners, make sure the <strong>/images/banners</strong> folder is writeable</li>";
				}
			} 
			

			if (!is_writable('../images/members')) 
			{	
				if (@chmod('../images/members', 0777) == false)
				{
					$body .= "<li>If you want to upload member images, make sure the  <strong>/images/members</strong> folder is writeable</li>";
				}
			} 
			
			if (!is_writable('../images/programs')) 
			{	
				if (@chmod('../images/programs', 0777) == false)
				{
					$body .= "<li>If you want to upload program logos, make sure the  <strong>/images/programs</strong> folder is writeable</li>";
				}
			} 
		
			$body .= "<li><a href=\"http://jam.jrox.com/kb\" target=\"_blank\">Click Here to go through the Quick Start Guide and the User Guides for more information on software features and usage.</a></li>
	</ul>
	</div>
			</div>";
			
			if (substr($_GET['l'], -1) != '/')
			{	
				$_GET['l'] .= '/';
			}
			
			$body .= "<div align='center' style='margin-bottom:2em'><a href=\"http://" . $_GET['l'] . "admin_login\" class='continue'>Click Here To Login</a></div>";
			
		break;
	}
	$body .= '</div>
				</div>
				</body>
				</html>
				';	
	echo $body;
} 
?>


<?php

// ------------------------------------------------------------------------	

function check_data()
{
	$error = '';
	
	extract($_POST);
	
	//check install code
	if (empty($_POST['jamcode']))
	{
		$error .= "Enter your JAM installation code\n";
	}
	elseif ($_POST['jamcode'] != 'jam0922')
	{
		//check the jem code
		$ch = curl_init('http://www.jrox.com/licensing/check_install_code/' . trim($_POST['jamcode']));
		curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, '10');
	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Returns response data instead of TRUE(1)
		//curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // uncomment this line if you get no gateway response. ###
		
		$resp = curl_exec($ch); //execute post and get results
		curl_close ($ch);
		 
		if (preg_match("/VALID_JEM_CODE/",$resp))
		{
			//good
		}
		else
		{
			//try checking via file get contents
			if (function_exists('file_get_contents'))
			{
				$resp2 = file_get_contents('http://www.jrox.com/licensing/check_install_code/' . trim($_POST['jamcode']));
				
				if (preg_match("/VALID_JEM_CODE/",$resp2))
				{
					//good
				}
				else
				{
					$error .= "Enter a valid JAM installation code\n";	
				}
			}
		}
	}
	
	//check if config file is writeable
	$path = $_POST['base_physical_path'] == 'PUBPATH' ? PUBPATH : $_POST['base_physical_path'];
		
	if (empty($_POST['skip_config']))
	{
		//first check db connectivity
		if (phpversion() < "5.5")
		{
			if ($conn = mysql_connect(trim($hostname), trim($username), trim($password)))
			{
				if (!mysql_select_db($database,$conn))
				{
					$error .= "Could not connect to database\n";
				}
			}
			else
			{
				$error .= "Could not connect to database server\n";
			}
		}
		else
		{
			$conn = mysqli_connect(trim($hostname), trim($username), trim($password), $database);
			if (!$conn)
			{
				$error .= "Could not connect to database server\n";
			}
		}
		
		if (!is_writable($path . '/system/application/config/config.php')) 
		{	
			$error .= "the <strong>$path/system/application/config/config.php</strong> file is not writeable.  please make sure that it is writeable (chmod 777)\n";
		} 
		
		
		if (!is_writable($path .'/system/application/config/database.php')) 
		{	
			$error .= "the <strong>$path/system/application/config/database.php</strong> file is not writeable.  please make sure that it is writeable (chmod 777)\n";
		} 
		
		extract($_POST);
	
	}
	else
	{
		require_once ($path .'/system/application/config/config.php');
		require_once ($path .'/system/application/config/database.php');
		
		$base_physical_path = $config['base_physical_path'];
		$base_folder_path = $config['base_folder_path'];
		$base_domain_name = $config['base_domain_name'];
		$base_subdomain_name = $config['base_subdomain_name'];
		$hostname = $db['default']['hostname'];
		$database = $db['default']['database'];
		$username = $db['default']['username'];
		$password = $db['default']['password'];
		
		if (phpversion() < "5.5")
		{
			if ($conn = mysql_connect(trim($hostname), trim($username), trim($password)))
			{
				if (!mysql_select_db($database,$conn))
				{
					$error .= "Could not connect to database\n";
				}
			}
			else
			{
				$error .= "Could not connect to database server\n";
			}
		}
		else
		{
			$conn = mysqli_connect(trim($hostname), trim($username), trim($password), $database);
			if (!$conn)
			{
				$error .= "Could not connect to database server\n";
			}
		}
		
	}
		
	//check email address
	if (valid_email(trim($_POST['admin_email'])) == false)
	{
		$error .= "the admin email address is not properly formatted.  please doublecheck\n";
	}
	
	
	if (valid_email(trim($_POST['system_email'])) == false)
	{
		$error .= "the system email address is not properly formatted.  please doublecheck\n";
	}
	
	$index_php = '';
	if (!empty($_POST['index_php']))
	{
		$index_php = 'index.php';	
	}
	
	if (empty($error))
	{
		if (empty($_POST['skip_config']))
		{	
		
			//write the config.php file
			$cdata = array(	'encryption_key' => md5(time()),
							'base_physical_path' => $_POST['base_physical_path'],
							'base_folder_path'	=> $_POST['base_folder_path'],
							'base_domain_name'	=>	$_POST['base_domain_name'],
							'base_subdomain_name'	=>	$_POST['base_subdomain_name'],
							'index_php'	=>	$index_php,
						);
			
			
			//write the database.php file
			$sdata = array(	'hostname' => $_POST['hostname'],
							'database' => $_POST['database'],
							'username'	=> $_POST['username'],
							'password'	=>	$_POST['password'],
							'cachedir'	=>	$path . '/system/cache/dbcache',
							
						);
		
			$config_file = write_data('config', $cdata, $path . '/system/application/config/config.php');
		
			$db_file = write_data('database', $sdata, $path . '/system/application/config/database.php');
		}
		
		//import sql
		$sql = read_file('jam_db.sql');
		
		//add admin user
		$admin_username = _generate_username();
		$admin_password = _generate_password();
		
		if (!empty($base_subdomain_name))
		{
			$config_base_url = $base_subdomain_name . '.' . $base_domain_name  . $base_folder_path;
			$home_base_url = $base_subdomain_name . '.' . $base_domain_name;
		}
		else
		{
			$config_base_url = $base_domain_name  . $base_folder_path;
			$home_base_url = $base_domain_name;
		}   
		
		$admin_fname = empty($_POST['admin_fname']) ? 'admin' : $_POST['admin_fname'];
		$admin_lname = empty($_POST['admin_lname']) ? 'user' : $_POST['admin_lname'];
		
		$sql = str_replace('{{base_url}}', 'http://' . $config_base_url, $sql);
		$sql = str_replace('{{home_base_url}}', 'http://' . $home_base_url, $sql);
		$sql = str_replace('{{pubpath}}', PUBPATH, $sql);
		$sql = str_replace('{{key}}', md5(rand(10,40)), $sql);
		$sql = str_replace('{{cron_key}}', md5(trim(strtolower($base_domain_name))), $sql);
		$sql = str_replace('{{secret}}', md5(rand(10,40)), $sql);
		$sql = str_replace('{{secret2}}', md5(rand(10,40)), $sql);
		$sql = str_replace('{{jamcode}}', $_POST['jamcode'], $sql);
		$sql = str_replace('{{base_domain_name}}', trim(strtolower($base_domain_name)), $sql);
		$sql = str_replace('{{admin_fname}}', $admin_fname, $sql);
		$sql = str_replace('{{admin_lname}}', $admin_lname, $sql);
		$sql = str_replace('{{admin_username}}', $admin_username, $sql);
		$sql = str_replace('{{admin_password}}', md5($admin_password), $sql);
		$sql = str_replace('{{admin_email}}', trim(strtolower($admin_email)), $sql);
		$sql = str_replace('{{system_email}}', trim(strtolower($system_email)), $sql);
        $sql = str_replace('{{date}}', time(), $sql);

        $sql = str_replace('{reseller_link}', 'by http://www.jrox.com', $sql);

		$sqlArray = explode('{{{~~~}}}', $sql);
		
		  foreach ($sqlArray as $stmt) {
			
		   if (strlen($stmt)>3){
			if (phpversion() < "5.5")
			{
				$result = mysql_query($stmt);
			}
			else
			{
				$result = mysqli_query($conn, $stmt);

			}

			  if (!$result){
				 $sqlErrorCode = mysql_errno();
				 $sqlErrorText = mysql_error();
				 $sqlStmt      = $stmt;
			  }
		   }
		  }
			 
		  if (!empty($sqlErrorCode)){
			
			  echo "<p>An error occured during installation!</p>";
			  echo "<p>Error code: $sqlErrorCode</p>";
			  echo "<p>Error text: $sqlErrorText</p>";
			  echo "<p>Statement:<br/> $sqlStmt</p>";
			  exit();
			  
		   }	

		$data['u'] = $admin_username;
		$data['p'] = $admin_password;
		
		$data['l'] = $config_base_url . '/' . $index_php;
		$data['errors'] = false;
		
		if (!empty($_POST['send_login']))
		{
			$to = trim(strtolower($_POST['admin_email']));
			$email_subject = strtoupper($base_domain_name) . ' Installation Details';
			$from_email = "system@" . $base_domain_name;
			$message = "Installation Details for " . $config_base_url . "\n";
			$message .= "===============================================\n\n";
			$message .= "http://". $config_base_url . "/index.php/admin_login/" . "\n";
			$message .= "username: " . $admin_username . "\n";
			$message .= "password: " . $admin_password . "\n";
			
			$message .= "\n===============================================\n\n";
			
			$message .= "Thanks!\n";
			$message .= "Support Team\n";
	
			
			$headers = "From: $from_email";
			
			//SEND EMAIL
			@mail($to,$email_subject,$message,$headers);
		}
		
		//update licensing database
		$fields = "";
		
		$auth_fields = array(
								'j_domain' 	=> 	"http://" . $config_base_url,
								'j_email' 	=> 	trim(strtolower($_POST['admin_email'])),
								'j_date' 	=>	date('m.d.Y'),
								'j_ip'		=> $_SERVER['REMOTE_ADDR'],
							);
							
		foreach( $auth_fields as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";		
		
		$ch = curl_init('http://www.jrox.com/licensing/install/jam');
		curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, '10');
	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Returns response data instead of TRUE(1)
		curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // uncomment this line if you get no gateway response. ###
		
		$resp = curl_exec($ch); //execute post and get results
		curl_close ($ch);
		
		return $data;
	}
	else
	{	
		$data['errors'] = nl2br($error);
		return $data;
	}
	
	
	
}

function valid_email($address)
{
	return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? FALSE : TRUE;
}

function read_file($file)
	{
		if ( ! file_exists($file))
		{
			return FALSE;
		}
	
		if (function_exists('file_get_contents'))
		{
			return file_get_contents($file);		
		}

		if ( ! $fp = @fopen($file, FOPEN_READ))
		{
			return FALSE;
		}
		
		flock($fp, LOCK_SH);
	
		$data = '';
		if (filesize($file) > 0)
		{
			$data =& fread($fp, filesize($file));
		}

		flock($fp, LOCK_UN);
		fclose($fp);

		return $data;
	}

function _generate_username()
{
	$a = random_string('alpha', 3);
	$a .= 'admin';
	
	return $a;
}

function _generate_password()
{
	$a = random_string('alnum', 8);
	
	return strtolower($a);
}

function random_string($type = 'alnum', $len = 8)
{					
	switch($type)
	{
		case 'alnum'	:
		case 'numeric'	:
		case 'alpha'	:
		case 'nozero'	:
	
				switch ($type)
				{
					case 'alnum'	:	$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						break;
					case 'numeric'	:	$pool = '0123456789';
						break;
					case 'nozero'	:	$pool = '123456789';
						break;
					case 'alpha'	:	$pool = 'abcdefghijklmnopqrstuvwxyz';
						break;
				}

				$str = '';
				for ($i=0; $i < $len; $i++)
				{
					$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
				}
				return $str;
		  break;
		case 'unique' : return md5(uniqid(mt_rand()));
		  break;
	}
}

function _clean_domain_name($domain = '')
{
	preg_match('/[^.]+\.[^.]+$/', $domain, $matches);
	return $matches[0];	
}

function _format_domain_name($domain)
{
	$url = parse_url($domain,  PHP_URL_PATH);
	
	if (substr($url, 0,4) == 'www.')
	{
		$url = substr($url, 4);		
	}
	
	return $url;
}

function curPageURL() 
{
	$pageURL = 'http';
	if (!empty($_SERVER["HTTPS"]) == "on") {$pageURL .= "s";}
		$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}





function write_data($type = '', $data = '', $path = '')
{
	if ($type == 'config')
	{
		if ($data['base_physical_path'] != 'PUBPATH') { $data['base_physical_path'] = '\'' . $data['base_physical_path'] . '\''; }
		
		if (!empty($data['base_subdomain_name']))
		{
			$config_base_url = '\'http://\' . $config[\'base_subdomain_name\'] . \'.\' . $config[\'base_domain_name\'] . $config[\'base_folder_path\'];';
			$config_base_ssl_url = '\'https://\' . $config[\'base_subdomain_name\'] . \'.\' . $config[\'base_domain_name\'] . $config[\'base_folder_path\'];';
		}
		else
		{
			$config_base_url = '\'http://\' . $config[\'base_domain_name\'] . $config[\'base_folder_path\'];';
			$config_base_ssl_url = '\'https://\' . $config[\'base_domain_name\'] . $config[\'base_folder_path\'];';
		}
		
		$content = '<?php  if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');

/*
|--------------------------------------------------------------------------
| Script Limits
|--------------------------------------------------------------------------
*/

$config[\'script_time_limit\'] = \'0\';

/*
|--------------------------------------------------------------------------
| Folder and License Path
|--------------------------------------------------------------------------
*/
$config[\'base_license_domain_name\'] = \'' . $data['base_domain_name'] . '\';
$config[\'base_physical_path\'] = ' . $data['base_physical_path'] . ';
$config[\'base_folder_path\'] = \'' . $data['base_folder_path'] . '\';
$config[\'base_domain_name\'] = \'' . $data['base_domain_name'] . '\';

$config[\'base_subdomain_name\'] = \'' . $data['base_subdomain_name'] . '\';

$config[\'base_mobile_subdomain\'] = \'m\';
$config[\'base_url\']	= ' . $config_base_url . '
$config[\'base_SSL_url\'] = ' . $config_base_ssl_url . '

$config[\'auto_check_current_version\'] = \'1\';

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
*/

$config[\'aff_cookie_name\']	= \'jamcom\';
$config[\'cookie_prefix\']	= \'\';
$config[\'cookie_domain\']	= \'.\' .  $config[\'base_domain_name\'];
$config[\'ssl_cookie_domain\']	= \'.\' . $config[\'base_domain_name\'];
$config[\'cookie_path\']		= \'/\';
$config[\'tracking_cookie_name\']	= \'jamtracker\';
$config[\'p3p_header\'] = \'P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"\';

/*
|--------------------------------------------------------------------------
| Facebook Connect Permissions
|--------------------------------------------------------------------------
*/

$config[\'facebook_permissions\'] = \'email,publish_stream\'; //email,publish_stream,create_event,rsvp_event,offline_access
$config[\'facebook_deny_url\'] = $config[\'base_url\'] . \'/login\';

/*
|--------------------------------------------------------------------------
| Pagination Links
|--------------------------------------------------------------------------
*/

$config[\'admin_pagination_links\'] = 2;
$config[\'member_pagination_links\'] = 4;

/*
|--------------------------------------------------------------------------
| Images Path and Settings
|--------------------------------------------------------------------------
*/
$config[\'images_admins_dir\'] = \'admins\';
$config[\'images_programs_dir\'] = \'programs\';
$config[\'images_members_dir\'] = \'members\';
$config[\'images_banners_dir\'] = \'banners\';
$config[\'images_maintain_ratio\'] = true;
$config[\'images_quality\'] = \'100%\';
$config[\'member_marketing_tool_ext\'] = \'png\';
$config[\'pragma_header_cache_control\'] = false;
$config[\'disable_db_autosorting\'] = false;

/*
|--------------------------------------------------------------------------
| URL Settings
|--------------------------------------------------------------------------
*/

$config[\'jrox_url_separator\'] = \'dash\'; //dash or underscore only
$config[\'tracker_unique_referrals_only\'] = false;
$config[\'jrox_custom_affiliate_url\'] = \'\'; //set {USERNAME} for the affiliate URL: http://www.domain.com/{USERNAME}

/*
|--------------------------------------------------------------------------
| Admin Settings
|--------------------------------------------------------------------------
*/

$config[\'admin_login_username_field\'] = \'username' . rand('100', '9999') . '\';
$config[\'admin_login_password_field\'] = \'password' . rand('100', '9999') . '\';

/*
|--------------------------------------------------------------------------
| Member Settings
|--------------------------------------------------------------------------
*/

$config[\'member_min_username_length\'] = \'4\';
$config[\'members_password_function\'] = \'sha1\'; //md5, sha1, or mcrypt
$config[\'member_mass_email_throttle\'] = \'0\';
$config[\'member_url_logout_redirect\'] = \'\';
$config[\'member_disable_registration_email\'] = false;
$config[\'member_list_append_unsubscribe\'] = true;
$config[\'member_enable_group_change_registration\'] = true;
$config[\'member_add_to_default_list_on_registration\'] = \'1\';
$config[\'member_admin_default_downline_view\'] = \'10\';

/*
|--------------------------------------------------------------------------
| Content Settings
|--------------------------------------------------------------------------
*/
$config[\'content_enable_javascript_code\'] = true;

/*
|--------------------------------------------------------------------------
| Links and Customization
|--------------------------------------------------------------------------
*/
$config[\'customizer_license_ordering_url\'] = \'http://jam.jrox.com/pricing/\'; //order button
$config[\'customizer_admin_area_help_url\'] = \'http://jam.jrox.com/kb/\'; //admin help button
$config[\'customizer_admin_area_forum_url\'] = \'http://community.jrox.com/\';
$config[\'customizer_admin_area_docs_url\'] = \'http://jam.jrox.com/kb/\';
$config[\'customizer_admin_area_videos_url\'] = \'http://community.jrox.com/videos/jam\';
$config[\'customizer_admin_area_quick_start_url\'] = \'http://jam.jrox.com/kb/\';
$config[\'customizer_member_area_help_url\'] = \'http://jam.jrox.com/docs/member_docs/\'; //members help button

/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
*/
$config[\'index_page\'] = \'' . $data['index_php'] . '\';
$config[\'admin_index_page\'] = \'' . $data['index_php'] . '\';

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
| \'AUTO\'			Default - auto detects
| \'PATH_INFO\'		Uses the PATH_INFO
| \'QUERY_STRING\'	Uses the QUERY_STRING
| \'REQUEST_URI\'		Uses the REQUEST_URI
| \'ORIG_PATH_INFO\'	Uses the ORIG_PATH_INFO
|
*/
$config[\'uri_protocol\']	= \'AUTO\';

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
*/
$config[\'language\']	= \'english\';

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
*/
$config[\'charset\'] = \'UTF-8\'; //ISO-8859-1 UTF-8

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
*/
$config[\'log_threshold\'] = \'0\';

/*
|--------------------------------------------------------------------------
| Error Logging Directory Path
|--------------------------------------------------------------------------
*/
$config[\'log_path\'] = \'\';

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
*/
$config[\'log_date_format\'] = \'Y-m-d H:i:s\';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
*/
$config[\'cache_path\'] = \'\';

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
*/

//CHANGING THIS WILL INVALIDATE CURRENT ENCRYPTED DATA!
$config[\'encryption_key\'] = \'' . $data['encryption_key'] . '\';

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
*/
$config[\'sess_cookie_name\']			= \'jrox_session' . rand('1000', '9999') . '\';
$config[\'sess_expiration\']			= 60 * 60 * 24 * 7;
$config[\'sess_expiration_pub\']		= 60 * 60 * 24 * 7;

$config[\'sess_encrypt_cookie\']		= FALSE;
$config[\'sess_use_database\']			= TRUE;
$config[\'sess_table_name\']			= \'sessions\';
$config[\'sess_match_ip\']				= TRUE;
$config[\'sess_match_useragent\']		= TRUE;
$config[\'sess_time_to_update\'] 		= 300;

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
*/
$config[\'compress_output\'] = FALSE;

/*
|--------------------------------------------------------------------------
| Host IP Lookup URL
|--------------------------------------------------------------------------
*/
$config[\'enable_geo_location_api\'] = false;
$config[\'geo_location_api_url\'] = \'http://ipinfo.io\';

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
*/
$config[\'time_reference\'] = \'gmt\';

//design_disable_dropdown_js

/*
|--------------------------------------------------------------------------
| Do Not Edit Anything Below.  It May Cause Your Site To Be Unstable
|--------------------------------------------------------------------------
*/
$config[\'subclass_prefix\'] = \'JROX_\';
$config[\'enable_hooks\'] = TRUE;
$config[\'permitted_uri_chars\'] = \'@+=\a-z 0-9~%.:_-\';
$config[\'enable_query_strings\'] = FALSE;
$config[\'directory_trigger\'] = \'d\';
$config[\'controller_trigger\'] = \'c\';
$config[\'function_trigger\'] = \'m\';
$config[\'rewrite_short_tags\'] = FALSE;
$config[\'global_xss_filtering\'] = FALSE;
$config[\'url_suffix\'] = \'\';
?>';
	}
	else
	{
		$content = '<?php  if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the "Database Connection"
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	[\'hostname\'] The hostname of your database server.
|	[\'username\'] The username used to connect to the database
|	[\'password\'] The password used to connect to the database
|	[\'database\'] The name of the database you want to connect to
|	[\'dbdriver\'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql
|	[\'dbprefix\'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	[\'pconnect\'] TRUE/FALSE - Whether to use a persistent connection
|	[\'db_debug\'] TRUE/FALSE - Whether database errors should be displayed.
|	[\'cache_on\'] TRUE/FALSE - Enables/disables query caching
|	[\'cachedir\'] The path to the folder where cache files should be stored
|	[\'char_set\'] The character set used in communicating with the database
|	[\'dbcollat\'] The character collation used in communicating with the database
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the "default" group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = \'default\';
$active_record = TRUE;

$db[\'default\'][\'hostname\'] = \'' . trim($data['hostname']) . '\';
$db[\'default\'][\'username\'] = \'' . trim($data['username']) . '\';
$db[\'default\'][\'password\'] = \'' . trim($data['password']) . '\';
$db[\'default\'][\'database\'] = \'' . trim($data['database']) . '\';
$db[\'default\'][\'dbdriver\'] = \'mysql\';
$db[\'default\'][\'dbprefix\'] = \'jam_\';
$db[\'default\'][\'pconnect\'] = FALSE;
$db[\'default\'][\'db_debug\'] = TRUE;
$db[\'default\'][\'cache_on\'] = FALSE;
$db[\'default\'][\'cachedir\'] = \'' . $data['cachedir'] . '\';
$db[\'default\'][\'char_set\'] = \'utf8\';
$db[\'default\'][\'dbcollat\'] = \'utf8_unicode_ci\';

?>';

	}
	
	//open the config.php file
	$handle = @fopen($path, 'w+');
	if ($handle)
	{
		if (fwrite($handle, $content))
		{
			fclose($handle);
		}
		else
		{
			$show_errors .= "Could not write to includes/" . $type . ".php.\n";  
			$show_errors .= "Please check the file permissions.\n";
			return $show_errors;
		}
	}
	else
	{
		$show_errors .= "Could not write to includes/" . $type . ".php.\n";  
		$show_errors .= "Please check the file permissions.\n";
		return $show_errors;
	}
}

?>