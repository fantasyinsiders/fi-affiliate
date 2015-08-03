<?php
/*
| -------------------------------------------------------------------------
| FILENAME - link.php
| -------------------------------------------------------------------------     
| 
| This js file is for use with invisilinks
|
*/

//$url = $_SERVER['HTTP_REFERER'];

$use_php = false; //set to true if you want to use PHP instead of JavaScript

require_once('JAM.php'); 
include APPPATH . 'config/config.php';
 
$post_url = $config['base_url'] . '/direct_track/index';
if (!empty($_GET['remote']))
{
	$post_url =  $config['base_url'] . '/js/remote/';
} 


if (!empty($use_php) && !empty($_GET['remote']))
{
	$fields = 'ref=' . $_GET['remote'] . '&pid=1&user_agent=' . $_SERVER['HTTP_USER_AGENT'];
	
	$ch = curl_init($config['base_url'] . '/direct_track/index');
	
	curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, '50');

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
	curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
	$resp = curl_exec($ch); //execute post and get results
	curl_close ($ch);
	
	$c = json_decode($resp);
	$length = time() + 60 * 60 * 24 *  $c->length;
	setcookie($c->name, $c->value, $length, $c->path, $c->domain);
	
}
else
{
	header("Content-type: text/javascript");
?>

function setCookie(c_name,value,exdays)
{
var exdate=new Date();
exdate.setDate(exdate.getDate() + exdays);
var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
document.cookie=c_name + "=" + c_value + "; path=/";

}

// create the XMLHttpRequest object, according browser
function get_XmlHttp() {
  // create the variable that will contain the instance of the XMLHttpRequest object (initially with null value)
  var xmlHttp = null;

  if(window.XMLHttpRequest) {		// for Forefox, IE7+, Opera, Safari, ...
    xmlHttp = new XMLHttpRequest();
  }
  else if(window.ActiveXObject) {	// for Internet Explorer 5 or 6
    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
  }

  return xmlHttp;
}

// sends data to a php file, via POST, and displays the received answer
function ajaxrequest(php_file, tagID) {
  var request =  get_XmlHttp();		// call the function for the XMLHttpRequest instance

  // create pairs index=value with data that must be sent to server
  var  the_data = 'ref='+url+'&pid=1';

  request.open("POST", php_file, true);			// set the request

  // adds  a header to tell the PHP script to recognize the data as is sent via POST
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  request.send(the_data);		// calls the send() method with datas as parameter

  // Check request status
  request.onreadystatechange = function() {
    if (request.readyState == 4) {
        var json = request.responseText;
        var obj = JSON.parse(json);
		//alert(obj.length);
        setCookie(obj.name,obj.value,obj.length);
        localStorage.setItem(obj.name,obj.value);
		//alert(localStorage.getItem(obj.name));
    }
  }
}

ajaxrequest('<?php echo $post_url; ?>');

<?php
}
?>