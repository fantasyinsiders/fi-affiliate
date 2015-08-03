<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="col-md-9">
	<form id="form" class="form-horizontal" method="post">
    <div class="panel panel-default">
    	<div class="panel-heading"><?=$this->lang->line($page_title)?></div>
        <div class="panel-body text-capitalize">
			<div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('name')?></label>
        		<div class="col-lg-5">
        			<input name="name" type="text"  value="<?=$this->validation->name?>" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('url')?></label>
        		<div class="col-lg-5">
        			<input name="url" type="text"  value="<?=$this->validation->url?>" class="form-control" />
        		</div>
        	</div>
            <hr />
           
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('cost')?></label>
        		<div class="col-lg-5">
        			<input name="cost" type="text"  value="<?=$this->validation->cost?>" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('cost_type')?></label>
        		<div class="col-lg-5">
        			<?=form_dropdown('cost_type',  array('one_time' => $this->lang->line('one_time'), 'ppc' => $this->lang->line('ppc'), 'recur' => $this->lang->line('recur')), $this->validation->cost_type, 'class="form-control"')?> 
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('recur_every_x_days')?></label>
        		<div class="col-lg-5">
        			<input name="recur" type="text"  value="<?=$this->validation->recur?>" class="form-control" />
        		</div>
        	</div>
            <hr />
           
            <?php if ($function == 'edit'): ?>
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('tracking_code')?></label>
        		<div class="col-lg-5">
        			<input type="text"  value="<?=site_url()?><?=TRACK_ROUTE?>/<?=$id?>" class="form-control" onClick="this.select()" />
        		</div>
        	</div>
            <hr />
			<?php endif; ?>
            <div class="form-group">
                <div class="col-md-offset-3 col-sm-9">
                    <button class="btn btn-success block-phone" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button>  
                    <?php if ($function == 'edit'): ?>
                    <a href="javascript:ConfirmDelete('<?=$id?>')" class="btn btn-danger"><i class="fa fa-trash-o"></i> <?=$this->lang->line('delete')?></a> 
                    <?php endif; ?>
                </div>
            </div>
    	</div>
	</div>
    </form>
</div>
<div class="col-md-3">
	<div class="panel panel-default">
    	<div class="panel-heading"><?=$this->lang->line($side_header)?></div>
        <div class="panel-body text-capitalize">
			<?php if ($function == 'edit'): ?>
            <p><div class="pull-right"><?=$clicks_month?></div><?=$this->lang->line('clicks_month')?>:</p>
            <p class="borderBottom"><div class="pull-right"><?=$comms_month?></div><?=$this->lang->line('comms_month')?>:</p>
            <p class="borderBottom"><div class="pull-right"><?=$total_clicks?></div><?=$this->lang->line('total_clicks')?>:</p>
            <p class="borderBottom"><div class="pull-right"><?=$total_comms?></div><?=$this->lang->line('total_comms')?>:</p>
            <?php else: ?>
            <p><?=$this->lang->line('desc_what_is_tracker')?></p>
            <?php endif; ?>
    	</div>
	</div>
</div>
<script>
function ConfirmDelete(id){
	var answer=confirm("<?=$this->lang->line('are_you_sure_you_want_to_delete')?>")
	if(answer) {
		window.location="<?=site_url('members')?>/tracking/delete/"+id;	
	} 
};
</script>