<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="prod_form"  method="post" class="form-horizontal" action="<?=admin_url()?>scaled_commissions/update_scaled_commissions" role="form">
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline($module)?>
    </div>
    <div class="col-md-8 text-right">        
        <button name="toggle" id="toggle" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add_scaled_commission')?></span></button>
    </div>
</div>
<hr />
<div class="row">   
	<div class="col-md-12">
    	<div class="box-info">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width:15%" class="text-center"><?=$this->lang->line('minimum_sale_amount')?></th>
                    <th style="width:15%" class="text-center"><?=$this->lang->line('maximum_sale_amount')?></th>
                    <th style="width:15%" class="text-center"><?=$this->lang->line('commission')?></th>
                	<th style="width:15%" class="text-center"><?=$this->lang->line('type')?></th>
                    <th style="width:15%" class="text-center"><?=$this->lang->line('level')?></th>
                    <th style="width:15%" class="text-center"><?=$this->lang->line('active')?></th>
                    <th style="widht:10%"></th>
                </tr>    
        	</thead>
            <tbody>
          		<tr>
                    <td><input name="min_amount-0" type="text" id="min_amount-0" value=""  class="form-control" /></td>
                    <td><input name="max_amount-0" type="text" id="max_amount-0" value=""  class="form-control" /></td>
                    <td><input name="comm_amount-0" type="text" id="comm_amount-0" value="" class="form-control"/></td>
                    <td><?=form_dropdown('type-0', array('flat' => $this->lang->line('flat'), 'percent' => $this->lang->line('percent')), '', 'class="form-control"')?></td>
                    <td><?=form_dropdown('level-0', $levels, '', 'class="form-control"')?></td>
                    <td class="text-center"><input name="status-0" type="checkbox" id="status-0" value="1" /></td>
                    <td>&nbsp;</td>
                </tr>
            	<?php if (!empty($scaled_commissions)): ?>
                <?php foreach ($scaled_commissions as $v): ?>
                <tr>
                    <td><input name="min_amount-<?=$v['id']?>" type="text" id="min_amount-<?=$v['id']?>" value="<?=$v['min_amount']?>"  class="form-control" /></td>
                    <td><input name="max_amount-<?=$v['id']?>" type="text" id="max_amount-<?=$v['id']?>" value="<?=$v['max_amount']?>"  class="form-control" /></td>
                    <td><input name="comm_amount-<?=$v['id']?>" type="text" id="comm_amount-<?=$v['id']?>" value="<?=$v['comm_amount']?>" class="form-control"/></td>
                    <td><?=form_dropdown('type-' . $v['id'], array('flat' => $this->lang->line('flat'), 'percent' => $this->lang->line('percent')), $v['type'], 'class="form-control"')?></td>
                    <td><?=form_dropdown('level-' . $v['id'], $levels, $v['level'], 'class="form-control"')?></td>
                    <td class="text-center"><input name="status-<?=$v['id']?>" type="checkbox" id="status-<?=$v['id']?>" value="1" <?php if ($v['status'] == 1):?> checked <?php endif; ?> /></td>
                    <td class="text-center">
                    	<a data-href="<?=admin_url()?>scaled_commissions/delete_scaled_commission/<?=$v['id']?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i></a>
                    </td>
                </tr>
               	<?php endforeach; ?>
                <?php endif; ?>
                <tfoot>
                	<td colspan="7" class="text-right">
                    	<button class="btn btn-primary" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button>
                	</td>        
                </tfoot>
        	</tbody>
    	</table>
        </div>
    </div>
</div>
</form>