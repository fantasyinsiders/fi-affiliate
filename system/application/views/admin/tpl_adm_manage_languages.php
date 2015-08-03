<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($languages)): ?>
<div class="alert alert-warning">
	<h3><i class="fa fa-warning"></i> <?=$this->lang->line('no_languages_found')?></h3>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-danger"><?=$this->lang->line('go_back')?></a>
    </p>
</div>
<?php else: ?>
<div class="row">
    <div class="col-md-4">
    	<?=_generate_sub_headline('manage_languages')?>
    </div>
    <div class="col-md-8 text-right">
		<?=_previous_next('previous', 'languages', $pagination['previous'], true);?>
        <a href="<?=admin_url()?>languages/add_language" class="btn btn-primary"><i class="fa fa-upload"></i> <?=$this->lang->line('add_language')?></a>
        <?=_previous_next('next', 'languages', $pagination['next'], true);?>
    </div>
</div>
<hr />
<div class="row data-row">    
<form name="prod_form" action="<?=admin_url()?>languages/update_languages" method="post">
   	<div class="col-md-12">
    	<div class="box-info">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width:10%" class="text-center"><a href="<?=$sort_header?>/status/" class="sortable"><?=$this->lang->line('status')?></a></th>
                    <th style="width:50%"><a href="<?=$sort_header?>/name/" class="sortable"><?=$this->lang->line('language_name')?></a></th>
                    <th style="width:10%" class="text-center"><a href="<?=$sort_header?>/code/" class="sortable"><?=$this->lang->line('code')?></a></th>
                    <th style="width:10%" class="text-center"><?=$this->lang->line('flag')?></th>
                	<th style="width:15%">&nbsp;</th>
                </tr>    
        	</thead>
            <tbody>
            <?php foreach ($languages as $v): ?>
            	<tr>
                    <td class="text-center">
                    	<?php if ($sts_site_default_language != $v['name']): ?>
                    	<a href="<?=admin_url()?>languages/update_status/<?=$v['language_id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>">
						<?php if ($v['status'] == '0'): ?>
                        <span class="label label-warning"> <?=$this->lang->line('inactive')?></span>
                        <?php else : ?>
                        <span class="label label-success"><?=$this->lang->line('active')?></span>
                        <?php endif; ?>
                        </a>
                        <?php else: ?>
                        <span class="label label-success"><?=$this->lang->line('default')?></span>
						<?php endif; ?>
                        
                    </td>
                    <td>
						<?=$v['name']?> 
                    </td>
                    <td class="text-center"><?=$v['code']?></td>
                    <td class="text-center"><i class="flag-<?=$v['image']?>"></i></td>
                	<td class="text-right">
                    	<?php if ($v['language_id'] != 1): ?>
                    	<a data-href="<?=admin_url()?>languages/delete_language/<?=$v['language_id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i></a>
                        <?php endif; ?>
                        <a href="<?=admin_url()?>languages/update_language/<?=$v['language_id']?>/common" class="btn btn-default tip" data-toggle="tooltip" title="<?=$this->lang->line('edit_common_file')?>"><i class="fa fa-edit"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
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
</form>
</div>
<?php endif; ?>