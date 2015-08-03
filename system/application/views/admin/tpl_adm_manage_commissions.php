<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($commissions)): ?>
<div class="alert alert-warning">
	<h2 class="text-warning"><?=$this->lang->line('no_commissions_found')?></h2>
    <p>
    <a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    <a href="<?=admin_url()?>commissions/add_commission" class="btn btn-warning"><?=$this->lang->line('add_commission')?></a>
    </p>
</div>
<?php else: ?>
<form id="form" name="prod_form" action="<?=admin_url()?>commissions/update_commissions" method="post">
<div class="row">
    <div class="col-md-6">
    	<?php if (!empty($filter_category) && !empty($filter_name)): ?>
    	<h4 class="hidden-xs"><span class="label label-info"><?=$this->lang->line('filter')?>: <?=$filter_category?> = <?=$filter_name?></span></h4>
    	<?php else: ?>
    	<?=_generate_sub_headline('commissions')?>
    <?php endif; ?> 
    </div>
    <div class="col-md-6 text-right">
		<?=_previous_next('previous', 'commissions', $pagination['previous'], true);?>
        <a href="<?=admin_url()?>commissions/add_commission" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add_commission')?></span></a>
        <div class="btn-group text-left">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-search"></i> <?=$this->lang->line('filter_commissions')?> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
            <li><a href="<?=admin_url()?>commissions/view_commissions/0/<?=$sort_order?>/<?=$sort_column?>/0/0/<?=$where_column2?>/<?=$where_value2?>"><?=$this->lang->line('show_all_commissions')?></a></li>
            <li><a href="<?=admin_url()?>commissions/view_commissions/0/<?=$sort_order?>/<?=$sort_column?>/comm_status/pending/<?=$where_column2?>/<?=$where_value2?>"><?=$this->lang->line('show_pending_commissions')?></a></li>
            <li><a href="<?=admin_url()?>commissions/view_commissions/0/<?=$sort_order?>/<?=$sort_column?>/comm_status/unpaid/<?=$where_column2?>/<?=$where_value2?>"><?=$this->lang->line('show_unpaid_commissions')?></a></li>
            <li><a href="<?=admin_url()?>commissions/view_commissions/0/<?=$sort_order?>/<?=$sort_column?>/comm_status/paid/<?=$where_column2?>/<?=$where_value2?>"><?=$this->lang->line('show_paid_commissions')?></a></li>
             <li><a href="<?=admin_url()?>commissions/view_commissions/0/<?=$sort_order?>/<?=$sort_column?>/recurring_comm/1/<?=$where_column2?>/<?=$where_value2?>"><?=$this->lang->line('show_recurring_commissions')?></a></li>
            </ul>
        </div> 
        <?=_previous_next('next', 'commissions', $pagination['next'], true);?>
    </div>
