<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row">
    <div class="col-md-4">
    <?=_generate_sub_headline('manage_member_events')?>
    </div>
    <div class="col-md-8 text-right">
        <a href="<?=admin_url()?>affiliate_marketing/view_affiliate_tools" class="btn btn-primary"><i class="fa fa-chevron-left"></i> <span class="hidden-xs"><?=$this->lang->line('affiliate_marketing_tools')?></span></a>
        <a href="<?=modules_url()?>module_affiliate_marketing_member_events/add" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add_event')?></span></a>
    </div>
</div>
<hr />
<div class="row">    
    <div class="col-md-12">
    	<div class="box-info">
    	<?=$calendar?>
        </div>
    </div>
</div>
<hr />
<div class="row">
	<div class="col-md-12">    
		<div id="events-content"></div>	
	</div>
</div>

<script type="text/javascript">
$(function() {
	$("#events-content").load("<?=modules_url()?>module_affiliate_marketing_member_events/events/<?=$month?>/<?=$day?>/<?=$year?>");
});
function ViewEvents(id){
		$("#events-content").load("<?=modules_url()?>module_affiliate_marketing_member_events/events/<?=$month?>/"+id+"/<?=$year?>");	
}
</script>