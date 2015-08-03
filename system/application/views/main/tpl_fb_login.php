<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="row">
    <div class="col-lg-12">
        <div id="login_response" class="ajax_response"><?=$show_message?></div> 
        <?=$fb_init?>
        <div id="fbLogin" class="marginOne">
        <h2><?=$this->lang->line('thank_you_login')?></h2>
        <?php if ($this->config->item('fb_session_enabled')): ?>
            <p><img src="themes/main/<?=$default_theme?>/images/gateway-loader.gif" alt="" onload="window.location='<?=site_url('members')?>'"/></p>
            <p><a href="<?=site_url('members')?>"><?=$this->lang->line('please_wait_forwarding')?></a></p>
        
        <?php else: ?>
            <p><img src="themes/main/<?=$default_theme?>/images/gateway-loader.gif" alt="" /></p>
            <p><?=$this->lang->line('please_wait_forwarding')?></p>
        <?php endif; ?>
        
         <p align="center" style="font-size: 22px; color: red; display: none; margin-top: 2em;" id="message"><a href="javascript:history.go(0)"><?=$this->lang->line('redirect_after_few_moments')?></a></p>
         
        </div>
    </div>
</div>
<script>
$(document).ready(function(){

	$('#message').delay(10000).fadeIn(2000);
	setTimeout(function(){
                        window.location.reload();
                    }, 3000);  

});
</script>