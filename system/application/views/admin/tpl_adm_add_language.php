<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form"  method="post" class="form-horizontal"  role="form" enctype="multipart/form-data">
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline('manage_language')?>
    </div>
    <div class="col-md-8 text-right">
        <a href="<?=admin_url()?>languages/view_languages" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_languages')?></span></a>
    </div>
</div>
<hr />
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">
        <h4 class="header"><i class="fa fa-cog"></i> <?=$this->lang->line($function)?></h4>
        	<div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('language')?></label>
        		<div class="col-lg-5">
        			<input type="text" name="name" id="name" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('code')?></label>
        		<div class="col-lg-5">
        			<input type="text" name="code" id="code" value="" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('country')?></label>
        		<div class="col-lg-5">
        			<?=form_dropdown('image', $flags_array, '', 'class="form-control"')?>
        		</div>
        	</div>
            <hr />
            <div class="alert alert-warning text-warning col-lg-7 col-lg-offset-3">
            	<p><?=$this->lang->line('desc_add_language_zip_1')?></p>
                <p><strong><?=APPPATH . 'languages/'?></strong></p>
                <p><?=$this->lang->line('desc_add_language_zip_2')?></p>
            </div>
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('zip_file')?></label>
        		<div class="col-lg-5">
        			<input type="file" name="zip_file" class="btn btn-default" title="<?=$this->lang->line('upload_file')?>"/>
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
<script>
$("#form").validate();
</script>