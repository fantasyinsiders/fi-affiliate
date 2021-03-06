<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline($page_title)?>
    </div>
    <div class="col-md-8 text-right">
        <a href="<?=admin_url()?>affiliate_payments/view_payment_options" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_payment_options')?></span></a>
    </div> 
</div>
<hr />
<form id="ajax-form"  method="post" class="form-horizontal"  role="form" enctype="multipart/form-data">
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">
        	<h4 class="header">
            <?php if (file_exists($base_physical_path . '/images/modules/' . $module  . '.' . $member_marketing_tool_ext)): ?>
        	<img src="<?=base_url() . 'images/modules/' . $module . '.' . $member_marketing_tool_ext?>" class="img-responsive hidden-xs payment-img"/>
			<?php else: ?>
            <?=$this->lang->line('update_module_options')?>
        	<?php endif; ?>
            </h4>
            <div class="form-group">
    		    <label for="status" class="col-sm-3 control-label"><?=$this->lang->line('include_commissions_based_on_date')?></label>
        		<div class="col-lg-5">
        			 <?=form_dropdown('module_affiliate_payment_dwolla_payment_use_date_range', array('0' => $this->lang->line('no'), '1' => $this->lang->line('yes')), $this->validation->module_affiliate_payment_dwolla_payment_use_date_range, 'class="form-control show-block"');?> 
        		</div>
        	</div>
            <hr />
            <div <?php if ($this->validation->module_affiliate_payment_dwolla_payment_use_date_range == '0'): ?>style="display:none"<?php endif; ?> class="show-div">
           <div class="form-group">
    		    <label for="module_affiliate_payment_dwolla_payment_start_date" class="col-sm-3 control-label"><?=$this->lang->line('start_date')?></label>
        		<div class="col-lg-5">
        			 <input name="module_affiliate_payment_dwolla_payment_start_date" id="module_affiliate_payment_dwolla_payment_start_date" class="datepicker-input form-control"value="<?=_format_date($module_affiliate_payment_dwolla_payment_start_date, $format_date2)?>" placeholder="<?=$format_date?>"/>
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="module_affiliate_payment_dwolla_payment_end_date" class="col-sm-3 control-label"><?=$this->lang->line('end_date')?></label>
        		<div class="col-lg-5">
        			 <input name="module_affiliate_payment_dwolla_payment_end_date" id="module_affiliate_payment_dwolla_payment_end_date" class="datepicker-input form-control"value="<?=_format_date($module_affiliate_payment_dwolla_payment_end_date, $format_date2)?>" placeholder="<?=$format_date?>"/>
        		</div>
        	</div>
            <hr />
            </div>
            <div class="form-group">
    		    <label for="module_affiliate_payment_dwolla_payment_exclude_minimum" class="col-sm-3 control-label"><?=$this->lang->line('exclude_minimum_affiliate_payment')?> <?=format_amounts($sts_affiliate_min_payment, $num_options)?></label>
        		<div class="col-lg-5">
        			 <?=form_dropdown('module_affiliate_payment_dwolla_payment_exclude_minimum', array('0' => $this->lang->line('no'), '1' => $this->lang->line('yes')), $this->validation->module_affiliate_payment_dwolla_payment_exclude_minimum, 'class="form-control"');?> 
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="module_affiliate_payment_dwolla_payment_total_rows" class="col-sm-3 control-label"><?=$this->lang->line('total_rows')?></label>
        		<div class="col-lg-5">
        			 <input name="module_affiliate_payment_dwolla_payment_total_rows" id="module_affiliate_payment_dwolla_payment_total_rows" class="form-control"value="<?=$this->validation->module_affiliate_payment_dwolla_payment_total_rows?>" placeholder="100"/> 
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="module_affiliate_payment_dwolla_payment_api_key" class="col-sm-3 control-label"><?=$this->lang->line('api_key')?></label>
        		<div class="col-lg-5">
        			 <input name="module_affiliate_payment_dwolla_payment_api_key" id="module_affiliate_payment_dwolla_payment_api_key" class="form-control"value="<?=$this->validation->module_affiliate_payment_dwolla_payment_api_key?>" /> 
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="module_affiliate_payment_dwolla_payment_api_secret" class="col-sm-3 control-label"><?=$this->lang->line('api_secret')?></label>
        		<div class="col-lg-5">
        			 <input name="module_affiliate_payment_dwolla_payment_api_secret" id="module_affiliate_payment_dwolla_payment_api_secret" class="form-control"value="<?=$this->validation->module_affiliate_payment_dwolla_payment_api_secret?>" /> 
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="module_affiliate_payment_dwolla_payment_api_token" class="col-sm-3 control-label"><?=$this->lang->line('token')?></label>
        		<div class="col-lg-5">
        			 <input name="module_affiliate_payment_dwolla_payment_api_token" id="module_affiliate_payment_dwolla_payment_api_token" class="form-control"value="<?=$this->validation->module_affiliate_payment_dwolla_payment_api_token?>" /> 
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="module_affiliate_payment_dwolla_payment_api_pin" class="col-sm-3 control-label"><?=$this->lang->line('pin')?></label>
        		<div class="col-lg-5">
        			 <input name="module_affiliate_payment_dwolla_payment_api_pin" id="module_affiliate_payment_dwolla_payment_api_pin" class="form-control"value="<?=$this->validation->module_affiliate_payment_dwolla_payment_api_pin?>"/> 
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="module_affiliate_payment_dwolla_payment_payment_details" class="col-sm-3 control-label"><?=$this->lang->line('payment_notes')?></label>
        		<div class="col-lg-5">
        			 <textarea name="module_affiliate_payment_dwolla_payment_payment_details" id="module_affiliate_payment_dwolla_payment_payment_details" class="form-control" rows="10"><?=$this->validation->module_affiliate_payment_dwolla_payment_payment_details?></textarea>
        		</div>
        	</div>
            <hr />
            <div class="form-group">
                <div class="col-md-offset-3 col-sm-9">
                    <button class="btn btn-success" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('generate_payment_list')?></button>
                    
                </div>
            </div>
		</div>
    </div>      
</div>
</form>