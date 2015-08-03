<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="col-lg-12">
    <div class="panel panel-default">
    	<div class="panel-body">
        	<h3><?=$content_title?></h3>
            <hr />
            <?php if ($this->session->userdata('adminid') && $date_published > _generate_timestamp()): ?>
            <span class="label label-warning">
				<?=$this->lang->line('admin_viewed_online')?> <?=$this->lang->line('publish_date')?>: <?=_show_date($date_published)?>
            </span>
            <?php endif; ?>
            <div><?=$content_body?></div>
        </div>
    </div>
</div>