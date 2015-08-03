<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($affiliate_payments)): ?>
<div class="alert alert-warning">
	<h2 class="text-warning"><?=$this->lang->line('no_affiliate_payments_found')?></h2>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    	<a href="<?=admin_url()?>affiliate_payments/view_payment_options" class="btn btn-warning"><?=$this->lang->line('make_affiliate_payments')?></a>
    </p> 
</div>
<?php else: ?>
<div class="row">
    <div class="col-md-4">
    <?=_generate_sub_headline('payments_history')?>
    </div>
    <div class="col-md-8 text-right">
    	<?=_previous_next('previous', 'affiliate_payments', $pagination['previous'], true);?>
        <a href="<?=admin_url()?>affiliate_payments/view_payment_options" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('make_affiliate_payments')?></span></a>
    	<?=_previous_next('next', 'affiliate_payments', $pagination['next'], true);?>
    </div>
</div>
<hr />
<div class="row data-row">    
   	<div class="col-md-12">
    	<div class="box-info">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width:5%" class="text-center hidden-xs"><a href="<?=$sort_header?>/id" class="sortable"><?=$this->lang->line('id')?></a></th>
                    <th style="width:15%" class="text-center"><a href="<?=$sort_header?>/payment_date" class="sortable"><?=$this->lang->line('date')?></a></th>
                    <th style="width:20%"><a href="<?=$sort_header?>/name" class="sortable"><?=$this->lang->line('name')?></a></th>
                    <th style="width:35%" class="hidden-xs"><a href="<?=$sort_header?>/payment_notes" class="sortable"><?=$this->lang->line('payment_notes')?></a></th>
                    <th style="width:10%" class="text-center"><a href="<?=$sort_header?>/amount" class="sortable"><?=$this->lang->line('amount')?></a></th>
                    <th style="width:15%">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
				<?php foreach($affiliate_payments as $v):?>
                <tr>
                	<td class="text-center hidden-xs"><?=$v['id']?></td>
                    <td class="text-center"><?=_show_date($v['payment_date'])?></td>
                    <td><a href="<?=admin_url()?>affiliate_payments/update_affiliate_payment/<?=$v['id']?>" title="<?=$this->lang->line('edit')?>"><?=$v['name']?></a></td>
                    <td class="hidden-xs"><?=$v['payment_details']?></td>
                    <td class="text-center"><?=format_amounts($v['payment_amount'], $num_options)?></td>
                    <td class="text-right">
                    	<a href="<?=admin_url()?>commissions/view_commissions/0/0/0/payment_id/<?=$v['id']?>" class="btn btn-default hidden-xs" title="<?=$this->lang->line('view_associated_commissions')?>"><i class="fa fa-search"></i></a>
                        <a data-href="<?=admin_url()?>affiliate_payments/delete_affiliate_payment/<?=$v['id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i></a>
                    	<a href="<?=admin_url()?>affiliate_payments/update_affiliate_payment/<?=$v['id']?>" class="btn btn-default hidden-xs" title="<?=$this->lang->line('edit')?>"><i class="fa fa-pencil"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
			</tbody>
            <tfoot>
                <tr>
	                 <td colspan="6" class="text-right hidden-xs">
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
<?php endif; ?>