<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="fb-root"></div>
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId   : '<?php echo $sts_site_facebook_app_id ?>',
          status  : true, // check login status
          cookie  : true, // enable cookies to allow the server to access the session
          xfbml   : true, // parse XFBML
		  oath    : true
        });

        // whenever the user logs in, we refresh the page
        FB.Event.subscribe('auth.login', function() {
			window.location.reload();
		});
		
		FB.Event.subscribe('auth.logout', function() {
    		window.location='<?=base_url()?>';
        });
		 
      };
 
      (function() {
        var e = document.createElement('script');
        e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
        e.async = true;
        document.getElementById('fb-root').appendChild(e);
      }());
    </script>