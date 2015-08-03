<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript">
$(function() {
	
var profiles =
	{
		windowCenter:
		{
			height:700,
			width:800,
			center:1,
			resizable:1,
			scrollbars:1
		}
	};
	$(".popupwindow").popupwindow(profiles);

});

function ConfirmDelete(id){
	var answer=confirm("<?=addslashes($this->lang->line('are_you_sure_you_want_to_do_this'))?>")
	if(answer) {
		$("#events-content").load("<?=modules_url()?>module_affiliate_marketing_member_events/delete/"+id+"/3/<?=$month?>/<?=$day?>/<?=$year?>");	
	} 
}

function ChangeEventStatus(id) { 
	$("#inactive_events-content").load("<?=modules_url()?>module_affiliate_marketing_member_events/change_status/"+id+"/3/<?=$month?>/<?=$day?>/<?=$year?>");	
}
</script>
<?php if (empty($events)): ?>
<div align="center" class="none-found"><?=$this->lang->line('no_events_found')?></div>
<?php else: ?>
<div id="inactive_events-content"></div>
<fieldset><legend><?=$this->lang->line('inactive_events')?></legend>


<table width="100%" border="0" cellspacing="0" cellpadding="5"  id="admin-table" class="table-form2">  

  <?php foreach ($events as $v): ?>
  <tr>
    <td width="82%">
      <strong style="font-size:16px"><?=$v['member_event_title']?></strong><br />
      <div style="border-bottom: 1px solid #ddd; margin-bottom:7px; padding-bottom: 5px;"><div style="float:right"><strong class="capitalize"><?=$this->lang->line('time')?>:</strong> <?=$v['date']?> <?=$v['start_time']?> - <?=$v['end_time']?></div>
      <strong class="capitalize"><?=$this->lang->line('location')?>:</strong> <?=$v['member_event_location']?>
      </div>
      <strong class="capitalize"><?=$this->lang->line('description')?></strong><br />
      <?=$v['member_event_description']?></td>
    <td width="18%" align="center">
    <label class="tooltip">
          <a href="javascript:ChangeEventStatus('<?=$v['id']?>')">
		  <img src="<?=base_url();?>themes/admin/<?=$sts_admin_layout_theme?>/images/<?=change_boolean($v['status'], 'Warning', 'Checked')?>.png" alt="" class="programIcons" />          </a>
           <span><?=change_boolean($v['status'], $this->lang->line('active'), $this->lang->line('inactive'))?></span></label>
    <label class="tooltip">
			<a href="javascript:ConfirmDelete('<?=$v['id']?>')">
            	<img src="<?=base_url();?>themes/admin/<?=$sts_admin_layout_theme?>/images/Delete.png" class="programIcons"/></a>
                <span><?=$this->lang->line('delete')?></span></label>  
                
      <label class="tooltip">
            <a href="<?=modules_url()?>module_affiliate_marketing_member_events/edit/<?=$v['id']?>/<?=$month?>_<?=$day?>_<?=$year?>">
            	<img src="<?=base_url();?>themes/admin/<?=$sts_admin_layout_theme?>/images/Edit2.png" class="programIcons" /></a>
                <span><?=$this->lang->line('edit')?></span></label>             
    </td>
  </tr>
  <?php endforeach; ?>
</table>

</fieldset>

<?php endif; ?>