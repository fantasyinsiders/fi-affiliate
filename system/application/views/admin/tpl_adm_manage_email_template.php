<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
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
<form id="form"  method="post" class="form-horizontal"  role="form">
<div class="row">   
	<div class="col-md-12">
    	<ul class="nav nav-tabs capitalize">
            <li class="active"><a href="#html" data-toggle="tab"><?=$this->lang->line('html_email_format')?></a></li>
            <li><a href="#text" data-toggle="tab"><?=$this->lang->line('text_email_format')?></a></li>
    	</ul>
        <div class="tab-content">
        	<div id="html" class="tab-pane fade in active">
	            <h4 class="header"><i class="fa fa-cog"></i> <?=str_replace('_', ' ', $this->validation->email_template_name)?></h4>
                <hr />
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('from_name')?></label>
                    <div class="col-lg-5">
                        <input name="email_template_from_name" type="text" class="form-control required" value="<?=$this->validation->email_template_from_name?>" />
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('from_email')?></label>
                    <div class="col-lg-5">
                        <input name="email_template_from_email" type="text" class="form-control required" value="<?=$this->validation->email_template_from_email?>" />
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('subject')?></label>
                    <div class="col-lg-5">
                        <input name="email_template_subject" type="text" class="form-control required" value="<?=$this->validation->email_template_subject?>" />
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
                          <?=_generate_dynamic_tags($this->validation->email_template_name)?>           
                    </div>
                </div>
            	<hr />
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('email_template_html')?></label>
                    <div class="col-lg-5">
                        <?=form_dropdown('email_template_html', array('html' => $this->lang->line('html'), 'text' => $this->lang->line('text')), $this->validation->email_template_html, 'class="form-control"');
            ?>    
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('cc')?></label>
                    <div class="col-lg-5">
                        <input name="email_template_cc" type="text" class="form-control required" value="<?=$this->validation->email_template_cc?>" />
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=$this->lang->line('bcc')?></label>
                    <div class="col-lg-5">
                        <input name="email_template_bcc" type="text" class="form-control required" value="<?=$this->validation->email_template_bcc?>" />
                    </div>
                </div>
                <hr />
            </div><!-- end #html -->
            <div id="text" class="tab-pane fade in">
                <hr />
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8 html-editor col-md-offset-2">
                            <textarea name="email_template_body_text"  rows="20" class="form-control"><?=$this->validation->email_template_body_text?></textarea>
                        </div>
                    </div>
                </div>
                <hr />
            </div><!-- end #text -->
        </div><!-- end .tab-content -->
   </div>
   <div class="col-md-5 col-md-offset-3"><button class="btn btn-success btn-lg" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button></div>
</div><!-- end .row -->
</form>