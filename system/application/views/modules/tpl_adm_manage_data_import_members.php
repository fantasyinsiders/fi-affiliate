<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form"  method="post" class="form-horizontal"  role="form" enctype="multipart/form-data">
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline('data_import')?>
    </div>
    <div class="col-md-8 text-right">        
        <a href="<?=admin_url()?>import/view_import_modules" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('data_import_modules')?></span></a>
    </div>
</div>
<hr />
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">
        <h4 class="header"><i class="fa fa-cog"></i> <?=$this->lang->line('import_members')?></h4>
        	<div class="form-group"> 
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('enable_file_path')?></label>
        		<div class="col-lg-5">
        			<?=form_dropdown('enable_file_path', array( 'no' => $this->lang->line('no'), 'yes' => $this->lang->line('yes')), $this->validation->enable_file_path, 'class="form-control"')?>
        		</div>
        	</div>
            <hr />
            <div class="form-group"> 
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('file_path')?></label>
        		<div class="col-lg-5">
        			<input name="file_path" id="file_path" type="text"  value="<?=$this->validation->file_path?>" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group"> 
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('upload_file')?> - <?=$this->lang->line('csv_txt_file_only') ?>
                </label>
        		<div class="col-lg-5">
        			<input type="file" name="userfile" size="40"  class="btn btn-default"/>
                    <small><?=$this->lang->line('max_file_upload')?></small>
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