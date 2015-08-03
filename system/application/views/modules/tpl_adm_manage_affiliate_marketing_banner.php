<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline($page_title)?>
    </div>
    <div class="col-md-8 text-right">
		<?php if ($function == 'edit'):?>
		<?=_previous_next('previous', 'affiliate_banners', $this->validation->id);?>
        <button class="btn btn-primary" data-toggle="modal" data-target="#preview"><i class="fa fa-search-plus"></i> <span class="hidden-xs"><?=$this->lang->line('preview')?></span></button>
         <a data-href="<?=modules_url()?>module_affiliate_marketing_banners/delete/<?=$this->validation->id?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i> <span class="hidden-xs"><?=$this->lang->line('delete')?></span></a>
        <?php endif; ?>
        <a href="<?=modules_url()?>module_affiliate_marketing_banners/view" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_banners')?></span></a>
        <?php if ($function == 'edit'):?>
        <?=_previous_next('next', 'affiliate_banners', $this->validation->id);?>
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
    		    <label for="name" class="col-sm-3 control-label"><?=$this->lang->line('banner_name')?></label>
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
    		    <label for="use_external_image" class="col-sm-3 control-label"><?=$this->lang->line('use_external_image')?></label>
        		<div class="col-lg-5">
        			  <?=form_dropdown('use_external_image', array('0' => $this->lang->line('no'), '1' => $this->lang->line('yes')), $this->validation->use_external_image, 'class="form-control" id="external-image"');?> 
        		</div>
        	</div>
            <hr />
            <div id="upload-banner">
            <div class="form-group">
    		    <label for="userfile" class="col-sm-3 control-label"><?=$this->lang->line('upload_banner')?></label>
        		<div class="col-lg-5">
        			  <input type="file" name="userfile" size="40" />
        		</div>
        	</div>
            <hr />
            </div>
            <div class="form-group">
    		    <label for="banner_file_name" class="col-sm-3 control-label"><?=$this->lang->line('banner_file_name')?></label>
        		<div class="col-lg-5">
        			 <input name="banner_file_name" type="text" class="form-control required" placeholder="<?=base_url()?>images/banners/banner.jpg" value="<?=$this->validation->banner_file_name?>" />
        		</div>
        	</div>
            <hr /> 
            <div class="form-group">
    		    <label for="banner_width" class="col-sm-3 control-label"><?=$this->lang->line('banner_width')?></label>
        		<div class="col-lg-5">
        			 <input name="banner_width" type="number" class="form-control required" placeholder="468" value="<?=$this->validation->banner_width?>" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="banner_height" class="col-sm-3 control-label"><?=$this->lang->line('banner_height')?></label>
        		<div class="col-lg-5">
        			 <input name="banner_height" type="number" class="form-control required" placeholder="60" value="<?=$this->validation->banner_height?>" />
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
        <h4 class="modal-title"><?=$this->validation->name?></h4>
      </div>
      <div class="modal-body">
        <div class="row">
        	<div class="col-md-12 text-center" style="overflow: auto;">
            	<?php if ($this->validation->use_external_image == 1): ?>
                <img src="<?=$this->validation->banner_file_name?>"  class="img-responsive thumbnail" <?php if ($this->validation->banner_height):?> height="<?=$this->validation->banner_height?>"<?php endif; ?> <?php if ($this->validation->banner_width):?> width="<?=$this->validation->banner_width?>"<?php endif; ?> />
                <?php else: ?>
                <img src="<?=base_url('js')?>images/banners/<?=$this->validation->banner_file_name?>" <?php if ($this->validation->banner_height):?> height="<?=$this->validation->banner_height?>"<?php endif; ?> <?php if ($this->validation->banner_width):?> width="<?=$this->validation->banner_width?>"<?php endif; ?>/>
                <?php endif; ?>
            </div>
        </div>
        <hr />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('close')?></button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
<script type="text/javascript">
$("select#external-image").change(function(){
	$( "select#external-image option:selected").each(function(){
		if($(this).attr("value")=="0"){
			$("#upload-banner").show(100);
		}
		else {
			$("#upload-banner").hide(100);
		}
	});
}).change();
$("#form").validate();
</script>