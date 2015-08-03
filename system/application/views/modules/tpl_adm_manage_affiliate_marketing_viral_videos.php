<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($tools)): ?>
<div class="alert alert-warning">
	<h2 class="text-warning"><?=$this->lang->line('no_tools_found')?></h2>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning btn-lg"><?=$this->lang->line('go_back')?></a> 
        <a href="<?=modules_url()?>module_affiliate_marketing_viral_videos/add" class="btn btn-warning btn-lg"><?=$this->lang->line('add')?></a>
    </p>
</div>
<?php else: ?>
<form name="prod_form" action="<?=modules_url()?>module_affiliate_marketing_viral_videos/sort_order" method="post">
<div class="row">
    <div class="col-md-4">
    <?=_generate_sub_headline('manage_viral_videos')?>
    </div>
    <div class="col-md-8 text-right">
    	<?=_previous_next('previous', 'affiliate_viral_videos', $pagination['previous'], true);?>
        <a href="<?=admin_url()?>affiliate_marketing/view_affiliate_tools" class="btn btn-primary"><i class="fa fa-chevron-left"></i> <span class="hidden-xs"><?=$this->lang->line('affiliate_marketing_tools')?></span></a>
        <a href="<?=base_url('js')?>modules/module_affiliate_marketing_viral_videos/add" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add_viral_video')?></span></a>
   		<?=_previous_next('next', 'affiliate_viral_videos', $pagination['next'], true);?>	     
    </div>
</div>
<hr />
<div class="row">    
    <div class="col-md-12">
    	<div class="box-info">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width:8%" class="text-center"><a href="<?=$sort_header?>/sort_order" class="sortable"><?=$this->lang->line('sort')?></a></th>
                    <th style="width:10%" class="text-center"><a href="<?=$sort_header?>/status" class="sortable"><?=$this->lang->line('status')?></a></th>
                    <th style="width:35%"><a href="<?=$sort_header?>/viral_video_title" class="sortable"><?=$this->lang->line('viral_video_name')?></a></th>
                    <th style="width:10%" class="hidden-xs text-center"><?=$this->lang->line('clicks')?></th>
                    <th style="width:10%" class="hidden-xs text-center"><?=$this->lang->line('comm')?></th>
                    <th style="width:10%" class="hidden-xs text-center"><?=$this->lang->line('sales')?></th>
                    <th style="width:17%">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
            	<?php foreach ($tools as $v): ?>
                <tr>
                	<td class="text-center"><?=form_dropdown('tool-' . $v['id'],$sort, $v['sort_order'], 'class="form-control"')?>   </td>
                    <td class="text-center">
                    	<a href="<?=modules_url()?>module_affiliate_marketing_viral_videos/change_status/<?=$v['id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>">
                        <?php if ($v['status'] == '1'): ?>
                        <span class="label label-success"> <?=$this->lang->line('active')?></span>
                        <?php else : ?>
                        <span class="label label-warning"><?=$this->lang->line('inactive')?></span>
                        <?php endif; ?>
                        </a>
                    </td>
                    <td><a href="<?=modules_url()?>module_affiliate_marketing_viral_videos/edit/<?=$v['id']?>"><?=$v['name']?></a></td>
                    <td class="hidden-xs text-center"><?=$v['clicks']?></td>
                    <td class="hidden-xs text-center"><?=format_amounts($v['commissions'], $num_options)?></td>
                    <td class="hidden-xs text-center"><?=format_amounts($v['sales'], $num_options)?></td>
                    <td>
                    	 <div class="text-right">
                         	 <a data-href="<?=modules_url()?>/module_affiliate_marketing_viral_videos/delete/<?=$v['id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o" title="<?=$this->lang->line('delete')?>"></i></a>
                            <a href="<?=modules_url()?>module_affiliate_marketing_viral_videos/edit/<?=$v['id']?>" class="btn btn-default" title="<?=$this->lang->line('edit')?>"><i class="fa fa-edit"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5"><button class="btn btn-primary" type="submit"><?=$this->lang->line('update_sort_order')?></button></td>
                    <td colspan="2" class="hidden-xs text-right"><?=$pagination['select_rows']?></td>
                </tr>
            </tfoot>
		</table>
        <?php if (!empty($pagination['rows'])): ?>
	    <div class="text-center"><small class="text-muted"><?=$pagination['num_pages']?> <?=$this->lang->line('total_pages')?></small></div>
		<div class="text-center"><?=$pagination['rows']?></div>    
    	<?php endif; ?>
        </div>
	</div>
</div>
<input name="redirect" type="hidden" id="redirect" value="<?=$this->uri->uri_string()?>" />
</form>   
<?php endif; ?>