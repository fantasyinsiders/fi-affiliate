<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="prod_form"  method="post"  class="form-horizontal" role="form">
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline('global_configuration')?>
    </div>
    <div class="col-md-8 text-right">
    	<a class="btn btn-primary" href="<?=admin_url();?>settings/run_updates"><i class="fa fa-download"></i> <?=$this->lang->line('run_updates')?></a>
		<a class="btn btn-info" href="<?=admin_url();?>license/view"><i class="fa fa-key"></i> <?=$this->lang->line('view_license')?></a>
    </div>
</div>
<hr />
<div class="row">
	<div class="col-md-12">
    	<div class="box-info">
    	<ul class="nav nav-tabs capitalize responsive">
            <li class="active"><a href="#site" data-toggle="tab"><?=$this->lang->line('site')?></a></li>
            <li><a href="#admin" data-toggle="tab"><?=$this->lang->line('admin')?></a></li>
            <li><a href="#marketing" data-toggle="tab"><?=$this->lang->line('marketing')?></a></li>
            <li><a href="#content" data-toggle="tab"><?=$this->lang->line('content')?></a></li>
            <li><a href="#media" data-toggle="tab"><?=$this->lang->line('media')?></a></li>
            <li><a href="#email" data-toggle="tab"><?=$this->lang->line('email')?></a></li>
            <li><a href="#security" data-toggle="tab"><?=$this->lang->line('security')?></a></li>
            <li><a href="#system" data-toggle="tab"><?=$this->lang->line('system')?></a></li>
            <li><a href="#automation" data-toggle="tab"><?=$this->lang->line('automation')?></a></li>
    	</ul>
        <div class="tab-content responsive">
        	<div id="site" class="tab-pane fade in active">
                <hr />
                <?php foreach ($frm_site_settings as $v): ?>
                <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('desc_' . $v['settings_key'])?>"><?=$this->lang->line($v['settings_key'])?></span></label>
                    <div class="col-lg-5">
                          <?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
                    </div>
                </div>
                <hr />
                <?php endforeach; ?>  
 			</div><!-- end #site -->
            <div id="admin" class="tab-pane fade in">
                <hr />
                <?php foreach ($frm_admin_settings as $v): ?>
                <?php if (defined('JAM_ENABLE_RESELLER_LINKS')): ?>
                <?php if ($v['settings_key'] == 'sts_admin_show_dashboard_video'): ?>
                <?php continue; ?>
                <?php endif; ?>
                <?php endif; ?>
                <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('desc_' . $v['settings_key'])?>"><?=$this->lang->line($v['settings_key'])?></span></label>
                    <div class="col-lg-5">
                          <?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
                    </div>
                </div>
                <hr />
                <?php endforeach; ?>
            </div><!-- end #admin -->
            <div id="marketing" class="tab-pane fade in">
                <ul class="nav nav-tabs capitalize">
                    <li class="active"><a href="#affiliate_marketing" data-toggle="tab"><?=$this->lang->line('affiliate_marketing')?></a></li>
                    <li><a href="#marketing_tools" data-toggle="tab"><?=$this->lang->line('marketing_tools')?></a></li>
                    <li><a href="#member_options" data-toggle="tab"><?=$this->lang->line('member_options')?></a></li>
                    <li><a href="#tracking" data-toggle="tab"><?=$this->lang->line('tracking')?></a></li>
                    <li><a href="#performance_bonuses" data-toggle="tab"><?=$this->lang->line('performance_bonuses')?></a></li>
                    <li><a href="#network_marketing" data-toggle="tab"><?=$this->lang->line('network_marketing')?></a></li>
                </ul>
                <div class="tab-content">
                	<div id="affiliate_marketing" class="tab-pane active fade in">
                    	<hr />
						<?php foreach ($frm_affiliate_settings as $v): ?>
                        <?php if ($v['settings_key'] == 'sts_affiliate_commission_levels_restrict_view'): ?>
						<?php if ($sts_site_showcase_multiple_programs == '0'): ?>
                        <?php continue; ?>
                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($v['settings_key'] == 'sts_affiliate_new_commission'): ?>
                        <?php if ($sts_site_showcase_multiple_programs == '0'): ?>
                        <?php continue; ?>
                        <?php endif; ?>
                        <?php endif; ?>
                        <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('desc_' . $v['settings_key'])?>"><?=$this->lang->line($v['settings_key'])?></span></label>
                            <div class="col-lg-5">
                                  <?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
                            </div>
                        </div>
                        <hr />
                        <?php endforeach; ?>
                    </div>
                    <div id="marketing_tools" class="tab-pane fade in">
                    	<hr />
						<?php foreach ($frm_aff_marketing_settings as $v): ?>
                        <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('desc_' . $v['settings_key'])?>"><?=$this->lang->line($v['settings_key'])?></span></label>
                            <div class="col-lg-5">
                                  <?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
                            </div>
                        </div>
                        <hr />
                        <?php endforeach; ?>
                    </div>
                    <div id="member_options" class="tab-pane fade in">
                    	<hr />
						<?php foreach ($frm_aff_member_settings as $v): ?>
                        <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('desc_' . $v['settings_key'])?>"><?=$this->lang->line($v['settings_key'])?></span></label>
                            <div class="col-lg-5">
                                  <?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
                            </div>
                        </div>
                        <hr />
                        <?php endforeach; ?>
                    </div>
                    <div id="tracking" class="tab-pane fade in">
                    	<div class="alert alert-warning"><?=$this->lang->line('cookie_tracking_always_enabled')?></div>
                        <hr />
						<?php foreach ($frm_tracking_settings as $v): ?>
                        <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('desc_' . $v['settings_key'])?>"><?=$this->lang->line($v['settings_key'])?></span></label>
                            <div class="col-lg-5">
                                  <?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
                            </div>
                        </div>
                        <hr />
                        <?php endforeach; ?>
                    </div>
                    <div id="performance_bonuses" class="tab-pane fade in">
                    	<hr />
						<?php foreach ($frm_performance_settings as $v): ?>
                        <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('desc_' . $v['settings_key'])?>"><?=$this->lang->line($v['settings_key'])?></span></label>
                            <div class="col-lg-5">
                                  <?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
                            </div>
                        </div>
                        <hr />
                        <?php endforeach; ?>
                    </div>
                    <div id="network_marketing" class="tab-pane fade in">
                    	<div class="alert alert-warning"><?=$this->lang->line('network_marketing_notice')?></div>
                        <hr />
						<?php foreach ($frm_network_marketing_settings as $v): ?>
                        <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('desc_' . $v['settings_key'])?>"><?=$this->lang->line($v['settings_key'])?></span></label>
                            <div class="col-lg-5">
                                  <?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
                            </div>
                        </div>
                        <hr />
                        <?php endforeach; ?>
                    </div>
				</div>
            </div><!-- end #marketing -->
            <div id="content" class="tab-pane fade in">
                <ul class="nav nav-tabs capitalize">
                    <li class="active"><a href="#content_settings" data-toggle="tab"><?=$this->lang->line('content_settings')?></a></li>
                    <li><a href="#members_area" data-toggle="tab"><?=$this->lang->line('members_area')?></a></li>
                    <li><a href="#program_links" data-toggle="tab"><?=$this->lang->line('program_links')?></a></li>
                </ul>
                <div class="tab-content">
                	<div id="content_settings" class="tab-pane active fade in">
                    	<hr />
						<?php foreach ($frm_content_settings as $v): ?>
                        <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('desc_' . $v['settings_key'])?>"><?=$this->lang->line($v['settings_key'])?></span></label>
                            <div class="col-lg-5">
                                  <?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
                            </div>
                        </div>
                        <hr />
                        <?php endforeach; ?>
                    </div>
                    <div id="members_area" class="tab-pane fade in">
                    	<hr />
						<?php foreach ($frm_members_settings as $v): ?>
                        <?php if ($v['settings_key'] == 'sts_content_members_dashboard_enable_downloads'): ?>
                        <?php if ($this->validation->sts_affiliate_show_downloads != '1'): ?>
                        <?=form_hidden('sts_content_members_dashboard_enable_downloads', '0')?>
                        <?php continue; ?>
                        <?php endif; ?>
                        <?php endif; ?>
                        <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('desc_' . $v['settings_key'])?>"><?=$this->lang->line($v['settings_key'])?></span></label>
                            <div class="col-lg-5">
                                  <?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
                            </div>
                        </div>
                        <hr />


                        <?php endforeach; ?>
                    </div>
                    <div id="program_links" class="tab-pane fade in">
                    	<hr />
                        <div class="form-group">
                            <label for="program_page" class="col-sm-3 control-label"><?=$this->lang->line('program_page')?></label>
                            <div class="col-lg-5">
                                  <p class="form-control-static"><a href="<?=base_url()?><?=PROGRAM_ROUTE?>/<?=$default_signup_link?>" target="_blank"><?=base_url()?><?=PROGRAM_ROUTE?>/<?=$default_signup_link?></a></p>
                            </div>
                        </div>
                        <hr />
                        <div class="form-group">
                            <label for="login_page" class="col-sm-3 control-label"><?=$this->lang->line('login_page')?></label>
                            <div class="col-lg-5">
                                 <p class="form-control-static"><a href="<?=base_url()?>login" target="_blank"><?=base_url()?>login</a></p>
                            </div>
                        </div>
                        <hr />
                        <div class="form-group">
                            <label for="registration_page" class="col-sm-3 control-label"><?=$this->lang->line('registration_page')?></label>
                            <div class="col-lg-5">
                                <p class="form-control-static"> <a href="<?=base_url()?>registration" target="_blank"><?=base_url()?>registration</a></p>
                            </div>
                        </div>
                        <hr />
                        <div class="form-group">
                            <label for="tos_page" class="col-sm-3 control-label"><?=$this->lang->line('tos_page')?></label>
                            <div class="col-lg-5">
                                 <p class="form-control-static"><a href="<?=base_url()?>tos" target="_blank"><?=base_url()?>tos</a></p>
                            </div>
                        </div>
                        <hr />
                        <div class="form-group">
                            <label for="privacy_policy_page" class="col-sm-3 control-label"><?=$this->lang->line('privacy_policy_page')?></label>
                            <div class="col-lg-5">
                                 <p class="form-control-static"><a href="<?=base_url()?>privacy" target="_blank"><?=base_url()?>privacy</a></p>
                            </div>
                        </div>
                        <hr />
                    </div>
                </div>
        	</div><!-- end #content -->
            <div id="media" class="tab-pane fade in">
                <ul class="nav nav-tabs capitalize">
                    <li class="active"><a href="#image_settings" data-toggle="tab"><?=$this->lang->line('image_settings')?></a></li>
                    <li><a href="#video_settings" data-toggle="tab"><?=$this->lang->line('video_settings')?></a></li>
                    <li><a href="#downloads_settings" data-toggle="tab"><?=$this->lang->line('downloads_settings')?></a></li>
                </ul>
                <div class="tab-content">
                	<div id="image_settings" class="tab-pane active fade in">
                    	<hr />
						<?php foreach ($frm_image_settings as $v): ?>
                        <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('desc_' . $v['settings_key'])?>"><?=$this->lang->line($v['settings_key'])?></span></label>
                            <div class="col-lg-5">
                                  <?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
                            </div>
                        </div>
                        <hr />
                        <?php endforeach; ?>
                    </div>
                    <div id="video_settings" class="tab-pane fade in">
                    	<hr />
						<?php foreach ($frm_video_settings as $v): ?>
                        <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('desc_' . $v['settings_key'])?>"><?=$this->lang->line($v['settings_key'])?></span></label>
                            <div class="col-lg-5">
                                  <?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
                            </div>
                        </div>
                        <hr />
                        <?php endforeach; ?>
                    </div>
                    <div id="downloads_settings" class="tab-pane fade in">
                    	<hr />
						<?php foreach ($frm_downloads_settings as $v): ?>
                        <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('desc_' . $v['settings_key'])?>"><?=$this->lang->line($v['settings_key'])?></span></label>
                            <div class="col-lg-5">
                                  <?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
                            </div>
                        </div>
                        <hr />
                        <?php endforeach; ?>
                    </div>
            	</div>
        	</div><!-- end #media -->
            <div id="email" class="tab-pane fade in">
                <hr />
                <?php foreach ($frm_email_settings as $v): ?>
                <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('desc_' . $v['settings_key'])?>"><?=$this->lang->line($v['settings_key'])?></span></label>
                    <div class="col-lg-5">
                          <?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
                    </div>
                </div>
                <hr />
                <?php endforeach; ?>
            </div><!-- end #email -->
            <div id="security" class="tab-pane fade in">
                <ul class="nav nav-tabs capitalize">
                    <li class="active"><a href="#security_settings" data-toggle="tab"><?=$this->lang->line('security_settings')?></a></li>
                    <li><a href="#click_security" data-toggle="tab"><?=$this->lang->line('click_security')?></a></li>
                    <li><a href="#commission_security" data-toggle="tab"><?=$this->lang->line('commission_security')?></a></li>
                </ul>
                <div class="tab-content">
                	<div id="security_settings" class="tab-pane active fade in">
                    	<hr />
						<?php foreach ($frm_security_settings as $v): ?>
                        <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('desc_' . $v['settings_key'])?>"><?=$this->lang->line($v['settings_key'])?></span></label>
                            <div class="col-lg-5">
                                  <?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
                            </div>
                        </div>
                        <hr />
                        <?php endforeach; ?>
                    </div>
                    <div id="click_security" class="tab-pane fade in">
                    	<hr />
						<?php foreach ($frm_click_security_settings as $v): ?>
                        <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('desc_' . $v['settings_key'])?>"><?=$this->lang->line($v['settings_key'])?></span></label>
                            <div class="col-lg-5">
                                  <?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
                            </div>
                        </div>
                        <hr />
                        <?php endforeach; ?>
                    </div>
                    <div id="commission_security" class="tab-pane fade in">
                    	<hr />
						<?php foreach ($frm_sale_security_settings as $v): ?>
                        <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('desc_' . $v['settings_key'])?>"><?=$this->lang->line($v['settings_key'])?></span></label>
                            <div class="col-lg-5">
                                  <?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
                            </div>
                        </div>
                        <hr />
                        <?php endforeach; ?>
                    </div>
       			</div>
            </div><!-- end #security -->
            <div id="system" class="tab-pane fade in">
                <ul class="nav nav-tabs capitalize">
                    <li class="active"><a href="#system_settings" data-toggle="tab"><?=$this->lang->line('system_settings')?></a></li>
            		<li><a href="#cron_job" data-toggle="tab"><?=$this->lang->line('cron_job')?></a></li>
                    <li><a href="#database_backup" data-toggle="tab"><?=$this->lang->line('database_backup')?></a></li>
                </ul>
                <div class="tab-content">
                	<div id="system_settings" class="tab-pane active fade in">
                    	<hr />
						<?php foreach ($frm_system_settings as $v): ?>
                        <?php if ($v['settings_key'] == 'sts_site_ssl_admin_area'): ?>
                        <div class="alert alert-warning"><?=$this->lang->line('ssl_warning_note')?></div>

                        <?php endif; ?>
                        <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('desc_' . $v['settings_key'])?>"><?=$this->lang->line($v['settings_key'])?></span></label>
                            <div class="col-lg-5">
                                  <?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
                            </div>
                        </div>
                        <hr />
                        <?php endforeach; ?>
                        <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('debug_mode')?>"><?=$this->lang->line('debug_mode')?></span></label>
                            <div class="col-lg-5">
                                <a href="<?=admin_url()?>settings/set_debug/<?=ENVIRONMENT?>" class="btn btn-default">
                                    <i class="fa fa-cogs"></i> <?=ENVIRONMENT?></a>
                            </div>
                        </div>
                        <hr />
                    </div>
                    <div id="cron_job" class="tab-pane fade in">
                    	<hr />
                        <div class="form-group"> <label class="col-lg-3 control-label"><?=$this->lang->line('cron_email_queue')?></label>
                            <div class="col-sm-9">
                                <p class="form-control">curl -s <?='http://' . _check_base_subdomain() . $this->config->item('base_domain_name') . $this->config->item('base_folder_path'). '/cron/send_email/' . $sts_cron_password_key ?> &gt;&gt; /dev/null 2&gt;&1 </p>
                                <p>Or</p>
                                <p class="form-control">
                                /usr/bin/wget -O - <?='http://' . _check_base_subdomain() . $this->config->item('base_domain_name') . $this->config->item('base_folder_path'). '/cron/send_email/' . $sts_cron_password_key ?> &gt;&gt; /dev/null 2&gt;&1
                                </p>
                                <p>
                                <a href="javascript:void(window.open('<?='http://' . _check_base_subdomain() . $this->config->item('base_domain_name') . $this->config->item('base_folder_path'). '/cron/send_email/' . $sts_cron_password_key ?>', 'popup', 'width=700,height=600, location=no, menubar=no, status=no,toolbar=no, scrollbars=yes, resizable=yes'))" class="btn btn-warning btn-sm" role="button"><i class="fa fa-cog"></i> <?=$this->lang->line('run_cron_job_manually')?></a>
                            	</p>
                            </div>
                        </div>
                        <hr />
                        <div class="form-group"> <label class="col-lg-3 control-label"><?=$this->lang->line('cron_process')?></label>
                            <div class="col-sm-9">
                                <p class="form-control">curl -s <?='http://' . _check_base_subdomain() . $this->config->item('base_domain_name') . $this->config->item('base_folder_path').  '/cron/process/' . $sts_cron_password_key ?> &gt;&gt; /dev/null 2&gt;&1  </p>
                                <p>Or</p>
                                <p class="form-control">
                                /usr/bin/wget -O - <?='http://' . _check_base_subdomain() . $this->config->item('base_domain_name') . $this->config->item('base_folder_path'). '/cron/process/' . $sts_cron_password_key ?> &gt;&gt; /dev/null 2&gt;&1
                                </p>
                                <p>
                                <a href="javascript:void(window.open('<?='http://' . _check_base_subdomain() . $this->config->item('base_domain_name') . $this->config->item('base_folder_path'). '/cron/process/' . $sts_cron_password_key ?>', 'popup', 'width=700,height=600, location=no, menubar=no, status=no,toolbar=no, scrollbars=yes, resizable=yes'))" class="btn btn-warning btn-sm" role="button"><i class="fa fa-cog"></i> <?=$this->lang->line('run_cron_job_manually')?></a>
                            	</p>
                            </div>
                        </div>
                        <hr />
                        <div class="form-group"> <label class="col-lg-3 control-label"><?=$this->lang->line('cron_aff_stats')?></label>
                            <div class="col-sm-9">
                                <p class="form-control">curl -s <?='http://' . _check_base_subdomain() . $this->config->item('base_domain_name') . $this->config->item('base_folder_path').  '/cron/affiliate_stats/' . $sts_cron_password_key ?> &gt;&gt; /dev/null 2&gt;&1 </p>
                                <p>Or</p>
                                <p class="form-control">
                                /usr/bin/wget -O - <?='http://' . _check_base_subdomain() . $this->config->item('base_domain_name') . $this->config->item('base_folder_path'). '/cron/affiliate_stats/' . $sts_cron_password_key ?> &gt;&gt; /dev/null 2&gt;&1
                                </p>
                                <p>
                                <a href="javascript:void(window.open('<?='http://' . _check_base_subdomain() . $this->config->item('base_domain_name') . $this->config->item('base_folder_path'). '/cron/affiliate_stats/' . $sts_cron_password_key ?>', 'popup', 'width=700,height=600, location=no, menubar=no, status=no,toolbar=no, scrollbars=yes, resizable=yes'))" class="btn btn-warning btn-sm" role="button"><i class="fa fa-cog"></i> <?=$this->lang->line('run_cron_job_manually')?></a>
                            	</p>
                            </div>
                        </div>
                        <hr />
                    </div>
                    <div id="database_backup" class="tab-pane fade in">
                    	<hr />
						<div class="form-group"> <label class="col-lg-3 control-label"><?=$this->lang->line('database_name')?></label>
                            <div class="col-lg-5">
                                  <span class="form-control"><?=$database_name?></span>
                            </div>
                        </div>
                        <hr />
						<?php foreach ($frm_db_backup_settings as $v): ?>
                        <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('desc_' . $v['settings_key'])?>"><?=$this->lang->line($v['settings_key'])?></span></label>
                            <div class="col-lg-5">
                                  <?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
                            </div>
                        </div>
                        <hr />
                        <?php endforeach; ?>
                        <div class="form-group"> <label class="col-lg-3 control-label"><?=$this->lang->line('current_db_backups')?></label>
                            <div class="col-lg-5">
                                <?php if (!empty($backups)): ?>
                                <p>
                                	<ul class="list-group">
									<?php foreach ($backups as $v): ?>
                                    <?php if (substr($v, -4, 4) == '.sql'):?>
                                    <li class="list-group-item"><a href="<?=admin_url()?>settings/restore_db/<?=$v?>"><?=$v?> - <?=$this->lang->line('click_to_restore')?></a></li>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                	</ul>
                                </p>
                                <?php endif; ?>
                                <p><a class="btn btn-default" href="<?=admin_url();?>settings/backup_db"><i class="fa fa-download"></i> <?=$this->lang->line('backup_now')?></a></p>
                            </div>
                        </div>
                        <hr />
                    </div>
        		</div>
            </div><!-- end #system -->
            <div id="automation" class="tab-pane fade in">
                <ul class="nav nav-tabs capitalize">
                    <li class="active"><a href="#auto_signup" data-toggle="tab"><?=$this->lang->line('auto_signup')?></a></li>
                	<!--<li><a href="#facebook_connect" data-toggle="tab"><?=$this->lang->line('facebook_connect')?></a></li>-->
                </ul>
                <div class="tab-content">
                    <div id="auto_signup" class="tab-pane active fade in">
                    	<hr />
						<?php foreach ($frm_auto_signup_settings as $v): ?>
                        <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('desc_' . $v['settings_key'])?>"><?=$this->lang->line($v['settings_key'])?></span></label>
                            <div class="col-lg-5">
                                  <?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
                            </div>
                        </div>
                        <hr />
                        <?php endforeach; ?>
                    </div>
                    <!--
                	<div id="facebook_connect" class="tab-pane fade in">
                    	<hr />
						<?php foreach ($frm_facebook_connect_settings as $v): ?>
                        <div class="form-group"> <label class="col-lg-3 control-label"><span class="tip"  data-toggle="tooltip" data-placement="bottom" title="<?=$this->lang->line('desc_' . $v['settings_key'])?>"><?=$this->lang->line($v['settings_key'])?></span></label>
                            <div class="col-lg-5">
                                  <?=_generate_settings_field($v, $this->validation->$v['settings_key'])?>
                            </div>
                        </div>
                        <hr />
                        <?php endforeach; ?>
                    </div>
                    -->
        		</div>
            </div><!-- end #automation -->
        </div><!-- end tab-content -->
        <div class="col-md-5 col-md-offset-3"><button class="btn btn-success btn-lg" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button></div>
        </div>
    </div><!-- end col-md-12 -->
</div><!-- end row -->
</form>
<script>
$('.pop').popover()
</script>