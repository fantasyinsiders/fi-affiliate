<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="col-md-8">
	<form class="form-horizontal" method="post" role="form">
    <div class="panel panel-default">
    	<div class="panel-heading">
        	<?=$this->lang->line('manage_invisilink')?>
        </div>
        <div class="panel-body">
        	<?php if ($function == 'edit'): ?>
        	<div class="form-group">
                <label class="col-lg-4 control-label text-capitalize"><?=$this->lang->line('status')?></label>
                <div class="col-lg-5">
                    <p class="form-control-static">
					<?php if ($this->validation->status == 1): ?>
                    <span class="label label-success"><?=$this->lang->line('active')?></span>
                    <?php else: ?>
                   	<span class="label label-warning"><?=$this->lang->line('inactive')?></span>
                    <?php endif; ?>
                    </p>
                </div>
            </div>
            <hr />
            <?php endif; ?>
            <div class="form-group">
                <label class="col-lg-4 control-label text-capitalize"><?=$this->lang->line('domain')?></label>
                <div class="col-lg-5">
                    <input type="text" name="invisilink_url" class="form-control" value="<?=$this->validation->invisilink_url;?>" />
                </div>
            </div>
            <hr />
            <div class="col-md-8 col-md-offset-4">
            	<?php if (empty($this->validation->status)): ?>
            	<button class="btn btn-success" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button>
            	<?php endif; ?>
                <a href="<?=site_url('members')?>/marketing/tools/0/0/0/module_id/<?=$module_id?>" class="btn btn-default"><i class="fa fa-search"></i> <?=$this->lang->line('view_domains')?></a>
            </div>
        </div>
    </div>
    </form>
</div>
<div class="col-md-4">
	<div class="panel panel-default">
    	<div class="panel-heading">
        	<?=$this->lang->line('what_is_invisilink')?>
        </div>
        <div class="panel-body">
        	<p><?=$this->lang->line('desc_what_is_invisilink')?></p>
        </div>
    </div>
</div>