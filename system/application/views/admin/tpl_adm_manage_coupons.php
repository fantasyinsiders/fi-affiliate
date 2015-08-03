<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($coupons)): ?>
<div class="alert alert-warning">
	<h3 class="text-warning"><?=$this->lang->line('no_coupons_found')?></h3>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    	<a href="<?=admin_url()?>coupons/add_coupon" class="btn btn-warning"><?=$this->lang->line('add_coupon')?></a>
    </p>
</div>
<?php else: ?>
<div class="row">
    <div class="col-md-4">
    	<?=_generate_sub_headline('coupons')?>
    </div>
    <div class="col-md-8 text-right">
		<?=_previous_next('previous', 'coupons', $pagination['previous'], true);?>
        <a href="<?=admin_url()?>coupons/add_coupon" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add_coupon')?></span></a>
        <?=_previous_next('next', 'coupons', $pagination['next'], true);?>
    </div>
</div>
<hr />
<div class="row data-row">    
   	<div class="col-md-12">
	    <div class="box-info">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width:20%"><a href="<?=$sort_header?>/product_id" class="sortable"><?=$this->lang->line('coupon_code')?></a></th>
                    <th style="width:10%" class="text-center"><a href="<?=$sort_header?>/status" class="sortable"><?=$this->lang->line('status')?></a></th>
                    <th style="width:20%" class="hidden-xs text-center"><a href="<?=$sort_header?>/id" class="sortable"><?=$this->lang->line('affiliate')?></a></th>
                    <th style="width:15%" class="text-center"><a href="<?=$sort_header?>/amount" class="sortable"><?=$this->lang->line('amount')?></a></th>
                    <th style="width:15%" class="text-center"><a href="<?=$sort_header?>/type" class="sortable"><?=$this->lang->line('type')?></a></th>
                    <th width="20%"></th>
                </tr>    
        	</thead>
            <tbody>
                <?php foreach ($coupons as $v): ?>
                <tr>
                	<td>
                    	<h5><a href="<?=admin_url()?>coupons/update_coupon/<?=$v['coupon_id']?>"><?=$v['coupon_code']?></a></h5>
                    </td>
                    <td class="text-center">
                    	<a href="<?=admin_url()?>coupons/update_status/<?=$v['coupon_id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>">
						<?php if ($v['status'] == '0'): ?>
                        <span class="label label-warning"> <?=$this->lang->line('inactive')?></span>
                        <?php elseif ($v['status'] == '1') : ?>
                        <span class="label label-success"><?=$this->lang->line('active')?></span>
                        <?php endif; ?>
                        </a>
                    </td>
                    <td class="hidden-xs text-center"><a href="<?=admin_url()?>members/update_member/<?=$v['member_id']?>"><?=$v['username']?></a></td>
                    <?php if ($v['use_program_comms'] == 1): ?>
                    <td  class="text-center">
                    <span class="label label-primary"><?=$this->lang->line('use_program_defaults')?></span>
                    </td>
                    <td>&nbsp;</td>
                    <?php else: ?>
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
                    <?php endif; ?>
                    <td class="text-right">
                    	 <a data-href="<?=admin_url()?>coupons/delete_coupon/<?=$v['coupon_id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i></a>
                        <a href="<?=admin_url()?>coupons/update_coupon/<?=$v['coupon_id']?>" class="btn btn-default hidden-xs"><i class="fa fa-edit"></i></a>
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