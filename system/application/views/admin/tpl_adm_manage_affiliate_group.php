<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form"  method="post" class="form-horizontal"  role="form">
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline($module)?>
    </div>
    <div class="col-md-8 text-right">
		<?php if ($function == 'update_group'): ?>
        <?=_previous_next('previous', 'affiliate_groups', $this->validation->group_id);?>
        <?php if ($this->validation->group_id != 1): ?>
        <a data-href="<?=admin_url()?>affiliate_groups/delete_group/<?=$this->validation->group_id?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i> <span class="hidden-xs"><?=$this->lang->line('delete')?></span></a>
        <?php endif; ?>
		<?php endif; ?>
        
        <a href="<?=admin_url()?>affiliate_groups/view_groups" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_affiliate_groups')?></span></a>
        <?php if ($function == 'update_group'): ?>
        <?=_previous_next('next', 'affiliate_groups', $this->validation->group_id);?>
        <?php endif; ?>
    </div>
</div>
<hr />
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">
        	<h4 class="header"><i class="fa fa-cog"></i> <?=$this->lang->line($function)?></h4>
        	<div class="form-group">
    		    <label for="aff_group_name" class="col-sm-3 control-label"><?=$this->lang->line('group_name')?></label>
        		<div class="col-lg-5">
        			<input name="aff_group_name" id="aff_group_name" type="text"  value="<?=$this->validation->aff_group_name?>" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="aff_group_code" class="col-sm-3 control-label"><?=$this->lang->line('group_code')?></label>
        		<div class="col-lg-5">
        			<input name="aff_group_code" id="aff_group_code" type="text"  value="<?=$this->validation->aff_group_code?>" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="aff_group_description" class="col-sm-3 control-label"><?=$this->lang->line('group_description')?></label>
        		<div class="col-lg-5">
        			<textarea name="aff_group_description" id="aff_group_description" class="form-control"><?=$this->validation->aff_group_description?></textarea>
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="ppc_amount" class="col-sm-3 control-label"><?=$this->lang->line('ppc_amount')?></label>
        		<div class="col-lg-5">
        			<input name="ppc_amount" id="ppc_amount" type="text"  value="<?=$this->validation->ppc_amount?>" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="cpm_amount" class="col-sm-3 control-label"><?=$this->lang->line('cpm_amount')?></label>
        		<div class="col-lg-5">
        			<input name="cpm_amount" id="cpm_amount" type="text"  value="<?=$this->validation->cpm_amount?>" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="commission_type" class="col-sm-3 control-label"><?=$this->lang->line('commission_type')?></label>
        		<div class="col-lg-5">
        			<?=form_dropdown('commission_type', array('flat' => $this->lang->line('flat'), 'percent' => $this->lang->line('percent')), $this->validation->commission_type, 'class="form-control"')?>
        		</div>
        	</div>
            <hr />
            <div class="form-group">
        		<div class="col-md-offset-3 col-md-5">
        			 <label for="commission_per_levels" class="control-label"><?=$this->lang->line('commission_per_levels')?></label>
        		</div>
        	</div>
            <div class="form-group">
        		<div class="col-md-offset-3 col-sm-5">
        			<div class="row">
                    	<div class="col-md-2 text-center">
                        	<label for="commission_per_levels" class="control-label"><?=$this->lang->line('level_1')?></label>
                        	<input name="commission_level_1" type="text"  class="form-control" value="<?=$this->validation->commission_level_1?>" />
                        </div>
                        <div class="col-md-2 text-center">
                        	<label for="commission_per_levels" class="control-label"><?=$this->lang->line('level_2')?></label>
                        	<input name="commission_level_2" type="text"  class="form-control" value="<?=$this->validation->commission_level_2?>" />
                        </div>
                        <div class="col-md-2 text-center">
                        	<label for="commission_per_levels" class="control-label"><?=$this->lang->line('level_3')?></label>
                        	<input name="commission_level_3" type="text"  class="form-control" value="<?=$this->validation->commission_level_3?>" />
                        </div>
                        <div class="col-md-2 text-center">
                        	<label for="commission_per_levels" class="control-label"><?=$this->lang->line('level_4')?></label>
                        	<input name="commission_level_4" type="text"  class="form-control" value="<?=$this->validation->commission_level_4?>" />
                        </div>
                        <div class="col-md-2 text-center">
                        	<label for="commission_per_levels" class="control-label"><?=$this->lang->line('level_5')?></label>
                        	<input name="commission_level_5" type="text"  class="form-control" value="<?=$this->validation->commission_level_5?>" />
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-2 text-center">
                        	<label for="commission_per_levels" class="control-label"><?=$this->lang->line('level_6')?></label>
                        	<input name="commission_level_6" type="text"  class="form-control" value="<?=$this->validation->commission_level_6?>" />
                        </div>
                        <div class="col-md-2 text-center">
                        	<label for="commission_per_levels" class="control-label"><?=$this->lang->line('level_7')?></label>
                        	<input name="commission_level_7" type="text"  class="form-control" value="<?=$this->validation->commission_level_7?>" />
                        </div>
                        <div class="col-md-2 text-center">
                        	<label for="commission_per_levels" class="control-label"><?=$this->lang->line('level_8')?></label>
                        	<input name="commission_level_8" type="text"  class="form-control" value="<?=$this->validation->commission_level_8?>" />
                        </div>
                        <div class="col-md-2 text-center">
                        	<label for="commission_per_levels" class="control-label"><?=$this->lang->line('level_9')?></label>
                        	<input name="commission_level_9" type="text"  class="form-control" value="<?=$this->validation->commission_level_9?>" />
                        </div>
                        <div class="col-md-2 text-center">
                        	<label for="commission_per_levels" class="control-label"><?=$this->lang->line('level_10')?></label>
                        	<input name="commission_level_10" type="text"  class="form-control" value="<?=$this->validation->commission_level_10?>" />
                        </div>
                    </div>
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