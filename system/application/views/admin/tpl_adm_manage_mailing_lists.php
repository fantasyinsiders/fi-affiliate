<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($mailing_lists)): ?>
<div class="alert alert-warning">
	<h2 class="text-warning"><?=$this->lang->line('no_mailing_lists_found')?></h2>
    <p><a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a></p>
</div>
<?php else: ?>
<form id="form" name="prod_form" method="post">
<div class="row">
    <div class="col-md-6">
    	<?php if (!empty($filter_category) && !empty($filter_name)): ?>
    	<h4 class="hidden-xs"><span class="label label-info"><?=$this->lang->line('filter')?>: <?=$filter_category?> = <?=$filter_name?></span></h4>
    	<?php else: ?>
    	<?=_generate_sub_headline($function)?>
    <?php endif; ?> 
    </div>
    <div class="col-md-6 text-right">
		<?=_previous_next('previous', 'mailing_lists', $pagination['previous'], true);?>
        <a href="<?=admin_url()?>mailing_lists/add_mailing_list" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add_mailing_list')?></span></a>
        <?=_previous_next('next', 'mailing_lists', $pagination['next'], true);?>
    </div>
</div>
<hr />
<div class="row">    
   	<div class="col-md-12">
    	<div class="box-info">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width:10%"></th>                 
                    <th style="width:50%"><a href="<?=$sort_header?>/mailing_list_name/" class="sortable"><?=$this->lang->line('mailing_list_name')?></a></th>
                    <th style="width:15%" class="text-center"><a href="<?=$sort_header?>/follow_ups/" class="sortable"><?=$this->lang->line('follow_ups')?></a></th>
                    <th style="width:15%" class="hidden-xs text-center"><a href="<?=$sort_header?>/total/" class="sortable"><?=$this->lang->line('subscribers')?></a></th>
                    <th style="width:10%">&nbsp;</th>
                </tr>
    		</thead>
            <tbody>
            	<?php foreach ($mailing_lists as $v): ?>
                <tr>
                    <td class="text-center"><input name="mailing_lists[]" type="checkbox" id="mailing_lists[]" value="<?=$v['mailing_list_id']?>"/></td>
                    <td><h5><a href="<?=admin_url()?>mailing_lists/update_mailing_list/<?=$v['mailing_list_id']?>"><?=$v['mailing_list_name']?></a></h5></td>
                    <td class="text-center"><a href="<?=admin_url()?>follow_ups/view_follow_ups/0/0/0/mailing_list_id/<?=$v['mailing_list_id']?>" class="btn btn-info btn-sm"><i class="fa fa-envelope"></i> <?=$v['follow_ups']?></a></td>
                    <td class="text-center"><a href="<?=admin_url()?>mailing_lists/view_list_members/0/0/0/mailing_list_id/<?=$v['mailing_list_id']?>" class="btn btn-info btn-sm"><i class="fa fa-users"></i> <?=$v['total']?></a></td>
                    <td class="text-right">
                    	<?php if ($v['mailing_list_id'] != '1'): ?>
                    	 <a data-href="<?=admin_url()?>mailing_lists/delete_mailing_list/<?=$v['mailing_list_id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i></a>
                         <?php endif; ?>
                        <a href="<?=admin_url()?>mailing_lists/update_mailing_list/<?=$v['mailing_list_id']?>" class="btn btn-default hidden-xs"><i class="fa fa-edit"></i></a>
                    </td>
				</tr>
                <?php endforeach; ?>
    		</tbody>
            <tfoot>
                <tr>
                    <td class="text-center">
                        <input type="checkbox" class="check-all" name="check-all" />
                    </td>
                    <td>
                         <button class="btn btn-primary" type="submit"><?=$this->lang->line('send_mass_email')?></button></span>
                    </td>
                    <td colspan="3">
                    <div class="hidden-xs text-right">
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
<input name="redirect" type="hidden" id="redirect" value="<?=$this->uri->uri_string()?>" />
</form>
<?php endif; ?>