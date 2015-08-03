<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<form id="form" class="form-horizontal text-capitalize" role="form" method="post">
<div class="col-md-6 col-md-offset-3">
	<div class="panel panel-default">
    	<div class="panel-heading">
        	<h4 class="text-capitalize border"><?=$this->lang->line('reset_password')?></h4>
        </div>
    	<div class="panel-body">
            <div class="form-group">
                <label class="col-lg-4 control-label"><?=$this->lang->line('new_password')?></label>
                <div class="col-lg-6">
                    	<input name="password" type="password" class="form-control required" />
                </div>
            </div>
            <hr />
            <div class="form-group">
                <label class="col-lg-4 control-label"><?=$this->lang->line('confirm_password')?></label>
                <div class="col-lg-6">
                    	<input name="passconf" type="password" class="form-control required" />
                </div>
            </div>
            <hr />
            <div class="form-group">
                <div class="col-lg-offset-4 col-lg-8">
                    <p>
                        <button class="btn btn-info block-phone" id="top" type="submit"><i class="fa fa-key"></i> <?=$this->lang->line('reset_password')?></button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</form>