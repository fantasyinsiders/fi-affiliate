<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($logs)): ?>
<div class="alert alert-warning">
	<h3 class="text-warning"><?=$this->lang->line('no_tracking_logs_found')?></h3>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    </p>
</div>
<?php else: ?>
<form id="form" action="<?=admin_url()?>tracking_log/update_logs" method="post">
<div class="row">
    <div class="col-md-4">
    	<?=_generate_sub_headline('lifetime_tracking_ids')?>
    </div>
    <div class="col-md-8 text-right">
        <a href="<?=admin_url()?>members/update_member/<?=$this->validation->id?>" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('go_back')?></span></a>
    </div>
</div>
<hr />
<div class="row data-row">    
   	<div class="col-md-12">
	    <div class="box-info">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width:5%"></th>
                    <th style="width:60%"><a href="<?=$sort_header?>/tracking_id" class="sortable"><?=$this->lang->line('tracking_log_id')?></a></th>
                    <th style="width:20%"><a href="<?=$sort_header?>/ip_address" class="sortable"><?=$this->lang->line('ip_address')?></a></th>
                     <th width="15%"></th>
                </tr>    
        	</thead>
            <tbody>
                <?php foreach ($logs as $v): ?>
                <tr>
                	<td class="text-center"><input name="id[]" type="checkbox" id="id[]" value="<?=$v['id']?>"/></td>
                    <td><?=$v['tracking_id']?></td>
                    <td><?=$v['ip_address']?></td>
                    <td class="text-right">
                    	 <a data-href="<?=admin_url()?>tracking_log/delete_log/<?=$v['id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>  
            <tfoot>
                <tr>
                	<td colspan="2"><button type="submit" class="btn btn-danger"><i class="fa fa-trash-o"></i> <?=$this->lang->line('delete')?></button></td>
                    <td colspan="2" class="hidden-xs text-right">
						<?=$pagination['select_rows']?>
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