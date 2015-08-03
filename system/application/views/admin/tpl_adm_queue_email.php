<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form" action="<?=$submit_url?>" method="post" enctype="multipart/form-data">  
<div class="row">
	<div class="col-md-4">
    	<?=_generate_sub_headline($function)?>
    </div>
   	<div class="col-md-8 text-right">
    <a href="<?=admin_url()?>email_queue/view_email_queue" class="btn btn-primary"><i class="icon-zoom-in"></i> <?=$this->lang->line('view_email_queue')?></a>
    </div>
</div>
<hr />
<?php if (!empty($do_send)): ?>
<div class="alert alert-warning">
	<div class="progress progress-striped active">
	  <div class="progress-bar progress-bar-warning"  role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: <?=$percent_done?>%"></div>
	</div>
	<h2 class="text-warning"><?=$msg ?></h2>
</div>
<?php else: ?>
<div class="alert alert-success" role="alert">
	<h2 class="text-success"><?=$msg ?></h2>
    <p>
    <a href="<?=admin_url()?>mailing_lists/view_mailing_lists" class="btn btn-success"><?=$this->lang->line('go_back')?></a>
    <a href="<?=admin_url()?>email_queue/flush_queue" class="btn btn-success" data-loading-text="<?=$this->lang->line('please_wait')?>"><i class="fa fa-refresh"></i> <?=$this->lang->line('flush_queue')?></a>
    </p>
</div>
<?php endif; ?>
<?php foreach ($_POST as $k => $v): ?>
<?php if ($k == 'html_body'): ?>
<input type="hidden" name="<?=$k?>" value="<?=htmlentities($v)?>" />
<?php else: ?>
<input type="hidden" name="<?=$k?>" value="<?=$v?>" />
<?php endif; ?>
<?php endforeach; ?>
<input type="hidden" name="offset" value="<?=$offset?>" />
</form>
 <?php if (!empty($do_send)): ?>
<script>
$(function(){
	$('#form').submit();
});

</script>
<?php endif; ?>