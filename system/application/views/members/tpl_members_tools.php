<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="col-lg-12">
	<?php if (empty($tools)): ?>
    <div class="alert alert-warning text-capitalize animated shake">
    	<h3><i class="fa fa-exclamation-triangle"></i> <?=$this->lang->line('no_tools_found')?></h3>
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
			<?php foreach ($tools as $v): ?>
            <div class="col-md-6 col-lg-3 tool-box" onclick="window.location='<?=site_url('members')?>/marketing/tools/0/0/0/module_id/<?=$v['module_id']?>/program_id/<?=$this->uri->segment(4)?>'">
                <div class="panel panel-default text-capitalize text-center">
                	<div class="panel-body">
                        <a href="<?=site_url('members')?>/marketing/tools/0/0/0/module_id/<?=$v['module_id']?>/program_id/<?=$this->uri->segment(4)?>"><img src="<?=$v['tool_image']?>" /></a>
                        <h5><strong><?=$this->lang->line($v['module_name'])?></strong></h5>
                        <p><small><?=$this->lang->line('desc_' . $v['module_file_name'])?></small></p>
                	</div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if ($sts_affiliate_enable_ad_trackers == 1): ?> 
            <div class="col-md-6 col-lg-3 tool-box" onclick="window.location='<?=site_url('members')?>/tracking/view'">
                <div class="panel panel-default text-capitalize text-center">
                	<div class="panel-body">
                        <a href="<?=site_url('members')?>/tracking/view">
                        <?php if (file_exists($this->config->item('base_physical_path') . '/themes/main/' . $default_theme . '/img/ad_trackers.png')):?>
						<img src="<?=base_url()?>themes/main/<?=$default_theme?>/img/ad_trackers.png" />
                 		<?php else: ?>
                        <img src="<?=base_url('js')?>images/modules/ad_trackers.png" />
                        <?php endif; ?>
                        </a>
                        <h5><strong><?=$this->lang->line('ad_trackers')?></strong></h5>
                        <p><small><?=$this->lang->line('desc_ad_trackers')?></small></p>
                	</div>
                </div>
            </div>
            <?php endif; ?>
            <?php if ($sts_affiliate_allow_downline_view == 1): ?>
            <div class="col-md-6 col-lg-3 tool-box" onclick="window.open('<?=site_url('members')?>/downline/view', 'popup', 'width=800,height=500, location=no, menubar=no, status=no,toolbar=no, scrollbars=yes, resizable=yes')">
                <div class="panel panel-default text-capitalize text-center">
                	<div class="panel-body">
                        <a href="javascript:void(0)" onclick="window.open('<?=site_url('members')?>/downline/view', 'popup', 'width=800,height=500, location=no, menubar=no, status=no,toolbar=no, scrollbars=yes, resizable=yes')">
                        <?php if (file_exists($this->config->item('base_physical_path') . '/themes/main/' . $default_theme . '/img/view_downline.png')):?>
						<img src="<?=base_url()?>themes/main/<?=$default_theme?>/img/view_downline.png" />
                 		<?php else: ?>
                        <img src="<?=base_url('js')?>images/modules/view_downline.png" />
                        <?php endif; ?>
                        </a>
                        <h5><strong><?=$this->lang->line('view_downline')?></strong></h5>
                        <p><small><?=$this->lang->line('desc_view_downline')?></small></p>
                	</div>
                </div>
            </div>
            <?php endif; ?> 
            <?php if ($sts_affiliate_show_downloads == 1): ?>
            <div class="col-md-6 col-lg-3 tool-box" onclick="window.location='<?=site_url('members')?>/downloads/view'">
                <div class="panel panel-default text-capitalize text-center">
                	<div class="panel-body">
                        <a href="<?=site_url('members')?>/downloads/view">
                        <?php if (file_exists($this->config->item('base_physical_path') . '/themes/main/' . $default_theme . '/img/view_downloads.png')):?>
						<img src="<?=base_url()?>themes/main/<?=$default_theme?>/img/view_downloads.png" />
                 		<?php else: ?>
                        <img src="<?=base_url('js')?>images/modules/view_downloads.png" />
                        <?php endif; ?>
                        </a>
                        <h5><strong><?=$this->lang->line('view_downloads')?></strong></h5>
                        <p><small><?=$this->lang->line('desc_view_downloads')?></small></p>
                	</div>
                </div>
            </div>
            <?php endif; ?>
            <?php if ($sts_affiliate_allow_downline_email == 1): ?>
            <div class="col-md-6 col-lg-3 tool-box" onclick="window.location='<?=site_url('members')?>/downloads/email'">
                <div class="panel panel-default text-capitalize text-center">
                	<div class="panel-body">
                        <a href="<?=site_url('members')?>/downline/email">
                        <?php if (file_exists($this->config->item('base_physical_path') . '/themes/main/' . $default_theme . '/img/email_downline.png')):?>
						<img src="<?=base_url()?>themes/main/<?=$default_theme?>/img/email_downline.png" />
                 		<?php else: ?>
                        <img src="<?=base_url('js')?>images/modules/email_downline.png" />
                        <?php endif; ?>
                        </a>
                        <h5><strong><?=$this->lang->line('email_downline')?></strong></h5>
                        <p><small><?=$this->lang->line('desc_email_downline')?></small></p>
                	</div>
                </div>
            </div>
            <?php endif; ?> 
        </div>
    </div>
    <?php endif; ?>
</div>