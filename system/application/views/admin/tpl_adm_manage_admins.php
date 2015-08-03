<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($admins)): ?>
<div class="alert alert-warning">
	<h2 class="text-warning"><?=$this->lang->line('no_admins_found')?></h2>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    	<a href="<?=admin_url()?>admin_users/add_admin" class="btn btn-warning"><?=$this->lang->line('add_admin')?></a>
    </p>
</div>
<?php else: ?>
<form id="form" name="prod_form" action="<?=admin_url()?>admin_users/update_admins" method="post">
<div class="row">
    <div class="col-md-4">
    <?=_generate_sub_headline('admin_users')?>
    </div>
    <div class="col-md-8 text-right">
    	<?=_previous_next('previous', 'admin_users', $pagination['previous'], true);?>
        <a href="<?=admin_url()?>admin_users/add_admin" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add_admin')?></span></a>
    	<?=_previous_next('next', 'admin_users', $pagination['next'], true);?>
    </div>
</div>
<hr />
<div class="row data-row">    
   	<div class="col-md-12">
    	<div class="box-info">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width:5%"></th>
                    <th style="width:10%" class="hidden-xs">&nbsp;</th>
                    <th style="width:10%"><?=$this->lang->line('status')?></th>
                    <th style="width:20%" class="hidden-xs"><a href="<?=$sort_header?>/fname" class="sortable"><?=$this->lang->line('admin_name')?></a></th>
                    <th style="width:25%"><a href="<?=$sort_header?>/username" class="sortable"><?=$this->lang->line('username')?></a></th>
                    <th style="width:15%"><a href="<?=$sort_header?>/primary_email" class="sortable"><?=$this->lang->line('email_address')?></a></th>
                    <th style="width:15%">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($admins as $admin):?>
                <tr>
                    <td class="text-center">
                    <?php if ($admin['admin_id'] != '1'): ?>
                    <input name="user[]" type="checkbox" id="user[]" value="<?=$admin['admin_id']?>"/></td>
					<?php endif; ?>
                    <td class="hidden-xs text-center">
						<?php if (!empty($admin['admin_photo']) && file_exists(PUBPATH . '/images/admins/' . $admin['admin_photo'])):?>
                        <img src="<?=base_url() ?>images/admins/<?=$admin['admin_photo']?>" class="img-circle dash-photo"/>
                        <?php else: ?>
                        <img src="<?=base_url('js')?>images/admins/<?=rand(1,5)?>.jpg"  class="img-circle dash-photo"/> 
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($admin['status'] == 'active'): ?>
                        <span class="label label-success">
                        <?php else : ?>
                        <span class="label label-warning">
                        <?php endif; ?>
                        <?=$this->lang->line($admin['status'])?>
                        </span>
                    </td>
                    <td class="hidden-xs">
                    	<h5><a href="<?=admin_url()?>admin_users/update_admin/<?=$admin['admin_id']?>"><?=$admin['fname'] . ' ' . $admin['lname']?></a></h5>
   						<div class="hidden-xs">
                        	<small class="text-muted capitalize">
							<?php if ($admin['last_login_date']) echo $this->lang->line('last_login_date') . ': '.  _show_date($admin['last_login_date'])?>
                            </small>
                    	</div>
                    </td>
                    <td><h5><a href="<?=admin_url()?>admin_users/update_admin/<?=$admin['admin_id']?>"><?=$admin['username']?></a></h5></td>
                    <td><a href="mailto:<?=$admin['primary_email']?>"><?=$admin['primary_email']?></a></td>
                    <td class="text-right">
						<?php if ($admin['admin_id'] != '1'): ?>
                         <a data-href="<?=admin_url()?>admin_users/delete_admin/<?=$admin['admin_id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i></a>
                        <?php endif; ?>
                        <a href="<?=admin_url()?>admin_users/update_admin/<?=$admin['admin_id']?>" class="btn btn-default hidden-xs" title="<?=$this->lang->line('edit')?>"><i class="fa fa-pencil"></i></a>
                        <a href="mailto:<?=$admin['primary_email']?>" class="btn btn-default hidden-xs"><i class="fa fa-envelope"></i></a>
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center">
                        <input type="checkbox" class="check-all" name="check-all" />
                    </td>
                    <td colspan="4">
                        <div class="input-group">
                            <span class="input-group-addon"><?=$this->lang->line('mark_checked_as')?> </span>
                            <select name="change-status" class="form-control">
                                <option value="active"><?=$this->lang->line('active')?></option>
                                <option value="inactive"><?=$this->lang->line('inactive')?></option>
                                <option value="delete"><?=$this->lang->line('deleted')?></option>
                            </select> <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit"><?=$this->lang->line('go')?></button></span>
                        </div>
                    </td>
                    <td colspan="2" class="text-right hidden-xs">
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
<input name="redirect" type="hidden" id="redirect" value="<?=$this->uri->uri_string()?>" />
</form>
<?php endif; ?>