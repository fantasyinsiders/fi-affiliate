<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($modules)): ?>
<div class="alert alert-warning">
	<h2 class="text-warning"><?=$this->lang->line('no_modules_found')?></h2>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    	<a href="<?=admin_url()?>affiliate_groups/add_group" class="btn btn-warning"><?=$this->lang->line('add_affiliate_group')?></a>
    </p>
</div>
<?php else: ?>
<div class="row">
    <div class="col-md-4">
    <?=_generate_sub_headline('affiliate_marketing_tools', $total)?>
    </div>
    <div class="col-md-8 text-right">
        <a href="<?=admin_url()?>modules/add_module/affiliate_marketing" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('install_affiliate_marketing_module')?></span></a>
    </div>
</div>
<hr />
<div class="row">
	<?php foreach ($modules as $v): ?>
    <div class="col-lg-4  member-box">
    	<div class="box-info wrapper">
        	<div class="ribbon-wrapper-green">
			<?php if ($v['module_status'] == 1): ?>
       		<div class="ribbon-green"><?=$this->lang->line('active')?></div>
			<?php else: ?>
            <div class="ribbon-yellow"><?=$this->lang->line('inactive')?></div>
            <?php endif; ?>
            </div>
        	<div class="row">
                <div class="col-sm-4 text-center"> 
					<a href="<?=modules_url() . 'module_' . $v['module_type'] . '_' . $v['module_file_name']?>/view">
                    <?php if (file_exists($base_physical_path . '/images/modules/module_' . $v['module_type'] . '_' . $v['module_file_name'] . '.' . $member_marketing_tool_ext)): ?>
                    <img src="<?=base_url() . 'images/modules/module_' . $v['module_type'] . '_' . $v['module_file_name'] . '.' . $member_marketing_tool_ext?>" />
                    <?php else: ?>
                    <img src="<?=base_url()?>images/modules/tools.png"  />
                    <?php endif; ?>
                    </a>
                </div>
                <hr class="visible-xs" />
                <div class="col-sm-8">
                    <h5 class="member-name"><a href="<?=modules_url() . 'module_' . $v['module_type'] . '_' . $v['module_file_name']?>/view"><?=$this->lang->line(str_replace(' ', '_', $v['module_name']))?></a></h5>
                    <p><?=$this->lang->line(str_replace(' ', '_', $v['module_description']))?></p>
                    <hr />
                    <div class="text-right">
                      <?php if ($v['module_status'] == '0'): ?>
                          <a href="<?=admin_url()?>affiliate_marketing/set_default/<?=$v['module_id']?>" class="btn btn-default"><i class="fa fa-exclamation-triangle"></i></a>
                          <?php else: ?>
                          <a href="<?=admin_url()?>affiliate_marketing/set_default/<?=$v['module_id']?>" class="btn btn-default"><i class="fa fa-check"></i></a>
                          <?php endif; ?>
                           <a href="<?=modules_url() . 'module_' . $v['module_type'] . '_' . $v['module_file_name']?>/view" class="btn btn-default" title="<?=$this->lang->line('view')?>"><i class="fa fa-search"></i></a>
                          <a href="<?=modules_url() . 'module_' . $v['module_type'] . '_' . $v['module_file_name']?>/add" class="btn btn-default" title="<?=$this->lang->line('add')?>"><i class="fa fa-plus"></i></a>
                      </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>