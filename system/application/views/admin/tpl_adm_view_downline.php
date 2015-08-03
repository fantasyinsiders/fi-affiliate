<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
<head>

<!-- load html charset -->
<meta http-equiv="Content-Type" content="text/html; charset=<?=$charset?>" />

<!-- load page title -->
<title><?=$sts_site_name?></title>


<!-- load admin stylesheets -->
<link href="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/css/style.css" rel="stylesheet">

<style>
body { background-image: none; padding: 2em; text-transform: capitalize;}
.downline_top {border-top: 1px solid #999; }

.downline_left {border-left: 1px solid #999; }

.downline_right {border-right: 1px solid #999; }

.downline_box {  padding: 0 4px; margin-top: -28px;}
.downline_box a { font-size: 11px; color: #333; text-decoration: none; }
.downline_image {height: 35px;width: 30px; }
</style>
<!-- load admin javascript -->
<script language="JavaScript" type="text/javascript" src="<?=base_url();?>js/jquery.js"></script>
<script language="JavaScript" type="text/javascript" src="<?=base_url();?>js/forms/jquery.form.js"></script>
<script language="JavaScript" type="text/javascript" src="<?=base_url();?>js/jquery.popupwindow.js"></script>
<script language="JavaScript" type="text/javascript" src="<?=base_url();?>js/common.js"></script>
<script type="text/javascript">
$(function() {
	
<?php if ($this->session->flashdata('success')): ?>
	fadeoutdiv('#success');
<?php endif; ?>
	
	var options = {target: '#content', success: showResponse};
	
	$('#prod_form').ajaxForm(options);
	
	var profiles =
	{
		windowCenter:
		{
			height:650,
			width:700,
			center:1
		}
	};

	$(".popupwindow").popupwindow(profiles);

});
</script>  
</head>
<body id="main_body" class="form-bg">
<div class="template-form">
<div id="content">
<?php if ($this->session->flashdata('success')): ?>
	<div class="success" id="success"><?=$this->session->flashdata('success');?></div>
<?php endif; ?>
</div>


<table width="90%" border="0" align="center" cellpadding="5" cellspacing="0" style="border:1px solid #333; background-color:#FFFFFF;">
  
  <tr valign="top">
	<td colspan="7" align="center"><h3><?=$this->lang->line('view_downline')?> - <?=$member_name?></h3><br/>
    <strong class="note">* <?=$this->lang->line('click_on_each_to_view_downline')?>
    <br />
	<?php if (!empty($sponsor_id)): ?>
    <a href="<?=admin_url()?>downline/view_downline/<?=$sponsor_id?>"><?=$this->lang->line('go_up_one_level')?></a>
    <?php else: ?>
    <a href="<?=admin_url()?>downline/view_downline/0"><?=$this->lang->line('view_top_level')?></a>
    <?php endif; ?>
    </strong>
    </td>
  </tr>
  <tr valign="top">
	<td colspan="7" align="center"><strong><?=$total_users?> <?=$this->lang->line('total_users') ?> - 
	<?=$this->lang->line('showing') ?> <?=$levels?> <?=$this->lang->line('levels')?><br />
    <?=$this->config->item('sts_affiliate_commission_levels')?> <?=$this->lang->line('total_levels') ?>
    </strong><br />
    <img src="<?=$member_photo?>" style="padding: 5px; margin: 5px;"/></td>
  </tr>
  
  <tr valign="top">
	<td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr valign="top">
		
           	
    
 		<?=$downline_table?>

		</tr>
		  
		</table></td>
	  </tr>
	</table>
<table width="300" border="0" style="margin-left:6em; margin-top: 1em">
  <tr>
    <td width="43"><img src="<?=base_url()?>images/misc/downline_inactive_1.gif" border="0"/></td>
    <td width="147"><?=$this->lang->line('inactive_members')?></td>
    <td width="39"><img src="<?=base_url()?>images/misc/downline_1.gif" border="0"/></td>
    <td width="153"><?=$this->lang->line('active_members')?></td>
  </tr>
</table>


</div>
</body>
</html>	