<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form"  method="post" class="form-horizontal"  role="form">
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline($module)?>
    </div>
    <div class="col-md-8 text-right">
		<?php if ($function == 'update_coupon'): ?>
        <?=_previous_next('previous', 'coupons', $this->validation->id);?>
         <a data-href="<?=admin_url()?>coupons/delete_coupon/<?=$this->validation->id?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i> <span class="hidden-xs"><?=$this->lang->line('delete')?></span></a>
        <a href="<?=admin_url()?>coupons/add_coupon" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add')?></span></a>
		<?php endif; ?>
        
        <a href="<?=admin_url()?>coupons/view_coupons" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_coupons')?></span></a>
        <?php if ($function == 'update_coupon'): ?>
        <?=_previous_next('next', 'coupons', $this->validation->id);?>
        <?php endif; ?>
    </div>
</div>
<hr />
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">
        <h4 class="header"><i class="fa fa-cog"></i> <?=$this->lang->line($function)?></h4>
        	<div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('status')?></label>
        		<div class="col-lg-5">
        			<?=form_dropdown('status', array('1' => $this->lang->line('enable'), '0' => $this->lang->line('disable')), $this->validation->status, 'class="form-control"')?>
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('coupon_code')?></label>
        		<div class="col-lg-5">
        			<input name="coupon_code" id="coupon_code" type="text"  value="<?=$this->validation->coupon_code?>" class="form-control required" placeholder="<?=$this->lang->line('coupon_code')?>" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('referring_affiliate')?></label>
        		<div class="col-lg-5">
        			<input name="referring_affiliate" id="referring_affiliate" class="form-control required" value="<?=$this->validation->referring_affiliate?>" placeholder="<?=$this->lang->line('enter_username')?>"/>
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('use_program_defaults')?></label>
        		<div class="col-lg-5">
        			<?=form_dropdown('use_program_comms', array('1' => $this->lang->line('yes - use_program_group_defaults'), '0' => $this->lang->line('no - use_one_time_amount')), $this->validation->use_program_comms, 'class="show-comm form-control"')?>
        		</div>
        	</div>
            <hr />
            <div id="div-comm-type" <?php if ($this->validation->type == 0):?> style="display:none"<?php endif; ?>>
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('commission_type')?></label>
        		<div class="col-lg-5">
        			<?=form_dropdown('type', array('flat' => $this->lang->line('flat'), 'percent' => $this->lang->line('percent')), $this->validation->type, 'class="form-control"')?>
        		</div>
        	</div>
            <hr />
			<div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('amount')?></label>
        		<div class="col-lg-5">
        			<input name="amount" id="amount" type="text"  value="<?=$this->validation->amount?>" class="form-control required" placeholder="<?=$this->lang->line('use_decimal_for_percentage_commissions')?>" />
        		</div>
        	</div>
            <hr />
            </div>
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('description')?></label>
        		<div class="col-lg-5">
        			<input name="coupon_description" id="coupon_description" type="text"  value="<?=$this->validation->coupon_description?>" class="form-control required" />
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
<script language="JavaScript" type="text/javascript" src="<?=base_url('js')?>js/autocomplete/jquery.autocomplete.js"></script>
<script type="text/javascript">
$(function() {	
	$("#referring_affiliate").autocomplete("<?=admin_url()?>search/ajax_members");
});
$("select.show-comm").change(function(){
	$( "select.show-comm option:selected").each(function(){
		if($(this).attr("value")=="1"){
			$("#div-comm-type").hide(300);
		}
		if($(this).attr("value")=="0"){
			$("#div-comm-type").show(300);
		}
	});
}).change();
$("#form").validate();
</script>