<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<!-- load html charset -->
<meta http-equiv="Content-Type" content="text/html; charset=<?=$charset?>" />

<!-- load page title -->
<title><?=$sts_site_name?></title>

<link href="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/css/bootstrap.min.css" rel="stylesheet">

<!-- load admin javascript -->
<script language="JavaScript" type="text/javascript" src="<?=base_url();?>js/jquery.js"></script>
<script language="JavaScript" type="text/javascript" src="<?=base_url();?>js/highcharts/highcharts.js"></script>
<script type="text/javascript">

<?=$graph_data?>;

</script>

</head>
<body id="main_body" class="form-bg">
<div class="template-form">


<div id="<?=$report_id?>" style="min-width: 400px; height: 286px; margin: 0 auto;"></div>

</div>
</body>
</html>	