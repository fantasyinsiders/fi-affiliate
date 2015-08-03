<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="col-lg-9 col-md-8">
	<?=$prg_program_description?>
    <hr />
    <h4 class="text-center text-capitalize"><?=$this->lang->line('dont_have_an_account')?> <a href="<?=base_url('js')?>registration/<?=$prg_signup_link?>"><?=$this->lang->line('create_account')?></a></h4>
</div>
<div class="col-lg-3 col-md-4 hidden-xs">
	<div class="panel panel-default">
    	<div class="panel-heading">
        	<h4 class="text-capitalize border"><?=$this->lang->line('member_login')?></h4>
        </div>
    	<div class="panel-body">
            <form id="form" class="text-capitalize" role="form" method="post" action="<?=base_url('js')?>login">
            <?php if (!empty($show_message)): ?>
            <div class="alert alert-danger animated shake hover-msg"><?=$show_message?></div>
            <?php endif; ?>
            <div class="form-group">
                <label class="control-label required email"><?=$this->lang->line('email_address')?></label>
                <input name="username" type="text"  class="form-control required email" />
            </div>
            <div class="form-group">
                <label class="control-label"><?=$this->lang->line('password')?></label>
                <input name="password" type="password" class="form-control required" />
            </div>
            <hr />
            <div class="form-group text-center">
                <button class="btn btn-info block-phone" id="top" type="submit"><i class="fa fa-key"></i> <?=$this->lang->line('login')?></button>
                <a  class="btn btn-info block-phone" href="<?=base_url('js')?>reset_password"><i class="fa fa-unlock"></i> <?=$this->lang->line('reset_password')?></a>
            </div>
            </form>
        </div>
    </div>
</div>