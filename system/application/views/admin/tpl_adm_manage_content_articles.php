<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($content_articles)): ?>
<div class="alert alert-warning">
	<h2 class="text-warning"><?=$this->lang->line('no_articles_found')?></h2>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    	<a href="<?=admin_url()?>content_articles/add_content_article" class="btn  btn-warning"><?=$this->lang->line('add_content_article')?></a>
    </p>
</div>
<?php else: ?>
<form id="form" role="form" name="prod_form" action="<?=admin_url()?>content_articles/update_articles" method="post">
<div class="row">
    <div class="col-md-4">
    <?=_generate_sub_headline($function)?>
    </div>
    <div class="col-md-8 text-right">
    	<?=_previous_next('previous', 'content_articles', $pagination['previous'], true);?>
        <div class="btn-group text-left hidden-xs">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-sort"></i> <span class="hidden-xs"><?=$this->lang->line('sort_data_by')?></span>
            </button>
            <ul class="dropdown-menu capitalize" role="menu">
                <li><a href="<?=admin_url()?>content_articles/view_content_articles/0/<?=$next_sort_order?>/content_title/<?=$where_column?>/<?=$where_value?>/<?=$where_column2?>/<?=$where_value2?>"><?=$this->lang->line('title')?> <?=$this->lang->line($next_sort_order)?></a></li>
                <li><a href="<?=admin_url()?>content_articles/view_content_articles/0/<?=$next_sort_order?>/article_id/<?=$where_column?>/<?=$where_value?>/<?=$where_column2?>/<?=$where_value2?>"><?=$this->lang->line('id')?> <?=$this->lang->line($next_sort_order)?></a></li>
                <li><a href="<?=admin_url()?>content_articles/view_content_articles/0/<?=$next_sort_order?>/status/<?=$where_column?>/<?=$where_value?>/<?=$where_column2?>/<?=$where_value2?>"><?=$this->lang->line('status')?> <?=$this->lang->line($next_sort_order)?></a></li>
                <li><a href="<?=admin_url()?>content_articles/view_content_articles/0/<?=$next_sort_order?>/date_published/<?=$where_column?>/<?=$where_value?>/<?=$where_column2?>/<?=$where_value2?>"><?=$this->lang->line('date')?> <?=$this->lang->line($next_sort_order)?></a></li>
            </ul>
        </div>
        <a href="<?=admin_url()?>content_articles/add_content_article" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add_content_article')?></span></a>
    	<?=_previous_next('next', 'content_articles', $pagination['next'], true);?>
    </div>
</div>
<hr />
<div class="row" id="data-content">
	<?php foreach ($content_articles as $v): ?>
    <div class="col-lg-6 data-box">
     	<div class="box-info">
        	<div class="additional-box">
                <?=form_dropdown('article_id-' . $v['article_id'],$sort, $v['sort_order'], 'class="form-control"')?>  
            </div>
        	<h4><input name="article[]" type="checkbox" id="article[]" value="<?=$v['article_id']?>"/> <a href="<?=admin_url()?>content_articles/update_content_article/<?=$v['article_id']?>"><?=$v['content_title']?></a></h4> 
            <p><?=limit_chars(strip_tags($v['content_body']), 400)?></p> 	
			<hr />
            <div class="row">
                <div class="col-md-6">
                <small class="capitalize"><i class="fa fa-calendar-o"></i> <?=$this->lang->line('publish_date')?>: <?=_show_date($v['date_published'])?></small>
                </div>
                <div class="col-md-6">
                    <div class="text-right">
                        <a data-href="<?=admin_url()?>content_articles/delete_content_article/<?=$v['article_id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></a>
                        <?php if ($v['status'] == '0'): ?>
                        <a href="<?=admin_url()?>content_articles/update_status/<?=$v['article_id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>" class="btn btn-sm btn-warning">
                        <i class="fa fa-exclamation-triangle"></i>
                        <?php else: ?>
                        <a href="<?=admin_url()?>content_articles/update_status/<?=$v['article_id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>" class="btn btn-sm btn-success">
                        <i class="fa fa-check"></i>
                        <?php endif; ?>
                        </a>
                        <a href="<?=admin_url()?>content_articles/update_content_article/<?=$v['article_id']?>" class="btn btn-default btn-sm" title="<?=$this->lang->line('edit')?>"><i class="fa fa-pencil"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php endforeach; ?>
</div>
<hr />
<div class="row">
	<div class="col-md-3">
    	 <div class="input-group">
         	<span class="input-group-addon"><input type="checkbox" class="check-all" name="check-all" /></span>
            <select name="change-status" class="form-control capitalize">
                <option value=""><?=$this->lang->line('select_option')?></option>
                <option value="sort"><?=$this->lang->line('update_sort_order')?></option>
                <?php if (!empty($affiliate_groups)): ?>
                <optgroup label="<?=$this->lang->line('restrict_to_affiliate_group')?>">
                <?php foreach ($affiliate_groups as $k => $v): ?>
                <option value="add_affiliate_group-<?=$k?>"><?=$v?></option>
                <?php endforeach; ?>
                <option value="remove_affiliate_groups"><?=$this->lang->line('remove_affiliate_group_restrictions')?></option>
                </optgroup>
                <?php endif; ?>
            </select> <span class="input-group-btn">
            <button class="btn btn-primary" type="submit"><?=$this->lang->line('go')?></button></span>
        </div>
    </div>
    <div class="col-md-6 visible-lg">
    	<p class="text-center"><small class="text-muted"><?=$pagination['num_pages']?> <?=$this->lang->line('total_pages')?></small></p>
    </div>
    <div class="col-lg-3 col-md-6 text-right">
    <?=$pagination['select_rows']?>
    </div>
</div>    
<div class="text-center"><?=$pagination['rows']?></div>     
<br />
<input name="redirect" type="hidden" id="redirect" value="<?=$this->uri->uri_string()?>" />
</form>
<script src="<?=path_url();?>themes/admin/<?=$sts_admin_layout_theme?>/third/masonry/masonry.js"></script>    
<script>
var msnry = new Masonry( '#data-content', {
  itemSelector: '.data-box',
  isAnimated: true
});

</script> 

<?php endif; ?>    
