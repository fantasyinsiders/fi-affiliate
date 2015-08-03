<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="col-lg-12">
	<?php if (empty($rows)): ?>
    <div class="alert alert-warning text-capitalize animated shake">
    	<h3><i class="fa fa-exclamation-triangle"></i> <?=$this->lang->line('no_payments_found')?></h3>
    	<p><a href="javascript:history.go(-1)" class="btn btn-warning"><i class="fa fa-chevron-left"></i> <?=$this->lang->line('go_back')?></a></p>
    </div>
	<?php else: ?>
	<div class="panel panel-default animated fadeInDown">
        <div class="panel-heading text-capitalize">
        	<div class="additional-btn">
            <a href="javascript:window.location.reload()"><i class="fa fa-refresh"></i> </a>
            </div>
			<?=$page_title?>
        </div>
        <div class="panel-body">
			<table class="table table-striped table-hover">
                <thead>
                    <tr class="text-capitalize">
                        <th class="text-center" style="width:5%"><a href="<?=$sort_header?>/id"><?=$this->lang->line('id')?></a></th>
                        <th class="text-center" style="width:20%"><a href="<?=$sort_header?>/payment_date"><?=$this->lang->line('date_paid')?></a></th>
                        <th style="width:60%"><a href="<?=$sort_header?>/payment_details"><?=$this->lang->line('details')?></a></th>
                        <th class="text-center" style="width:15%"><a href="<?=$sort_header?>/payment_amount"><?=$this->lang->line('amount')?></a></th>
                    </tr>
                </thead>
                <tbody>
                	<?php foreach ($rows as $v): ?>
                    <tr>
                        <td class="text-center"><?=$v['id']?></td>
                        <td class="text-center"><?=_show_date($v['payment_date'])?></td>
                        <td><?=$v['payment_details']?></td>
                        <td class="text-center"><?=format_amounts($v['payment_amount'], $num_options)?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="text-center"><?=$pagination_rows?></div>
        </div>
	</div>
</div>
<?php endif; ?>