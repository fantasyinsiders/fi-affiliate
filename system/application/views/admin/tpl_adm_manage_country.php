<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form"  method="post" class="form-horizontal"  role="form">
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline($module)?>
    </div>
    <div class="col-md-8 text-right">
		<?php if ($function == 'update_country'): ?>
        <?=_previous_next('previous', 'countries', $this->validation->id);?>
         <a data-href="<?=admin_url()?>countries/delete_country/<?=$this->validation->id?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i> <span class="hidden-xs"><?=$this->lang->line('delete')?></span></a>
        <a href="<?=admin_url()?>countries/add_country" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add_country')?></span></a>
		<?php endif; ?>
        
        <a href="<?=admin_url()?>countries/view_countries" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_countries')?></span></a>
        <?php if ($function == 'update_country'): ?>
        <?=_previous_next('next', 'countries', $this->validation->id);?>
        <?php endif; ?>
    </div>
</div>
<hr />
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">
        <h4 class="header"><i class="fa fa-cog"></i> <?=$this->lang->line($function)?></h4>
        	<div class="form-group">
    		    <label for="country_name" class="col-sm-3 control-label"><?=$this->lang->line('country_name')?></label>
        		<div class="col-lg-5">
        			<input name="country_name" id="country_name" type="text"  value="<?=$this->validation->country_name?>" class="form-control" placeholder="<?=$this->lang->line('country_name')?>" />
        		</div>
        	</div>
            <hr />
			<div class="form-group">
    		    <label for="country_iso_code_2" class="col-sm-3 control-label"><?=$this->lang->line('iso_2')?></label>
        		<div class="col-lg-5">
        			<input name="country_iso_code_2" id="country_iso_code_2" type="text"  value="<?=$this->validation->country_iso_code_2?>" class="form-control"  />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="country_iso_code_3" class="col-sm-3 control-label"><?=$this->lang->line('iso_3')?></label>
        		<div class="col-lg-5">
        			<input name="country_iso_code_3" id="country_iso_code_3" type="text"  value="<?=$this->validation->country_iso_code_3?>" class="form-control"  />
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