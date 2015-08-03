<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($module)): ?>
<div class="alert alert-warning">
	<h3><i class="fa fa-warning"></i> <?=$this->lang->line('no_modules_found')?></h3>
    <p><a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a></p>
</div>
<?php else: ?>
<div class="row">
    <div class="col-md-4">
    	<?=_generate_sub_headline('data_export')?>
    </div>
    <div class="col-md-8 text-right">
        <a href="<?=admin_url()?>modules/add_module/data_export" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('install_export_module')?></span></a>
    </div>
</div>
<hr />
<div class="row" id="data-content">  
   	<div class="col-md-12">
        <div class="box-info">
			<?php foreach($module as $v):?>
            <div class="row">
                <div class="col-md-7">
                    <h5><strong><?=$v['module_name']?></strong></h5>
                    <p><?=$v['module_description']?></p>
                </div>
                <div class="col-md-5 text-right">
                	<a href="<?=modules_url() . 'module_' . $v['module_type'] . '_' . $v['module_file_name'] . '/update/' . $v['module_id']?>" class="btn btn-info block-phone"><i class="fa fa-cog"></i> <?=$this->lang->line('export_settings')?></a>
                    <a href="<?=modules_url() . 'module_' . $v['module_type'] . '_' . $v['module_file_name'] . '/run_module/' . $v['module_id']?>" class="btn btn-info block-phone"><i class="fa fa-download"></i> <?=$this->lang->line('start_export')?></a>
                </div>
            </div>
            <hr />
            <?php endforeach; ?>
        </div>
    </div>
</div>
</form>
<?php endif; ?>


