<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<!-- load html charset -->
<meta http-equiv="Content-Type" content="text/html; charset=<?=$charset?>" />
<base href="<?=$base_url?>" />


<link rel="stylesheet" href="themes/main/<?=$default_theme?>/css/style.css" type="text/css" media="screen, projection" />  
<link rel="stylesheet" href="themes/main/downline.css" type="text/css" media="screen, projection" />  

</head>
<body id="main_body" class="form-bg">
<div>
	<div class="downlineMemBox">
    	 <h3><?=$fname?> <?=$lname?></h3>
         <div id="downlineMemImage" class="jroxMembersPhotoImage jroxMarginOne">
                <img src="<?=$member_photo?>" alt=""/>
        </div>
        
        <div class="downlineMemDetails">
            
            <p><?=$primary_email?></p>
            <p><?=$billing_address_1?></p>
            <p><?=$billing_address_2?></p>
            <p><?=$billing_city?> <?=$billing_state?> <?=$billing_postal_code?> </p>
            <p><?=$billing_country_name?></p>
            <p><?=$home_phone?></p>
            <p><?=$work_phone?></p>
        	<p><?=$mobile_phone?></p>
        </div>
        
       
        <div class="clear"></div>
	</div>
</div>
</body>
</html>	