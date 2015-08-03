<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($groups)): ?>
<div class="alert alert-warning">
	<h2 class="text-warning"><?=$this->lang->line('no_groups_found')?></h2>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    	<a href="<?=admin_url()?>affiliate_groups/add_group" class="btn btn-warning"><?=$this->lang->line('add_affiliate_group')?></a>
    </p>
</div>
<?php else: ?>
<form id="form" name="prod_form" action="<?=admin_url()?>affiliate_groups/update_groups" method="post">
<div class="row">
    <div class="col-md-4">
    <?=_generate_sub_headline('affiliate_groups')?>
    </div>
    <div class="col-md-8 text-right">
    	<?=_previous_next('previous', 'affiliate_groups', $pagination['previous'], true);?>
        <a href="<?=admin_url()?>affiliate_groups/add_group" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add_affiliate_group')?></span></a>
    	<?=_previous_next('next', 'affiliate_groups', $pagination['next'], true);?>
    </div>
</div>
<hr />
<div class="row data-row">    
   	<div class="col-md-12">
    	<div class="box-info">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                	<th style="width:5%" class="text-center"><a href="<?=$sort_header?>/tier/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" class="sortable"><?=$this->lang->line('tier')?></a></th>
                    <th style="width:5%" class="text-center hidden-xs"><a href="<?=$sort_header?>/group_id/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" class="sortable"><?=$this->lang->line('id')?></a></th>
                    <th style="width:70%"><a href="<?=$sort_header?>/aff_group_name/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" class="sortable"><?=$this->lang->line('group_name')?></a></th>
                    <th style="width:10%" class="text-center"><a href="<?=$sort_header?>/total/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" class="sortable"><?=$this->lang->line('members')?></a></th>
                    <th style="width:10%" class="hidden-xs"></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($groups as $v): ?>
            	<tr>
                	<td>
                    <?=form_dropdown('group-' . $v['group_id'],$tiers, $v['tier'], 'class="form-control"')?></td>
                	<td class="text-center hidden-xs"><?=$v['group_id']?></td>
                    <td><h5><a href="<?=admin_url()?>affiliate_groups/update_group/<?=$v['group_id']?>"><?=$v['aff_group_name']?></a></h5></td>
                    <td class="text-center">
                    <?php if (!empty($v['total'])): ?>
                    <a href="<?=admin_url()?>members/view_members/0/0/0/affiliate_group/<?=$v['group_id']?>" class="btn btn-info"><i class="fa fa-users"></i> <?=$v['total']?></a>
                    <?php else: ?>
                    <?=$v['total']?>
                    <?php endif; ?>
                    </td>
                    <td class="text-right">
                    <?php if ($v['group_id'] != '1'): ?>
                     <a data-href="<?=admin_url()?>affiliate_groups/delete_group/<?=$v['group_id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i></a>
                    <?php endif; ?>
                    <a href="<?=admin_url()?>affiliate_groups/update_group/<?=$v['group_id']?>" class="btn btn-default hidden-xs"><i class="fa fa-edit"></i></a>
                    </td>
                </tr>        
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                	<td><button type="submit" class="btn btn-info"><?=$this->lang->line('update_tiers')?></button></td>
                    <td colspan="4">
                    <div class="text-right">
						<?=$pagination['select_rows']?>
                    </div>
                    </td>
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
</form>
<?php endif; ?>