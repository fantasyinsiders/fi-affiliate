<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($reports)): ?>
<div class="alert alert-warning">
	<h3 class="text-warning"><?=$this->lang->line('no_reports_found')?></h3>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    </p>
</div>
<?php else: ?>
<div class="row">
    <div class="col-md-4">
    	<?=_generate_sub_headline('stats_reports')?>
    </div>
    <div class="col-md-8 text-right">
    	<div class="btn-group text-left hidden-xs">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-sort"></i> <span class="hidden-xs"><?=$this->lang->line('sort_data_by')?></span>
            </button>
            <ul class="dropdown-menu capitalize" role="menu">
                <li><a href="<?=admin_url()?>reports/view_reports/0/<?=$next_sort_order?>/module_name/<?=$where_column?>/<?=$where_value?>/<?=$where_column2?>/<?=$where_value2?>"><?=$this->lang->line('report_name')?> <?=$this->lang->line($next_sort_order)?></a></li>
            </ul>
        </div>
        <a href="<?=admin_url()?>modules/add_module/stats_reporting" class="btn btn-info"><i class="fa fa-plus"></i> <?=$this->lang->line('add_report')?></a>
    </div>
</div>
<hr />
<div class="row" id="data-content">  
   	<div class="col-md-12">
        <div class="box-info">
        <?php $i = 1 + $offset; ?>
		<?php foreach ($reports as $v): ?>
        <div class="row">   
            <div class="col-md-9 capitalize">
               <h5><a href="<?=modules_url()?>module_<?=$v['module_type']?>_<?=$v['module_file_name']?>/generate"><?=$i?>. <?=$this->lang->line($v['module_file_name'])?></a></h5>
                <p><?=$this->lang->line('desc_'. $v['module_file_name'])?></p>
            </div>
            <div class="col-md-3 text-right">
                <a href="<?=modules_url()?>module_<?=$v['module_type']?>_<?=$v['module_file_name']?>/generate" class="btn btn-default"><i class="fa fa-bar-chart-o"></i> <?=$this->lang->line('view_report')?></a>
            </div>
        </div>
        <hr />
        <?php $i++; ?>
        <?php endforeach; ?>
		<div class="row">
            <div class="col-lg-3"></div>
            <div class="col-lg-6 text-center"><small class="text-muted"><?=$pagination['num_pages']?> <?=$this->lang->line('total_pages')?></small></div>
            <div class="col-lg-3 text-right">
            <?=$pagination['select_rows']?>
            </div>
        </div>    
        <div class="text-center"><?=$pagination['rows']?></div> 
        </div>
	</div>
</div>    
<hr />
<?php endif; ?>