<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($product_commissions)): ?>
<div class="alert alert-warning">
	<h3 class="text-warning"><?=$this->lang->line('no_commissions_found')?></h3>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    	<a href="<?=admin_url()?>product_commissions/add_product_commission" class="btn btn-warning"><?=$this->lang->line('add_product_commission')?></a>
    </p>
</div>
<?php else: ?>
<div class="row">
    <div class="col-md-4">
    	<?=_generate_sub_headline('product_commissions')?>
    </div>
    <div class="col-md-8 text-right">
		<?=_previous_next('previous', 'product_commissions', $pagination['previous'], true);?>
        <a href="<?=admin_url()?>product_commissions/add_product_commission" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add_product_commission')?></span></a>
        <?=_previous_next('next', 'product_commissions', $pagination['next'], true);?>
    </div>
</div>
<hr />
<div class="row data-row">    
   	<div class="col-md-12">
	    <div class="box-info">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width:6%" class="hidden-xs text-center"><a href="<?=$sort_header?>/id" class="sortable"><?=$this->lang->line('id')?></a></th>
                    <th style="width:12%" class="text-center"><a href="<?=$sort_header?>/status" class="sortable"><?=$this->lang->line('status')?></a></th>
                    <th style="width:27%"><a href="<?=$sort_header?>/product_id" class="sortable"><?=$this->lang->line('product_identifier')?></a></th>
                     <th style="width:15%" class="text-center"><a href="<?=$sort_header?>/amount" class="sortable"><?=$this->lang->line('amount')?></a></th>
                     <th style="width:15%" class="text-center"><a href="<?=$sort_header?>/type" class="sortable"><?=$this->lang->line('type')?></a></th>
                     <th width="15%"></th>
                </tr>    
        	</thead>
            <tbody>
                <?php foreach ($product_commissions as $v): ?>
                <tr>
                	<td class="hidden-xs text-center"><?=$v['id']?></td>
                    <td class="text-center">
                    	<a href="<?=admin_url()?>product_commissions/update_status/<?=$v['id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>">
						<?php if ($v['status'] == '0'): ?>
                        <span class="label label-warning"> <?=$this->lang->line('inactive')?></span>
                        <?php elseif ($v['status'] == '1') : ?>
                        <span class="label label-success"><?=$this->lang->line('active')?></span>
                        <?php endif; ?>
                        </a>
                    </td>
                    <td>
                    	<h5><a href="<?=admin_url()?>product_commissions/update_product_commission/<?=$v['id']?>"><?=$v['product_id']?></a></h5>
                    </td>
                    <td class="text-center"><h5><?=$v['amount']?></h5></td>
                    <td class="text-center">
						<?php if ($v['type'] == 'flat'): ?>
                       	<span class="label label-warning">
                        <?php else: ?>
                        <span class="label label-info">
                    	<?php endif; ?>
                        <?=$v['type']?>
                        </span>
                    </td>
                    <td class="text-right">
                    	 <a data-href="<?=admin_url()?>product_commissions/delete_product_commission/<?=$v['id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i></a>
                        <a href="<?=admin_url()?>product_commissions/update_product_commission/<?=$v['id']?>" class="btn btn-default hidden-xs"><i class="fa fa-edit"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>  
            <tfoot>
                <tr>
                    <td colspan="7" class="hidden-xs text-right">
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
</form>
<?php endif; ?>