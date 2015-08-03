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
        <div class="btn-group text-left hidden-xs">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-sort"></i> <span class="hidden-xs"><?=$this->lang->line('sort_data_by')?></span>
            </button>
            <ul class="dropdown-menu capitalize" role="menu">
                <li><a href="<?=admin_url()?>members/view_members/<?=$offset?>/<?=$next_sort_order?>/member_id/<?=$where_column?>/<?=$where_value?>/<?=$where_column2?>/<?=$where_value2?>"><?=$this->lang->line('member_id')?> <?=$this->lang->line($next_sort_order)?></a></li>
                <li><a href="<?=admin_url()?>members/view_members/<?=$offset?>/<?=$next_sort_order?>/username/<?=$where_column?>/<?=$where_value?>/<?=$where_column2?>/<?=$where_value2?>"><?=$this->lang->line('username')?> <?=$this->lang->line($next_sort_order)?></a></li>
                <li><a href="<?=admin_url()?>members/view_members/<?=$offset?>/<?=$next_sort_order?>/fname/<?=$where_column?>/<?=$where_value?>/<?=$where_column2?>/<?=$where_value2?>"><?=$this->lang->line('first_name')?> <?=$this->lang->line($next_sort_order)?></a></li>
                <li><a href="<?=admin_url()?>members/view_members/<?=$offset?>/<?=$next_sort_order?>/primary_email/<?=$where_column?>/<?=$where_value?>/<?=$where_column2?>/<?=$where_value2?>"><?=$this->lang->line('email_address')?> <?=$this->lang->line($next_sort_order)?></a></li>
                <li><a href="<?=admin_url()?>members/view_members/<?=$offset?>/<?=$next_sort_order?>/comms/<?=$where_column?>/<?=$where_value?>/<?=$where_column2?>/<?=$where_value2?>"><?=$this->lang->line('total_commissions')?> <?=$this->lang->line($next_sort_order)?></a></li>
            </ul>
        </div>
        <a href="<?=admin_url()?>update_session/view_table/table/<?=str_replace('/', ':', $this->uri->uri_string())?>" class="btn btn-primary"><i class="fa fa-list"></i> <span class="hidden-xs"><?=$this->lang->line('view_list')?></span></a>
        <a href="<?=admin_url()?>members/add_member" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add_member')?></span></a>
        <?=_previous_next('next', 'members', $pagination['next'], true);?>
   </div> 
</div>
<hr />
<form id="form" name="prod_form" action="<?=admin_url()?>members/update_members" method="post">
<div class="row" id="members"> 
<?php foreach($members as $v):?>   
   	<div class="col-lg-4  member-box" style="position:absolute;">
    	<div class="box-info wrapper" style="height:auto;">
        	<div class="ribbon-wrapper-green">
			<?php if ($v['status'] == 1): ?>
       		<div class="ribbon-green"><?=$this->lang->line('active')?></div>
			<?php else: ?>
            <div class="ribbon-yellow"><?=$this->lang->line('inactive')?></div>
            <?php endif; ?>
            </div>
        	<div class="row">
                <div class="col-sm-4 text-center"> 
					<?php if (!empty($v['facebook_id'])): ?>
                    <img src="//graph.facebook.com/<?=$v['facebook_id']?>/picture/?type=large" class="img-thumbnail img-responsive data-photo"/> 
                    <?php elseif (!empty($v['photo_file_name']) && file_exists(PUBPATH . '/images/members/' . $v['photo_file_name'])):?>
                    <img src="<?=base_url('js') ?>images/members/<?=$v['photo_file_name']?>" class="img-thumbnail img-responsive data-photo"/>
                    <?php else: ?>
                    <img src="<?=base_url('js') ?>themes/admin/<?=$sts_admin_layout_theme?>/img/profile.png" class="img-thumbnail img-responsive data-photo"/>
                    <?php endif; ?>
                    <h3><input name="user[]" type="checkbox" id="user[]" value="<?=$v['mid']?>"/> <small><?=$v['username']?></small></h3>
                </div>
                <hr class="visible-xs" />
                <div class="col-sm-8">
                    <h5 class="member-name"><a href="<?=admin_url()?>members/update_member/<?=$v['mid']?>"><?=limit_chars($v['fname'] . ' ' . $v['lname'], 25) ?></a></h5>
                    <p><a href="<?=admin_url()?>email_send/member/<?=$v['mid']?>"><i class="fa fa-envelope"></i>  <?=$v['primary_email']?></a> </p>
                    <p><a href="<?=admin_url()?>commissions/view_commissions/0/0/0/member_id/<?=$v['mid']?>" class="capitalize"><i class="fa fa-bar-chart-o"></i> <?=$this->lang->line('commissions')?>: <?=format_amounts($v['comms'], $num_options)?></a></p>
                    <p>&nbsp;
                    <?php if (!empty($v['billing_city']) && !empty($v['billing_state'])): ?>
                    <a href="<?=admin_url()?>members/update_member/<?=$v['mid']?>"><i class="fa fa-map-marker"></i> <?=$v['billing_city']?>, <?=limit_chars($v['billing_state'],15)?></a></p>
                    <?php endif; ?>
                    <hr />
                    <div class="text-right">
                      <a href="<?=admin_url()?>members/update_status/<?=$v['mid']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>" class="btn btn-default">
						  <?php if ($v['status'] == 1): ?>
                          <i class="fa fa-check"></i> 
                          <?php else: ?>
                          <i class="fa fa-exclamation-triangle"></i> 
                          <?php endif; ?>
                      </a>
                      <a href="<?=admin_url()?>members/login_member/<?=$v['mid']?>" target="member-login" class="btn btn-default"><i class="fa fa-lock"></i></a>
                      <a href="<?=admin_url()?>members/update_member/<?=$v['mid']?>" class="btn btn-default" title="<?=$this->lang->line('edit')?>"><i class="fa fa-pencil"></i></a>
                      <a data-href="<?=admin_url()?>members/delete_member/<?=$v['mid']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i></a>
                      </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>    
</div>  
<hr />
<div class="row">
	<div class="col-lg-4 col-md-6">
		<div class="input-group">
            <span class="input-group-addon"><input type="checkbox" class="check-all" name="check-all" /> <?=$this->lang->line('mark_checked_as')?></span>
            <select name="change-status" class="form-control">
                <option value="1"><?=$this->lang->line('active')?></option>
                <option value="0"><?=$this->lang->line('inactive')?></option>
                <option value="delete"><?=$this->lang->line('deleted')?></option>
            </select> <span class="input-group-btn">
            <button class="btn btn-primary" type="submit"><?=$this->lang->line('go')?></button></span>
        </div>
	</div>
    <div class="col-lg-5 visible-lg text-center"><small class="text-muted"><?=$pagination['num_pages']?> <?=$this->lang->line('total_pages')?></small></div>
    <div class="col-lg-3 col-md-6 text-right">
    <?=$pagination['select_rows']?>
    </div>
</div>    
<div class="text-center"><?=$pagination['rows']?></div>    
<br />
<input type="hidden" name="redirect" value="<?=$this->uri->uri_string()?>" /> 
</form>   
<script>
$(document).ready(function() {		
	$("#toggle").click(function() {
	$("#add_block").toggle(400);
	});
}); 
</script> 

<script src="<?=path_url();?>themes/admin/<?=$sts_admin_layout_theme?>/third/masonry/masonry.js"></script>    
<script>
var msnry = new Masonry( '#members', {
  itemSelector: '.member-box',
  isAnimated: true
});

</script> 

<?php endif; ?>