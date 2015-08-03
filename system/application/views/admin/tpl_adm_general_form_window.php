<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<!-- load html charset -->
<meta http-equiv="Content-Type" content="text/html; charset=<?=$charset?>" />

<!-- load page title -->
<title><?=$sts_site_name?></title>

<!-- load admin javascript -->
<script language="JavaScript" type="text/javascript" src="<?=base_url();?>js/jquery.js"></script>
<script language="JavaScript" type="text/javascript" src="<?=base_url();?>js/common.js"></script>
<?=$load_js?>


<style>

a.button, button {
  display:block;
  float:left;
  margin:0 0.583em 0.667em 0;
  padding:5px 10px 5px 7px;   /* Links */
  
  border:1px solid #ccc;
  border-top:1px solid #eee;
  border-left:1px solid #eee;

  background-color:#f5f5f5;
  font-family:"Lucida Grande", Tahoma, Arial, Verdana, sans-serif;
  font-size:12px;
  line-height:130%;
  text-decoration:none;
  font-weight:bold;
  color:#565656;
  cursor:pointer;
}
button {
  width:auto;
  overflow:visible;
  padding:4px 10px 3px 7px;   /* IE6 */
}
button[type] {
  padding:4px 10px 4px 7px;   /* Firefox */
  line-height:17px;           /* Safari */
}
*:first-child+html button[type] {
  padding:4px 10px 3px 7px;   /* IE7 */
}
button img, a.button img{
  margin:0 3px -3px 0 !important;
  padding:0;
  border:none;
  width:16px;
  height:16px;
  float:none;
}


/* Button colors
-------------------------------------------------------------- */

/* Standard */
button:hover, a.button:hover{
  background-color:#dff4ff;
  border:1px solid #c2e1ef;
  color:#336699;
}
a.button:active{
  background-color:#6299c5;
  border:1px solid #6299c5;
  color:#fff;
}

/* Positive */
body .positive {
  color:#529214;
}
a.positive:hover, button.positive:hover {
  background-color:#E6EFC2;
  border:1px solid #C6D880;
  color:#529214;
}
a.positive:active {
  background-color:#529214;
  border:1px solid #529214;
  color:#fff;
}

/* Negative */
body .negative {
  color:#d12f19;
}
a.negative:hover, button.negative:hover {
  background:#fbe3e4;
  border:1px solid #fbc2c4;
  color:#d12f19;
}
a.negative:active {
  background-color:#d12f19;
  border:1px solid #d12f19;
  color:#fff;
}

/* Neutral */
body .neutral {
  color: #FF6600;
}

a.neutral:hover, button.neutral:hover {
  background: #FFDDCC;
  border:1px solid  #FF9933;
   color: #FF6600;
}
a.neutral:active {
  background-color:#d12f19;
  border:1px solid #d12f19;
  color:#fff;
}

</style>

<!-- load admin stylesheets -->

</head>
<body id="main_body" class="form-bg">
<div class="template-form">
 <div style="margin:1em 0 0 0; float:right">
     <?php if (!empty($show_button)): ?>  
     <a href="#" id="show-ad" class="button positive" >
          <img src="<?=base_url();?>themes/admin/<?=$sts_admin_layout_theme?>/images/tick.png" alt=""/>
         <?=$this->lang->line('show')?>
     </a>
	<?php endif;?>
    
   <a href="#" onclick="self.close();return false;" class="button negative" >
          <img src="<?=base_url();?>themes/admin/<?=$sts_admin_layout_theme?>/images/cross.png" alt=""/>
         <?=$this->lang->line('close')?>
     </a>
    </div>	
<table width="100%" border="0" cellpadding="0" cellspacing="0" id="update-table" class="popup-form">
 <tr>
    <td width="100%">
    
    <!-- START CODE -->
    
   <?=$code?>
   
   <!-- END CODE -->
    
    </td>
 </tr>
  
  
 <tr>
    <td>
   
   	</td>
 </tr>
</table>
</div>
</body>
</html>	