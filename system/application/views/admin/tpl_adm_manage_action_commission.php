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
        
        <a href="<?=admin_url()?>action_commissions/view_action_commissions" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_commissions')?></span></a>
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
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('action_commission_code')?></label>
        		<div class="col-lg-5">
        			<input name="action_commission_name" id="action_commission_name" type="text"  value="<?=$this->validation->action_commission_name?>" class="form-control" placeholder="<?=$this->lang->line('short_code_action_commission')?>" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('status')?></label>
        		<div class="col-lg-5">
        			<?=form_dropdown('status', array('1' => $this->lang->line('enable'), '0' => $this->lang->line('disable')), $this->validation->status, 'class="form-control"')?>
        		</div>
        	</div>
            <hr />
			<div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('amount')?></label>
        		<div class="col-lg-5">
        			<input name="amount" id="amount" type="text"  value="<?=$this->validation->amount?>" class="form-control" placeholder="<?=$this->lang->line('use_decimal_for_percentage_commissions')?>" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('commission_type')?></label>
        		<div class="col-lg-5">
        			<?=form_dropdown('type', array('flat' => $this->lang->line('flat'), 'percent' => $this->lang->line('percent')), $this->validation->type, 'class="form-control"')?>
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('auto_approve')?></label>
        		<div class="col-lg-5">
        			<?=form_dropdown('auto_approve', array('1' => $this->lang->line('enable'), '0' => $this->lang->line('disable')), $this->validation->auto_approve, 'class="form-control"')?>
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('action_commission_description')?></label>
        		<div class="col-lg-5">
        			<textarea name="action_commission_description" class="form-control" id="action_commission_description" rows="4"><?=$this->validation->action_commission_description?></textarea>
        		</div>
        	</div>
            <hr />
            <div class="form-group">
                <div class="col-md-offset-3 col-sm-9">
                    <button class="btn btn-success block-phone" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button>        
                </div>
            </div>
		</div>
    </div>
</div>
</form>