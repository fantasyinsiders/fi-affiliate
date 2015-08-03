<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form"  method="post" class="form-horizontal"  role="form">
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline('manage_members')?>
    </div>
    <div class="col-md-8 text-right">
        <a href="<?=admin_url()?>members/view_members" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_members')?></span></a>
    </div>
</div>
<hr />
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">
        <h4 class="header"><i class="fa fa-cog"></i> <?=$this->lang->line($function)?></h4>
        	<div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('first_name')?></label>
        		<div class="col-lg-5">
        			<input name="fname" id="fname" type="text"  value="<?=$this->validation->fname?>" class="form-control required" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('last_name')?></label>
        		<div class="col-lg-5">
        			<input name="lname" id="lname" type="text"  value="<?=$this->validation->lname?>" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('username')?></label>
        		<div class="col-lg-5">
        			<input name="username" id="username" type="text"  value="<?=$this->validation->username?>" class="form-control required" />
        		</div>
        	</div>
            <hr />
			<div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('email')?></label>
        		<div class="col-lg-5">
        			<input name="primary_email" id="primary_email" type="text"  value="<?=$this->validation->primary_email?>" class="form-control required email" placeholder="user@domain.com" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
                <label class="col-lg-3 control-label"><?=$this->lang->line('password')?></label>
                <div class="col-lg-5">
                    <input name="password" id="password" type="password" class="form-control required"  />
                </div>
            </div>
            <hr />
			<div class="form-group">
                <label class="col-lg-3 control-label"><?=$this->lang->line('confirm_password')?></label>
                <div class="col-lg-5">
                    <input name="passconf" id="passconf" type="password" class="form-control required"  />
                </div>
            </div>
            <hr />
            <div class="form-group">
                <label class="col-lg-3 control-label"><?=$this->lang->line('send_welcome_email')?></label>
                <div class="col-lg-5">
                    <?=form_dropdown('send_welcome_email', array('0' => $this->lang->line('no'), '1' => $this->lang->line('yes')), $this->validation->send_welcome_email, 'class="form-control"')?>
                </div>    
            </div>
            <hr />
            <div class="form-group">
                <div class="col-md-offset-3 col-sm-9">
                    <button class="btn btn-success block-phone" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('continue')?></button>        
                </div>
            </div>
		</div>
    </div>
</div>
</form>
<script>
$("#form").validate();
</script>