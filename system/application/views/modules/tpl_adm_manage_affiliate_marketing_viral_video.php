<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline($page_title)?>
    </div>
    <div class="col-md-8 text-right">
		<?php if ($function == 'edit'):?>
		<?=_previous_next('previous', 'affiliate_viral_videos', $this->validation->id);?>
        <button class="btn btn-primary" data-toggle="modal" data-target="#preview"><i class="fa fa-search-plus"></i> <span class="hidden-xs"><?=$this->lang->line('preview')?></span></button>
         <a data-href="<?=modules_url()?>module_affiliate_marketing_viral_videos/delete/<?=$this->validation->id?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i> <span class="hidden-xs"><?=$this->lang->line('delete')?></span></a>
        <?php endif; ?>
        <a href="<?=modules_url()?>module_affiliate_marketing_viral_videos/view" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_viral_videos')?></span></a>
        <?php if ($function == 'edit'):?>
        <?=_previous_next('next', 'affiliate_viral_videos', $this->validation->id);?>
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
    		    <label for="name" class="col-sm-3 control-label"><?=$this->lang->line('viral_video_name')?></label>
        		<div class="col-lg-5">
        			<input name="name" type="text" class="form-control required" placeholder="<?=$this->lang->line('name')?>" value="<?=$this->validation->name?>" />
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
    		    <label for="viral_video_title" class="col-sm-3 control-label"><?=$this->lang->line('viral_video_link')?></label>
        		<div class="col-lg-5">
        			<textarea name="viral_video_link" class="form-control required" rows="5"><?=$this->validation->viral_video_link?></textarea>     
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
      <div class="modal-body">
       <?php if ($this->validation->enable_redirect == 0): ?>
        <?=$this->viral_videos_model->_generate_viral_video_code($viral_video)?>
       <?php else: ?>
        <?=$this->viral_videos_model->_generate_viral_video_code($viral_video, $this->validation->redirect_custom_url)?>
       <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('close')?></button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>