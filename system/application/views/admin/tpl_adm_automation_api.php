<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form"  method="post" class="form-horizontal"  role="form">
<div class="row">
   	<div class="col-md-4">
    <h4 class="header"><i class="fa fa-cog"></i> <?=$this->lang->line('automation_api')?></h4>
    </div>
    <div class="col-md-8 text-right">
        <a href="<?=admin_url()?>integration/options" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('website_integration')?></span></a>
    </div>
</div>
<br />
<div class="row">   
	<div class="col-md-12">
    	<div class="alert alert-danger hidden-xs hidden-sm">
            <i class="fa fa-exclamation-triangle"></i> This requires advanced programming knowledge. Please contact us if you need help integrating this into your system.
        </div>	
		<div class="box-info">
            <ul class="nav nav-tabs capitalize">
            	<li class="active"><a href="#main" data-toggle="tab"><?=$this->lang->line('overview')?></a></li>
                <li><a href="#commands" data-toggle="tab"><?=$this->lang->line('api_commands')?></a></li>
                <li><a href="#js" data-toggle="tab"><?=$this->lang->line('javascript_api')?></a></li>
            </ul>
        	<div class="tab-content">
        		<div id="main" class="tab-pane fade in active">
                    <h3>What is the Automation API?</h3>
                    <p>The Affiliate Automation API allows you to do certain tasks outside of the affiliate management script.  Certain actions such as affiliate registration, account login and logout, and even group upgrades can be done using the Automation API.  
                    </p>
                    <p><strong>What are the requirements to use the Automation API?</strong></p>
                    <ul>
                    	<li>In order to use the Automation API, you must have some knowledge of PHP programming and how CURL / socket connections work.  </li>
                    	<li>You also need to know the inner workings of shopping cart or payment gateway in order to integrate the Affiliate Automation API into your site.  </li>
                    	<li>Your site also requires the ability to use PHP Curl to connect to the API using name-value pairs.</li>
                    </ul>
                    <p><strong>What Type of Automation Tasks can the API Do?</strong></p>
                    <p>The Affiliate Automation API can do a number of actions in regards to your affiliate user. </p>
                    <ol>
                    	<li><strong>Account Creation and Registration </strong>- You can automatically create and register an affiliate when an affiliate creates an account on your main store or website. There won't be a need for the user to manually signup on your affiliate form.</li>
                    	<li><strong>Account Login and Logout </strong>- Automatic Account Login, also known as Single Signon.  If the user logs into your main web site, he / she will be automatically logged into the affiliate program as well.</li>
                    	<li><strong>Affiliate Group Update (upgrade / downgrade)</strong> - Automatically update an affiliate's group membership.  This is useful if you have multiple affiliate groups with different commission payment plans.  For instance, if a user cancels a subscription to a specific membership of yours, you can downgrade him / her to a different affiliate group.</li>
                    	<li><strong>Affiliate Account Activation / Deactivation</strong>  - Activate or Deactivate an affiliate account's status.  This does not delete their account in your affiliate program if you deactivate them, but it does restrict them from being able to login to your program.</li>
                    	<li><strong>Account Deletion</strong> - Delete a user's account entirely.</li>
                    </ol>
       		  	</div>
                <div id="commands" class="tab-pane fade in">
                	<h3>API Commands and Sample Code</h3>
                    <hr />
                	<h5><strong>Register - Create a user account</strong></h5>
                  	<p>Required name / value pairs:</p>
                        <ul>
                        	<li>access_key - Automation access key set in your Settings &gt; Global Configuration &gt; Automation &gt; Auto Signup</li>
                        	<li>access_id - Automation access ID set in your Settings &gt; Global Configuration &gt; Automation &gt; Auto Signup</li>
                        	<li>email - email variable for new user</li>
                        	<li>fname - first name variable for new user</li>
                       	</ul>
                  	<p>Optional name / value pairs</p>
                        <ul>
                          <li>sponsor cookie - affiliate manager sponsor cookie</li>
                          <li>affiliate_group - numeric affiliate group ID (if you want the user to be in a custom tier /group</li>
                          <li>password - hashed md5 password (if hashed, included the encrypted name/value pair as well)</li>
                          <li>encrypted - if password is hashed, set this to 'true'</li>
                          <li>program ID - optional numeric program ID</li>
                        </ul>
 					<p><strong>Sample Register API Code:</strong></p>                   
<pre>
$access_key = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; 
$access_id = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

$sdata = array(
               'access_key' => $access_key,
               'access_id' => $access_id,
               
               'email' => 'automation@<?=$base_domain_name?>',
               'fname' => 'automation',
               //'affiliate_group' => '2',
               //'password' => 'dc724af18fbdd4e59189f5fe768a5f8311527050',
               //'encrypted' => true
               //'program_id' => '2',
               'sponsor' => !empty($_COOKIE['<?=$aff_cookie_name?>']) ? $_COOKIE['<?=$aff_cookie_name?>'] : '',
               );

$fields = "";
foreach( $sdata as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";

$ch = curl_init('<?=base_url()?>automate/register');

curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, '50');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
$resp = curl_exec($ch); //execute post and get results
curl_close ($ch);

echo $resp;
        </pre>
				<hr />
                	<h5><strong>Login - Auto Login a user account</strong></h5>
                  	<p>Required name / value pairs: </p>
                        <ul>
                        	<li>access_key - Automation access key set in your Settings &gt; Global Configuration &gt; Automation &gt; Auto Signup</li>
                        	<li>access_id - Automation access ID set in your Settings &gt; Global Configuration &gt; Automation &gt; Auto Signup</li>
                        	<li>ip - remote IP address</li>
                        	<li>user_agent  - HTTP user agent</li>
                        	<li>email - affiliate user's email address</li>
                        	<li>password - hashed or raw password value</li>
                        	<li>bypass_pwd - if you don't want to match the password in the affiliate software's database, set this to 'true' and the system will only match the user's email address</li>
                        	<li>encrypted - if you are sending a hashed password value, set this to 'true'</li>
                        </ul>
						<p><strong>Sample Auto Login API Code:</strong></p>                   
<pre>
$access_key = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; 
$access_id = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

$sdata = array(
               'access_key' => $access_key,
               'access_id' => $access_id,
               'ip' => $_SERVER['REMOTE_ADDR'],
               'user_agent' => $_SERVER['HTTP_USER_AGENT'],
               'email' => 'automation@<?=$base_domain_name?>',
               //'password' => 'testing',
               //'password' => 'dc724af18fbdd4e59189f5fe768a5f8311527050',
               'bypass_pwd' => 1,
                'encrypted' => false,
               );

$fields = "";
foreach( $sdata as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";

$ch = curl_init('<?=base_url()?>automate/login');

curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, '50');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
$resp = curl_exec($ch); //execute post and get results
curl_close ($ch);

$aff_cookie = unserialize(base64_decode($resp));

if (setcookie(
            $aff_cookie['name'],
            $aff_cookie['value'],
            $aff_cookie['expire'],
            $aff_cookie['path'],
            $aff_cookie['domain'],
            0
            ))
{
    echo 'SUCCESS: User logged in';	
}
</pre>
<p><strong>Sample Auto Logout API Code:</strong></p>       
<pre>
$access_key = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; 
$access_id = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

$sdata = array(
               'access_key' => $access_key,
               'access_id' => $access_id,
               'session' => $_COOKIE['<?=$sess_cookie_name?>'],
               );

$fields = "";
foreach( $sdata as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";

$ch = curl_init('<?=base_url()?>automate/logout');

curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, '50');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
$resp = curl_exec($ch); //execute post and get results
curl_close ($ch);
</pre>
<hr />
                	<h5><strong>Set Tracking Cookie - set a tracking cookie based on the affiliate username</strong></h5>
                  	<p>Required name / value pairs:</p>
                        <ul>
                        	<li>access_key - Automation access key set in your Settings &gt; Global Configuration &gt; Automation &gt; Auto Signup</li>
                        	<li>access_id - Automation access ID set in your Settings &gt; Global Configuration &gt; Automation &gt; Auto Signup</li>
                        	<li>ip - remote IP address</li>
                        	<li>user_agent  - HTTP user agent</li>
                        	<li>subdomain - the affiliate username</li>
                        </ul>
						<p><strong>Sample Set Tracking Cookie API Code:</strong></p>                   
<pre>
$access_key = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; 
$access_id = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

$sdata = array(
               'access_key' => $access_key,
               'access_id' => $access_id,
               'ip' => $_SERVER['REMOTE_ADDR'],
               'user_agent' => $_SERVER['HTTP_USER_AGENT'],
               'subdomain' => $username, //affiliate username
               );

$fields = "";
foreach( $sdata as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";

$ch = curl_init('<?=base_url()?>automate/set_tracking_cookie');

curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, '50');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
$resp = curl_exec($ch); //execute post and get results
curl_close ($ch);

$aff_cookie = unserialize(base64_decode($resp));

if (setcookie(
            $aff_cookie['name'],
            $aff_cookie['value'],
            $aff_cookie['expire'],
            $aff_cookie['path'],
            $aff_cookie['domain'],
            0
            ))
{
    echo 'SUCCESS: User logged in';	
}
</pre>
				<hr />
                	<h5><strong>Activate / Deactivate - activate or deactivate an affiliate account</strong></h5>
                  	<p>Required name / value pairs:</p>
                        <ul>
                        	<li>access_key - Automation access key set in your Settings &gt; Global Configuration &gt; Automation &gt; Auto Signup</li>
                        	<li>access_id - Automation access ID set in your Settings &gt; Global Configuration &gt; Automation &gt; Auto Signup</li>
                        	<li>email - affiliate user's email address</li>
                        </ul>
						<p><strong>Sample Activate API Code:</strong></p>                   
<pre>
$access_key = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; 
$access_id = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

$sdata = array(
               'access_key' => $access_key,
               'access_id' => $access_id,
               'email' => 'automation@<?=$base_domain_name?>',
               );

$fields = "";
foreach( $sdata as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";

$ch = curl_init('<?=base_url()?>automate/activate');

curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, '50');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
$resp = curl_exec($ch); //execute post and get results
curl_close ($ch);
</pre>	
<p><strong>Sample Deactivate API Code:</strong></p>                   
<pre>
$access_key = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; 
$access_id = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

$sdata = array(
               'access_key' => $access_key,
               'access_id' => $access_id,
               'email' => 'automation@<?=$base_domain_name?>',
               );

$fields = "";
foreach( $sdata as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";

$ch = curl_init('<?=base_url()?>automate/deactivate');

curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, '50');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
$resp = curl_exec($ch); //execute post and get results
curl_close ($ch);
</pre>	
				<hr />
                	<h5><strong>Delete - Delete an affiliate account based on email address</strong></h5>
                  	<p>Required name / value pairs:</p>
                        <ul>
                        	<li>access_key - Automation access key set in your Settings &gt; Global Configuration &gt; Automation &gt; Auto Signup</li>
                        	<li>access_id - Automation access ID set in your Settings &gt; Global Configuration &gt; Automation &gt; Auto Signup</li>
                        	<li>email - affiliate user's email address</li>
                        </ul>
						<p><strong>Sample Activate API Code:</strong></p>                   
<pre>
$access_key = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; 
$access_id = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

$sdata = array(
               'access_key' => $access_key,
               'access_id' => $access_id,
               'email' => 'automation@<?=$base_domain_name?>',
               );

$fields = "";
foreach( $sdata as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";

$ch = curl_init('<?=base_url()?>automate/delete');

curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, '50');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
$resp = curl_exec($ch); //execute post and get results
curl_close ($ch);
</pre>	
				<hr />
                	<h5><strong>Update Group - Update an affiliate's associated group / tier</strong></h5>
                  	<p>Required name / value pairs:</p>
                        <ul>
                        	<li>access_key - Automation access key set in your Settings &gt; Global Configuration &gt; Automation &gt; Auto Signup</li>
                        	<li>access_id - Automation access ID set in your Settings &gt; Global Configuration &gt; Automation &gt; Auto Signup</li>
                        	<li>id - affiliate user's email address</li>
                            <li>field - set to 'primary_email' to use the affiliate user's email address</li>
                            <li>group_id - numeric group ID to upgrade to</li>
                        </ul>
						<p><strong>Sample Upgrade Group API Code:</strong></p>                   
<pre>
$access_key = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx'; 
$access_id = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

$sdata = array(
               'access_key' => $access_key,
               'access_id' => $access_id,
               'id' => 'automation@<?=$base_domain_name?>',
               'field' => 'primary_email',
               'group_id' => '1',
               );

$fields = "";
foreach( $sdata as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";

$ch = curl_init('<?=base_url()?>automate/update_group');

curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, '50');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
$resp = curl_exec($ch); //execute post and get results
curl_close ($ch);
</pre>	
              </div>
              	<div id="js" class="tab-pane fade in">
                	<h3>JavaScript API?</h3>
                	<p>The Javascript API allows you to show specific affiliate data on external pages using simple Javascript calls. &nbsp;For example:</p>
					<pre>&lt;script src="<?=base_url()?>js/show/primary_email">&lt;/script></pre>
                    <p>Once a user has clicked on an affiliate link, adding that javascript above will show the referring affiilate's email address, like:</p>
                    <p><strong>user@domain.com</strong></p>
                    <p>You can show any of the following affiliate fields that you want via Javascript.</p>
                    <ul>
                        <li>fname - the afiliate's first name</li>
                        <li>lname - the affiliate's last name</li>
                        <li>primary_email - the affiliate's email address</li>
                        <li>username - the affiliate's username</li>
                        <li>company - the affiliate's company</li>
                        <li>home_phone - affiliate home phone</li>
                        <li>work_phone - affiliate work phone</li>
                        <li>mobile_phone - affiliate mobile phone</li>
                        <li>program_custom_field_1 - affiliate custom field 1</li>
                        <li>program_custom_field_2 - affiliate custom field 2</li>
                        <li>program_custom_field_3 - affiliate custom field 3</li>
                        <li>program_custom_field_4 - affiliate custom field 4</li>
                        <li>program_custom_field_5 - affiliate custom field 5</li>
                        <li>program_custom_field_6 - affiliate custom field 6</li>
                        <li>program_custom_field_7 - affiliate custom field 7</li>
                        <li>program_custom_field_8 - affiliate custom field 8</li>
                        <li>program_custom_field_9 - affiliate custom field 9</li>
                        <li>program_custom_field_10 - affiliate custom field 10</li>
                    </ul>
                    <p>You can also show affiliate data in a hidden field, so that you can add hidden form fields to any web form that you want. Just append the <strong>field</strong> value to each javascript call, and it will do a javascript:<strong> document.write('&lt;input type="hidden" name="fname" value="joe">');</strong></p>
                    <pre>&lt;script src="<?=base_url()?>js/show/primary_email/field">&lt;/script></pre>
                    <p><strong>Enabling the Javascript API</strong></p>
                    <p>To enable this option, you need to first enable it in the affiliate admin area.</p>
                    <ol>
                        <li>Click on <a href="<?=admin_url()?>settings#marketing"><strong>Settings > Global Configuration</strong></a>on the top menu of your affiliate admin area</li>
                        <li>Click on <strong>Marketing > Marketing Tools </strong>tab and enable the <strong>Allow Javascript Info Display</strong> option.</li>
                        <li>Click <strong>Save Changes</strong></li>
                    </ol>
                </div>
            </div>
		</div>
    </div>
</div>
</form>