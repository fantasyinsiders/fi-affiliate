<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<!-- load html charset -->
<meta http-equiv="Content-Type" content="text/html; charset=<?=$charset?>" />
<base href="<?=$base_url?>" />
<!-- load page title -->
<title><?=$page_title?></title>
<link rel="stylesheet" href="themes/main/<?=$default_theme?>/css/style.css" type="text/css" media="screen, projection" />  
<link rel="stylesheet" href="themes/main/downline.css" type="text/css" media="screen, projection" />  


<!-- load admin stylesheets -->
 
</head>
<body id="main_body" class="form-bg">
<div class="template-form" align="center">

<table width="90%" border="0" align="center" cellpadding="5" cellspacing="0" style="border:1px solid #000000; background-color:#FFFFFF;">
  
  <tr valign="top">
	<td colspan="7" align="center"><h3 class="jroxBold jroxCapitalize"><?=$this->lang->line('view_downline_for')?> <?=$member_name?></h3>
   
    <?php if ($show_upper_sponsor == 1):?>
     <span class="jroxBold jroxCapitalize">* <?=$this->lang->line('click_on_each_to_view_downline')?>
    <br/>
    <a href="<?=site_url('members')?>/downline/view/<?=$sponsor_id?>"><?=$this->lang->line('go_up_one_level')?></a>
    
    <?php endif; ?>
	</span>
    </td>
  </tr>
  <tr valign="top">
	<td colspan="7" align="center"><strong><?=$total_users?> <?=$this->lang->line('total_users')?> - 
	<?=$this->lang->line('showing')?> <?=$levels?> <?=$this->lang->line('level_s')?><br />
    <?=$prg_commission_levels?> <?=$this->lang->line('total_levels')?>
    </strong><br />
    <img src="<?=$member_photo?>"/></td>
  </tr>
  
  <tr valign="top">
	<td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr valign="top">
		
           	
    
 		<?=$downline_table?>

		</tr>
		  
		</table></td>
	  </tr>
	</table>

</div>
</body>
</html>	