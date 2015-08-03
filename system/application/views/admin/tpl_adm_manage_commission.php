<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form"  method="post" class="form-horizontal"  role="form">
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline('manage_commissions')?>
    </div>
    <div class="col-md-8 text-right">
		<?php if ($function == 'update_commission'): ?>
        <?=_previous_next('previous', 'commissions', $this->validation->id);?>
         <a data-href="<?=admin_url()?>commissions/delete_commission/<?=$this->validation->id?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i> <span class="hidden-xs"><?=$this->lang->line('delete')?></span></a>
        <a href="<?=admin_url()?>commissions/add_commission" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add')?></span></a>
		<?php endif; ?>
        
        <a href="<?=admin_url()?>commissions/view_commissions" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_commissions')?></span></a>
        <?php if ($function == 'update_commission'): ?>
        <?=_previous_next('next', 'commissions', $this->validation->id);?>
        <?php endif; ?>
    </div>
</div>
<hr />
<div class="row">   
	<div class="col-md-12">
    	<div class="box-info">
    	<ul class="nav nav-tabs capitalize">
            <li class="active"><a href="#main" data-toggle="tab"><?=$this->lang->line('commission_details')?></a></li>
            <li><a href="#custom" data-toggle="tab"><?=$this->lang->line('custom_fields')?></a></li>
            <li><a href="#options" data-toggle="tab"><?=$this->lang->line('options')?></a></li>
    	</ul>
        <div class="tab-content">
        	<div id="main" class="tab-pane fade in active">
                <hr />
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('status')?></label>
                    <div class="col-lg-2">
                        <?=form_dropdown('comm_status', array('pending' => $this->lang->line('pending'), 'unpaid' => $this->lang->line('unpaid'), 'paid' => $this->lang->line('paid')), $this->validation->comm_status, 'class="form-control"')?>
                    </div>
                    <hr class="hidden-lg" />
                    <label class="col-lg-1 control-label"><?=$this->lang->line('approved')?></label>
                    <div class="col-lg-2">
                        <?=form_dropdown('approved', array('0' => $this->lang->line('no'), '1' => $this->lang->line('yes')), $this->validation->approved, 'class="form-control"')?>
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('date_created')?></label>
                    <div class="col-lg-2">
                        <div class="input-group">
                           	<input name="date" id="date" class="datepicker-input form-control"value="<?=$this->validation->date?>" placeholder="<?=$format_date?>"/> 
                        	<span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                        </div>
                    </div>
                    <hr class="hidden-lg" />
                    <label class="col-lg-1 control-label"><?=$this->lang->line('date_paid')?></label>
                    <div class="col-lg-2">
                    	<div class="input-group">
                           	<input name="date_paid" id="date_paid" class="datepicker-input form-control"value="<?=$this->validation->date_paid?>" placeholder="<?=$format_date?>"/> 
                        	<span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('program_name')?></label>
                    <div class="col-lg-5">
                        <?=form_dropdown('program_id', $programs, $this->validation->program_id, 'class="form-control"')?>
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('referring_affiliate')?></label>
                    <div class="col-lg-2">
                        <input name="referring_affiliate" id="referring_affiliate" class="form-control required" value="<?=$this->validation->referring_affiliate?>" placeholder="<?=$this->lang->line('enter_username')?>"/>
                    </div>
                    <?php if ($function == 'add_commission'): ?>
                    <hr class="hidden-lg" />
                    <label class="col-lg-1 control-label"><?=$this->lang->line('send_email_alert')?></label>
                    <div class="col-lg-2">
                        <?=form_dropdown('send_email_alert', array('0' => $this->lang->line('no'), '1' => $this->lang->line('yes')), $this->validation->send_email_alert, 'class="form-control"')?>
                    </div>
                	<?php endif; ?>
                </div>
                <hr />
                <?php if ($function == 'add_commission'): ?>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('use_program_defaults')?></label>
                    <div class="col-lg-5">
                        <?=form_dropdown('use_program_defaults', array('1' => $this->lang->line('no'), '0' => $this->lang->line('yes')), $this->validation->use_program_defaults, 'class="form-control show-block"')?>
                    </div>
                </div>
                <hr />
                <?php endif; ?>
                <?php if ($function == 'add_commission'): ?>
                <div class="form-group">
                	 <label class="col-lg-3 control-label"><?=$this->lang->line('level')?></label>
                    <div class="col-lg-2">
                        <?=form_dropdown('commission_level', $commission_levels, $this->validation->commission_level, 'id="levels" class="form-control"')?>
                    </div>
                    <div id="credit_upline">
                    <hr class="hidden-lg" />
                    <label class="col-lg-1 control-label"><?=$this->lang->line('credit_upline')?></label>
                    <div class="col-lg-2">
                        <?=form_dropdown('credit_upline', array('0' => $this->lang->line('no'), '1' => $this->lang->line('yes')), $this->validation->credit_upline, 'class="form-control"')?>
                    </div>
                    </div>
                </div>
                <hr />
                <?php endif; ?>
                <div class="show-div" <?php if ($this->validation->use_program_defaults == '1'): ?>style="display:none"<?php endif; ?>>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=$this->lang->line('commission_amount')?></label>
                        <div class="col-lg-5">
                            <input name="commission_amount" id="commission_amount" class="form-control"value="<?=$this->validation->commission_amount?>" placeholder="0.00"/>
                        </div>
                    </div>
                    <hr />
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('sale_amount')?></label>
                    <div class="col-lg-5">
                        <input name="sale_amount" type="text" id="sale_amount" class="form-control"value="<?=$this->validation->sale_amount?>" placeholder="0.00"/>
                    </div>
                </div>
                <hr />
               <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('transaction_id')?></label>
                    <div class="col-lg-5">
                        <input name="trans_id" type="text" id="trans_id" class="form-control"value="<?=$this->validation->trans_id?>" />
                    </div>
                </div>
                 <hr />
               <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('customer_name')?></label>
                    <div class="col-lg-5">
                        <input name="customer_name" type="text" id="customer_name" class="form-control"value="<?=$this->validation->customer_name?>" />
                    </div>
                </div>
                <hr />
               <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('referral_url')?></label>
                    <div class="col-lg-5">
                        <input name="referrer" type="text" id="referrer" class="form-control"value="<?=$this->validation->referrer?>" />
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('commission_notes')?></label>
                    <div class="col-lg-5">
                        <textarea name="commission_notes" class="form-control" rows="5"><?=$this->validation->commission_notes?></textarea>
                    </div>
                </div>
                <hr />
 			</div><!-- end #main -->
    		<div id="custom" class="tab-pane fade in">
                <hr />
                <?php for ($i=1; $i<=20; $i++): ?>
				<?php $field = 'custom_commission_field_' . $i?>  
	 			<div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('custom_commission_field_' . $i)?></label>
                    <div class="col-lg-5">
                        <input name="custom_commission_field_<?=$i?>" type="text" class="form-control"  id="custom_commission_field_<?=$i?>" value="<?=$this->validation->$field?>" />
                    </div>
                </div>
                <hr />	
  				<?php endfor; ?>
 			</div><!-- end #custom -->
            <div id="options" class="tab-pane fade in">
            <?php if ($function == 'update_commission'):?>
            <?php if ($this->validation->recurring_comm == 1): ?>
            <div class="alert alert-warning"><a href="<?=admin_url()?>commissions/view_commissions/0/0/0/parent_id/<?=$this->validation->id?>/0/0"><?=$this->lang->line('view_related_recurring_commissions')?></a></div>
            <?php endif; ?>
            <?php endif; ?>
                <hr />
                <div class="form-group">
                     <label class="col-lg-3 control-label"><?=$this->lang->line('is_recurring_commission')?></label>
                    <div class="col-lg-5">
                        <?=form_dropdown('recurring_comm', array('0' => $this->lang->line('no'), '1' => $this->lang->line('yes')), $this->validation->recurring_comm, 'class="form-control show-recur"')?>
                    </div>
                </div>
                <hr />
                <div id="div-recur" <?php if ($this->validation->recurring_comm == 0):?> style="display:none"<?php endif; ?>>
                <div class="form-group">
                     <label class="col-lg-3 control-label"><?=$this->lang->line('next_recurring_date')?></label>
                    <div class="col-lg-5">
                        <input name="recur" id="recur" class="datepicker-input form-control"value="<?=$this->validation->recur?>" placeholder="<?=$format_date?>"/> 
                    </div>
                </div>
                <hr />
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('action_commission_id')?></label>
                    <div class="col-lg-5">
                        <input name="action_commission_id" type="text" id="action_commission_id" class="form-control"value="<?=$this->validation->action_commission_id?>" />
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('product_identifier')?></label>
                    <div class="col-lg-5">
                        <input name="product_id" type="text" id="product_id" class="form-control"value="<?=$this->validation->product_id?>" />
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('tracking_id')?></label>
                    <div class="col-lg-5">
                        <input name="tracking_id" type="text" id="tracking_id" class="form-control"value="<?=$this->validation->tracking_id?>" />
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('order_id')?></label>
                    <div class="col-lg-5">
                        <input name="order_id" type="text" id="order_id" class="form-control"value="<?=$this->validation->order_id?>" />
                    </div>
                </div>
                <hr />
                <?php if ($function == 'update_commission'): ?>
                <?php if ($this->validation->payment_id): ?>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('payment_id')?></label>
                    <div class="col-lg-5">
                        <a href="<?=admin_url()?>affiliate_payments/update_affiliate_payment/<?=$this->validation->payment_id?>" class="btn btn-default"><i class="fa fa-refresh"></i> <?=$this->lang->line('payment_id')?> <?=$this->validation->payment_id?></a>
                    </div>
                </div>
                <hr />
                <?php endif; ?>
                <?php endif; ?>
                <?php if ($this->validation->recurring_comm == 0 && !empty($this->validation->parent_id)): ?>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('parent_recurring_commission')?></label>
                    <div class="col-lg-5">
                        <a href="<?=admin_url()?>commissions/update_commission/<?=$this->validation->parent_id?>" class="btn btn-default"><i class="fa fa-refresh"></i> <?=$this->lang->line('commission')?> <?=$this->validation->parent_id?></a>
                    </div>
                </div>
                <hr />
                <?php endif; ?>
 			</div><!-- end #options -->
       </div>
       <div class="col-md-5 col-md-offset-3"><button class="btn btn-success btn-lg" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button></div>
       </div>
	</div>
</div>
<br />
</form>
<script language="JavaScript" type="text/javascript" src="<?=base_url('js')?>js/autocomplete/jquery.autocomplete.js"></script>
<script type="text/javascript">
$(function() {	
	$("#referring_affiliate").autocomplete("<?=admin_url()?>search/ajax_members");
});
$("select.show-recur").change(function(){
	$( "select.show-recur option:selected").each(function(){
		if($(this).attr("value")=="1"){
			$("#div-recur").show(300);
		}
		if($(this).attr("value")=="0"){
			$("#div-recur").hide(300);
		}
	});
}).change();
$("select#levels").change(function(){
	$( "select#levels option:selected").each(function(){
		if($(this).attr("value")=="1"){
			$("#credit_upline").show(100);
		}
		else {
			$("#credit_upline").hide(100);
		}
	});
}).change();
$("#form").validate();
</script>