<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form"  method="post" class="form-horizontal"  role="form">
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline($module)?>
    </div>
    <div class="col-md-8 text-right">
		<?php if ($function == 'update_currency'): ?>
        <?=_previous_next('previous', 'currencies', $this->validation->id);?>
         <a data-href="<?=admin_url()?>currencies/delete_currency/<?=$this->validation->id?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i> <span class="hidden-xs"><?=$this->lang->line('delete')?></span></a>
        <a href="<?=admin_url()?>currencies/add_currency" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add')?></span></a>
		<?php endif; ?>
        
        <a href="<?=admin_url()?>currencies/view_currencies" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_commissions')?></span></a>
        <?php if ($function == 'update_currency'): ?>
        <?=_previous_next('next', 'currencies', $this->validation->id);?>
        <?php endif; ?>
    </div>
</div>
<hr />
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">
        	<h4 class="header"><i class="fa fa-cog"></i> <?=$this->lang->line($function)?></h4>
       		<div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('currency_name')?></label>
        		<div class="col-lg-5">
        			<input name="title" id="title" type="text"  value="<?=$this->validation->title?>" class="form-control" />
        		</div>
        	</div>
            <hr />
       		<div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('code')?></label>
        		<div class="col-lg-5">
        			<input name="code" id="code" type="text"  value="<?=$this->validation->code?>" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('value')?></label>
        		<div class="col-lg-5">
        			<input name="value" id="value" type="text"  value="<?=$this->validation->value?>" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('symbol_left')?></label>
        		<div class="col-lg-5">
        			<input name="symbol_left" id="symbol_left" type="text"  value="<?=$this->validation->symbol_left?>" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('symbol_right')?></label>
        		<div class="col-lg-5">
        			<input name="symbol_right" id="symbol_right" type="text"  value="<?=$this->validation->symbol_right?>" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('decimal_point')?></label>
        		<div class="col-lg-5">
        			<input name="decimal_point" id="decimal_point" type="text"  value="<?=$this->validation->decimal_point?>" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('thousands_point')?></label>
        		<div class="col-lg-5">
        			<input name="thousands_point" id="thousands_point" type="text"  value="<?=$this->validation->thousands_point?>" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('decimal_places')?></label>
        		<div class="col-lg-5">
        			<input name="decimal_places" id="decimal_places" type="text"  value="<?=$this->validation->decimal_places?>" class="form-control" />
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