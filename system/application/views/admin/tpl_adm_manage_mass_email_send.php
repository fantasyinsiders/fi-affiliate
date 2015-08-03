<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (!empty($send_mass_mail)): ?>
<div class="row">
	<div class="col-md-4">
    	<?=_generate_sub_headline('send_mass_email')?>
    </div>
   	<div class="col-md-8 text-right">
    <a href="<?=admin_url()?>email_queue/view_email_queue" class="btn btn-primary"><i class="icon-zoom-in"></i> <?=$this->lang->line('view_email_queue')?></a>
    </div>
</div>
<hr />
<div class="alert alert-warning">
	<div class="progress progress-striped active">
	  <div class="progress-bar progress-bar-warning"  role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 10%"></div>
	</div>
	<h2 class="text-warning"><?=$this->lang->line('please_wait')?></h2>
</div>
<form id="form" method="post"  action="<?=admin_url()?>email_send/send_mass_email" />
	<?php foreach ($_POST as $k => $v): ?>
    <?php if ($k == 'html_body'): ?>
    <input type="hidden" name="<?=$k?>" value="<?=htmlentities($v)?>" />
    <?php else: ?>
    <input type="hidden" name="<?=$k?>" value="<?=$v?>" />
    <?php endif; ?>
    <?php endforeach; ?>	
</form>
<script>
$(function(){
	$('#form').submit();
});
</script>
<?php else: ?>
<?=$editor_path?>
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline($function)?>
    </div>
    <div class="col-md-8 text-right">
		<?php if ($function == 'update_email_template'):?>
		<?=_previous_next('previous', 'email_templates', $this->validation->id);?>
        <?php if($this->validation->email_template_type == 'custom'): ?>
         <a data-href="<?=admin_url()?>email_templates/delete_email_template/<?=$this->validation->id?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i> <span class="hidden-xs"><?=$this->lang->line('delete')?></span></a>
        <?php endif; ?>
        <?php endif; ?>
        <a href="<?=admin_url()?>email_templates/view_email_templates" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_email_templates')?></span></a>
        <?php if ($function == 'update_email_template'):?>
        <?=_previous_next('next', 'email_templates', $this->validation->id);?>
        <?php endif; ?>
    </div>
</div>
<hr />
<form id="form"  method="post" class="form-horizontal form" role="form">
<div class="row">   
	<div class="col-md-12">
        <div class="box-info">
            <div class="form-group">
                <label class="col-lg-3 control-label"><?=$this->lang->line('send_date')?></label>
                <div class="col-lg-2">
                	<div class="input-group">
                     	<input name="send_date" type="text" class="datepicker-input form-control required" value="<?=$this->validation->send_date?>" />
                       	<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
               		</div>
                </div>
                <label class="col-lg-1 control-label"><?=$this->lang->line('mailing_lists')?></label>
                <div class="col-lg-2">
                    <input readonly type="text" id="group" value="<?=$lists?>" class="form-control"/>
                </div>
                <label class="col-lg-1 control-label"><?=$this->lang->line('email_type')?></label>
                <div class="col-lg-1">
                    <?=form_dropdown('email_type', array('html' => $this->lang->line('html'), 'text' => $this->lang->line('text')), $this->validation->email_type, 'class="form-control show-editor"')?>    
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
            <div style="display:none" class="show-text">
            	<div class="form-group">
                    <div class="row">
                        <div class="col-md-8 html-editor col-md-offset-2">
                            <textarea name="text_body"  rows="20" class="form-control"><?=$this->validation->text_body?></textarea>
                        </div>
                    </div>
                </div>
                <hr />
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label"><?=$this->lang->line('from_name')?></label>
                <div class="col-sm-3">
                    <input name="sender_name" type="text" class="form-control required" value="<?=$this->validation->sender_name?>" />
                </div>
                <label class="col-lg-1 control-label"><?=$this->lang->line('from_email')?></label>
                <div class="col-sm-3">
                    <input name="sender_email" type="text" class="form-control required email" value="<?=$this->validation->sender_email?>" />
                </div>
            </div>
            <hr />
            <div class="form-group">
                <label class="col-lg-3 control-label"><?=$this->lang->line('cc')?></label>
                <div class="col-sm-3">
                    <input name="cc" type="text" class="form-control" value="<?=$this->validation->cc?>" />
                </div>
                <label class="col-lg-1 control-label"><?=$this->lang->line('bcc')?></label>
                <div class="col-sm-3">
                    <input name="bcc" type="text" class="form-control" value="<?=$this->validation->bcc?>" />
                </div>
            </div>
            <hr />
            <div class="form-group">
            	<div class="col-md-8 col-md-offset-2 alert alert-warning">
                <h5 class="text-center"><?=$this->lang->line('send_test_email_first')?></h5>
                <label class="col-lg-3 control-label"><?=$this->lang->line('send_preview_email')?></label>
                <div class="col-sm-7">
                    <input name="send_draft" type="text" class="email form-control" />
                </div>
                </div>
            </div>
            <hr />
            <div class="col-md-5 col-md-offset-3">
            	<button class="btn btn-success btn-lg" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button>
            </div>
        </div><!-- end .box-info -->
   </div>
</div><!-- end .row -->
<br />
<input name="list_ids" type="hidden" value="<?=$list_ids?>" />
</form>
<script>
$("select.show-editor").change(function(){
  $( "select.show-editor option:selected").each(function(){
	  if($(this).attr("value")=="html"){
		  $(".show-html").show(300);
		  $(".show-text").hide(300);
	  }
	  if($(this).attr("value")=="text"){
		  $(".show-html").hide(300);
		  $(".show-text").show(300);
	  }
  });
}).change();

$("#form").validate();
</script>
<?php endif; ?>
