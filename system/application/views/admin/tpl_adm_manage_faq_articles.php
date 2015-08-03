<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($faq_articles)): ?>
<div class="alert alert-warning">
	<h2 class="text-warning"><?=$this->lang->line('no_articles_found')?></h2>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    	<a href="<?=admin_url()?>faq_articles/add_faq_article" class="btn  btn-warning"><?=$this->lang->line('add_faq_article')?></a>
    </p>
</div>
<?php else: ?>
<form id="form" name="prod_form" action="<?=admin_url()?>faq_articles/update_articles" method="post">
<div class="row">
    <div class="col-md-4">
    <?=_generate_sub_headline($function)?>
    </div>
    <div class="col-md-8 text-right">
    	<?=_previous_next('previous', 'faq_articles', $pagination['previous'], true);?>
        <div class="btn-group text-left hidden-xs">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-sort"></i> <span class="hidden-xs"><?=$this->lang->line('sort_data_by')?></span>
            </button>
            <ul class="dropdown-menu capitalize" role="menu">
                <li><a href="<?=admin_url()?>faq_articles/view_faq_articles/0/<?=$next_sort_order?>/content_title/<?=$where_column?>/<?=$where_value?>/<?=$where_column2?>/<?=$where_value2?>"><?=$this->lang->line('title')?> <?=$this->lang->line($next_sort_order)?></a></li>
                <li><a href="<?=admin_url()?>faq_articles/view_faq_articles/0/<?=$next_sort_order?>/article_id/<?=$where_column?>/<?=$where_value?>/<?=$where_column2?>/<?=$where_value2?>"><?=$this->lang->line('id')?> <?=$this->lang->line($next_sort_order)?></a></li>
                <li><a href="<?=admin_url()?>faq_articles/view_faq_articles/0/<?=$next_sort_order?>/status/<?=$where_column?>/<?=$where_value?>/<?=$where_column2?>/<?=$where_value2?>"><?=$this->lang->line('status')?> <?=$this->lang->line($next_sort_order)?></a></li>
            </ul>
        </div>
        <a href="<?=admin_url()?>faq_articles/add_faq_article" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add_faq_article')?></span></a>
    	<?=_previous_next('next', 'faq_articles', $pagination['next'], true);?>
    </div>
</div>
<hr />
<div class="row" id="data-content">
	<?php foreach ($faq_articles as $v): ?>
    <div class="col-md-6 data-box">
        <div class="box-info wrapper">
			<div class="ribbon-wrapper-green">
			<?php if ($v['status'] == 1): ?>
       		<div class="ribbon-green"><?=$this->lang->line('active')?></div>
			<?php else: ?>
            <div class="ribbon-yellow"><?=$this->lang->line('inactive')?></div>
            <?php endif; ?>
            </div>
            <h4><a href="<?=admin_url()?>faq_articles/update_faq_article/<?=$v['article_id']?>"><?=$v['content_title']?></a></h4> 
            <p><?=limit_chars(strip_tags($v['content_body']), 400)?></p>
            <hr />
            <div class="pull-right">
                <a data-href="<?=admin_url()?>faq_articles/delete_faq_article/<?=$v['article_id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></a>
				<a href="<?=admin_url()?>faq_articles/update_status/<?=$v['article_id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>" class="btn btn-sm btn-default">
                <?php if ($v['status'] == '0'): ?>
                <i class="fa fa-exclamation-triangle"></i>
                <?php else: ?>
                <i class="fa fa-check"></i>
                <?php endif; ?>
                </a>
                <a href="<?=admin_url()?>faq_articles/update_faq_article/<?=$v['article_id']?>" class="btn btn-default btn-sm" title="<?=$this->lang->line('edit')?>"><i class="fa fa-pencil"></i></a>
            </div>   
        </div>
	</div>
	<?php endforeach; ?>
</div>
<div class="row">
	<div class="col-md-6 col-md-offset-3">
    	<p class="text-center"><small class="text-muted"><?=$pagination['num_pages']?> <?=$this->lang->line('total_pages')?></small></p>
    </div>
    <div class="col-md-3 text-right">
    <?=$pagination['select_rows']?>
    </div>
</div>    
<div class="text-center"><?=$pagination['rows']?></div>     
<br />
<script src="<?=path_url();?>themes/admin/<?=$sts_admin_layout_theme?>/third/masonry/masonry.js"></script>    
<script>
var msnry = new Masonry( '#data-content', {
  itemSelector: '.data-box',
  isAnimated: true
});

</script> 

<?php endif; ?>    
