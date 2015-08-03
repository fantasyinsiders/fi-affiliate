<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($rewards)): ?>
<div class="alert alert-warning">
	<h3 class="text-warning"><?=$this->lang->line('no_rewards_found')?></h3>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    	<a href="<?=admin_url()?>rewards/add_reward" class="btn btn-warning"><?=$this->lang->line('add_performance_reward')?></a>
    </p>
</div>
<?php else: ?>
<form id="prod_form" name="prod_form" action="<?=admin_url()?>rewards/sort_order" method="post">
<div class="row">
    <div class="col-md-4">
    	<?=_generate_sub_headline('performance_rewards')?>
    </div>
    <div class="col-md-8 text-right">
        <a href="<?=admin_url()?>rewards/add_reward" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add_performance_reward')?></span></a>
    </div>
</div>
<hr />
<div class="row" id="data-content">  
   	<div class="col-md-12">
        <div class="box-info capitalize">
			<?php foreach ($rewards as $v): ?>
            <div class="row">   
                <div class="col-md-1">
                <?=form_dropdown('reward-' . $v['id'],$sort, $v['sort_order'], 'class="form-control"')?>
                </div>
                <div class="col-md-8">
                    <h5><a href="<?=admin_url()?>rewards/update_reward/<?=$v['id']?>"><?=$v['rule']?></a></h5>
                </div>
                <div class="col-md-3 text-right">
                    <a data-href="<?=admin_url()?>rewards/delete_reward/<?=$v['id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i></a>
                    <a href="<?=admin_url()?>rewards/update_reward/<?=$v['id']?>" class="btn btn-default"><i class="fa fa-edit"></i></a>
                </div>
            </div>
            <hr />
            <?php endforeach; ?>
            <div class="row">
            	<div class="col-md-12">
                	<button class="btn btn-primary" type="submit"><?=$this->lang->line('update_sort_order')?></button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<?php endif; ?>