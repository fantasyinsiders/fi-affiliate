<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline($module)?>
    </div>
    <div class="col-md-8 text-right">
        <a href="<?=admin_url()?>settings#system" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('global_configuration')?></span></a>      
    </div>
</div>
<hr />
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">
        	<h4 class="header"><i class="fa fa-cog"></i> <?=$this->lang->line($function)?></h4>
        	<div class="form-group">           	
                <div id="content" class="alert alert-success text-success">
                    <div id="wait">
                        <div class="progress progress-striped active">
                          <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                            <span class="sr-only">60% Complete</span>
                          </div>
                        </div>
                        <p><?=$this->lang->line($text)?></p>
                    </div>
                </div>
                <p><a href="<?=admin_url()?>settings#system" class="btn btn-success"><i class="fa fa-caret-left"></i> <?=$this->lang->line('go_back')?></a></p>
            </div>
    	</div>        
	</div>
</div>
<script language="JavaScript" type="text/javascript">
$(function(){
	$("#content").load("<?=admin_url();?>settings/<?=$type?>");
});
</script>



