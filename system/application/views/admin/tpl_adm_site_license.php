<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="prod_form"  method="post" class="form-horizontal"  role="form">
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline('system_license')?>
    </div>
    <div class="col-md-8 text-right">    
        <a href="<?=admin_url()?>settings" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('global_configuration')?></span></a>
    </div>
</div>
<hr />
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">
        	<h4 class="header"><i class="fa fa-cog"></i> <?=$this->lang->line($page_title)?></h4>
            <?php if (!empty($jam_license_alert)):?>
			<div class="alert alert-warning"><?=$this->lang->line('enter_license_key_to_unlock_no_need_to_reinstall')?></div>
			<?php endif; ?>
            <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('base_license_domain_name')?>"><?=$this->lang->line('base_domain_name')?></span></label>
                <div class="col-lg-5">
                   <input type="text" name="base_domain_name" value="<?=$this->validation->base_domain_name?>" class="form-control" />
                </div>
            </div>
            <hr />
            <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('base_subdomain_name')?>"><?=$this->lang->line('base_subdomain_name')?></span></label>
                <div class="col-lg-5">
                    <input type="text" name="base_subdomain_name" value="<?=$this->validation->base_subdomain_name?>" class="form-control" />
                </div>
            </div>
            <hr />
            <?php foreach ($frm_license_settings as $v): ?>
            <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('desc_' . $v['settings_key'])?>"><?=$this->lang->line($v['settings_key'])?></span></label>
                <div class="col-lg-5">
                      <?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
                </div>
            </div>
            <hr />
            <?php endforeach; ?>
            <div class="form-group"> <label class="col-lg-3 control-label"><?=$this->lang->line('installation_code')?></label>
                <div class="col-lg-5">
                     <p class="form-control-static"><?=$this->config->item('sts_site_jam_installation_id')?></p>
                </div>
            </div>
            <hr />
            <div class="form-group"> <label class="col-lg-3 control-label"><?=$this->lang->line('affiliate_manager_version')?></label>
                <div class="col-lg-5">
                     <p class="form-control-static"><?=APP_VERSION?></p>
                </div>
            </div>
            <hr />
            <div class="form-group"> <label class="col-lg-3 control-label"><?=$this->lang->line('server_software')?></label>
                <div class="col-lg-5">
                     <p class="form-control-static"><?=$_SERVER['SERVER_SOFTWARE']?></p>
                </div>
            </div>
            <hr />
            <div class="form-group"> <label class="col-lg-3 control-label"><?=$this->lang->line('php_version')?></label>
                <div class="col-lg-5">
                     <p class="form-control-static"><?=phpversion()?></p>
                </div>
            </div>
            <hr />
            <div class="form-group"> <label class="col-lg-3 control-label"><?=$this->lang->line('mysql_version')?></label>
                <div class="col-lg-5">
                     <p class="form-control-static"><?=$this->db->version()?></p>
                </div>
            </div>
            <hr />
            <div class="form-group"> <label class="col-lg-3 control-label"><?=$this->lang->line('base_url')?></label>
                <div class="col-lg-5">
                     <p class="form-control-static"><?=$base_url?></p>
                </div>
            </div>
            <hr />
            <div class="form-group"> <label class="col-lg-3 control-label"><?=$this->lang->line('base_physical_path')?></label>
                <div class="col-lg-5">
                     <p class="form-control-static"><?=$base_physical_path?></p>
                </div>
            </div>
            <hr />
            <div class="form-group"> <label class="col-lg-3 control-label"><?=$this->lang->line('view_phpinfo')?></label>
                <div class="col-lg-5">
                     <a href="javascript:void(window.open('<?=admin_url().'settings/view_phpinfo'?>', 'popup', 'width=750,height=600, location=no, menubar=no, status=no,toolbar=no, scrollbars=yes, resizable=yes'))" class="btn btn-default"><i class="fa fa-search"></i> <?=$this->lang->line('click_to_view')?></a>
                </div>
            </div>
            <hr />
         	<div class="col-md-5 col-md-offset-3">
                <button class="btn btn-success btn-lg" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button>
                <a href="<?=admin_url()?>license/reset_license" class="btn btn-warning btn-lg"><i class="fa fa-exclamation-triangle"></i> <?=$this->lang->line('reset')?></a>
            </div>
		</div>
	</div>
</div>
<input type="hidden" name="check" value="1" />
</form>