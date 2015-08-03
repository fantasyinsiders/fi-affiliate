<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="col-lg-12">
	<?php if (empty($rows)): ?>
    <div class="alert alert-warning text-capitalize animated shake">
    	<h3><i class="fa fa-exclamation-triangle"></i> <?=$this->lang->line('no_traffic_found')?></h3>
    	<p><a href="javascript:history.go(-1)" class="btn btn-warning"><i class="fa fa-chevron-left"></i> <?=$this->lang->line('go_back')?></a></p>
    </div>
	<?php else: ?>
	<div class="panel panel-default animated fadeInDown">
        <div class="panel-heading text-capitalize">
        	<div class="additional-btn">
            <a href="javascript:window.location.reload()"><i class="fa fa-refresh"></i> </a>
            </div>
			<?=$page_title?>
        </div>
        <div class="panel-body">
			<table class="table table-striped table-hover">
                <thead>
                    <tr class="text-capitalize">
                        <th class="text-center" style="width:10%"><a href="<?=$sort_header?>/date"><?=$this->lang->line('date')?></a></th>
                        <th style="width:50%"><a href="<?=$sort_header?>/referrer"><?=$this->lang->line('referring_url')?></a></th>
                        <th class="text-center" style="width:10%"><a href="<?=$sort_header?>/os"><?=$this->lang->line('os')?></a></th>
                        <th class="text-center" style="width:10%"><a href="<?=$sort_header?>/browser"><?=$this->lang->line('browser')?></a></th>
                        <?php if (!empty($sts_affiliate_enable_ad_trackers)): ?>
                        <th class="text-center"><?=$this->lang->line('tracker')?></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                	<?php foreach ($rows as $v): ?>
                    <tr>
                        <td class="text-center"><?=_show_date($v['date'])?></td>
                        <td>
							 <?php if (empty($v['referrer'])): ?>
							<?=$this->lang->line('unknown_site')?>
                            <?php else: ?>
                            <a href="<?=$v['referrer']?>" target="_blank"><?=limit_chars($v['referrer'], 75)?></a>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
							<?php if (strstr(strtolower($v['os']), 'windows')):?>
                        	<span class="label label-primary"><i class="fa fa-windows"></i></span>	
    	                    <?php elseif (strstr(strtolower($v['os']), 'apple')):?>
                            <span class="label label-default"><i class="fa fa-apple"></i></span>
                            <?php else: ?>
							<?=$v['os']?>
                        	<?php endif; ?>
                        </td>
                        <td class="text-center"><?=$v['browser']?></td>
                        <?php if (!empty($sts_affiliate_enable_ad_trackers)): ?>
                        <td class="text-center"><?=$v['tracker']?></td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="text-center"><?=$pagination_rows?></div>
        </div>
	</div>
</div>
<?php endif; ?>