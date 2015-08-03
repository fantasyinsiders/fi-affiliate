<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline($page_title)?>
    </div>
    <div class="col-md-8 text-right">
		<?php if ($function == 'edit'):?>
		<?=_previous_next('previous', 'affiliate_page_peel_ads', $this->validation->id);?>
        <?=anchor_popup(modules_url() . 'module_affiliate_marketing_page_peel_ads/view_ad/' . $this->validation->id, '<i class="fa fa-search"></i> <span class="hidden-xs">' . $this->lang->line('preview') . '</span>', '', 'btn btn-primary')?>
        <a data-href="<?=modules_url()?>module_affiliate_marketing_page_peel_ads/delete/<?=$this->validation->id?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i> <span class="hidden-xs"><?=$this->lang->line('delete')?></span></a>
        <?php endif; ?>
        <a href="<?=modules_url()?>module_affiliate_marketing_page_peel_ads/view" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_page_peel_ads')?></span></a>
        <?php if ($function == 'edit'):?>
        <?=_previous_next('next', 'affiliate_page_peel_ads', $this->validation->id);?>
        <?php endif; ?>
    </div>
</div>
<hr />
<form id="ajax-form"  method="post" class="form-horizontal"  role="form" enctype="multipart/form-data">
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">
        	<h4 class="header"><i class="fa fa-cog"></i> <?=$this->lang->line($function)?></h4>
        	<div class="form-group">
    		    <label for="page_peel_ad_name" class="col-sm-3 control-label"><?=$this->lang->line('page_peel_ad_name')?></label>
        		<div class="col-lg-5">
        			<input name="page_peel_ad_name" type="text" class="form-control required" value="<?=$this->validation->page_peel_ad_name?>" />
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
    		    <label for="page_peel_ad_small_image" class="col-sm-3 control-label"><?=$this->lang->line('page_peel_ad_small_image')?></label>
        		<div class="col-lg-5">
        			<input name="page_peel_ad_small_image" type="text" class="form-control required" value="<?=$this->validation->page_peel_ad_small_image?>" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="page_peel_ad_large_image" class="col-sm-3 control-label"><?=$this->lang->line('page_peel_ad_large_image')?></label>
        		<div class="col-lg-5">
        			<input name="page_peel_ad_large_image" type="text" class="form-control required" value="<?=$this->validation->page_peel_ad_large_image?>" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="userfile1" class="col-sm-3 control-label"><?=$this->lang->line('upload_page_peel_ad_small_image')?></label>
        		<div class="col-lg-5">
        			  <input type="file" name="userfile1" size="40" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="userfile2" class="col-sm-3 control-label"><?=$this->lang->line('upload_page_peel_ad_large_image')?></label>
        		<div class="col-lg-5">
        			  <input type="file" name="userfile2" size="40" />
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