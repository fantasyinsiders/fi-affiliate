<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form" method="post" class="form-horizontal" role="form" >
<div class="row">
    <div class="col-md-4">
    	<?=_generate_sub_headline('integration_profile')?>
    </div>
    <div class="col-md-8 text-right">
    	<a href="<?=admin_url()?>integration/integration_profiles" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_integration_profiles')?></span></a>
    </div>
</div>
<hr />
<div class="row data-row">    
   	<div class="col-md-12">
    	<div class="box-info">
        	<div class="alert alert-danger hidden-xs hidden-sm">
                <i class="fa fa-exclamation-triangle"></i> This requires advanced programming knowledge. Please contact us if you need help integrating this into your system.
            </div>	
        	<ul class="nav nav-tabs capitalize">
                <li class="active"><a href="#main" data-toggle="tab"><?=$this->lang->line('integration_profile')?></a></li>
                <li><a href="#custom" data-toggle="tab"><?=$this->lang->line('custom_fields')?></a></li>
            </ul>
            <div class="tab-content">
                <div id="main" class="tab-pane fade in active">
                    <hr />
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?=$this->lang->line('integration_id')?></label>
                        <div class="col-sm-5">
                            <input name="name" class="form-control" value="<?=$this->validation->name?>"/>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?=$this->lang->line('description')?></label>
                        <div class="col-sm-5">
                            <textarea name="description" class="form-control" rows="5"><?=$this->validation->description?></textarea>
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
                        <label class="col-lg-3 control-label"><?=$this->lang->line('amount')?></label>
                        <div class="col-lg-5">
                            <input name="amount" class="form-control" value="<?=$this->validation->amount?>"/>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=$this->lang->line('trans_id')?></label>
                        <div class="col-lg-5">
                            <input name="trans_id" class="form-control" value="<?=$this->validation->trans_id?>"/>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=$this->lang->line('tracking_code')?></label>
                        <div class="col-lg-5">
                            <input name="tracking_code" class="form-control" value="<?=$this->validation->tracking_code?>"/>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=$this->lang->line('invoice_id')?></label>
                        <div class="col-lg-5">
                            <input name="invoice_id" class="form-control" value="<?=$this->validation->invoice_id?>"/>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=$this->lang->line('customer_name')?></label>
                        <div class="col-lg-5">
                            <input name="customer_name" class="form-control" value="<?=$this->validation->customer_name?>"/>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=$this->lang->line('first_name')?></label>
                        <div class="col-lg-5">
                            <input name="first_name" class="form-control" value="<?=$this->validation->first_name?>"/>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=$this->lang->line('last_name')?></label>
                        <div class="col-lg-5">
                            <input name="last_name" class="form-control" value="<?=$this->validation->last_name?>"/>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=$this->lang->line('lifetime_data')?></label>
                        <div class="col-lg-5">
                            <input name="lf_data" class="form-control" value="<?=$this->validation->lf_data?>"/>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=$this->lang->line('product_code')?></label>
                        <div class="col-lg-5">
                            <input name="product_code" class="form-control" value="<?=$this->validation->product_code?>"/>
                        </div>
                    </div>
                    <hr />
                </div>
                <div id="custom" class="tab-pane fade in">
                	<hr />
                	<?php foreach ($fields as $k =>$v): ?>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=$this->lang->line($k)?></label>
                        <div class="col-lg-5">
                            <input name="<?=$v?>" class="form-control" value="<?=$this->validation->$v?>"/>
                        </div>
                    </div>
                    <hr />
                    <?php endforeach; ?>
                </div>
           </div>
           <div class="col-md-5 col-md-offset-3"><button class="btn btn-success btn-lg" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button></div>
        </div>
    </div>
</div>
</form>