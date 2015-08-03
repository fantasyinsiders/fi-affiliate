<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($list_members)): ?>
<div class="alert alert-warning">
	<h3 class="text-warning"><?=$this->lang->line('no_members_found')?></h3>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    	<a href="<?=admin_url()?>mailing_lists/view_mailing_lists" class="btn btn-warning"><?=$this->lang->line('view_mailing_lists')?></a>
    </p>
</div>
<?php else: ?>
<form name="prod_form" action="<?=admin_url()?>mailing_lists/update_list_members/<?=$mailing_list_id?>" method="post">
<div class="row">
    <div class="col-md-4">
    	<?=_generate_sub_headline($page_title)?>
    </div>
    <div class="col-md-8 text-right">
        <a href="<?=admin_url()?>mailing_lists/view_mailing_lists" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_mailing_lists')?></span></a>
    </div>
</div>
<hr />
<div class="row data-row">    
   	<div class="col-md-12">
    	<div class="box-info">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                	<th style="width:10%" class="text-center"><a href="<?=$sort_header?>/sequence_id/<?=$mailing_list_id?>" class="sortable"><?=$this->lang->line('sequence')?></a></th>
                    <th style="width:75%"><a href="<?=$sort_header?>/member/<?=$mailing_list_id?>" class="sortable"><?=$this->lang->line('member_name')?></a></th>
                    <th style="width:20%" class="text-center"><a href="<?=$sort_header?>/send_date/<?=$mailing_list_id?>" class="sortable"><?=$this->lang->line('next_send_date')?></a></th>
                </tr>    
        	</thead>
            <tbody>
                <?php foreach ($list_members as $v): ?>
                <tr>
                	<td class="text-center">
           				<?php if (empty($v['send_date'])): ?>
						<?=$this->lang->line('done_finished')?>	
						<?php else: ?>
                        <?=form_dropdown('sequence-'.$v['member_id'], $total_follow_ups, $v['sequence_id'], 'class="form-control"')?>
                        <?php endif; ?>
               		</td>
                    <td><h5><a href="<?=admin_url()?>members/update_member/<?=$v['member_id']?>"><?=$v['member']?></a></h5></td>
                    <td class="text-center">
						<?php if (empty($v['send_date'])):?>
                        <?=$this->lang->line('done_finished')?>
                        <?php else: ?>
                        <?=_show_date($v['send_date'], true)?>
                        <?php endif; ?>
                	</td>
                </tr>
                <?php endforeach; ?>
            </tbody>  
            <tfoot>
                <tr>
                	<td colspan="2">
	                    <button type="submit" class="btn btn-success"><?=$this->lang->line('update_sequence')?></button>
                    </td>
                    <td class="hidden-xs text-right">
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
<input type="hidden" name="redirect" value="<?=$this->uri->uri_string()?>" />
</form>
<?php endif; ?>