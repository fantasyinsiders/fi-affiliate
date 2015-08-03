<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?=$editor_path?>
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline($function)?>
    </div>
    <div class="col-md-8 text-right">
		<?php if ($function == 'update_follow_up'):?>
         <a data-href="<?=admin_url()?>follow_ups/delete_follow_up/<?=$this->validation->follow_up_id?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i> <span class="hidden-xs"><?=$this->lang->line('delete')?></span></a>
        <?php endif; ?>
        <a href="<?=admin_url()?>follow_ups/view_follow_ups/0/0/0/mailing_list_id/<?=$this->validation->mailing_list_id?>" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_follow_ups')?></span></a>
    </div>
</div>
<hr />
<form id="form"  method="post" class="form-horizontal"  role="form">
<div class="row">   
	<div class="col-md-12">
    	<ul class="nav nav-tabs capitalize">
            <li class="active"><a href="#html" data-toggle="tab"><?=$this->lang->line('html_email_format')?></a></li>
            <li><a href="#text" data-toggle="tab"><?=$this->lang->line('text_email_format')?></a></li>
    	</ul>
        <div class="tab-content">
        	<div id="html" class="tab-pane fade in active">
	            <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('follow_up_name')?></label>
                    <div class="col-lg-5">
                        <input name="follow_up_name" type="text" class="form-control required" value="<?=$this->validation->follow_up_name?>" />
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('from_name')?></label>
                    <div class="col-lg-5">
                        <input name="from_name" type="text" class="form-control required" value="<?=$this->validation->from_name?>" />
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('from_email')?></label>
                    <div class="col-lg-5">
                        <input name="from_email" type="text" class="form-control required" value="<?=$this->validation->from_email?>" />
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('subject')?></label>
                    <div class="col-lg-5">
                        <input name="email_subject" type="text" class="form-control required" value="<?=$this->validation->email_subject?>" />
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8 html-editor col-md-offset-2">
                            <?=$editor?>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('dynamic_tags')?></label>
                    <div class="col-sm-5 text-right">
                          <?=_generate_dynamic_tags('followups')?>           
                    </div>
                </div>
            	<hr />
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('email_type')?></label>
                    <div class="col-lg-5">
                        <?=form_dropdown('email_type', array('html' => $this->lang->line('html'), 'text' => $this->lang->line('text')), $this->validation->email_type, 'class="form-control"');
            ?>    
                    </div>
                </div>
                <hr />
            </div><!-- end #html -->
            <div id="text" class="tab-pane fade in">
                <hr />
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8 html-editor col-md-offset-2">
                            <textarea name="text_message"  rows="20" class="form-control"><?=$this->validation->text_message?></textarea>
                        </div>
                    </div>
                </div>
                <hr />
            </div><!-- end #text -->
        </div><!-- end .tab-content -->
   </div>
   <div class="col-md-5 col-md-offset-3"><button class="btn btn-success btn-lg" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button></div>
</div><!-- end .row -->
<br />
<input name="mailing_list_id" type="hidden" value="<?=$this->validation->mailing_list_id?>" />    
</form>