<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="col-lg-12">
	<?php if (empty($rows)): ?>
    <div class="alert alert-warning text-capitalize animated shake">
    	<h3><i class="fa fa-exclamation-triangle"></i> <?=$this->lang->line('no_reports_found')?></h3>
    	<p><a href="javascript:history.go(-1)" class="btn btn-warning"><i class="fa fa-chevron-left"></i> <?=$this->lang->line('go_back')?></a></p>
    </div>
	<?php else: ?>
	<div class="panel panel-default animated fadeInDown">
        <div class="panel-heading text-capitalize">
			<?=$page_title?>
        </div>
        <div class="panel-body">
			<table class="table table-striped table-hover">
                <thead>
                    <tr class="text-capitalize">
                        <th style="width:80%"><?=$this->lang->line('report_name')?></th>
                        <th style="width:20%"></th>
                    </tr>
                </thead>
                <tbody>
                	<?php foreach ($rows as $v): ?>
                    <tr>
                        <td><h5><a href="<?=site_url('members')?>/report/module_member_reporting_<?=$v['module_file_name']?>/<?=$current_month?>/<?=$current_year?>"><?=$this->lang->line($v['module_name'])?></a></h5></td>
                        <td class="text-right"><a href="<?=site_url('members')?>/report/module_member_reporting_<?=$v['module_file_name']?>/<?=$current_month?>/<?=$current_year?>" class="btn btn-sm btn-default"><i class="fa fa-refresh"></i> <?=$this->lang->line('generate')?></a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="text-center"><?=$pagination_rows?></div>
        </div>
	</div>
</div>
<?php endif; ?>