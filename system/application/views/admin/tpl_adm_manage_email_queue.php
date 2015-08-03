<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($email_queue)): ?>
<div class="alert alert-warning">
	<h2 class="text-warning"><?=$this->lang->line('no_emails_found')?></h2>
    <p><a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a></p>
</div>
<?php else: ?>
<form id="form" name="prod_form" action="<?=admin_url()?>email_queue/update_email_queue" method="post">
<div class="row">
    <div class="col-lg-4">
    	<?=_generate_sub_headline($function)?>
    </div>
    <div class="col-lg-8 text-right">
    	<?=_previous_next('previous', 'email_queue', $pagination['previous'], true);?>
        <a data-href="<?=admin_url()?>email_queue/delete_queue/" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger" title="<?=$this->lang->line('delete')?>"><i class="fa fa-trash-o"></i> <?=$this->lang->line('delete_queue')?></a>
        <a href="<?=admin_url()?>email_queue/flush_queue" class="btn btn-primary loading" data-loading-text="<?=$this->lang->line('please_wait')?>"><i class="fa fa-refresh"></i> <?=$this->lang->line('flush_queue')?></a>
        <?=_previous_next('next', 'email_queue', $pagination['next'], true);?>
   </div>
</div>
<hr />
<div class="row">    
   	<div class="col-lg-12">
    	<div class="box-info">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width: 5%"></th>
                    <th style="width: 10%" class="text-center"><a href="<?=$sort_header?>/send_date/<?=$where_column?>/<?=$show_where_value?>" class="sortable"><?=$this->lang->line('date')?></a></th>
                    <th style="width: 10%" class="hidden-xs text-center"><a href="<?=$sort_header?>/recipient_name/<?=$where_column?>/<?=$show_where_value?>" class="sortable"><?=$this->lang->line('name')?></a></th>                    
                    <th style="width: 70%"><a href="<?=$sort_header?>/subject/<?=$where_column?>/<?=$show_where_value?>" class="sortable"><?=$this->lang->line('subject')?></a></th>
                    <th style="width: 5%">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($email_queue as $v):?>
                <tr>
                    <td class="text-center"><input name="email[]" type="checkbox" id="email[]" value="<?=$v['eqid']?>"/></td>
                    <td class="text-center"><small class="text-muted"><?=_show_date($v['send_date'])?></small></td>
					<td class="hidden-xs text-center">
                    	<small class="text-muted"><?=limit_chars($v['recipient_name'], 20)?></small>
                    	<br />
                        <small class="text-muted"><?=limit_chars($v['recipient_email'], 20)?></small>
                    </td>
                    <td>
                        <h5><?=limit_chars($v['subject'], 50)?></h5>
                        <?php if ($sts_email_show_email_content_queue == 1): ?>
                        <small class="text-muted">
                        <?php if (!empty($v['html_body'])): ?>
                        <?=word_limiter(strip_tags($v['html_body']), 100)?>
                        <?php elseif (!empty($v['text_body'])): ?>
                        <?=word_limiter(nl2br($v['text_body']), 100)?>
                        <?php endif; ?>
                        </small>
                        <?php endif; ?>
                    </td>
                    <td class="text-right">
                        <div class="text-right">
                            <a data-href="<?=admin_url()?>email_queue/delete_email/<?=$v['eqid']?>/2/<?=$offset?>/<?=$sort_order?>/<?=$sort_column?>/<?=$where_column?>/<?=$show_where_value?>/<?=$where_column2?>/<?=$where_value2?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-sm btn-danger" title="<?=$this->lang->line('delete')?>"><i class="fa fa-trash-o"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center">
                        <input type="checkbox" class="check-all" name="check-all" />
                    </td>
                    <td colspan="2">
                        <div class="input-group">
                            <button class="btn btn-primary" type="submit"><?=$this->lang->line('delete')?></button></span>
                        </div>
                    </td>
                    <td colspan="2" class="hidden-xs">
                    <div class="text-right">
						<?=$pagination['select_rows']?>
                    </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="text-center"><small class="text-muted"><?=$pagination['num_pages']?> <?=$this->lang->line('total_pages')?></small></div>
		<div class="text-center"><?=$pagination['rows']?></div>    
        </div>
    </div> 
</div>
<br />
<input name="redirect" type="hidden" id="redirect" value="<?=$this->uri->uri_string()?>" />
</form>
<?php endif; ?>