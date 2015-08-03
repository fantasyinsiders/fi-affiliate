<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="col-lg-12">
	<?php if (empty($rows)): ?>
    <div class="alert alert-warning text-capitalize animated shake">
    	<h3><i class="fa fa-exclamation-triangle"></i> <?=$this->lang->line('no_commissions_found')?></h3>
    	<p><a href="javascript:history.go(-1)" class="btn btn-warning"><i class="fa fa-chevron-left"></i> <?=$this->lang->line('go_back')?></a></p>
    </div>
	<?php else: ?>
	<div class="panel panel-default animated fadeInDown">
        <div class="panel-heading text-capitalize">
        	<div class="additional-btn">
            <a data-toggle="collapse" data-target=".comm-details"><i class="fa fa-chevron-down"></i></a>
            <a href="javascript:window.location.reload()"><i class="fa fa-refresh"></i> </a>
            </div>
			<?=$page_title?>
        </div>
        <div class="panel-body">
			<table class="table table-striped table-hover">
                <thead>
                    <tr class="text-capitalize">
                    	<th style="width:20%" class="text-center"><a href="<?=$sort_header?>/date/<?=$where_column?>/<?=$show_where_value?>"><?=$this->lang->line('date')?></a></th>
                        <th style="width:10%" class="text-center"><a href="<?=$sort_header?>/comm_status/<?=$where_column?>/<?=$show_where_value?>"><?=$this->lang->line('status')?></a></th>
                        <th style="width:25%"><a href="<?=$sort_header?>/trans_id/<?=$where_column?>/<?=$show_where_value?>"><?=$this->lang->line('transaction_id')?></a></th>
                        <th style="width:12%" class="text-center"><a href="<?=$sort_header?>/commission_amount/<?=$where_column?>/<?=$show_where_value?>"><?=$this->lang->line('commission')?></a></th>
                        <th style="width:13%" class="hidden-xs text-center"><a href="<?=$sort_header?>/sale_amount/<?=$where_column?>/<?=$show_where_value?>"><?=$this->lang->line('sale_amount')?></a></th>
                        <th style="width:10%" class="hidden-xs text-center"><a href="<?=$sort_header?>/date_paid/<?=$where_column?>/<?=$show_where_value?>"><?=$this->lang->line('date_paid')?></a></th>
                        <?php if (!empty($sts_affiliate_enable_ad_trackers)): ?> 
                        <th class="hidden-xs text-center"><a href="<?=$sort_header?>/tracker/<?=$where_column?>/<?=$show_where_value?>"><?=$this->lang->line('tracker')?></a></th>
                        <?php endif; ?> 
                    </tr>
                </thead>
                <tbody>
                	<?php foreach ($rows as $v): ?>
                    <tr>
                        <td class="text-center"><small><?=_show_date($v['date'],true)?></small></td>
                        <td class="text-center">
							<?php if ($v['comm_status'] == 'pending'): ?>
                            <span class="label label-danger">
                            <?php elseif ($v['comm_status'] == 'unpaid'): ?>
                            <span class="label label-warning">
                            <?php else: ?>
                            <span class="label label-success">
                            <?php endif; ?>
                            <?=$v['comm_status']?>
                            </span>
                        </td>
                        <td class="p-<?=$v['comm_id']?>">
                            <h5><a class="collapsed" data-toggle="collapse" data-parent="p-<?=$v['comm_id']?>" data-target="#<?=$v['comm_id']?>"><?=$v['trans_id']?></a></h5>
                            <div id="<?=$v['comm_id']?>" class="collapse fade comm-details">
                            <small>
							<?=$this->lang->line('comm_level')?>: <?=$v['commission_level']?>
                            <?php if (!empty($v['tracking_id'])): ?>
                            <br /><?=$this->lang->line('tracking_id')?>: <a href="<?=site_url('members')?>/tracking/edit/<?=$v['tracking_id']?>"><?=$v['tracking_id']?></a>
                            <?php endif; ?>
                            <br /><?=$this->lang->line('program')?>: <?=$v['program_name']?>
                            <br /><?=$v['s_commission_notes']?>
                            </small>
                            </div>
                        </td>
                        <td class="text-center"><strong><?=$v['s_commission_amount']?></strong ></td>
                        <td class="hidden-xs text-center"><?=$v['s_sale_amount']?></td>
                        <td class="hidden-xs text-center">
                            <?php if (!empty($v['date_paid'])): ?>
                            <a href="<?=site_url('members')?>/payments/view/" class="btn btn-sm btn-default"><?=_show_date($v['date_paid'])?></a>
                            <?php endif; ?>    
                        </td>
                        <?php if (!empty($sts_affiliate_enable_ad_trackers)): ?>
                        <td class="hidden-xs text-center"><?=$v['tracker']?></td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                	<tr>
                    	<td colspan="6">
                        	<a href="<?=$sort_header?>/<?=$this->uri->segment(6,0)?>/comm_status/unpaid/" class="btn btn-sm btn-warning"><i class="fa fa-exclamation-triangle"></i> <?=$this->lang->line('view_unpaid_commissions')?></a>
        					<a href="<?=$sort_header?>/<?=$this->uri->segment(6,0)?>/comm_status/paid/" class="btn btn-sm btn-success"><i class="fa fa-check"></i> <?=$this->lang->line('view_paid_commissions')?></a>
                    	</td>
                    </tr>
                </tfoot>
            </table>
            <div class="text-center"><?=$pagination_rows?></div>
        </div>
	</div>
</div>
<?php endif; ?>