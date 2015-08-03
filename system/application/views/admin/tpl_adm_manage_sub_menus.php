<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?> 
<form id="form" method="post" enctype="multipart/form-data"> 
<div class="row">
   	<div class="col-lg-4">
    <?=_generate_sub_headline('manage_sub_menus')?>
    </div>
    <div class="col-lg-8 text-right">
    	<a href="<?=admin_url()?>layout/generate_menu" class="btn btn-primary"><i class="fa fa-search"></i> <?=$this->lang->line('menu_maker')?></a>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-lg-12 capitalize">
    	<div class="box-info">
    	<table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th style="width: 30%"><?=$this->lang->line('link_name')?></th>
                    <th style="width: 35%"><?=$this->lang->line('link_url')?></th>
                    <th style="width: 15%"><?=$this->lang->line('options')?></th>
                    <th style="width: 5%" class="text-center"><?=$this->lang->line('sort')?></th>
                    <th style="width: 5%" class="text-center"><?=$this->lang->line('show')?></th>
                    <th style="width: 10%" class="text-center"><?=$this->lang->line('sub')?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input name="menu_name-0" type="text" id="menu_name-0" class="form-control" placeholder="title"/></td>
                    <td><input name="menu_url-0" type="text" id="menu_url-0" class="form-control" placeholder="<?=base_url()?>" /></td>
                    <td><input name="menu_options-0" type="text" id="menu_options-0" class="form-control" placeholder='target="_blank"'/></td>
                    <td class="text-center"><input name="menu_sort_order-0" type="text" id="menu_sort_order-0" value="1" class="form-control"/></td>
                    <td class="text-center"><input name="menu_status-0" type="checkbox" id="menu_status-0" value="1" checked /></td>
                    <td></td>
                </tr>
                <?php foreach ($subs as $v): ?>
                <tr id="row-<?=$v['id']?>">
                    <td><input name="menu_name-<?=$v['id']?>" type="text" id="menu_name-<?=$v['id']?>"  value="<?=$v['menu_name']?>" class="form-control"/></td>
                    <td><input name="menu_url-<?=$v['id']?>" type="text" id="menu_url-<?=$v['id']?>"  value="<?=$v['menu_url']?>" class="form-control"/></td>
                    <td><input name="menu_options-<?=$v['id']?>" type="text" id="menu_options-<?=$v['id']?>"  value="<?=$v['menu_options']?>" class="form-control"/></td>
                    <td class="text-center"><input name="menu_sort_order-<?=$v['id']?>" type="text" id="menu_sort_order-<?=$v['id']?>"  value="<?=$v['menu_sort_order']?>" class="form-control"/></td>
                    <td class="text-center"><input name="menu_status-<?=$v['id']?>" type="checkbox" id="menu_status-<?=$v['id']?>"  value="1" <?php if ($v['menu_status'] == 1): ?>checked<?php endif; ?> /></td>
                    <td class="text-center">
                    	<a href="javascript:removeFormField('#row-<?=$v['id']?>', '#menu_name-<?=$v['id']?>')" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
                   	</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
            	<tr>
                	<td colspan="6" class="text-right"><button type="submit" class="btn btn-success"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button></td>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>
</div>
</form>
<script>
function removeFormField(row, field) {
	$(field).val('');
	$(row).fadeOut(300,function(){$(this).hide();});
}
</script>