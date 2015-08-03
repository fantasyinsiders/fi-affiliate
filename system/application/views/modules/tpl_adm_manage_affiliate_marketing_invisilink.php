<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline($page_title)?>
    </div>
    <div class="col-md-8 text-right">
		<?php if ($function == 'edit'):?>
		<?=_previous_next('previous', 'affiliate_invisilinks', $this->validation->id);?>
         <a data-href="<?=modules_url()?>module_affiliate_marketing_invisilinks/delete/<?=$this->validation->id?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i> <span class="hidden-xs"><?=$this->lang->line('delete')?></span></a>
        <?php endif; ?>
        <a href="<?=modules_url()?>module_affiliate_marketing_invisilinks/view" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_invisilinks')?></span></a>
        <?php if ($function == 'edit'):?>
        <?=_previous_next('next', 'affiliate_invisilinks', $this->validation->id);?>
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
    		    <label for="name" class="col-sm-3 control-label"><?=$this->lang->line('username')?></label>
        		<div class="col-lg-5">
        			<input name="member_id" id="member_id" type="text" class="form-control required" value="<?=$this->validation->member_id?>" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="invisilink_url" class="col-sm-3 control-label"><?=$this->lang->line('invisilink_url')?></label>
        		<div class="col-lg-5">
        			<input name="invisilink_url" type="text" class="form-control required" placeholder="<?=base_url()?>" value="<?=$this->validation->invisilink_url?>" />
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

<script language="JavaScript" type="text/javascript" src="<?=base_url('js')?>js/autocomplete/jquery.autocomplete.js"></script>
<script type="text/javascript">
$(function() {	
	$("#member_id").autocomplete("<?=admin_url()?>search/ajax_members");
});
</script>