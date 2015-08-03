<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="col-md-12">
	<?php if (empty($rows)): ?>
    <div class="alert alert-warning text-capitalize animated shake">
    	<h3><i class="fa fa-exclamation-triangle"></i> <?=$this->lang->line('no_tracking_found')?></h3>
        <p>
            <a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
            <a href="<?=site_url('members')?>/tracking/add" class="btn btn-warning"><?=$this->lang->line('add_tracker')?></a>
        </p>
    </div>
    <?php else: ?>      
    <div class="panel panel-default animated fadeInDown">
        <div class="panel-heading text-capitalize">
            <div class="additional-btn">
            <a href="<?=site_url('members')?>/tracking/add" class="btn btn-default"><i class="fa fa-plus"></i> <?=$this->lang->line('add_tracker')?></a>
            </div>
            <h4><?=$page_title?></h4>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width:10%" class="text-center hidden-xs"><a href="<?=$sort_header?>/id" class="sortable"><?=$this->lang->line('id')?></a></th>              
                        <th style="width:85%"><a href="<?=$sort_header?>/name" class="sortable"><?=$this->lang->line('ad_tracker')?></a></th>              
                        <th style="width:5%">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rows as $v):?>
                    <tr>
                        <td class="text-center hidden-xs"><?=$v['id']?></td>
                        <td>
                            <h5><a href="<?=site_url('members')?>/tracking/edit/<?=$v['id']?>"><?=$v['name']?></a></h5>
                            <p><a href="<?=$v['url']?>" target="_blank" class="small"><?=limit_chars($v['url'], '75')?></a></p>
                           
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
                                      <td align="center"> <?=$v['clicks']?></td>	
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
                            <a href="<?=site_url('members')?>/tracking/edit/<?=$v['id']?>" class="btn btn-default hidden-xs tip" data-toggle="tooltip" title="<?=$this->lang->line('edit')?>"><i class="fa fa-pencil"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="hidden-xs text-right">
                            <?=$pagination_rows?>
                        </td>
                    </tr>
                </tfoot>  
            </table>
            <?php if (!empty($pagination['rows'])): ?>
            <div class="text-center"><small><?=$pagination['num_pages']?> <?=$this->lang->line('total_pages')?></small></div>
            <div class="text-center"><?=$pagination_rows?></div>    
            <?php endif; ?>
            <div class="text-right">
                <small>
                    <?=$this->lang->line('CPC')?> - <?=$this->lang->line('cost_per_click') ?> |
                    <?=$this->lang->line('CPA')?> - <?=$this->lang->line('cost_per_action') ?> |
                     <?=$this->lang->line('CPS')?> - <?=$this->lang->line('cost_per_sale') ?> 
                </small>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>