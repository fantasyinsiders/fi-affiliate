<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($tracking)): ?>
<div class="alert alert-warning">
	<h3 class="text-warning"><?=$this->lang->line('no_tracking_found')?></h3>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    	<a href="<?=admin_url()?>tracking/add_tracking" class="btn btn-warning"><?=$this->lang->line('add_tracking')?></a>
    </p>
</div>
<?php else: ?>
<div class="row">
    <div class="col-md-4">
    	<?=_generate_sub_headline($function)?>
    </div>
    <div class="col-md-8 text-right">
		<?=_previous_next('previous', 'tracking', $pagination['previous'], true);?>
        <a href="<?=admin_url()?>tracking/add_tracking" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add_tracking')?></span></a>
        <?=_previous_next('next', 'tracking', $pagination['next'], true);?>
    </div>
</div>
<hr />
<div class="row data-row">    
   	<div class="col-md-12">
    	<div class="box-info">
       	<table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width:5%" class="text-center hidden-xs"><a href="<?=$sort_header?>/id" class="sortable"><?=$this->lang->line('id')?></a></th>
                    <th style="width:10%" class="text-center hidden-xs"><a href="<?=$sort_header?>/member_id" class="sortable"><?=$this->lang->line('member')?></a></th>              
                    <th style="width:60%"><a href="<?=$sort_header?>/name" class="sortable"><?=$this->lang->line('tracking_name')?></a></th>              
                    <th style="width:25%">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($tracking as $v):?>
                <tr>
                	<td class="text-center hidden-xs"><?=$v['id']?></td>
                    <td class="text-center hidden-xs">
                    <?php if (!empty($v['member_id'])):?>
                    <a href="<?=admin_url()?>members/update_member/<?=$v['member_id']?>"><?=$v['member_id']?></a>
                    <?php else: ?>
                    <?=$this->lang->line('none')?>
					<?php endif; ?>
                    </td>
                    <td>
                    	<h5><a href="<?=admin_url()?>tracking/update_tracking/<?=$v['id']?>"><?=$v['name']?></a></h5>
                    	<p><a href="<?=$v['url']?>" target="_blank" class="small"><?=limit_chars($v['url'], '75')?></a></p>
                        <p style="display:none" id="view-<?=$v['id']?>">
				        	<input onclick="this.select()" value="<?=_public_url() . TRACK_ROUTE . '/' . $v['id']?>" class="form-control" />
 		  				</p>
                        <div class="box-info visible-lg">
                           <table width="100%" border="0" cellspacing="1" cellpadding="5">
                                <tr>
                                      <th class="text-center"><?=$this->lang->line('clicks')?></th>	
                                      <th class="text-center"><?=$this->lang->line('comms')?></th>	
                                      <th class="text-center"><?=$this->lang->line('sales')?></th>
                                      <th class="text-center"><?=$this->lang->line('CPC')?></th>
                                      <th class="text-center"><?=$this->lang->line('CPA')?></th>
                                      <th class="text-center"><?=$this->lang->line('CPS')?></th>
                                      <th class="text-center"><?=$this->lang->line('cost')?></th>
                                      <th class="text-center"><?=$this->lang->line('type')?></th>
                                      <th class="text-center"><?=$this->lang->line('total_cost')?></th>
                                      <th class="text-center"><?=$this->lang->line('net')?></th>
                                      <th class="text-center"><?=$this->lang->line('ROI')?></th>
                                </tr>
                                <tr>
                                  <td align="center"> <?php if (!empty($v['total'])): ?>
                                      <a href="<?=admin_url()?>tracking/view_referrals/<?=$v['id']?>"><?=$v['clicks']?></a>
                                      <?php else: ?>
                                      <?=$v['clicks']?>
                                      <?php endif;?>     
                                  </td>	
                                  <td class="text-center"><?=format_amounts($v['comms'], $num_options)?></td>	
                                  <td class="text-center"><?=format_amounts($v['sales'], $num_options)?></td>
                                  <td class="text-center"><?=format_amounts($v['cpc'], $num_options)?></td>
                                  <td class="text-center"><?=format_amounts($v['cpa'], $num_options)?></td>
                                  <td class="text-center"><?=format_amounts($v['cps'], $num_options)?></td>
                                  <td class="text-center"><?=format_amounts($v['cost'], $num_options)?></td>
                                  <td class="text-center"><?=$this->lang->line($v['cost_type'])?></td>
                                  <td class="text-center"><?=format_amounts($v['total_cost'], $num_options)?></td>
                                  <td class="text-center"><?=format_amounts($v['net'], $num_options)?></td>
                                  <td class="text-center"><?=format_amounts($v['roi'], $num_options, true)?>%</td>
                                </tr>
                          </table>
                      </div>
                    </td>
                    <td class="text-right">
                    	<a data-href="<?=admin_url()?>tracking/delete_tracking/<?=$v['id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i></a>
                        <a href="<?=admin_url()?>tracking/reset_tracking/<?=$v['id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" class="btn btn-default tip hidden-xs" data-toggle="tooltip" title="<?=$this->lang->line('reset')?>"><i class="fa fa-rotate-left"></i></a> 
                        <a href="javascript:ShowCode('<?=$v['id']?>')" class="btn btn-default tip" data-toggle="tooltip" title="<?=$this->lang->line('tracking_get_code')?>"><i class="fa fa-download"></i></a>
                    	<a href="<?=admin_url()?>tracking/update_tracking/<?=$v['id']?>" class="btn btn-default hidden-xs tip" data-toggle="tooltip" title="<?=$this->lang->line('edit')?>"><i class="fa fa-pencil"></i></a>
                    	<a href="<?=admin_url()?>tracking/view_referrals/0/0/0/tracker/<?=$v['id']?>" class="btn btn-default hidden-xs tip" data-toggle="tooltip" title="<?=$this->lang->line('view_referrals')?>"><i class="fa fa-users"></i></a>
                        <a href="<?=modules_url()?>module_stats_reporting_month_ad_tracking_stats/generate/<?=$v['id']?>" class="btn btn-default hidden-xs tip" data-toggle="tooltip" title="<?=$this->lang->line('monthly_reports')?>"><i class="fa fa-bar-chart-o"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="hidden-xs text-right">
						<?=$pagination['select_rows']?>
                    </td>
                </tr>
            </tfoot>  
        </table>
        <?php if (!empty($pagination['rows'])): ?>
    	<div class="text-center"><small class="text-muted"><?=$pagination['num_pages']?> <?=$this->lang->line('total_pages')?></small></div>
		<div class="text-center"><?=$pagination['rows']?></div>    
    	<?php endif; ?>
        </div>
	</div>
</div>
<script>
function ShowCode(id) {
	$("#view-"+id+"").toggle(500);
}
</script>
<?php endif; ?>