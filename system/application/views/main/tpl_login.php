<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?=$fb_init?>
<form id="form" class="form-horizontal text-capitalize" role="form" method="post">
<div class="col-md-6 col-md-offset-3">
	<div class="panel panel-default">
    	<div class="panel-heading">
        	<h4><?=$this->lang->line('member_login')?></h4>
        </div>
    	<div class="panel-body">
            <div class="form-group">
                <label class="col-lg-4 control-label"><?=$this->lang->line('email_address')?></label>
                <div class="col-lg-6">
                    	<input name="username" type="text"  value="<?=$this->validation->username?>" placeholder="user@domain.com" class="form-control required email" />
                </div>
            </div>
            <hr />
            <div class="form-group">
                <label class="col-lg-4 control-label"><?=$this->lang->line('password')?></label>
                <div class="col-lg-6">
                   		<input name="password" type="password" placeholder="***********" class="form-control required" />
                </div>          
            </div>
            <hr />
            <div class="form-group">
                <div class="col-lg-offset-4 col-lg-8">
                    <p>
                        <button class="btn btn-info block-phone" id="top" type="submit"><i class="fa fa-unlock"></i> <?=$this->lang->line('login')?></button>
                        <a  class="btn btn-info block-phone" href="<?=site_url('reset_password')?>"><i class="fa fa-key"></i> <?=$this->lang->line('reset_password')?></a>
                    </p>
                </div>
            </div>
        </div>
    </div>
     <div class="text-center">
        	<small><?=$this->lang->line('dont_have_an_account')?> <a href="<?=site_url()?>registration/<?=$prg_signup_link?>"><?=$this->lang->line('create_account')?></a></small>
    	</div>
</div>
</form>