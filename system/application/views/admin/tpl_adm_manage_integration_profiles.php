<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($integration)): ?>
<div class="alert alert-warning">
	<h3><i class="fa fa-warning"></i> <?=$this->lang->line('no_integrations_found')?></h3>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    	<a href="<?=admin_url()?>integration/add_integration_profile" class="btn btn-warning"><?=$this->lang->line('add_integration_profile')?></a>
    </p>
</div>
<?php else: ?>
<div class="row">
    <div class="col-md-4">
    	<?=_generate_sub_headline('integration_profiles')?>
    </div>
    <div class="col-md-8 text-right">
        <a href="<?=admin_url()?>integration/add_integration_profile" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add_integration_profile')?></span></a>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-12">
        <div class="box-info">
			<?php foreach ($integration as $v): ?>
            <div class="row" id="data-content">
                <div class="col-md-10">  
                    <div class=" capitalize">
                        <h5><a href="<?=admin_url()?>integration/update_integration_profile/<?=$v['id']?>"><?=$v['name']?></a></h5>
                        <p><?=$v['description']?></p>
                    </div>
                </div>
                <div class="col-md-2 text-right">
                	<a data-href="<?=admin_url()?>integration/delete_integration_profile/<?=$v['id']?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i></a>
                    <a href="<?=admin_url()?>integration/update_integration_profile/<?=$v['id']?>" class="btn btn-default block-phone"><i class="fa fa-pencil"></i></a>
                </div>
            </div>
            <hr/>
            <?php endforeach; ?> 
        </div>
    </div>
</div>
<?php endif; ?>