</div>
<hr />
<div class="row">    
   	<div class="col-md-12">
    	<div class="box-info">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width:5%"></th>
                    <th style="width:5%" class="hidden-xs text-center"><a href="<?=$sort_header?>/comm_id/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" class="sortable"><?=$this->lang->line('id')?></a></th>
                    
                    <th style="width:7%" class="text-center"><a href="<?=$sort_header?>/comm_status/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" class="sortable"><?=$this->lang->line('status')?></a></th>
                    <th style="width:8%" class="text-center"><a href="<?=$sort_header?>/approved/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" class="sortable"><?=$this->lang->line('approved')?></a></th>
                    <th style="width:10%" class="hidden-xs text-center"><a href="<?=$sort_header?>/date/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" class="sortable"><?=$this->lang->line('date')?></a></th>
                    <?php if (!empty($where_column) && $where_column == 'recurring_comm'): ?>
                    <th style="width:15%" class="hidden-xs text-center"><a href="<?=$sort_header?>/invoice_id/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" class="sortable"><?=$this->lang->line('next_recurring_date')?></a></th>
                    <?php else: ?>
                    <th style="width:15%" class="hidden-xs text-center"><a href="<?=$sort_header?>/invoice_id/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" class="sortable"><?=$this->lang->line('transaction_id')?></a></th>
                    <?php endif; ?>
                    <th style="width:8%" class="hidden-xs text-center"><a href="<?=$sort_header?>/username/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" class="sortable"><?=$this->lang->line('affiliate')?></a></th>
                    <th style="width:7%" class="text-center"><a href="<?=$sort_header?>/commission_amount/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" class="sortable"><?=$this->lang->line('commission')?></a></th>
                    <th style="width:5%" class="text-center"><a href="<?=$sort_header?>/sale_amount/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" class="sortable"><?=$this->lang->line('total_sale')?></a></th>
                    <th style="width:5%" class="hidden-xs text-center"><?=$this->lang->line('level')?></th>
                    <th style="width:10%">&nbsp;</th>
                </tr>
    		</thead>
            <tbody>
            	<?php foreach ($commissions as $v): ?>
                <tr <?php if ($v['commission_level'] > '1'): ?> class="sublevel" <?php endif; ?>>
                    <td class="text-center"><input name="commission[]" type="checkbox" id="commission[]" value="<?=$v['comm_id']?>"/></td>
                    <td class="hidden-xs text-center">

                        <span><?=$v['comm_id']?></span>
                    </td>
                    <td class="text-center">
                        <?php if ($v['comm_status'] == 'pending'): ?>
                        <span class="label label-danger"> <?=$this->lang->line('pending')?></span>
                        <?php elseif ($v['comm_status'] == 'unpaid') : ?>
                        <span class="label label-warning"><?=$this->lang->line('unpaid')?></span>
                        <?php else : ?>
                        <span class="label label-success"><?=$this->lang->line('paid')?></span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                    	<a href="<?=admin_url()?>commissions/update_status/<?=$v['comm_id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>">
                        <?php if ($v['approved'] == '0'): ?>
                        <span class="label label-danger"> <?=$this->lang->line('no')?></span>
                        <?php elseif ($v['approved'] == '1') : ?>
                        <span class="label label-success"><?=$this->lang->line('yes')?></span>
                        <?php endif; ?>
                        </a>
                    </td>
                    <td class="hidden-xs text-center"><span><?=_show_date($v['date'], true)?></span></td>
					<td class="text-center hidden-xs">
                        <span><?php if (!empty($where_column) && $where_column == 'recurring_comm'): ?>
                        <?=_show_date($v['recur'], true)?>
                        <?php else: ?>
                    	<strong  <?php if ($v['commission_level'] > '1'): ?> class="sublevel" <?php endif; ?>>
                            <a href="<?=admin_url()?>commissions/update_commission/<?=$v['comm_id']?>">
                                <?php if ($v['commission_level'] > '1'): ?> <i class="fa fa-level-up fa-rotate-90"></i> <?php endif; ?>
                                <?=$v['trans_id']?>
                            </a>
                        </strong>
						<?php endif; ?>
                        </span>
                        <?php if ($v['recurring_comm'] == 1): ?>
                        <br /><span class="label label-info"><?=$this->lang->line('recurring')?></span>
                        <?php endif; ?>
                    </td>
                    <td class="hidden-xs text-center"><a href="<?=admin_url()?>members/update_member/<?=$v['member_id']?>"><span><?=$v['username']?></span></a></td>
                    <td class="text-center"><strong><a href="<?=admin_url()?>commissions/update_commission/<?=$v['comm_id']?>"><span><?=format_amounts($v['commission_amount'], $num_options)?></span></a></strong></td>
                    <td class="text-center">
                        <?php if ($v['commission_level'] == 1): ?>
                        <strong><span><?=format_amounts($v['sale_amount'], $num_options)?></span></strong>
                        <?php else: ?>
                        --
                        <?php endif; ?>
                    </td>
                    <td class="hidden-xs text-center">
                        <?php if ($v['commission_level'] == 1): ?>
                        <strong class="badge"><?=$v['commission_level']?></strong>
                        <?php else: ?>
                        <?=$v['commission_level']?>
                        <?php endif; ?>
                    </td>
                    <td class="text-right">
                    	 <a data-href="<?=admin_url()?>commissions/delete_commission/<?=$v['comm_id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i></a>
                        <a href="<?=admin_url()?>commissions/update_commission/<?=$v['comm_id']?>" class="btn btn-default hidden-xs"><i class="fa fa-pencil"></i></a>
                    </td>
				</tr>
                <?php endforeach; ?>
    		</tbody>
            <tfoot>
                <tr>
                    <td class="text-center">
                        <input type="checkbox" class="check-all" name="check-all" />
                    </td>
                    <td colspan="4">
                        <div class="input-group">
                            <select name="change-status" class="form-control">
                                <option value="1"><?=$this->lang->line('mark_checked_as_approved_for_payment')?></option>
                                <option value="0"><?=$this->lang->line('mark_checked_as_unapproved')?></option>
                                <option value="pending"><?=$this->lang->line('mark_checked_as_pending')?></option>
                                <option value="unpaid"><?=$this->lang->line('mark_checked_as_unpaid')?></option>
                                <!-- <option value="paid"><?=$this->lang->line('mark_checked_as_paid')?></option> -->
                                <option value="delete"><?=$this->lang->line('mark_checked_for_deletion')?></option>
                            </select> <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit"><?=$this->lang->line('go')?></button></span>
                        </div>
                    </td>
                    <td colspan="7">
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



