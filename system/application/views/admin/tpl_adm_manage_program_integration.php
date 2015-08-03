<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form"  method="post" class="form-horizontal"  role="form">
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline('integration_methods')?>
    </div>
    <div class="col-md-8 text-right">  
    	<?=_previous_next('previous', 'program_integration', $pagination['previous'], true);?>    
        <div class="btn-group text-left hidden-xs">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-sort"></i> <span class="hidden-xs"><?=$this->lang->line('sort_data_by')?></span>
            </button>
            <ul class="dropdown-menu capitalize" role="menu">
                <li><a href="<?=admin_url()?>integration/options/<?=$offset?>/<?=$next_sort_order?>/id/<?=$where_column?>/<?=$where_value?>/<?=$where_column2?>/<?=$where_value2?>"><?=$this->lang->line('id')?> <?=$this->lang->line($next_sort_order)?></a></li>
                <li><a href="<?=admin_url()?>integration/options/<?=$offset?><?=$next_sort_order?>/name/<?=$where_column?>/<?=$where_value?>/<?=$where_column2?>/<?=$where_value2?>"><?=$this->lang->line('name')?> <?=$this->lang->line($next_sort_order)?></a></li>
            </ul> 
        </div>  
        <a href="<?=admin_url()?>integration/integration_profiles" class="btn btn-primary"><i class="fa fa-cog"></i> <span class="hidden-xs"><?=$this->lang->line('integration_profiles')?></span></a>
        <?php if (!defined('JAM_ENABLE_RESELLER_LINKS')): ?>
        <a href=" https://jam.jrox.com/redirect/kb.php?id=integration" class="btn btn-danger" target="_blank"><i class="fa fa-download"></i> <span class="hidden-xs"><?=$this->lang->line('more_integration_options')?></span></a>
        <?php else: ?>
        <a href="<?=admin_url()?>integration/updates" class="btn btn-primary"><i class="fa fa-download"></i> <span class="hidden-xs"><?=$this->lang->line('check_for_updates')?></span></a>
    	<?php endif; ?>
		<?=_previous_next('next', 'program_integration', $pagination['next'], true);?>
    </div>
</div>
<hr />
<div class="row">
	<div class="col-lg-12">
        <div class="box-info" id="data-content">
			<?php foreach ($integration as $v): ?>
            <div class="col-lg-2 col-md-4 col-sm-6 data-box">
                <div class="thumbnail box-info integration-box">
                    <a href="<?=admin_url()?>integration/method/<?=$v['id']?>/<?=$v['code']?>">
						<?php if (!empty($v['img']) && file_exists($base_physical_path . '/images/integration/' . $v['img'])): ?>
                        <img src="<?=base_url('js') . 'images/integration/' . $v['img']?>" />
                        <?php else: ?>
                        <img src="<?=base_url('js')?>images/modules/tools.png"  />
                        <?php endif; ?>
                    </a>
                    <div class="caption">
                    	<p class="text-center capitalize"><a href="<?=admin_url()?>integration/method/<?=$v['id']?>/<?=$v['code']?>"><?=$v['name']?></a></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-12 text-right">
    <?=$pagination['select_rows']?>
    </div>
</div>    
<div class="text-center"><?=$pagination['rows']?></div>   
<div class="text-center visible-xs"><small class="text-muted"><?=$pagination['num_pages']?> <?=$this->lang->line('total_pages')?></small></div>