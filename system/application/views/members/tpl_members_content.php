<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="col-lg-12">
	<?php if (empty($rows)): ?>
    <div class="alert alert-warning text-capitalize animated shake">
    	<h3><i class="fa fa-exclamation-triangle"></i> <?=$this->lang->line('no_content_found')?></h3>
    	<p><a href="javascript:history.go(-1)" class="btn btn-warning"><i class="fa fa-chevron-left"></i> <?=$this->lang->line('go_back')?></a></p>
    </div>
	<?php else: ?>
    <?php foreach ($rows as $v): ?>
    <div class="panel panel-default animated fadeInUp">
    	<div class="panel-body">
        	<h3><a href="<?=site_url('members')?><?=CONTENT_ROUTE?>/article/<?=$v['article_id']?>"><?=$v['content_title']?></a></h3>
            <hr />
            <?php if ($this->session->userdata('adminid')): ?>
			<?php if ($v['status'] == '0' || $v['date_published'] > _generate_timestamp()): ?>
            <span class="label label-warning">
				<?=$this->lang->line('admin_viewed_online')?> <?=$this->lang->line('publish_date')?>: <?=_show_date($v['date_published'])?>
            </span>
            <?php endif; ?>
            <?php endif; ?>
            <div><?=$v['content_body']?></div>
            <p class="text-right"><a href="<?=site_url('members')?>/<?=CONTENT_ROUTE?>/article/<?=$v['article_id']?>" class="btn btn-sm btn-default"><i class="fa fa-search"></i> <?=$this->lang->line('read_more')?></a></p>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
    <div class="text-center"><?=$pagination_rows?></div>
</div>