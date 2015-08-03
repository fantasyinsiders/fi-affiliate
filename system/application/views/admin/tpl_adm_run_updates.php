<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline('system_updates')?>
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
                    <div id="msg" class="msg">
                        <p>This software will try and download the latest system updates from the server.  <strong>This may overwrite any custom coding that you may have done on the /system files. </strong> This does not overwrite any files in the /themes folder or custom CSS files.
                        </p>
                    </div>
                    <div id="wait" style="display:none;">     
                         <div class="progress progress-striped active">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                         	   <span class="sr-only">60% Complete</span>
                        	</div>
                    	</div>
                    	<p><?=$this->lang->line('please_wait')?></p>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-3 col-sm-9">
                    <button class="btn btn-success block-phone" id="confirm"><i class="fa fa-refresh"></i> <?=$this->lang->line('click_here_to_continue')?></button>     
                    <a href="javascript:location.reload()" id="restart" class="btn btn-success block-phone" style="display:none;"><i class="fa fa-refresh"></i> <span class="hidden-xs"><?=$this->lang->line('restart_update')?></span></a>     
                </div>
            </div>
    	</div>        
	</div>
</div>
<script language="JavaScript" type="text/javascript">
$(document).ready(function(){   
	$('#confirm').click(function() {
		$('#msg').hide(); 
		$('#wait').show(); 
		$("#content").load("<?=admin_url();?>settings/check_updates");	
		$('#confirm').hide(); 
		$('#restart').show(); 
	});
});
</script>
