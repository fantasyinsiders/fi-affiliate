<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline($page_title)?>
    </div>
    <div class="col-md-8 text-right">
		<?php if ($function == 'edit'):?>
		<a data-href="<?=modules_url()?>module_affiliate_marketing_member_events/delete/<?=$this->validation->id?>/2/<?=$month?>/<?=$day?>/<?=$year?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-sm btn-danger"><i class="fa fa-trash-o"></i> <span class="hidden-xs"><?=$this->lang->line('delete')?></span></a>
        <?php endif; ?>
        <a href="<?=modules_url()?>module_affiliate_marketing_member_events/view/<?=$year?>/<?=$month?>/<?=$day?>" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_calendar')?></span></a>
    </div>
</div>
<hr />
<form id="ajax-form"  method="post" class="form-horizontal"  role="form">
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">
        	<h4 class="header"><i class="fa fa-cog"></i> <?=$this->lang->line($function)?></h4>
        	<div class="form-group">
    		    <label for="status" class="col-sm-3 control-label"><?=$this->lang->line('status')?></label>
        		<div class="col-lg-5">
        			 <?=form_dropdown('status',  array('1' => $this->lang->line('active'), '0' => $this->lang->line('inactive')), $this->validation->status, 'class="form-control"')?>
        		</div>
        	</div>
            <hr />
            <div class="form-group">
                <label for="date" class="col-sm-3 control-label"><?=$this->lang->line('date')?></label>
        		<div class="col-lg-5">
        			 <input name="date" id="date" class="datepicker-input form-control"value="<?=$this->validation->date?>" placeholder="<?=$format_date?>"/> 
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="start_hour" class="col-sm-3 control-label"><?=$this->lang->line('start_time')?></label>
        		<div class="col-lg-5">
        			  <?=form_dropdown('start_hour', $hour,  $this->validation->start_hour, 'class="form-control time"'); ?> : <?=form_dropdown('start_min', $min,  $this->validation->start_min, 'class="form-control time"'); ?> <?=form_dropdown('start_ampm', $ampm,  $this->validation->start_ampm, 'class="form-control time"'); ?>    		
                </div>
        	</div>
            <hr />
            <div class="form-group">
                 <label for="end_hour" class="col-sm-3 control-label"><?=$this->lang->line('end_time')?></label>
        		<div class="col-lg-5">
        			  <?=form_dropdown('end_hour', $hour, $this->validation->end_hour, 'class="form-control time"')?> : <?=form_dropdown('end_min', $min,  $this->validation->end_min, 'class="form-control time"')?> <?=form_dropdown('end_ampm', $ampm,  $this->validation->end_ampm, 'class="form-control time"') ?>  		
                </div>
        	</div>
            <hr />
            <?php if ($function == 'add'): ?>
            <div class="form-group">
    		    <label for="recur_in_days" class="col-sm-3 control-label"><?=$this->lang->line('recur_in_days')?></label>
        		<div class="col-lg-5">
                     <?=form_dropdown('recur_in_days',  $options, '', 'class="form-control"')?>
        		</div>
        	</div>
            <hr />
            <?php endif; ?>
            <div class="form-group">
    		    <label for="restrict_group" class="col-sm-3 control-label"><?=$this->lang->line('restrict_group')?></label>
        		<div class="col-lg-5">
        			 <?= form_dropdown('restrict_group', $aff_groups,  $this->validation->restrict_group, 'class="form-control"')?>
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="member_event_title" class="col-sm-3 control-label"><?=$this->lang->line('member_event_title')?></label>
        		<div class="col-lg-5">
        			 <input name="member_event_title" class="form-control"value="<?=$this->validation->member_event_title?>" /> 
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="member_event_location" class="col-sm-3 control-label"><?=$this->lang->line('member_event_location')?></label>
        		<div class="col-lg-5">
        			 <input name="member_event_location" class="form-control"value="<?=$this->validation->member_event_location?>" /> 
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label for="member_event_description" class="col-sm-3 control-label"><?=$this->lang->line('member_event_description')?></label>
        		<div class="col-lg-5">
        			<textarea name="member_event_description" class="form-control required" rows="15"><?=$this->validation->member_event_description?></textarea>
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
</form>