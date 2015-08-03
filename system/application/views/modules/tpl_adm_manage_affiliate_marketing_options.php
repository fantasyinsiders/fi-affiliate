<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($sts_config)): ?>
<div class="alert alert-warning">
	<h3><i class="fa fa-warning"></i> <?=$this->lang->line('no_options_found')?></h3>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    </p>
</div>
<?php else: ?>
<form id="ajax-form"  method="post" class="form-horizontal"  role="form">
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline($page_title)?>
    </div>
    <div class="col-md-8 text-right">
        <a href="<?=admin_url()?>affiliate_marketing/view_affiliate_tools" class="btn btn-primary"><i class="fa fa-chevron-left"></i> <span class="hidden-xs"><?=$this->lang->line('affiliate_marketing_tools')?></span></a>
    </div>
</div>
<hr />
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">
			<?php foreach ($sts_config as $v): ?>
        	<div class="form-group">
    		    <label for="<?=$this->lang->line($v['settings_key'])?>" class="col-sm-3 control-label"><?=$this->lang->line($v['settings_key'])?></label>
        		<div class="col-lg-5">
        			<?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
        		</div>
        	</div>
            <hr />
            <?php endforeach; ?>
            <div class="form-group">
                <div class="col-md-offset-3 col-sm-9">
                    <button class="btn btn-success block-phone" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button>  
                </div>
            </div>
    	</div>
	</div>  
</div>    
</form>    
<?php endif; ?>