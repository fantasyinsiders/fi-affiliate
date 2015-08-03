<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($rows)): ?>
<div class="alert alert-warning">
	<h2 class="text-warning"><?=$this->lang->line('no_members_found')?></h2>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    	<a href="<?=admin_url()?>members/add_member" class="btn btn-warning"><?=$this->lang->line('add_member')?></a>
    </p>
</div>
<?php else: ?>
<form id="form" name="prod_form" action="<?=admin_url()?>search/advanced/<?=$post_data['next_offset']?>" method="post">
<div class="row">
    <div class="col-md-4">
    	<h2 class="sub-header block-title"><i class="fa fa-search"></i> <?=$this->lang->line('search_term')?>: "<?=$post_data['search_term']?>"</h2>
    </div>
    <div class="col-md-8 text-right">
    	<?php if ($offset > 0): ?>
    	<a href="javascript:history.go(-1)" class="btn btn-primary"><i class="fa fa-chevron-left"></i> <?=$this->lang->line('previous_results')?></a>
    	<?php endif; ?>
		<?php if ($post_data['next_offset'] < $total_rows): ?>
    	<button type="submit" class="btn btn-primary"><i class="fa fa-chevron-right"></i> <?=$this->lang->line('next_results')?></button>
    	<?php endif; ?>
    </div>
</div>
<hr />
<div class="row">    
   	<div class="col-md-12">
    	<div class="box-info" style="overflow:auto;">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                	<?php foreach ($post_data['search_fields_members'] as $c): ?>
                    <th><?=$this->lang->line($c)?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody> 
                <?php foreach($rows as $v):?>
                <tr style="cursor:pointer"  onclick="window.location='<?=admin_url()?>members/update_member/<?=$v['member_id']?>'">
                	<?php foreach ($post_data['search_fields_members'] as $c): ?>   
                	<td>
                    <?php if ($c == 'status'): ?>
                    <?php if ($v['status'] == '1'): ?>
                    <span class="label label-success"><?=$this->lang->line('active')?></span>
                    <?php else: ?>
                    <span class="label label-warning"><?=$this->lang->line('inactive')?></span>
                    <?php endif; ?>
                    <?php else: ?>
                    <?=highlight_phrase($v[$c], $post_data['search_term'], '<span style="color:#F00">', '</span>')?>
					
                    <?php endif; ?>
                    </td>
                   	<?php endforeach; ?>
                    <td class="text-right">
                    	<a data-href="<?=admin_url()?>members/delete_member/<?=$v['member_id']?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger" title="<?=$this->lang->line('delete')?>"><i class="fa fa-trash-o"></i></a>
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="<?=count($post_data['search_fields_members']) + 1?>" class="text-center">
						<p><small class="text-muted"><?=$total_rows?> <?=$this->lang->line('total_rows')?></small></p>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    </div>
</div>

<?php foreach ($post_data as $b => $c): ?>
<?php if ($b == 'search_fields_members' || $b == 'search_fields_commissions'): ?>
<input type="hidden" name="<?=$b?>" value="<?=base64_encode(serialize($c))?>" />
<?php else: ?>
<input type="hidden" name="<?=$b?>" value="<?=$c?>" />
<?php endif; ?>
<?php endforeach; ?>
</form>
<?php endif; ?>