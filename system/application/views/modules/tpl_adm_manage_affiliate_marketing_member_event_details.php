<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (!empty($events)): ?>
<div class="box-info">
	<h2 class="capitalize"><?=$this->lang->line('manage_events_for')?> <?=$current_day?></h2>
   	<div class="the-timeline">
        <ul>
        	<?php foreach ($events as $v): ?>
            <li>
    	        <div class="the-date"><small><?=$v['start_time']?></small></div>
        	    <h4><strong><?=$this->lang->line('location')?>:</strong> <?=$v['member_event_location']?></h4>

                <span class="pull-right">
                	<a data-href="<?=modules_url()?>module_affiliate_marketing_member_events/delete/<?=$v['id']?>/2/<?=$month?>/<?=$day?>/<?=$year?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></a>
                    <?php if ($v['status'] == 1): ?>
                    <a href="javascript:ChangeEventStatus('<?=$v['id']?>')" class="btn btn-sm btn-success"><i class="fa fa-check"></i></a>
                    <?php else:?>
                    <a href="javascript:ChangeEventStatus('<?=$v['id']?>')" class="btn btn-sm btn-warning"><i class="fa fa-exclamation-triangle"></i></a>
                    <?php endif;?>
        			<a href="<?=modules_url()?>module_affiliate_marketing_member_events/edit/<?=$v['id']?>/<?=$month?>/<?=$day?>/<?=$year?>" class="btn btn-sm btn-default"><i class="fa fa-pencil"></i></a>
                </span>
                <p><span class="label label-default"><?=$this->lang->line('time')?>: <?=$v['start_time']?> - <?=$v['end_time']?></p>
                <p><?=$v['member_event_description']?></p>
            </li>
            <?php endforeach;?>
        </ul>
    </div>
</div>
<script>
function ChangeEventStatus(id) {
	$("#events-content").load("<?=modules_url()?>module_affiliate_marketing_member_events/change_status/"+id+"/2/<?=$month?>/<?=$day?>/<?=$year?>");	
}
</script>
<?php endif;?>