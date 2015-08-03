<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form"  method="post" class="form-horizontal"  role="form">
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
        <h4 class="header"><i class="fa fa-cog"></i> <?=$this->lang->line($page_title)?></h4>
        	 <?php foreach ($sts_config as $v): ?>            
            <div class="form-group"> 
    		    <label class="col-lg-3 control-label"><?=$this->lang->line($v['settings_key'])?></label>
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