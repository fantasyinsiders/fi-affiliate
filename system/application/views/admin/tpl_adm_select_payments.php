<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($modules)): ?>
<div class="alert alert-warning">
	<h2 class="text-warning"><?=$this->lang->line('no_modules_found')?></h2>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    </p>
</div>
<?php else: ?>
<div class="row">
    <div class="col-md-4">
    <?=_generate_sub_headline($module)?> 
    </div>
    <div class="col-md-8 text-right">
    	 <a data-href="<?=admin_url()?>commissions/mark_all_approved" data-toggle="modal" data-target="#confirm-approval" href="#" class="md-trigger btn btn-warning"><i class="fa fa-thumbs-up"></i> <span class="hidden-xs"><?=$this->lang->line('approve_pending_commissions')?></span></a>
        <a href="<?=admin_url()?>modules/add_module/affiliate_payment" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('install_affiliate_payment_module')?></span></a>
    </div>
</div>
<hr />
<div class="row" id="data-content">
<?php foreach ($modules as $v): ?>
	<div class="col-lg-6  data-box">
    	<div class="box-info">
        	<div class="row">
                <div class="col-sm-3 text-center"> 
					<?php if (file_exists($base_physical_path . '/images/modules/module_' . $v['module_type'] . '_' . $v['module_file_name'] . '.' . $member_marketing_tool_ext)): ?>
                    <img src="<?=base_url() . 'images/modules/module_' . $v['module_type'] . '_' . $v['module_file_name'] . '.' . $member_marketing_tool_ext?>" class="img-responsive" />
                    <?php else: ?>
                    <img src="<?=base_url()?>images/modules/tools.png" class="img-responsive" />
                    <?php endif; ?>
                </div>
                <hr class="visible-xs" />
                <div class="col-sm-9">
                    <h5 class="member-name"><?=$v['module_name']?></h5>
                    <p class="card-height"><?=$v['module_description']?></p>
                    <hr />
                    <div class="text-right">
                      <a href="<?=modules_url() . 'module_' . $v['module_type'] . '_' . $v['module_file_name']?>/update/<?=$v['module_id']?>" class="btn btn-info block-phone" title="<?=$this->lang->line('generate_payment')?>"><i class="fa fa-refresh"></i> <?=$this->lang->line('get_started')?></a>
                      </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
<hr />
<div class="row">
    <div class="col-md-12 text-right">
    <?=$pagination['select_rows']?>
    </div>
</div>    
<div class="text-center"><?=$pagination['rows']?></div>   
<div class="text-center visible-xs"><small class="text-muted"><?=$pagination['num_pages']?> <?=$this->lang->line('total_pages')?></small></div>
<br />  
<?php endif; ?>
<!-- Start Modal -->
<div class="modal fade" id="confirm-approval" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        	<div class="modal-header">
            	<h3><i class="fa fa-exclamation-triangle"></i> <?=$this->lang->line('automatically_approve_all_pending_commissions')?></h3>
            </div>
            <div class="modal-body capitalize">
                <p><?=$this->lang->line('automatically_approve_all_pending_commissions_desc')?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('cancel')?></button>
                <a href="<?=admin_url()?>commissions/mark_all_approved" class="btn btn-success"><?=$this->lang->line('proceed')?></a>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
    