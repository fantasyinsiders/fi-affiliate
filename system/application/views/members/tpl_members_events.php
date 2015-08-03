<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="col-md-4">
	<div class="panel panel-default">
    	<div class="panel-body">
			<?=$calendar?>
		</div>
	</div>
</div>
<div class="col-md-8">
	<div class="panel panel-default">
    	<div class="panel-heading">
        	<h4 class="dashboardHeader" id="dashboard_events_day"><?=$cmonth?> <?=$day ?> <?=$year?></h4> 
        </div>
    	<div class="panel-body">
			<?php if (empty($events)): ?>
            <h5 class="text-capitalize"><?=$this->lang->line('no_events_found')?></h5>
            <?php else: ?>
            <?php foreach ($events as $v): ?>
            <div class="row">
            	<div class="col-md-8">
                <p><strong><?=$v['member_event_title']?></strong></p>
                <strong class="capitalize"><?=$this->lang->line('location')?>:</strong> <?=$v['member_event_location']?>
                <p><?=$v['member_event_description']?></p>
                </div>
                <div class="col-md-4 text-right">
                	<span class="label label-warning"><?=$v['start_time']?> - <?=$v['end_time']?></span>
                </div>
            </div>
            <hr />
            <?php endforeach; ?>
            <?php endif; ?>
		</div>
	</div>
</div>