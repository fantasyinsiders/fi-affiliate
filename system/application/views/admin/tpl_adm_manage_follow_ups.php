<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($follow_ups)): ?>
<div class="alert alert-warning">
	<h2 class="text-warning"><?=$this->lang->line('no_follow_ups_found')?></h2>
    <p><a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    <a href="<?=admin_url()?>follow_ups/add_follow_up/<?=$mailing_list_id?>" class="btn btn-warning"><?=$this->lang->line('add_follow_up')?></a>
    </p>
</div>
<?php else: ?>
<form id="form" name="prod_form" action="<?=admin_url()?>follow_ups/update_follow_ups" method="post">
<div class="row">
    <div class="col-md-6">
    	<?php if (!empty($filter_category) && !empty($filter_name)): ?>
    	<h4 class="hidden-xs"><span class="label label-info"><?=$this->lang->line('filter')?>: <?=$filter_category?> = <?=$filter_name?></span></h4>
    	<?php else: ?>
    	<?=_generate_sub_headline('follow_ups', $mailing_list_name)?>
    <?php endif; ?> 
    </div>
    <div class="col-md-6 text-right">
		<?=_previous_next('previous', 'follow_ups', $pagination['previous'], true);?>
        <a href="<?=admin_url()?>mailing_lists/view_mailing_lists/" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_mailing_lists')?></span></a>
        <a href="<?=admin_url()?>follow_ups/add_follow_up/<?=$mailing_list_id?>" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add_follow_up')?></span></a>
        <?=_previous_next('next', 'follow_ups', $pagination['next'], true);?>
    </div>
</div>
<hr />
<div class="row">    
   	<div class="col-md-12">
    	<div class="box-info">
        <table class="table table-striped table-hover">
            <thead>
                <tr>   
                    <th style="width:5%"><a href="<?=$sort_header?>/days/" class="sortable"><?=$this->lang->line('sequence')?></a></th>                 
                    <th style="width:15%"><a href="<?=$sort_header?>/follow_up_name/" class="sortable"><?=$this->lang->line('follow_up_name')?></a></th>
                    <th style="width:50%" class="hidden-xs"><a href="<?=$sort_header?>/subject/" class="sortable"><?=$this->lang->line('subject')?></a></th>
                    <th style="width:10%"><a href="<?=$sort_header?>/follow_up_name/" class="sortable"><?=$this->lang->line('days')?></a></th>
                    <th style="width:20%">&nbsp;</th>
                </tr>
    		</thead>
            <tbody>
            	<?php foreach ($follow_ups as $v): ?>
                <tr>
                    <td class="text-center">
                    <?=form_dropdown('sequence-' . $v['follow_up_id'],$sort, $v['sequence'], 'class="form-control"')?>    
                    </td>
                    <td><a href="<?=admin_url()?>follow_ups/update_follow_up/<?=$v['follow_up_id']?>"><?=$v['follow_up_name']?></a></td>
                    <td class="hidden-xs"><?=$v['email_subject']?></td>
                    <td class="text-center">
                    <?php if ($v['sequence'] == 1): ?>
                    <input name="days-<?=$v['follow_up_id']?>" type="text" id="days-<?=$v['days_apart']?>" value="0" readonly="readonly" class="form-control"/>
                    <?php else: ?> 
                    <input name="days-<?=$v['follow_up_id']?>" type="number" id="sequence-<?=$v['follow_up_id']?>" value="<?=$v['days_apart']?>" class="form-control"/>   
                    <?php endif; ?>
                    </td>
                    <td class="text-right">
                    	<?php if ($v['follow_up_id'] != '1'): ?>
                    	 <a data-href="<?=admin_url()?>follow_ups/delete_follow_up/<?=$v['follow_up_id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i></a>
                         <?php endif; ?>
                        <a href="<?=admin_url()?>follow_ups/update_follow_up/<?=$v['follow_up_id']?>" class="btn btn-default hidden-xs"><i class="fa fa-edit"></i></a>
                    </td>
				</tr>
                <?php endforeach; ?>
    		</tbody>
            <tfoot>
                <tr>
                    <td><button class="btn btn-primary" type="submit"><?=$this->lang->line('update_sequence')?></button></td>
                    <td colspan="4">
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
<input name="mailing_list_id" type="hidden" value="<?=$mailing_list_id?>" />  
</form>
<?php endif; ?>