<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($modules)): ?>
<div class="alert alert-warning">
	<h3><i class="fa fa-warning"></i> <?=$this->lang->line('no_modules_found')?></h3>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    	<a href="<?=admin_url()?>modules/add_module" class="btn btn-warning"><?=$this->lang->line('install_module')?></a>
    </p>
</div>
<?php else: ?>
<div class="row">
    <div class="col-md-4">
    	<?=_generate_sub_headline('manage_modules')?>
    </div>
    <div class="col-md-8 text-right">
		<?=_previous_next('previous', 'modules', $pagination['previous'], true);?>
        <a href="<?=admin_url()?>modules/add_module" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('install_module')?></span></a>
        <?=_previous_next('next', 'modules', $pagination['next'], true);?>
    </div>
</div>
<hr />
<div class="row data-row">    
   	<div class="col-md-12">
    	<div class="box-info">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width:10%" class="hidden-xs text-center"><a href="<?=$sort_header?>/module_id" class="sortable"><?=$this->lang->line('id')?></a></th>
                    <th style="width:10%" class="text-center"><a href="<?=$sort_header?>/module_status" class="sortable"><?=$this->lang->line('status')?></a></th>
                    <th style="width:15%" class="hidden-xs text-center"><a href="<?=$sort_header?>/module_type" class="sortable"><?=$this->lang->line('module_type')?></a></th>
                	 <th style="width:45%"><a href="<?=$sort_header?>/module_name" class="sortable"><?=$this->lang->line('module_name')?></a></th>
                     <th width="20%"></th>
                </tr>    
        	</thead>
            <tbody>
				<?php foreach ($modules as $v): ?>
                <tr>
                	<td class="hidden-xs text-center">
					<?php if (file_exists($base_physical_path . '/images/modules/module_' . $v['module_type'] . '_' . $v['module_file_name'] . '.' . $member_marketing_tool_ext)): ?>
                    <img src="<?=base_url() . 'images/modules/module_' . $v['module_type'] . '_' . $v['module_file_name'] . '.' . $member_marketing_tool_ext?>" class="img-module" />
                    <?php else: ?>
                    <?php if ($v['module_type'] == 'stats_reporting' || $v['module_type'] == 'member_reporting'):?>
                    <i class="fa fa-bar-chart-o fa-3x text-muted"></i>
                    <?php else: ?>
                    <i class="fa fa-wrench fa-3x text-muted"></i>
                    <?php endif; ?>
                    <?php endif; ?>
                    </td>
                    <td class="text-center">
                   		<a href="<?=admin_url()?>modules/change_status/<?=$v['module_id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>">
						<?php if ($v['module_status'] == '0'): ?>
                        <span class="label label-warning"> <?=$this->lang->line('inactive')?></span>
                        <?php elseif ($v['module_status'] == '1') : ?>
                        <span class="label label-success"><?=$this->lang->line('active')?></span>
            			<?php endif; ?> 
                        </a>
            		</td>
                    <td class="hidden-xs text-center"><span class="label label-info"><?=humanize($v['module_type'])?></span></td>
                    <td>
                    	<h5 class="capitalize"><a href="<?=admin_url().'modules/update_module/' . $v['module_id']?>"><?=$v['module_name']?></a></h5>
        				<p><?=$v['module_description']?></p>
        			</td>
                	<td class="text-right">
                    	<a href="<?=admin_url().'modules/update_module/' . $v['module_id']?>" class="hidden-xs btn btn-default"><i class="fa fa-pencil"></i></a>
        				<a data-href="<?=admin_url()?>modules/delete_module/<?=$v['module_id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i></a>
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
</div>
<?php endif; ?>  