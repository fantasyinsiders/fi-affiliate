<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline('manage_module')?>
    </div>
    <div class="col-md-8 text-right">
		<?php if ($function == 'update_module'): ?>
        <?=_previous_next('previous', 'modules', $this->validation->id);?>
         <a data-href="<?=admin_url()?>modules/delete_module/<?=$this->validation->id?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i> <span class="hidden-xs"><?=$this->lang->line('delete')?></span></a>
        <a href="<?=admin_url()?>modules/add_module" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add')?></span></a>
		<?php endif; ?>
        <a href="<?=admin_url()?>modules/view_modules" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_installed_modules')?></span></a>
        <?php if ($function == 'update_module'): ?>
        <?=_previous_next('next', 'modules', $this->validation->id);?>
        <?php endif; ?>
    </div>
</div>
<hr />
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">
        	<ul class="nav nav-tabs capitalize">
                <li class="active"><a href="#main" data-toggle="tab"><?=$this->lang->line($function)?></a></li>
                <?php if ($function == 'update_module'): ?>
                <?php if (!empty($module_config)): ?>
                <li><a href="#settings" data-toggle="tab"><?=$this->lang->line('module_settings')?></a></li>
            	<?php endif; ?>
                <?php endif; ?>
            </ul>
            <div class="tab-content">
                <div id="main" class="tab-pane fade in active">
                	<form id="form"  method="post" class="form-horizontal"  role="form" enctype="multipart/form-data">
                    <hr />        
                    <div class="form-group">
                        <label for="module_name" class="col-sm-3 control-label"><?=$this->lang->line('module_name')?></label>
                        <div class="col-lg-5">
                            <input name="module_name" id="module_name" type="text"  value="<?=$this->validation->module_name?>" class="form-control" />
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label for="module_file_name" class="col-sm-3 control-label"><?=$this->lang->line('module_file_name')?></label>
                        <div class="col-lg-5">
                            <?=form_dropdown('module_file_name', $module_list, $this->validation->module_file_name, 'class="form-control"')?>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label for="auto_approve" class="col-sm-3 control-label"><?=$this->lang->line('module_type')?></label>
                        <div class="col-lg-5">
                            <?=form_dropdown('module_type', $module_options, $this->validation->module_type, 'class="form-control"')?>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label for="module_description" class="col-sm-3 control-label"><?=$this->lang->line('module_description')?></label>
                        <div class="col-lg-5">
                            <textarea name="module_description" class="form-control" id="module_description" rows="4"><?=$this->validation->module_description?></textarea>
                        </div>
                    </div>
                    <hr />
                    <?php if ($function == 'add_module'): ?>
                    <div class="form-group">
                        <label for="module_name" class="col-sm-3 control-label"><?=$this->lang->line('module_zip_file')?></label>
                        <div class="col-lg-5">
                             <input type="file" name="zip_file" class="btn btn-default" title="<?=$this->lang->line('optional_upload_module_zip_file')?>" />
                        </div>
                    </div>
                    <hr />
                    <?php endif; ?>
                    <div class="form-group">
                        <div class="col-md-offset-3 col-sm-9">
                            <button class="btn btn-success block-phone" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button>
                            <?php if ($function == 'update_module'): ?>
                            <?php if ($this->validation->module_status == 1): ?>
                            <a href="<?=admin_url()?>modules/change_status/<?=$this->validation->id?>" class="btn btn-info"><i class="fa fa-check"></i> <?=$this->lang->line('active')?></a>
                            <?php else: ?>
                            <a href="<?=admin_url()?>modules/change_status/<?=$this->validation->id?>" class="btn btn-warning"><i class="fa fa-exclamation-triangle"></i> <?=$this->lang->line('inactive')?></a>
                            <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    </form>
				</div>
                <?php if ($function == 'update_module'): ?>
                <?php if (!empty($module_config)): ?>
                <div id="settings" class="tab-pane fade in">
                    <form id="form"  method="post" action="<?=modules_url()?>module_<?=$this->validation->module_type?>_<?=$this->validation->module_file_name?>/update/<?=$this->validation->id?>" class="form-horizontal"  role="form" enctype="multipart/form-data">
                    <hr />        
                    <?php foreach ($module_config as $v): ?>     
                    <?php if ($v['settings_type'] != 'hidden'): ?>       
                    <div class="form-group"> 
                        <label class="col-lg-3 control-label"><?=$this->lang->line(str_replace('module_' . $this->validation->module_type .'_' . $this->validation->module_file_name, ' ', $v['settings_key']))?></label>
                        <div class="col-lg-5">
                            <?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
                        </div>
                    </div>
                    <hr />
                    <?php endif; ?>
                    <?php endforeach; ?>
                    <div class="form-group">
                        <div class="col-md-offset-3 col-sm-9">
                            <button class="btn btn-success block-phone" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button>
                            
                        </div>
                    </div>
                    <input type="hidden" name="redirect" value="<?=$this->uri->uri_string()?>#settings" />
                    </form>
                </div>
				<?php endif; ?>
                <?php endif; ?>
            </div>
		</div><!-- end .box-info -->
    </div>
</div>