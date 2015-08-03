<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form" method="post" enctype="multipart/form-data">  
<div class="row">
	<div class="col-md-4">
    <?=_generate_sub_headline($function)?>
    </div>
   	<div class="col-lg-8 text-right">
    <a href="<?=admin_url()?>mailing_lists/view_mailing_lists" class="btn btn-primary"><i class="icon-zoom-in"></i> <?=$this->lang->line('view_mailing_lists')?></a>
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
<div class="alert alert-success">
	<h2 class="text-success"><?=$msg ?></h2>
    <p><a href="<?=admin_url()?>mailing_lists/view_mailing_lists" class="btn btn-success btn-lg"><?=$this->lang->line('go_back')?></a></p>
</div>
<?php endif; ?>

</form>
 <?php if (!empty($do_send)): ?>
<script>
$(function(){
	$('#form').submit();
});

</script>
<?php endif; ?>