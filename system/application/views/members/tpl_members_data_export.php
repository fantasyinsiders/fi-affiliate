<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<form id="form" class="form-horizontal" method="post" role="form">
<div class="col-md-12">
	<div class="panel panel-default">
    	<div class="panel-heading">
        	<h4><?=$this->lang->line('data_export')?></h4>
        </div>
        <div class="panel-body">
        	<div class="form-group">
                <label class="col-lg-4 control-label text-capitalize"><?=$this->lang->line('module_data_export_commissions_by_date_commission_type')?> </label>
                <div class="col-lg-5">
                    <?=form_dropdown('module_data_export_commissions_by_date_delimiter', array('comma' => 'comma', 'tab' => 'tab'), $this->validation->module_data_export_commissions_by_date_delimiter, 'class="form-control"');?>
                </div>
            </div>
            <hr />
            <div class="form-group">
                <label class="col-lg-4 control-label text-capitalize"><?=$this->lang->line('module_data_export_commissions_by_date_commission_type')?> </label>
                <div class="col-lg-5">
                    <?=form_dropdown('module_data_export_commissions_by_date_commission_type', array('all' => 'all', 'pending' => 'pending', 'unpaid' => 'unpaid', 'paid' => 'paid'), $this->validation->module_data_export_commissions_by_date_commission_type, 'class="form-control"');?>
                </div>
            </div>
            <hr />	
            <div class="form-group">
                <label class="col-lg-4 control-label text-capitalize"><?=$this->lang->line('module_data_export_commissions_by_date_start_date')?> </label>
                <div class="col-lg-5">
                    <input name="module_data_export_commissions_by_date_start_date" type="text" value="<?=$this->validation->module_data_export_commissions_by_date_start_date?>" class="datepicker-input form-control required" placeholder="<?=$format_date?>" /> 
                </div>
            </div>
            <hr />	
            <div class="form-group">
                <label class="col-lg-4 control-label text-capitalize"><?=$this->lang->line('module_data_export_commissions_by_date_end_date')?> </label>
                <div class="col-lg-5">
                    <input name="module_data_export_commissions_by_date_end_date" type="text" value="<?=$this->validation->module_data_export_commissions_by_date_end_date?>" class="datepicker-input form-control required" placeholder="<?=$format_date?>" /> 
                </div>
            </div>
            <hr />	
            <div class="col-md-5 col-md-offset-4"><button class="btn btn-success btn-lg btn-block" id="top" type="submit"><i class="fa fa-download"></i> <?=$this->lang->line('export_data')?></button></div>
        </div>
    </div>
</div>
</form>
<script src="<?=base_url('js');?>js/datepicker/js/bootstrap-datepicker.js"></script>
<script>
$(function() {	
		$('.datepicker-input').datepicker({format: '<?=$format_date?>'});
	});
	
</script>