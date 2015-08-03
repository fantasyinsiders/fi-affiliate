<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$charset?>" />

<title><?=$sts_site_name?></title>

<link href="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/css/style.css" rel="stylesheet">
<style>
.google-visualization-orgchart-node {background: #fff; border-color: #ccc;}
.google-visualization-orgchart-linebottom { border-color: #666; }
.google-visualization-orgchart-table a { color: #333; text-decoration: none; font-size: 11px;}
body { background-image: none; }
</style>

<!-- load admin javascript -->
<script language="JavaScript" type="text/javascript" src="<?=base_url();?>js/jquery.js"></script>
<script language="JavaScript" type="text/javascript" src="<?=base_url();?>js/jquery.form.js"></script>
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
    <script type='text/javascript' src='<?=_check_ssl('www.google.com/jsapi')?>'></script>
    <script type='text/javascript'>
      google.load('visualization', '1', {packages:['orgchart']});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Name');
        data.addColumn('string', 'Manager');
        data.addColumn('string', 'ToolTip');
        data.addRows([
          <?=$rows?>
        ]);
        var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
        chart.draw(data, {allowHtml:true});
      }
    </script>

</head>
<body id="main_body" class="form-bg">
<div class="template-form">
<div id="content">
<?php if ($this->session->flashdata('success')): ?>
	<div class="success" id="success"><?=$this->session->flashdata('success');?></div>
<?php endif; ?>
</div>
<div style="padding: 2em;">
<table width="90%" border="0" align="center" cellpadding="5" cellspacing="0">
  
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
    </strong></td>
  </tr>

	</table>
    <div id='chart_div'></div>
    </div>
   <table width="300" border="0" style="margin-left:6em; margin-top: 1em; border: 1px solid #eee">
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