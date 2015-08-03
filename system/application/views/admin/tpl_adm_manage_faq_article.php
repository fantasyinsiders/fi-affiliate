<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?=$editor_path?>
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline('manage_faqs')?>
    </div>
    <div class="col-md-8 text-right">
		<?php if ($function == 'update_faq_article'):?>
		<?=_previous_next('previous', 'faq_articles', $this->validation->id);?>
         <a data-href="<?=admin_url()?>faq_articles/delete_faq_article/<?=$this->validation->id?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i> <span class="hidden-xs"><?=$this->lang->line('delete')?></span></a>
        <?php endif; ?>
        <a href="<?=admin_url()?>faq_articles/view_faq_articles" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_faq_articles')?></span></a>
        <?php if ($function == 'update_faq_article'):?>
        <?=_previous_next('next', 'faq_articles', $this->validation->id);?>
        <?php endif; ?>
    </div>
</div>
<hr />
<form id="form"  method="post" class="form-horizontal"  role="form">
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">
            <h4 class="header"><i class="fa fa-cog"></i> <?=$this->lang->line($function)?></h4>
        	<div class="form-group">
    		    <label for="status" class="col-sm-3 control-label"><?=$this->lang->line('status')?></label>
        		<div class="col-lg-5">
        			 <?=form_dropdown('status', array('0' => $this->lang->line('inactive'), '1' => $this->lang->line('active')), $this->validation->status, 'class="form-control"');?> 
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="content_title" class="col-sm-3 control-label"><?=$this->lang->line('faq_question')?></label>
        		<div class="col-lg-5">
        			<input name="content_title" type="text" class="form-control required" value="<?=$this->validation->content_title?>" />
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
                <div class="col-md-offset-3 col-sm-9">
                    <button class="btn btn-success block-phone" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button> 
                </div>
            </div>
		</div>
    </div>      
</div>