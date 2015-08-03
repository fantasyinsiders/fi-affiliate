<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($templates)): ?>
<div class="alert alert-warning">
	<h3><i class="fa fa-warning"></i> <?=$this->lang->line('no_templates_found')?></h3>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    	<a href="<?=admin_url()?>templates/add_template" class="btn btn-warning"><?=$this->lang->line('add_template')?></a>
    </p>
</div>
<?php else: ?>
<div class="row">
    <div class="col-md-4">
    	<?=_generate_sub_headline('email_templates')?>
    </div>
    <div class="col-md-8 text-right">
        <a href="<?=admin_url()?>mailing_lists/view_mailing_lists" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_mailing_lists')?></span></a>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-12">
        <div class="box-info">
			<?php foreach ($templates as $v): ?>
            <div class="row" id="data-content">
                <div class="col-md-10">  
                    <div class=" capitalize">
                        <h5><a href="<?=admin_url()?>email_templates/update_email_template/<?=$v['id']?>"><?=$this->lang->line($v['email_template_name'])?></a>
                            <small>
								<?php if ($v['email_template_type'] == 'admin'): ?>
                                <span class="label label-info">
                                <?php elseif ($v['email_template_type'] == 'member'): ?>
                                <span class="label label-success">
                                <?php else: ?>
                                <span class="label label-warning">
                                <?php endif; ?>
                                <?=$v['email_template_type']?>
                                </span>
                            </small>
                        </h5>
                        <p><?=$v['email_template_description']?></p>
                    </div>
                </div>
                <div class="col-md-2 text-right">
                    <a href="<?=admin_url()?>email_templates/update_email_template/<?=$v['id']?>" class="btn btn-default block-phone"><i class="fa fa-pencil"></i> <?=$this->lang->line('edit')?></a>
                </div>
            </div>
            <hr/>
            <?php endforeach; ?> 
        </div>
    </div>
</div>
<?php endif; ?>