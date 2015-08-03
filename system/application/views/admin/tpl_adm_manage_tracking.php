<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form"  method="post" class="form-horizontal"  role="form">
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline($module)?>
    </div>
    <div class="col-md-8 text-right">
		<?php if ($function == 'update_tracking'): ?>
        <?=_previous_next('previous', 'tracking', $this->validation->id);?>
         <a data-href="<?=admin_url()?>tracking/delete_tracking/<?=$this->validation->id?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i> <span class="hidden-xs"><?=$this->lang->line('delete')?></span></a>
        <a href="<?=admin_url()?>tracking/add_tracking" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add')?></span></a>
		<?php endif; ?>
        
        <a href="<?=admin_url()?>tracking/view_tracking" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_tracking')?></span></a>
        <?php if ($function == 'update_tracking'): ?>
        <?=_previous_next('next', 'tracking', $this->validation->id);?>
        <?php endif; ?>
    </div>
</div>
<hr />
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">
        	<h4 class="header"><i class="fa fa-cog"></i> <?=$this->lang->line($function)?></h4>
        	<div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('name')?></label>
        		<div class="col-lg-5">
        			<input name="name" id="name" type="text"  value="<?=$this->validation->name?>" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('cost')?></label>
        		<div class="col-lg-5">
        			<input name="cost" id="cost" type="text"  value="<?=$this->validation->cost?>" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('url')?></label>
        		<div class="col-lg-5">
        			<input name="url" id="url" type="text"  value="<?=$this->validation->url?>" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('cost_type')?></label>
        		<div class="col-lg-5">
        			 <?=form_dropdown('cost_type', array('one_time' => $this->lang->line('one_time'), 'ppc' => $this->lang->line('ppc'), 'recur' => $this->lang->line('recur')), $this->validation->cost_type, 'class="form-control"')?> 
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('recur')?></label>
        		<div class="col-lg-5">
        			<input name="recur" id="recur" type="text"  value="<?=$this->validation->recur?>" class="form-control" />
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