<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form"  method="post" class="form-horizontal"  role="form">
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline($module)?>
    </div>
    <div class="col-md-8 text-right">
		<?php if ($function == 'update_action_commission'): ?>
        <?=_previous_next('previous', 'action_commissions', $this->validation->id);?>
         <a data-href="<?=admin_url()?>action_commissions/delete_action_commission/<?=$this->validation->id?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i> <span class="hidden-xs"><?=$this->lang->line('delete')?></span></a>
        <a href="<?=admin_url()?>action_commissions/add_action_commission" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add')?></span></a>
		<?php endif; ?>
        
        <a href="<?=admin_url()?>affiliate_payments/view_affiliate_payments" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_payments')?></span></a>
        <?php if ($function == 'update_action_commission'): ?>
        <?=_previous_next('next', 'action_commissions', $this->validation->id);?>
        <?php endif; ?>
    </div>
</div>
<hr />
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">
        <h4 class="header"><i class="fa fa-cog"></i> <?=$this->lang->line($function)?></h4>
        	<div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('payment_date')?></label>
        		<div class="col-lg-5">
                	<div class="input-group">
                    	<input name="payment_date" id="payment_date" type="text"  value="<?=$this->validation->payment_date?>" class="datepicker-input form-control"  />
                        <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                    </div>
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('payment_amount')?></label>
        		<div class="col-lg-5">
        			<input name="payment_amount" id="payment_amount" type="text"  value="<?=$this->validation->payment_amount?>" class="form-control"  />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('username')?></label>
        		<div class="col-lg-5">
        			<input name="username" id="username" type="text"  value="<?=$this->validation->username?>" class="form-control"  />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('payment_details')?></label>
        		<div class="col-lg-5">
        			<textarea name="payment_details" id="payment_details" class="form-control" rows="5"><?=$this->validation->username?></textarea>
        		</div>
        	</div>
            <hr />
            <div class="form-group">
                <div class="col-md-offset-3 col-sm-9">
                    <button class="btn btn-success block-phone" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button>
                    <a href="<?=admin_url()?>commissions/view_commissions/0/0/0/payment_id/<?=$this->validation->id?>" class="btn btn-primary" title=""><i class="fa fa-search"></i> <?=$this->lang->line('view_associated_commissions')?></a>
                </div>
            </div>
            
		</div>
	</div>
</div>
</form>