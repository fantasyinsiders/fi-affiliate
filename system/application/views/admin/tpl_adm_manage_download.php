<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form"  method="post" class="form-horizontal"  role="form" enctype="multipart/form-data">
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline($module)?>
    </div>
    <div class="col-md-8 text-right">
		<?php if ($function == 'update_download'): ?>
        <?=_previous_next('previous', 'downloads', $this->validation->id);?>
         <a data-href="<?=admin_url()?>downloads/delete_download/<?=$this->validation->id?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i> <span class="hidden-xs"><?=$this->lang->line('delete')?></span></a>
        <a href="<?=admin_url()?>downloads/add_download" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add')?></span></a>
		<?php endif; ?>
        
        <a href="<?=admin_url()?>downloads/view_downloads" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_downloads')?></span></a>
        <?php if ($function == 'update_download'): ?>
        <?=_previous_next('next', 'downloads', $this->validation->id);?>
        <?php endif; ?>
    </div>
</div>
<hr />
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">
        <h4 class="header"><i class="fa fa-cog"></i> <?=$this->lang->line($function)?></h4>
        <div class="alert alert-warning"><?=$this->lang->line('five_files_per_download')?></div>
        	<div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('status')?></label>
        		<div class="col-lg-5">
        			<?=form_dropdown('status', array('0' => $this->lang->line('inactive'), '1' => $this->lang->line('active')), $this->validation->status, 'class="form-control"')?>
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('download_name')?></label>
        		<div class="col-lg-5">
        			<input name="download_name" id="download_name" type="text"  value="<?=$this->validation->download_name?>" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('description')?></label>
        		<div class="col-lg-5">
        			<textarea name="description" id="description" class="form-control" rows="5"><?=$this->validation->description?></textarea>
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('restrict_group')?></label>
        		<div class="col-lg-5">
        			<?=form_dropdown('group_id', $aff_groups, $this->validation->group_id, 'class="form-control"')?>
        		</div>
        	</div>
            <hr />
			<div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('download_location_1')?></label>
        		<div class="col-sm-4">
        			<input name="download_location_1" id="download_location_1" type="text"  value="<?=$this->validation->download_location_1?>" class="form-control" />
        		</div>
                <div class="col-lg-2">
                	<input type="file" name="userfile1" class="btn btn-default" title="<?=$this->lang->line('upload_file')?>"/>
                </div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('download_location_2')?></label>
        		<div class="col-sm-4">
        			<input name="download_location_2" id="download_location_2" type="text"  value="<?=$this->validation->download_location_2?>" class="form-control" />
        		</div>
                <div class="col-lg-2">
                	<input type="file" name="userfile2" class="btn btn-default" title="<?=$this->lang->line('upload_file')?>"/>
                </div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('download_location_3')?></label>
        		<div class="col-sm-4">
        			<input name="download_location_3" id="download_location_3" type="text"  value="<?=$this->validation->download_location_3?>" class="form-control" />
        		</div>
                <div class="col-lg-2">
                	<input type="file" name="userfile3" class="btn btn-default" title="<?=$this->lang->line('upload_file')?>"/>
                </div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('download_location_4')?></label>
        		<div class="col-sm-4">
        			<input name="download_location_4" id="download_location_4" type="text"  value="<?=$this->validation->download_location_4?>" class="form-control" />
        		</div>
                <div class="col-lg-2">
                	<input type="file" name="userfile4" class="btn btn-default" title="<?=$this->lang->line('upload_file')?>"/>
                </div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('download_location_5')?></label>
        		<div class="col-sm-4">
        			<input name="download_location_5" id="download_location_5" type="text"  value="<?=$this->validation->download_location_5?>" class="form-control" />
        		</div>
                <div class="col-lg-2">
                	<input type="file" name="userfile5" class="btn btn-default" title="<?=$this->lang->line('upload_file')?>"/>
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
 <input type="hidden" name="program_id" value="1"/>
</form>