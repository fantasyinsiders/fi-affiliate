<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="col-lg-<?php if (!empty($fb_init)): ?>8<?php else: ?>12<?php endif; ?>">
	<div class="panel panel-default">
    	<div class="panel-heading">
        	<h4 class="text-capitalize border"><small class="pull-right">* <?=$this->lang->line('required_fields')?></small> <?=$this->lang->line('register_now_become_affiliate')?></h4>
        </div>
    	<div class="panel-body">
            <form id="form" role="form" class="form-horizontal" method="post"> 
            <br />
            <?php if (check_tracking_cookie() == true): ?>
            <div class="form-group">
                <label class="col-lg-3 control-label text-capitalize"><?=$this->lang->line('referred_by')?></label>
                <div class="col-lg-5">
                    <p class="form-control">
                        <?=$sponsor_referring_username?>
                        <?=form_hidden('sponsor', $sponsor_referring_username)?>
                    </p>
                </div>
            </div>
            <hr />
            <?php else: ?>
            <?php if ($this->config->item('sts_affiliate_require_referral_code') == 1): ?>
            <div class="form-group">
                <label class="col-lg-3 control-label text-capitalize"><?=$this->lang->line('referred_by')?></label>
                <div class="col-lg-5">
               		<?=$sponsor?> 
                </div>
            </div>
            <hr />
            <?php endif; ?>
            <?php endif; ?>
        
            <?php foreach ($form_fields as $k => $v): ?>
            
            <?php if ($v['form_name'] == 'show_tos'): ?>
            <div class="form-group">
                <label class="col-lg-3 control-label text-capitalize"><?=$v['form_description']?></label>
                <div class="col-lg-<?php if (!empty($fb_init)): ?>9<?php else: ?>7<?php endif; ?>">
                    <div class="terms"><?=$prg_terms_of_service?></div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-5 col-md-offset-3">
                    <label class="text-capitalize">
                        <input type="radio" name="show_tos" value="" checked="checked"/>
                        <?=$this->lang->line('agree_with_tos_no')?>
                    </label>
                    <label class="text-capitalize">
                        <input type="radio" name="show_tos" value="1" class="required"/>
                        <?=$this->lang->line('agree_with_tos_yes')?>
                    </label>
                    <?=$this->validation->show_tos_error?>
                </div>
            </div>
            <?php else: ?>
            <div class="form-group">
                <label class="col-lg-3 control-label text-capitalize"><?=$v['form_description']?> <?=$v['required']?></label>
                <div class="col-lg-<?php if (!empty($fb_init)): ?>7<?php else: ?>5<?php endif; ?>">
                    <?=$v['form_field']?>
                </div>
            </div>
            <?php endif;?>
            <hr />
            <?php endforeach;?>
            <?php if (!empty($captcha)): ?>
            <div class="form-group">
                <label class="col-lg-3 control-label text-capitalize"><?=$this->lang->line('enter_text')?></label>
                <div class="col-lg-<?php if (!empty($fb_init)): ?>7<?php else: ?>5<?php endif; ?>">
                    <script type="text/javascript">
                    var RecaptchaOptions = {
                    theme :  '<?=$sts_sec_recaptcha_theme?>'
                    }
                    </script>
                    <?=$captcha?>
                    <?=$this->validation->recaptcha_response_field_error?>
                </div>
            </div>
            <?php endif; ?> 	        
            <div class="col-md-5 col-md-offset-3"><button class="btn btn-success btn-lg btn-block" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('submit')?></button></div>
            </form>
        </div>
	</div>
</div>
<?php if (!empty($fb_init)): ?>
<div class="col-lg-4">
	<div class="panel panel-default">
    	<div class="panel-heading">
        	<h4 class="text-capitalize border"><?=$this->lang->line('got_facebook_account')?></h4>
        </div>
    	<div class="panel-body">
            <p><?=$this->lang->line('got_facebook_login')?></p>
            <p class="text-center"><a href="<?=site_url()?>login/<?=$prg_signup_link?>"><img src="<?=base_url('js')?>themes/main/<?=$default_theme?>/img/facebook-button.png" alt="" /></a></p>
            <hr />
            <div class="fbLikeBox">
                <?=base64_decode($sts_site_facebook_page_code)?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>