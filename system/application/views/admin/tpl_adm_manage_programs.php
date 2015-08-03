<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($programs)): ?>
<div class="alert alert-warning">
	<h2 class="text-warning"><?=$this->lang->line('no_programs_found')?></h2>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    	<a data-href="<?=admin_url()?>programs/add_program" data-toggle="modal" data-target="#add-program" href="#" class="btn btn-warning"><?=$this->lang->line('add_program')?></a>
    </p>
</div>
<?php else: ?>
<form id="form" name="prod_form" action="<?=admin_url()?>programs/update_programs" method="post">
<div class="row">
    <div class="col-md-4">
    	<?php if (!empty($filter_category) && !empty($filter_name)): ?>
    	<h4 class="capitalize"><span class="label label-info"><?=$this->lang->line('filter')?>: <?=$filter_category?> = <?=$filter_name?></span></h4>
    	<?php else: ?> 
    	<?=_generate_sub_headline($function)?>
    <?php endif; ?> 
    </div>
    <div class="col-md-8 text-right">
    	<?=_previous_next('previous', 'programs', $pagination['previous'], true);?>
        <div class="btn-group text-left hidden-xs">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-sort"></i> <span class="hidden-xs"><?=$this->lang->line('sort_data_by')?></span>
            </button>
            <ul class="dropdown-menu capitalize" role="menu">
                <li><a href="<?=admin_url()?>programs/view_programs/<?=$offset?>/<?=$next_sort_order?>/program_name/<?=$where_column?>/<?=$where_value?>/<?=$where_column2?>/<?=$where_value2?>"><?=$this->lang->line('program_name')?> <?=$this->lang->line($next_sort_order)?></a></li>
                <li><a href="<?=admin_url()?>programs/view_programs/<?=$offset?>/<?=$next_sort_order?>/sort_order/<?=$where_column?>/<?=$where_value?>/<?=$where_column2?>/<?=$where_value2?>"><?=$this->lang->line('sort_order')?> <?=$this->lang->line($next_sort_order)?></a></li>
            </ul>
        </div>
        <a href="<?=admin_url()?>programs/add_program" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add_program')?></span></a>
        <?=_previous_next('next', 'programs', $pagination['next'], true);?>
   </div> 
</div>
<hr />
<div class="row" id="programs"> 
<?php foreach($programs as $v):?>   
   	<div class="col-lg-6  program-box">
    	<div class="box-info wrapper">
        	<div class="ribbon-wrapper-green">
			<?php if ($v['program_status'] == 1): ?>
       		<div class="ribbon-green"><?=$this->lang->line('active')?></div>
			<?php else: ?>
            <div class="ribbon-yellow"><?=$this->lang->line('inactive')?></div>
            <?php endif; ?>
            </div>
        	<div class="row">
                <div class="col-sm-4 text-center">  
					<p>
                    	<img src="<?=base_url('js') ?><?=$v['program_photo']?>" class=" img-thumbnail img-responsive data-photo"/>
                    </p>
                    <div class="row">
                    <div class="col-md-6 col-md-offset-3"><?=form_dropdown('program-' . $v['program_id'],$sort, $v['sort_order'], 'class="form-control col-sm-1"')?></div>
                    </div>
                </div>
                <hr class="visible-xs" />
                <div class="col-sm-8 capitalize">
                    <h5 class="program-name"><a href="<?=admin_url()?>programs/update_program/<?=$v['program_id']?>"><?=$v['program_name'] ?></a></h5>
                    <p><small class="text-muted">
                    	<i class="fa fa-sitemap"></i>  <?=$this->lang->line('commission_levels')?>: <span class="label label-default"><?=$v['commission_levels']?></span>
                   		<?php if ($v['enable_pay_per_action'] == 1):?>
						<?=$this->lang->line('per_action')?>: <span class="label label-default"><?=$v['commission_type']?></span>
                        <?php endif; ?>
                        <?php if ($v['enable_pay_per_click'] == 1):?>
                        <?=$this->lang->line('per_click')?>:  <span class="label label-default"><?=$v['ppc_amount']?></span>
                        <?php endif; ?>
                        <?php if ($v['enable_cpm'] == 1):?>
                        <?=$this->lang->line('cpm')?>: <span class="label label-default"><?=$v['cpm_amount']?></span>
            			<?php endif; ?>
                    </small></p>
                    <div class="box-info prg-payout-box hidden-xs"><?=$v['payout']?></div>
                    <hr />
                    <div class="text-right">
                    	
                      	<?php if ($v['program_id'] != '1'): ?>
                        <a href="<?=admin_url()?>programs/update_status/<?=$v['program_id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>" class="btn btn-default">
						<?php if ($v['program_status'] == 1): ?>
                        <i class="fa fa-check"></i> 
                        <?php else: ?>
                        <i class="fa fa-exclamation-triangle"></i> 
                        <?php endif; ?>
                      	</a>
                      	<?php endif; ?>
                        <a href="<?=admin_url()?>programs/update_program/<?=$v['program_id']?>" class="btn btn-default" title="<?=$this->lang->line('edit')?>"><i class="fa fa-pencil"></i></a>
                        <?php if ($v['program_id'] != '1'): ?>
                      	<a data-href="<?=admin_url()?>programs/delete_program/<?=$v['program_id']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i></a>
                    	<?php endif; ?>
                	</div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>    
</div>  
<hr />
<div class="row">
	<div class="col-md-6">
		<button class="btn btn-primary" type="submit"><?=$this->lang->line('update_sort_order')?></button></span>
	</div>
    <div class="col-md-6 text-right">
    <?=$pagination['select_rows']?>
    </div>
</div>    
<div class="text-center"><?=$pagination['rows']?></div>   
<div class="text-center visible-xs"><small class="text-muted"><?=$pagination['num_pages']?> <?=$this->lang->line('total_pages')?></small></div>
<br />  
<input type="hidden" name="redirect" value="<?=$this->uri->uri_string()?>" />
</form>    
      <!--
  
<script src="<?=path_url();?>themes/admin/<?=$sts_admin_layout_theme?>/third/masonry/masonry.js"></script>    
<script>
var msnry = new Masonry( '#programs', {
  itemSelector: '.program-box',
  isAnimated: true
});

</script> 
-->
<?php endif; ?>