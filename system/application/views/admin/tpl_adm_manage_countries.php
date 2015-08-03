<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($countries)): ?>
<div class="alert alert-warning">
	<h3><i class="fa fa-warning"></i> <?=$this->lang->line('no_countries_found')?></h3>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-danger"><?=$this->lang->line('go_back')?></a>
    </p>
</div>
<?php else: ?>
<form name="prod_form" action="<?=admin_url()?>countries/update_countries" method="post">
<div class="row">
    <div class="col-md-4">
    	<?=_generate_sub_headline('manage_countries')?>
    </div>
    <div class="col-md-8 text-right">
		<?=_previous_next('previous', 'countries', $pagination['previous'], true);?>
        <a href="<?=admin_url()?>countries/add_country" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add_country')?></span></a>
        <?=_previous_next('next', 'countries', $pagination['next'], true);?>
    </div>
</div>
<hr />
<div class="row data-row">    
   	<div class="col-md-12">
    	<div class="box-info">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th width="5%">&nbsp;</th>
                    <th style="width:10%" class="text-center"><a href="<?=$sort_header?>/ship_to/" class="sortable"><?=$this->lang->line('status')?></a></th>
                    <th style="width:65%"><a href="<?=$sort_header?>/country_name/" class="sortable"><?=$this->lang->line('country_name')?></a></th>
                    <th style="width:5%" class="text-center"><a href="<?=$sort_header?>/country_iso_code_2/" class="sortable"><?=$this->lang->line('iso_2')?></a></th>
                    <th style="width:5%" class="text-center"><a href="<?=$sort_header?>/country_iso_code_3/" class="sortable"><?=$this->lang->line('iso_3')?></a></th>
                	<th style="width:10%">&nbsp;</th>
                </tr>    
        	</thead>
            <tbody>
            <?php foreach ($countries as $v): ?>
            	<tr>
                	<td class="text-center"><input name="country[]" type="checkbox" id="country[]" value="<?=$v['country_id']?>"/></td>
                    <td class="text-center">
						<?php if ($v['ship_to'] == '0'): ?>
                        <span class="label label-warning"> <?=$this->lang->line('hide')?></span>
                        <?php elseif ($v['ship_to'] == '1') : ?>
                        <span class="label label-success"><?=$this->lang->line('show')?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <img src="<?=base_url('js')?>images/misc/invisible.gif" class="flag flag-<?=strtolower($v['country_iso_code_2'])?>" alt="<?=$v['country_name']?>" />
						<?=$v['country_name']?> 
                    </td>
                    <td class="text-center"><?=$v['country_iso_code_2']?></td>
                    <td class="text-center"><?=$v['country_iso_code_3']?></td>
                	<td class="text-right">
                    	<a data-href="<?=admin_url()?>countries/delete_country/<?=$v['country_id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i></a>
                        <a href="<?=admin_url()?>countries/update_country/<?=$v['country_id']?>" class="btn btn-default hidden-xs"><i class="fa fa-edit"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center">
                        <input type="checkbox" class="check-all" name="check-all" />
                    </td>
                    <td colspan="4">
                        <div class="input-group">
                            <select name="change-status" class="form-control">
                                <option value="1"><?=$this->lang->line('mark_checked_as_registration_to')?></option>
              					<option value="0"><?=$this->lang->line('mark_checked_as_not_registration_to')?></option>
                            </select> <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit"><?=$this->lang->line('go')?></button></span>
                        </div>
                    </td>
                    <td colspan="2" class="hidden-xs text-right">
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