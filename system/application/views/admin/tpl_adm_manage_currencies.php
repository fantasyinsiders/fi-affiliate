<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($currencies)): ?>
<div class="alert alert-warning">
	<h3><i class="fa fa-warning"></i> <?=$this->lang->line('no_currencies_found')?></h3>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-danger"><?=$this->lang->line('go_back')?></a>
    </p>
</div>
<?php else: ?>
<form name="prod_form" action="<?=admin_url()?>currencies/update_currencies" method="post">
<div class="row">
    <div class="col-md-4">
    	<?=_generate_sub_headline('manage_currencies')?>
    </div>
    <div class="col-md-8 text-right">
		<?=_previous_next('previous', 'currencies', $pagination['previous'], true);?>
        <a href="<?=admin_url()?>currencies/add_currency" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add_currency')?></span></a>
        <?=_previous_next('next', 'currencies', $pagination['next'], true);?>
    </div>
</div>
<hr />
<div class="row data-row">     
   	<div class="col-md-12">
    	<div class="box-info">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width:10%" class="text-center"><?=$this->lang->line('default')?></th>
                    <th style="width:45%"><a href="<?=$sort_header?>/title/" class="sortable"><?=$this->lang->line('currency_name')?></a></th>
                    <th style="width:15%" class="text-center hidden-xs"><?=$this->lang->line('format')?></th>
                    <th style="width:10%" class="text-center"><a href="<?=$sort_header?>/code/" class="sortable"><?=$this->lang->line('code')?></a></th>
                    <th style="width:10%" class="text-center"><a href="<?=$sort_header?>/value/" class="sortable"><?=$this->lang->line('value')?></a></th>
                	<th style="width:10%">&nbsp;</th>
                </tr>    
        	</thead>
            <tbody>
            <?php foreach ($currencies as $v): ?>
            	<tr>
                	<td class="text-center">
						<?php if ($v['code'] == $this->config->item('sts_site_default_currency')): ?>
                        <span class="label label-success"><?=$this->lang->line('default')?></span>
                        <?php else: ?>
                        <a href="<?=admin_url()?>currencies/set_default/<?=$v['code']?>">
                        <span class="label label-warning"><?=$this->lang->line('set_as_default')?></span>
                    	</a>
						<?php endif; ?>
                    </td>
                    <td><h5><a href="<?=admin_url()?>currencies/update_currency/<?=$v['currency_id']?>"><?=$v['title']?></a></h5></td>
                    <td class="text-center hidden-xs"><?=$v['symbol_left'] . number_format('9999.99', $v['decimal_places'], $v['decimal_point'], $v['thousands_point']) . $v['symbol_right']?></td>
                    <td class="text-center"><?=$v['code']?></td>
                    <td class="text-center"><?=$v['value']?></td>
                	<td class="text-right">
                    	<?php if ($v['currency_id'] != 1): ?>
                    	<a data-href="<?=admin_url()?>currencies/delete_currency/<?=$v['currency_id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i></a>
                        <?php endif; ?>
                        <a href="<?=admin_url()?>currencies/update_currency/<?=$v['currency_id']?>" class="btn btn-default hidden-xs"><i class="fa fa-edit"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="hidden-xs text-right">
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
</form>
<?php endif; ?>