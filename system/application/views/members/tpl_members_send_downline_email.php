<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?=$editor_path?>
<form id="form" class="form-horizontal" method="post" role="form">
<div class="col-lg-12">
	<div class="panel panel-default animated fadeInDown">
        <div class="panel-heading text-capitalize">
        	<div class="additional-btn">
            <a href="javascript:window.location.reload()"><i class="fa fa-refresh"></i> </a>
            </div>
			<?=$page_title?>
        </div>
        <div class="panel-body">
			<?=$editor?>    
            <hr />
            <div><button type="submit" class="btn btn-default"><i class="fa fa-envelope"></i> <?=$this->lang->line('send_email')?></button></div>
        </div>
	</div>
</div>
</form>