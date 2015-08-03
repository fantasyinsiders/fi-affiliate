<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($members)): ?>
<div class="alert alert-warning">
	<h2 class="text-warning"><?=$this->lang->line('no_members_found')?></h2>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    	<a href="<?=admin_url()?>members/add_member" class="btn btn-warning"><?=$this->lang->line('add_member')?></a>
    </p>
</div>
<?php else: ?>
<form id="form" name="prod_form" action="<?=admin_url()?>members/update_members" method="post">
<div class="row">
    <div class="col-md-4">
    	<?php if (!empty($filter_category) && !empty($filter_name)): ?>
    	<h4><span class="label label-info text-capitalize"><?=$this->lang->line('filter')?>: <?=$filter_category?> = <?=$filter_name?></span></h4>
    	<?php else: ?> 
    	<?=_generate_sub_headline('total_members', $total_rows)?>
    <?php endif; ?> 
    </div>
    <div class="col-md-8 text-right">
    	<?=_previous_next('previous', 'members', $pagination['previous'], true);?>
        <a href="<?=admin_url()?>update_session/view_table/card/<?=str_replace('/', ':', $this->uri->uri_string())?>" class="btn btn-primary"><i class="fa fa-picture-o"></i> <span class="hidden-xs"><?=$this->lang->line('view_cards')?></span></a>
    	<a data-href="<?=admin_url()?>members/add_member" data-toggle="modal" data-target="#add-member" href="#" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add_member')?></span></a>
        <?=_previous_next('next', 'members', $pagination['next'], true);?>
   </div> 
</div>
<hr />
<div class="row">    
   	<div class="col-md-12">
    	<div class="box-info">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width:5%"></th>
                    <th style="width:5%" class="text-center hidden-xs"><a href="<?=$sort_header?>/member_id/<?=$where_column?>/<?=$show_where_value?>" class="sortable"><?=$this->lang->line('id')?></a></th>
                    <th style="width:5%" class="hidden-xs">&nbsp;</th>
                    <th style="width:10%" class="text-center"><?=$this->lang->line('status')?></th>
                    <th style="width:20%" ><a href="<?=$sort_header?>/fname/<?=$where_column?>/<?=$show_where_value?>" class="sortable"><?=$this->lang->line('name')?></a></th>
                    <th style="width:10%" class="hidden-xs"><a href="<?=$sort_header?>/username/<?=$where_column?>/<?=$show_where_value?>" class="sortable"><?=$this->lang->line('username')?></a></th>
                    <th style="width:20%" class="hidden-xs"><a href="<?=$sort_header?>/primary_email/<?=$where_column?>/<?=$show_where_value?>" class="sortable"><?=$this->lang->line('email_address')?></a></th>
                    <th style="width:10%" class="text-center hidden-xs"><a href="<?=$sort_header?>/comms/<?=$where_column?>/<?=$show_where_value?>" class="sortable"><?=$this->lang->line('commissions')?></a></th>
                    <th style="width:15%" >&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($members as $v):?>
                <tr>
                    <td class="text-center"><input name="user[]" type="checkbox" id="user[]" value="<?=$v['mid']?>"/></td>
                    <td class="text-center hidden-xs"><?=$v['mid']?></td>
                    <td class="text-center hidden-xs">
                    	<a href="<?=admin_url()?>members/update_member/<?=$v['mid']?>">
						<?php if (!empty($v['facebook_id'])): ?>
                        <img src="//graph.facebook.com/<?=$v['facebook_id']?>/picture" class="img-circle dash-photo"/> 
                        <?php elseif (!empty($v['photo_file_name']) && file_exists(PUBPATH . '/images/members/' . $v['photo_file_name'])):?>
                        <img src="<?=base_url() ?>images/members/<?=$v['photo_file_name']?>" class="img-circle dash-photo"/>
                        <?php else: ?>
                        <img src="<?=base_url('js') ?>themes/admin/<?=$sts_admin_layout_theme?>/img/profile.png" class="img-thumbnail img-responsive"/>
                        <?php endif; ?>
                        </a>
                    </td>
                    <td class="text-center">
                    	<a href="<?=admin_url()?>members/update_status/<?=$v['mid']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>">
                        <?php if ($v['status'] == '1'): ?>
                        <span class="label label-success"> <?=$this->lang->line('active')?></span>
                        <?php else : ?>
                        <span class="label label-warning"><?=$this->lang->line('inactive')?></span>
                        <?php endif; ?>
                        </a>
                    </td>
                    <td>
                    	<h5><a href="<?=admin_url()?>members/update_member/<?=$v['mid']?>"><?=$v['fname'] . ' ' . $v['lname'] ?></a></h5>
   						<div class="hidden-xs capitalize"><small class="text-muted"><?php if ($v['signup_date']) echo $this->lang->line('signup_date') . ': '.  _show_date($v['signup_date'])?></small></div>
                    </td>
                    <td class="hidden-xs"><a href="<?=admin_url()?>members/update_member/<?=$v['mid']?>"><?=$v['username']?></a></td>
                    <td class="hidden-xs"><a href="<?=admin_url()?>email_send/member/<?=$v['mid']?>"><?=$v['primary_email']?></a></td> 
                    <td class="text-center hidden-xs"><a href="<?=admin_url()?>commissions/view_commissions/<?=$v['mid']?>" class="btn btn-sm btn-default"><?=format_amounts($v['comms'], $num_options)?></a></td>
                    <td class="text-right">
                        <div class="btn-group hidden-xs">
                            <a data-href="<?=admin_url()?>members/delete_member/<?=$v['mid']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger" title="<?=$this->lang->line('delete')?>"><i class="fa fa-trash-o"></i></a>
                            <a href="<?=admin_url()?>members/update_member/<?=$v['mid']?>" class="btn btn-default" title="<?=$this->lang->line('edit')?>"><?=$this->lang->line('manage')?></a>
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu text-left capitalize" role="menu">
                            	<li>
            					<a href="<?=admin_url()?>members/update_status/<?=$v['mid']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>">
								<?php if ($v['status'] == 0): ?>
                                <?=$this->lang->line('activate_user')?>
                                <?php else: ?>
                                <?=$this->lang->line('deactivate_user')?>
                                </a>
            					<?php endif; ?>
            					</li>
								<li>
                                <a href="<?=admin_url()?>invoices/view_invoices/0/0/0/0/0/member_id/<?=$v['mid']?>" target="_blank"><?=$this->lang->line('view_invoices')?></a>
                                </li>
                    			<li>
                                <a href="<?=admin_url()?>members/login_member/<?=$v['mid']?>" target="member-login"><?=$this->lang->line('login_to_members_area')?></a>
                                </li>                            
                            </ul>
                        </div>
                        <div class="visible-xs"><a data-href="<?=admin_url()?>members/delete_member/<?=$v['mid']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger" title="<?=$this->lang->line('delete')?>"><i class="fa fa-trash-o"></i></a></div>
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
                                <option value="1"><?=$this->lang->line('active')?></option>
                                <option value="0"><?=$this->lang->line('inactive')?></option>
                                <option value="delete"><?=$this->lang->line('deleted')?></option>
                            </select> <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit"><?=$this->lang->line('go')?></button></span>
                        </div>
                    </td>
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
<input name="redirect" type="hidden" value="<?=$this->uri->uri_string()?>" />
</form>
<?php endif; ?>