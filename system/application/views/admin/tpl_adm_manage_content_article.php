<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?=$editor_path?>
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline('manage_content')?>
    </div>
    <div class="col-md-8 text-right">
		<?php if ($function == 'update_content_article'):?>
		<?=_previous_next('previous', 'content_articles', $this->validation->id);?>
         <a data-href="<?=admin_url()?>content_articles/delete_content_article/<?=$this->validation->id?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i> <span class="hidden-xs"><?=$this->lang->line('delete')?></span></a>
        <?php endif; ?>
        <a href="<?=admin_url()?>content_articles/view_content_articles" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_content_articles')?></span></a>
        <?php if ($function == 'update_content_article'):?>
        <?=_previous_next('next', 'content_articles', $this->validation->id);?>
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
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('status')?></label>
        		<div class="col-lg-5">
        			 <?=form_dropdown('status', array('0' => $this->lang->line('inactive'), '1' => $this->lang->line('active')), $this->validation->status, 'class="form-control"');?> 
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('content_title')?></label>
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
                <label class="col-lg-3 control-label"><?=$this->lang->line('dynamic_tags')?></label>
        		<div class="col-sm-5 text-right">
        			  <?=_generate_dynamic_tags('htmlads')?>           
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('publish_date')?></label>
        		<div class="col-lg-5">
        			 <div class="input-group">
                     	<input name="date_published" type="text" class="datepicker-input form-control required" value="<?=_format_date($this->validation->date_published, $format_date2)?>" />
                       	<span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
               		</div>
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('drip_date')?></label>
        		<div class="col-lg-5">
        			 <input name="drip_date" type="number" class="form-control required" value="<?=$this->validation->drip_date?>" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('enable_affiliate_group_permissions')?></label>
        		<div class="col-lg-5">
        			 <?=form_dropdown('enable_affiliate_group_permissions', array('0' => $this->lang->line('no'), '1' => $this->lang->line('yes')), $this->validation->enable_affiliate_group_permissions, 'class="form-control show-block"');?> 
        		</div>
        	</div>
            <hr />
            <div <?php if ($this->validation->enable_affiliate_group_permissions == '0'): ?>style="display:none"<?php endif; ?> class="show-div">
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('restrict_access_to')?></label>
        		<div class="col-lg-5">
        			 <?=form_dropdown('content_permissions[]', 
							  $aff_groups, 
							  $article_to_groups,
							  'multiple="multiple" id="content_permissions" class="form-control"');
						?>
        		</div>
        	</div>
            <hr />
            </div>
            <div class="form-group">
                <div class="col-md-offset-3 col-sm-9">
                    <button class="btn btn-success block-phone" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button> 
                </div>
            </div>
		</div>
    </div>      
</div>
</form>