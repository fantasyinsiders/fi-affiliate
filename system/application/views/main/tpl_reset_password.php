<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<form id="prod_form" class="form-horizontal text-capitalize" role="form" method="post">
<div class="col-md-6 col-md-offset-3">
	<div class="panel panel-default">
    	<div class="panel-heading">
        	<h4 class="text-capitalize border"><?=$this->lang->line('reset_password')?></h4>
        </div>
    	<div class="panel-body">
            <div class="form-group">
                <label class="col-lg-4 control-label"><?=$this->lang->line('email_address')?></label>
                <div class="col-lg-6">
                    	<input name="primary_email" type="text"  placeholder="user@domain.com" class="form-control required email" />
                </div>
            </div>
            <hr />
            <div class="form-group">
                <div class="col-lg-offset-4 col-lg-8">
                    <p>
                        <button class="btn btn-info block-phone" id="top" type="submit"><i class="fa fa-key"></i> <?=$this->lang->line('reset_password')?></button>
                        <a  class="btn btn-info block-phone" href="<?=site_url()?>login"><i class="fa fa-unlock"></i> <?=$this->lang->line('login')?></a>
                    </p>
                    <?php if (!empty($fb_init)): ?>
                    <?php if ($this->config->item('fb_session_enabled')): ?>
                    <p>
                    <a href="">
                      <img src="<?=base_url('js')?>themes/main/<?=$default_theme?>/img/facebook-button.png" alt="" />
                    </a>
                    </p>   
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
     <div class="text-center">
        	<small><?=$this->lang->line('dont_have_an_account')?> <a href="<?=site_url()?>registration/<?=$prg_signup_link?>"><?=$this->lang->line('create_account')?></a></small>
    	</div>
</div>
</form>