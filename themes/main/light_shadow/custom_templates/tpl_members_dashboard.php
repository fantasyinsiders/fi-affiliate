<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="col-md-8">
	<div class="row">
    	<div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading text-capitalize">
                    <a class="pull-right"  data-toggle="collapse" data-target="#icons"><i class="fa fa-chevron-down"></i></a>
                    <?=$this->lang->line('your_dashboard')?>
                </div>
                <div id="icons" class="panel-body collapse in">
                    <?php if ($sts_content_members_dashboard_enable == 1): ?>
                    <div class="row">
                        <?php if ($sts_content_members_dashboard_enable_account_details == 1): ?>
                        <div class="dashboard-icons col-md-4 col-lg-3" onclick="window.location='<?=base_url('members')?>account'">
                            <div class="box-info text-center">
                                <h5><?=$this->lang->line('dashboard_edit_profile')?></h5>
                                <p class="icon"><i class="fa fa-4x fa-edit"></i></p>
                                <p><?=$this->lang->line('desc_dashboard_edit_profile')?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($sts_content_members_dashboard_enable_tools == 1): ?>
                        <div class="dashboard-icons col-md-4 col-lg-3" onclick="window.location='<?=base_url('members')?>marketing'">
                            <div class="box-info text-center">
                            <h5><?=$this->lang->line('dashboard_marketing_tools')?></h5>
                            <p class="icon"><i class="fa fa-4x fa-wrench"></i></p>
                            <p><?=$this->lang->line('desc_dashboard_marketing_tools')?></p>
                        </div>  
                        </div>
                        <?php endif; ?>
                        <?php if ($sts_content_members_dashboard_enable_traffic == 1): ?>
                        <div class="dashboard-icons col-md-4 col-lg-3" onclick="window.location='<?=base_url('members')?>traffic'">
                            <div class="box-info text-center">
                                <h5><?=$this->lang->line('dashboard_referral_traffic')?></h5>
                                <p class="icon"><i class="fa fa-4x fa-car"></i></p>
                            <p><?=$this->lang->line('desc_dashboard_referral_traffic')?></p>
                            </div>
                        </div>  
                        <?php endif; ?>
                        <?php if ($sts_content_members_dashboard_enable_commissions == 1): ?>
                        <div class="dashboard-icons col-md-4 col-lg-3" onclick="window.location='<?=base_url('members')?>commissions'">
                            <div class="box-info text-center">
                                <h5><?=$this->lang->line('dashboard_commissions')?></h5>
                                <p class="icon"><i class="fa fa-4x fa-money"></i></p>
                                <p><?=$this->lang->line('desc_dashboard_commissions')?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($sts_content_members_dashboard_enable_content == 1): ?>
                        <div class="dashboard-icons col-md-4 col-lg-3" onclick="window.location='<?=base_url('members')?>content'">
                           <div class="box-info text-center">
                               <h5><?=$this->lang->line('dashboard_content')?></h5>
                                <p class="icon"><i class="fa fa-4x fa-file-text-o"></i></p>         
                                <p><?=$this->lang->line('desc_dashboard_content')?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($sts_content_members_dashboard_enable_downline == 1): ?>
                        <div class="dashboard-icons col-md-4 col-lg-3" onclick="window.open('<?=base_url('members')?>downline/view', 'popup', 'width=800,height=500, location=no, menubar=no, status=no,toolbar=no, scrollbars=yes, resizable=yes')">
                            <div class="box-info text-center">
                                <h5><?=$this->lang->line('dashboard_downline')?></h5>
                                <p class="icon"><i class="fa fa-4x fa-sitemap"></i></p>
                                <p><?=$this->lang->line('desc_dashboard_downline')?></p>
                            </div>
                         </div>
                         <?php endif; ?>
                        <?php if ($sts_content_members_dashboard_enable_downloads == 1): ?>
                        <div class="dashboard-icons col-md-4 col-lg-3" onclick="window.location='<?=base_url('members')?>downloads'">
                            <div class="box-info text-center">
                                <h5><?=$this->lang->line('dashboard_downloads')?></h5>
                                <p class="icon"><i class="fa fa-4x fa-download"></i></p>
                                <p><?=$this->lang->line('desc_dashboard_downloads')?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($sts_content_members_dashboard_enable_reports == 1): ?>
                         <div class="dashboard-icons col-md-4 col-lg-3" onclick="window.location='<?=base_url('members')?>reports'">
                            <div class="box-info text-center">
                                <h5><?=$this->lang->line('dashboard_reports')?></h5>
                                <p class="icon"><i class="fa fa-4x fa-bar-chart-o"></i></p>
                                <p><?=$this->lang->line('desc_dashboard_reports')?></p>
                             </div>  
                         </div>
                         <?php endif; ?>
                         <?php if ($sts_content_members_dashboard_enable_programs == 1): ?>
                         <div class="dashboard-icons col-md-4 col-lg-3" onclick="window.location='<?=base_url('members')?>programs'">
                             <div class="box-info text-center">
                                <h5><?=$this->lang->line('dashboard_programs')?></h5>
                                <p class="icon"><i class="fa fa-4x fa-th"></i></p>
                                <p><?=$this->lang->line('desc_dashboard_programs')?></p>
                            </div> 
                        </div>
                        <?php endif; ?>
                          <?php if ($sts_content_members_dashboard_enable_referral_payments == 1): ?>
                          <div class="dashboard-icons col-md-4 col-lg-3" onclick="window.location='<?=base_url('members')?>payments'">
                              <div class="box-info text-center">
                                  <h5><?=$this->lang->line('dashboard_referral_payments')?></h5>
                                  <p class="icon"><i class="fa fa-4x fa-edit"></i></p>
                                  <p><?=$this->lang->line('desc_dashboard_referral_payments')?></p>
                              </div>  
                          </div>
                          <?php endif; ?>
                          <?php if ($sts_content_members_video_tutorials): ?>
                          <div class="dashboard-icons col-md-4 col-lg-3" onclick="window.open('<?=$sts_content_members_video_tutorials?>', 'videos')">
                              <div class="box-info text-center">
                                  <h5><?=$this->lang->line('dashboard_affiliate_videos')?></h5>
                                  <p class="icon"><i class="fa fa-4x fa-video-camera"></i></p>
                                  <p><?=$this->lang->line('desc_dashboard_affiliate_videos')?></p>
                              </div>  
                          </div>
                          <?php endif; ?>
                          <?php if ($sts_content_members_help_link): ?>
                          <div class="dashboard-icons col-md-4 col-lg-3" onclick="window.open('<?=$sts_content_members_help_link?>', 'help')">
                              <div class="box-info text-center">
                                  <h5><?=$this->lang->line('dashboard_help_link')?></h5>
                                  <p class="icon"><i class="fa fa-4x fa-question-circle"></i></p>
                                  <p><?=$this->lang->line('desc_dashboard_help_link')?></p>
                              </div>  
                          </div>
                          <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading text-capitalize">
                	<a class="pull-right"  data-toggle="collapse" data-target="#links"><i class="fa fa-chevron-down"></i></a>
                    <?=$this->lang->line('referral_links')?>
                </div>
                <div id="links" class="panel-body collapse in">
                    <p id="aff-link"><?=$this->lang->line('dashboard_general_affiliate_link')?>: <a href="<?=_get_aff_link($this->session->userdata('m_username'))?>" target="_blank"><?=_get_aff_link($this->session->userdata('m_username'))?></a></p>
                    <p id="aff-signup"><?=$this->lang->line('dashboard_affiliate_signup_link')?>: <a href="<?=_get_aff_link($this->session->userdata('m_username'), 'regular') . '/signup'?>" target="_blank"><?=_get_aff_link($this->session->userdata('m_username'), 'regular') . '/signup'?></a></p>
                </div>
            </div>
        </div>
    </div>
    <?php if (!empty($articles)): ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading text-capitalize">
                	<a class="pull-right"  data-toggle="collapse" data-target="#articles"><i class="fa fa-chevron-down"></i></a>
                    <?=$this->lang->line('getting_started')?>
                </div>
                <div id="articles" class="panel-body collapse in">
                    <?php foreach ($articles as $k => $v): ?>
                    <h4><a href="<?=base_url('members')?><?=CONTENT_ROUTE?>/article/<?=$v['article_id']?>"><?=$v['content_title']?></a></h4>
                    <p onclick="window.location='<?=base_url('members')?><?=CONTENT_ROUTE?>/article/<?=$v['article_id']?>'">
                    <?=$v['content_body']?>
                    </p>
                    <p class="readMore text-right"><a href="<?=base_url('members')?><?=CONTENT_ROUTE?>/article/<?=$v['article_id']?>"><span class="label label-info"><?=$this->lang->line('read_more')?></span></a></p>
                    <hr />
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<div class="col-md-4">
    <?php if ($sts_content_members_dashboard_quick_stats == 1): ?>
    <div class="row">
    	<div class="col-md-12">
        	<div class="panel panel-default">
            	<div class="panel-heading text-capitalize">
                	<a class="pull-right"  data-toggle="collapse" data-target="#quickstats"><i class="fa fa-chevron-down"></i></a>
                	<?=$this->lang->line('dashboard_quickstats')?> - <?=date('M Y')?>
                </div>
            	<div id="quickstats" class="panel-body text-capitalize collapse in">
					<p><div class="pull-right"><?=$month_clicks?></div> <?=$this->lang->line('affiliate_clicks')?></p>
                    <hr />
                    <p><div class="pull-right"><?=format_amounts($month_clicks_avg, $num_options, true)?></div> <?=$this->lang->line('avg_clicks_per_day')?></p>
                    <hr />
                    <p><div class="pull-right"><?=format_amounts($month_comm, $num_options)?></div> <?=$this->lang->line('commissions')?></p>
                    <hr />
                    <p><div class="pull-right"><?=format_amounts($month_comm_avg, $num_options)?></div> <?=$this->lang->line('avg_comm_per_day')?></p>
                    <hr />
                    <p><div class="pull-right"><?=$month_signups?></div> <?=$this->lang->line('affiliate_referrals')?></p>
                    <hr />
        		</div>
            </div>
        </div>
    </div> 
    <?php endif; ?>
    <?php if ($sts_content_members_dashboard_total_stats == 1): ?>
    <div class="row">
    	<div class="col-md-12">
        	<div class="panel panel-default">
            	<div class="panel-heading text-capitalize">
                	<a class="pull-right"  data-toggle="collapse" data-target="#totalstats"><i class="fa fa-chevron-down"></i></a>
                	<?=$this->lang->line('dashboard_totalstats')?>
                </div>
            	<div id="totalstats" class="panel-body text-capitalize collapse in">
					<p><div class="pull-right"><?=$total_clicks?></div> <?=$this->lang->line('total_clicks')?></p>
                    <hr />
                    <p><div class="pull-right"><?=format_amounts($total_comm, $num_options)?></div> <?=$this->lang->line('total_commissions')?></p>
                    <hr />
                    <p><div class="pull-right"><?=$total_referrals?></div> <?=$this->lang->line('total_referrals')?></p>
                    <hr />
        		</div>
            </div>
        </div>
    </div> 
    <?php endif; ?>
    <?php if (!empty($calendar)): ?>
    <div class="row">
        <div class="col-md-12">
        	<div class="panel panel-default">
            	<div class="panel-body">
					<?=$calendar?>
        		</div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($sts_content_members_dashboard_latest_commissions && !empty($latest_commissions)): ?>
    <div class="row">
    	<div class="col-md-12">
        	<div class="panel panel-default">
            	<div class="panel-heading text-capitalize">
                	<a class="pull-right"  data-toggle="collapse" data-target="#latestcomm"><i class="fa fa-chevron-down"></i></a>
                	<?=$this->lang->line('latest_commissions')?>
                </div>
            	<div id="latestcomm" class="panel-body text-capitalize collapse in">
                    <?php foreach ($latest_commissions as $v): ?>
                    	<p><div class="pull-right"><?=_show_date($v['date'])?></div> <?=format_amounts($v['commission_amount'], $num_options)?></p>
                        <hr />
					<?php endforeach; ?>											 
        		</div>
            </div>
        </div>
    </div> 
    <?php endif; ?>
    <?php if ($sts_content_members_dashboard_latest_referrals && !empty($latest_referrals)): ?>
    <div class="row">
    	<div class="col-md-12">
        	<div class="panel panel-default">
            	<div class="panel-heading text-capitalize">
                	<a class="pull-right"  data-toggle="collapse" data-target="#latestref"><i class="fa fa-chevron-down"></i></a>
                	<?=$this->lang->line('last_referrals')?>
                </div>
            	<div id="latestref" class="panel-body text-capitalize collapse in">
                    <?php foreach ($latest_referrals as $v): ?>
                    	<p><div class="pull-right"><?=_show_date($v['signup_date'])?></div> <?=$v['fname'] . ' ' . $v['lname']?></p>
                        <hr />
					<?php endforeach; ?>											 
        		</div>
            </div>
        </div>
    </div> 
    <?php endif; ?>
</div>