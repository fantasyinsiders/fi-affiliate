<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?=$editor_path?>
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline('email_member')?>
    </div>
    <div class="col-md-8 text-right">
    </div>
</div>
<hr />
<form id="prod_form"  method="post" class="form-horizontal form" role="form">
<div class="row">   
	<div class="col-md-12"> 
        <div class="box-info">
            <div class="form-group"> 
                <label class="col-lg-3 control-label"><?=$this->lang->line('to')?></label>
                <div class="col-lg-4">
                    <input name="recipient_email" type="text" class="form-control required email" readonly value="<?=$this->validation->recipient_email?>" />
                </div>
            </div>
            <hr />
            <div class="form-group">
                <label class="col-lg-3 control-label"><?=$this->lang->line('subject')?></label>
                <div class="col-lg-4">
                    <input name="subject" type="text" class="form-control required" value="<?=$this->validation->subject?>" />
                </div>
                 <label class="col-lg-1 control-label"><?=$this->lang->line('dynamic_tags')?></label>
                <div class="col-lg-2 text-right">
                      <?=_generate_dynamic_tags($module_name)?>           
                </div>
            </div>
            <hr />
            <div class="show-html">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8 html-editor col-md-offset-2">
                            <?=$editor?>
                        </div>
                    </div>
                </div>
                <hr />
            </div>
            <div class="col-md-5 col-md-offset-3">
            	<button class="btn btn-success btn-lg" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('send_now')?></button>
            </div>
        </div><!-- end .box-info -->
   </div>
</div><!-- end .row -->
<br />
 <input name="send_date" type="hidden" value="<?=$this->validation->send_date?>" />
</form>