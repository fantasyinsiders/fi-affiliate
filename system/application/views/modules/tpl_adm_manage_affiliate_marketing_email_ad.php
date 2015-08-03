<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?=$editor_path?>
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline($page_title)?>
    </div>
    <div class="col-md-8 text-right">
		<?php if ($function == 'edit'):?>
		<?=_previous_next('previous', 'affiliate_email_ads', $this->validation->id);?>
        <button class="btn btn-primary" data-toggle="modal" data-target="#preview"><i class="fa fa-search-plus"></i> <span class="hidden-xs"><?=$this->lang->line('preview')?></span></button>
         <a data-href="<?=modules_url()?>module_affiliate_marketing_email_ads/delete/<?=$this->validation->id?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i> <span class="hidden-xs"><?=$this->lang->line('delete')?></span></a>
        <?php endif; ?>
        <a href="<?=modules_url()?>module_affiliate_marketing_email_ads/view" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_email_ads')?></span></a>
        <?php if ($function == 'edit'):?>
        <?=_previous_next('next', 'affiliate_email_ads', $this->validation->id);?>
        <?php endif; ?>
    </div>
</div>
<hr />
<form id="ajax-form"  method="post" class="form-horizontal"  role="form">
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">
        	<h4 class="header"><i class="fa fa-cog"></i> <?=$this->lang->line($function)?></h4>
        	<div class="form-group">
    		    <label for="name" class="col-sm-3 control-label"><?=$this->lang->line('email_ad_name')?></label>
        		<div class="col-lg-5">
        			<input name="name" type="text" class="form-control required" placeholder="<?=$this->lang->line('name')?>" value="<?=$this->validation->name?>" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="email_ad_title" class="col-sm-3 control-label"><?=$this->lang->line('email_ad_title')?></label>
        		<div class="col-lg-5">
        			<input name="email_ad_title" type="text" class="form-control required" placeholder="<?=$this->lang->line('email_ad_title')?>" value="<?=$this->validation->email_ad_title?>" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="status" class="col-sm-3 control-label"><?=$this->lang->line('status')?></label>
        		<div class="col-lg-5">
        			 <?=form_dropdown('status', array('0' => $this->lang->line('inactive'), '1' => $this->lang->line('active')), $this->validation->status, 'class="form-control"');?> 
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="status" class="col-sm-3 control-label"><?=$this->lang->line('program_name')?></label>
        		<div class="col-lg-5">
        			 <?= form_dropdown('program_id', $programs, $this->validation->program_id,'class="form-control"');?> 
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
                <label for="status" class="col-sm-3 control-label"><?=$this->lang->line('dynamic_tags')?></label>
        		<div class="col-sm-5 text-right">
        			  <?=_generate_dynamic_tags('emailads')?>           
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="enable_redirect" class="col-sm-3 control-label"><?=$this->lang->line('enable_redirect')?></label>
        		<div class="col-lg-5">
        			  <?=form_dropdown('enable_redirect', array('0' => $this->lang->line('no'), '1' => $this->lang->line('yes')), $this->validation->enable_redirect, 'class="form-control"');?> 
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="redirect_custom_url" class="col-sm-3 control-label"><?=$this->lang->line('redirect_custom_url')?></label>
        		<div class="col-lg-5">
        			<input name="redirect_custom_url" type="text" class="form-control required" placeholder="<?=base_url()?>" value="<?=$this->validation->redirect_custom_url?>" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="notes" class="col-sm-3 control-label"><?=$this->lang->line('notes')?></label>
        		<div class="col-lg-5">
        			<textarea name="notes" class="form-control required" rows="5"><?=$this->validation->notes?></textarea>
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

<?php if ($function == 'edit'): ?>
<div class="modal fade" id="preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('close')?></span></button>
        <h4 class="modal-title"><?=$this->validation->email_ad_title?></h4>
      </div>
      <div class="modal-body">
       <?=_preview_module_code($this->validation->email_ad_body)?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('close')?></button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>