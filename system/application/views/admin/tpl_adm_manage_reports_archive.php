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
    	<?=_generate_sub_headline('reports_archive')?>
    </div>
    <div class="col-md-8 text-right">
        <a href="<?=admin_url()?>reports/view_reports" class="btn btn-info"><i class="fa fa-search"></i> <?=$this->lang->line('view_reports')?></a>
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
              <h5><a href="<?=admin_url()?>reports_archive/view_report/<?=$v['id']?>"><?=$v['report_name']?></a></h5>
            </div>
            <div class="col-md-3 text-right">
                <a href="<?=admin_url()?>reports_archive/view_report/<?=$v['id']?>" class="btn btn-default"><i class="fa fa-bar-chart-o"></i> <?=$this->lang->line('view_report')?></a>
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