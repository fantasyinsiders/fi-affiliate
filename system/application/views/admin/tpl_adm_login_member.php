<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE HTML>
<html>
<head>

<!-- load html charset -->
<meta http-equiv="Content-Type" content="text/html; charset=<?=$charset?>" />
</head>
<body >
<form action="<?=base_url()?><?=$this->config->slash_item('index_page')?>login/index/admin" method="post" name="frm" id="frm">

  <div align="center">
  <input name="a_username" type="hidden" value="<?=$a_username?>" />
  <input name="a_password" type="hidden" value="<?=$a_password?>" />
  <input type="submit" value="<?=$this->lang->line('if_not_forwarded_click_here')?>" style="border: 0; background:#FFFFFF; color:#CCCCCC;" />
  </div>
</form>
</body>
</html>	