<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($traffic)): ?>
<div class="alert alert-warning">
	<h3 class="text-warning"><?=$this->lang->line('no_traffic_found')?></h3>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    	<a href="<?=admin_url()?>action_commissions/add_action_commission" class="btn btn-warning"><?=$this->lang->line('add_action_commission')?></a>
    </p>
</div>
<?php else: ?>
<form id="form" name="prod_form" action="<?=modules_url()?><?=$module?>/update" method="post">
<div class="row">
    <div class="col-md-4">
    	<?=_generate_sub_headline('affiliate_traffic')?>
    </div>
    <div class="col-md-8 text-right">
        <a href="<?=admin_url()?>reports/view_reports" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('view_reports')?></span></a>
    </div>
</div>
<hr />
<div class="row data-row">    
   	<div class="col-md-12">
    	<div class="box-info">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                	<th width="5%"></th>
                    <th style="width:13%" class="text-center"><a href="<?=$sort_header?>/date" class="sortable"><?=$this->lang->line('date')?></a></th>
                    <th style="width:12%"><a href="<?=$sort_header?>/username" class="sortable"><?=$this->lang->line('username')?></a></th>
                
                    
                    <?php if ($this->config->item('enable_geo_location_api') == true): ?>
                    <th style="width:35%" class="hidden-xs"><a href="<?=$sort_header?>/url" class="sortable"><?=$this->lang->line('url')?></a></th>
                    <th style="width:10%" class="hidden-xs text-center"><a href="<?=$sort_header?>/region" class="sortable"><?=$this->lang->line('region')?></a></th>
                    <th style="width:10%" class="hidden-xs text-center"><a href="<?=$sort_header?>/country" class="sortable"><?=$this->lang->line('country')?></a></th>
                    <?php else: ?>
                    <th style="width:55%" class="hidden-xs"><a href="<?=$sort_header?>/url" class="sortable"><?=$this->lang->line('url')?></a></th>
                    <?php endif; ?>
                    
                    <th style="width:10%" class="text-center"><a href="<?=$sort_header?>/ip_address" class="sortable"><?=$this->lang->line('ip_address')?></a></th>
                    <th style="width:10%" class="hidden-xs text-center"><a href="<?=$sort_header?>/browser" class="sortable"><?=$this->lang->line('browser')?></a></th>
                </tr>    
        	</thead>
            <tbody>
                <?php foreach ($traffic as $v): ?>
                <tr>
                	<td class="text-center"><input name="traffic[]" type="checkbox" id="traffic[]" value="<?=$v['traffic_id']?>"/></td>
                    <td class="text-center"><small class="text-muted"><?=_show_date($v['date'], true)?></small></td>
                    <td><a href="<?=admin_url()?>members/update_member/<?=$v['member_id']?>"><?=$v['username']?></a></td>
                    <td class="hidden-xs">
						<?php if (!empty($v['referrer'])): ?>
                        <a href="<?=$v['referrer']?>" target="_blank"><?=limit_chars($v['referrer'], 60)?></a>          
                        <?php else: ?>
                        <?=$this->lang->line('unknown')?>
                        <?php endif; ?>
                    </td>
                    
                    <?php if ($this->config->item('enable_geo_location_api') == true): ?>
                    <td class="hidden-xs text-center"><?=$v['region']?></td>
                    <td class="hidden-xs text-center"><?=$v['country_code']?></td>
                    <?php endif; ?>
                 
                	<td class="text-center"><?=$v['ip_address']?></td>
                    <td class="hidden-xs text-center"><?=$v['browser']?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>  
            <tfoot>
                <tr>
                	<td class="text-center">
                    <input type="checkbox" class="check-all" name="check-all" />
                    </td>
                    <td colspan="3">
                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash-o"></i> <?=$this->lang->line('delete')?></button>
                    </td>
                    <td colspan="5" class="hidden-xs text-right">
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
<input type="hidden" name="redirect" value="<?=$this->uri->uri_string()?>" />
</form>
<?php endif; ?>