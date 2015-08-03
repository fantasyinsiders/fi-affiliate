<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($affiliate_payments)): ?>
<div class="alert alert-warning">
	<h2 class="text-warning"><?=$this->lang->line('no_affiliate_payments_found')?></h2>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning btn-lg"><?=$this->lang->line('go_back')?></a>
    	<a href="<?=admin_url()?>affiliate_payments/add_affiliate_payment" class="btn  btn-warning btn-lg"><?=$this->lang->line('add_affiliate_payment')?></a>
    </p>
</div>
<?php else: ?>
<form id="form" name="prod_form" action="<?=modules_url() . $module?>/generate_affiliate_payments" method="post">
<div class="row">
    <div class="col-md-4">
    	<?php if (file_exists($base_physical_path . '/images/modules/' . $module  . '.' . $member_marketing_tool_ext)): ?>
    	<img src="<?=base_url() . 'images/modules/' . $module . '.' . $member_marketing_tool_ext?>" class="img-responsive hidden-xs payment-img"/>
		<?php else: ?>
		<?=_generate_sub_headline('make_affiliate_payments')?>
    	<?php endif; ?>
    </div>
    <div class="col-md-8 text-right">
    	<?php if (!empty($coinbase_balance)): ?>
    	<a href="http://www.coinbase.com" target="_blank" class="btn btn-default"><i class="fa fa-external-link"></i> <?=$this->lang->line('your_current_btc_balance')?>: <?=$coinbase_balance?></a>
		<?php endif; ?>
        <a href="<?=admin_url()?>affiliate_payments/view_affiliate_payments" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('view_payment_history')?></span></a>
    </div>
</div>
<hr />
<div class="row data-row">    
   	<div class="col-md-12">
    	<div class="box-info">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width:5%">&nbsp;</th>
                    <th style="width:10%" class="text-center hidden-xs"><?=$this->lang->line('id')?></th>
                    <th style="width:20%"><?=$this->lang->line('name')?></th>
                    <th style="width:20%" class="hidden-xs"><?=$this->lang->line('payment_name')?></th>
                    <th style="width:15%"  class="hidden-xs"><?=$this->lang->line($payment_id)?></th>
                    <th style="width:10%" class="text-center hidden-xs"><?=$this->lang->line('minimum')?></th>
                    <th style="width:10%" class="text-center"><a href="<?=$sort_header?>/total" class="sortable"><?=$this->lang->line('total')?></a></th>
                    <td style="width: 10%"></td>
                </tr>
            </thead>
            <tbody>
                <?php foreach($affiliate_payments as $v):?> 
                <?php if (!empty($exclude_minimum) && $v['total'] < $sts_affiliate_min_payment) continue;?>
                <?php $name = explode('|', $v['payment_name']) ?>
				<?php if (empty($name[3])): ?> 
                    <?php $min = $sts_affiliate_min_payment; ?>
                <?php else: ?>
                    <?php $min = $name[3]; ?>
                <?php endif; ?> 
                <tr>
                    <td class="text-center">
                    	<?php if (!empty($name[2])): ?>
                    	<input name="user[<?=$v['id']?>]" type="checkbox" id="user[<?=$v['id']?>]" value="<?=format_amounts($v['total'], $num_options, true)?>" /></td>
                    	<?php endif; ?>
                    <td class="hidden-xs text-center"><?=$v['id']?></td>
                    <td><h5><a href="<?=admin_url()?>members/update_member/<?=$v['id']?>"><?=$name[0]?></a></h5></td>
                    <td class="hidden-xs"><h5><?=$name[1]?></h5></td>
                    <td class="hidden-xs"><?=$name[2]?></td>
                    <td class="text-center hidden-xs"><?=format_amounts($min, $num_options)?></td>
                    <td class="text-center">
						<?=format_amounts($v['total'], $num_options)?>
					</td>
                    <td>
                    	<?php if (!empty($name[2])): ?>
                    	<a href="<?=modules_url()?>module_affiliate_payment_dwolla_payment/direct_pay/<?=$this->convert->AsciiToHex(base64_encode($v['id'] . '-' . $name[2] . '-' . $v['total']))?>"><img src="<?=base_url()?>images/misc/pay_dwolla.png" /></a>
               			<?php endif; ?>
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center">
                        <input type="checkbox" class="check-all" /> 
                    </td>
                    <td colspan="7">
                        <div class="input-group">
                            <select name="payment_type" class="form-control">
                            	<option value="<?=$module_name?>"><?=$this->lang->line('include_check_in_mass_payment')?></option>			
                            	<option value="mark_as_paid"><?=$this->lang->line('mark_as_paid')?></option>
                            </select> <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit"><?=$this->lang->line('generate')?></button></span>
                        </div>
                    </td> 
                </tr>
            </tfoot>
        </table>
        </div>
        <?php if (!empty($pagination['rows'])): ?>
        <div class="text-center"><small class="text-muted"><?=$pagination['num_pages']?> <?=$this->lang->line('total_pages')?></small></div>
        <div class="text-center"><?=$pagination['rows']?></div>    
        <?php endif; ?>
	</div>
</div>
</form>
<?php endif; ?>