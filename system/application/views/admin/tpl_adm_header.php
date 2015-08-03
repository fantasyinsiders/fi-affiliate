<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
<head>
	<title><?=ucwords($this->lang->line($page_title))?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="">

	<link href="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/css/bootstrap-theme.min.css" rel="stylesheet">
	<link href="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/css/style.css" rel="stylesheet">
	<link href="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/css/style-responsive.css" rel="stylesheet">
	<link href="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/css/animate.css" rel="stylesheet">
	<link href="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/third/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/third/nifty-modal/css/component.css" rel="stylesheet">
	<link href="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/third/sortable/sortable-theme-bootstrap.css" rel="stylesheet"> 
	<link href="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/third/icheck/skins/minimal/grey.css" rel="stylesheet"> 
	<link href="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/third/select/bootstrap-select.min.css" rel="stylesheet"> 
	<link href="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/third/magnific-popup/magnific-popup.css" rel="stylesheet"> 
    <link href="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/third/validator/bootstrapValidator.css" rel="stylesheet"> 
	<link href="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/third/datepicker/css/datepicker.css" rel="stylesheet">
    <link href="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/third/tabdrop/css/tabdrop.css" rel="stylesheet">
    <link href="<?=base_url('js')?>themes/flags/flags.css" rel="stylesheet">
    <link href="<?=base_url('js')?>js/autocomplete/jquery.autocomplete.css" rel="stylesheet"> 

    <link rel="shortcut icon" href="<?=base_url('js')?>favicon.ico" type="image/x-icon" />
    
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->

	<script src="<?=base_url('js');?>themes/admin/<?=$sts_admin_layout_theme?>/js/jquery.js"></script>
	<script src="<?=base_url('js');?>themes/admin/<?=$sts_admin_layout_theme?>/js/bootstrap.min.js"></script>
    <script src="<?=base_url('js');?>themes/admin/<?=$sts_admin_layout_theme?>/third/datepicker/js/bootstrap-datepicker.js"></script>
    <script src="<?=base_url('js')?>js/jquery.validate.js"></script> 
    <script>
	var time1;
	function timer() {
		time1=window.setTimeout("redirect()",<?=ADMIN_SESSION_EXPIRATION_TIMER?>);
	}
	function redirect() {
		window.location = "<?=admin_url()?>logout/index/timer_expired/<?=$this->config->item('uri_timer_redirect')?>";
	}
	function detime() {
		window.clearTimeout(time1);
		timer();
	}
	</script>
</head>
<body class="tooltips" onload="timer()" onmousemove="detime()">
	<div class="container">    
    	<div class="logo-brand header sidebar rows">
			<div class="logo">
            	<a href="<?=admin_url()?>">
				<?php if (!empty($customizer_reseller_logo)): ?>  
		    	<img src="//<?=$customizer_reseller_logo?>" style="max-height:150px; max-width: 250px;" />
       			<?php else: ?>
 				<h1><i class="fa fa-group"></i> Affiliate Manager</h1>
    			<?php endif; ?>
                </a>
			</div>
		</div>
		<div class="left side-menu">	
            <div class="body rows scroll-y">
                <div class="sidebar-inner slimscroller">
					<div class="media">
						<a class="pull-left" href="<?=admin_url();?>admin_users/update_admin/<?=$this->session->userdata('adminid')?>">
							<?php if ($this->session->userdata('admin_photo')): ?>
                            <img class="media-object img-circle" src="<?=base_url('js')?>images/admins/<?=$this->session->userdata('admin_photo')?>" alt="Avatar">
                            <?php else: ?>
                            <img class="media-object img-circle" src="<?=base_url('js')?>images/admins/5.jpg" alt="Avatar">
							<?php endif; ?>
                        </a>
						<div class="media-body">
							<?=$this->lang->line('welcome_back')?>,
							<h4 class="media-heading"><strong><?=$this->session->userdata('fname')?></strong></h4>
							<a href="<?=admin_url();?>admin_users/update_admin/<?=$this->session->userdata('adminid')?>" ><span class="badge badge-success"><?=$this->lang->line('edit')?></span></a>
							<a href="<?=admin_url();?>logout" class="md-trigger" data-modal="logout-modal-alt"><span class="badge badge-success"><?=$this->lang->line('logout')?></span></a>
						</div>
					</div>	
					<div id="sidebar-menu">
						<ul class="capitalize">
							<li <?php if ($menu == 'm'):?>class="active"<?php endif;?>><a href="#"><i class="fa fa-users"></i><i class="fa fa-angle-double-down i-right"></i> <?=$this->lang->line('members')?></a> 
								<ul <?php if ($menu == 'm'):?>class="visible"<?php endif;?>>
									<li><a href="<?=admin_url()?>members/view_members"><i class="fa fa-angle-right"></i> <?=$this->lang->line('manage_members')?></a></li>
                                    <li><a href="<?=admin_url()?>affiliate_groups/view_groups"><i class="fa fa-angle-right"></i> <?=$this->lang->line('manage_tier_groups')?></a></li>
                                    <li><a href="<?=admin_url()?>admin_users/view_admins"><i class="fa fa-angle-right"></i> <?=$this->lang->line('manage_admins')?></a></li>
								</ul>
							</li>                            
                            <li <?php if ($menu == 'c'):?>class="active"<?php endif;?>><a href="#"><i class="fa fa-money"></i><i class="fa fa-angle-double-down i-right"></i> <?=$this->lang->line('commissions')?></a>
								<ul <?php if ($menu == 'c'):?>class="visible"<?php endif;?>>
									<li><a href="<?=admin_url()?>commissions/view_commissions"><i class="fa fa-angle-right"></i> <?=$this->lang->line('manage_commissions')?></a></li>
                                    <li><a href="<?=admin_url()?>action_commissions/view_action_commissions"><i class="fa fa-angle-right"></i> <?=$this->lang->line('action_commissions')?></a></li>
                                    <li><a href="<?=admin_url()?>scaled_commissions/view_scaled_commissions"><i class="fa fa-angle-right"></i> <?=$this->lang->line('scaled_commissions')?></a></li>
                                    <li><a href="<?=admin_url()?>product_commissions/view_product_commissions"><i class="fa fa-angle-right"></i> <?=$this->lang->line('product_commissions')?></a></li>
								</ul>
							</li>
                            <li <?php if ($menu == 'p'):?>class="active"<?php endif;?>><a href="#"><i class="fa fa-edit"></i><i class="fa fa-angle-double-down i-right"></i> <?=$this->lang->line('affiliate_payments')?></a>
                            	<ul <?php if ($menu == 'p'):?>class="visible"<?php endif;?>>
                                    <li><a href="<?=admin_url();?>affiliate_payments/view_payment_options"><i class="fa fa-angle-right"></i> <?=$this->lang->line('make_payments')?></a></li>
                                	<li><a href="<?=admin_url();?>affiliate_payments/view_affiliate_payments"><i class="fa fa-angle-right"></i> <?=$this->lang->line('payments_history')?></a></li>
                                </ul>
                            </li>
                            <li <?php if ($menu == 'r'):?>class="active"<?php endif;?>><a href="#"><i class="fa fa-bar-chart-o"></i><i class="fa fa-angle-double-down i-right"></i> <?=$this->lang->line('stats_reports')?></a>
                            	<ul <?php if ($menu == 'r'):?>class="visible"<?php endif;?>>
                                	<li><a href="<?=admin_url()?>reports/view_reports"><i class="fa fa-angle-right"></i> <?=$this->lang->line('generate_reports')?></a></li>
                                    <li><a href="<?=admin_url()?>reports_archive/view_archive"><i class="fa fa-angle-right"></i> <?=$this->lang->line('reports_archive')?></a></li>
                            	</ul>
                            </li>
                            <li <?php if ($menu == 'o'):?>class="active"<?php endif;?>><a href="#"><i class="fa fa-desktop"></i><i class="fa fa-angle-double-down i-right"></i> <?=$this->lang->line('program_offers')?></a>
                            	<ul <?php if ($menu == 'o'):?>class="visible"<?php endif;?>>
                                	<li><a href="<?=admin_url();?>programs/view_programs"><i class="fa fa-angle-right"></i> <?=$this->lang->line('manage_offers')?></a></li>
                                    <li><a href="<?=admin_url();?>programs/add_program"><i class="fa fa-angle-right"></i> <?=$this->lang->line('add_program')?></a></li>
                                </ul>
                            </li>
                            <li <?php if ($menu == 'w'):?>class="active"<?php endif;?>><a href="#"><i class="fa fa-edit"></i><i class="fa fa-angle-double-down i-right"></i> <?=$this->lang->line('content')?></a>
                            	<ul <?php if ($menu == 'w'):?>class="visible"<?php endif;?>>
                                    <li><a href="<?=admin_url();?>content_articles/view_content_articles"><i class="fa fa-angle-right"></i> <?=$this->lang->line('manage_content')?></a></li>
                                    <li><a href="<?=admin_url();?>faq_articles/view_faq_articles"><i class="fa fa-angle-right"></i> <?=$this->lang->line('manage_faq')?></a></li>
                                    <li><a href="<?=admin_url();?>downloads/view_downloads"><i class="fa fa-angle-right"></i> <?=$this->lang->line('manage_downloads')?></a></li>
                                	<li><a href="<?=admin_url();?>replication"><i class="fa fa-angle-right"></i> <?=$this->lang->line('webpage_replication')?></a></li>
                                </ul>
                            </li>
                            <li <?php if ($menu == 'a'):?>class="active"<?php endif;?>><a href="#"><i class="fa fa-bullhorn"></i><i class="fa fa-angle-double-down i-right"></i> <?=$this->lang->line('marketing_tools')?></a>
                            	<ul <?php if ($menu == 'a'):?>class="visible"<?php endif;?>>
									<li><a href="<?=admin_url()?>affiliate_marketing/view_affiliate_tools"><i class="fa fa-angle-right"></i> <?=$this->lang->line('affiliate_marketing')?></a></li>
                                    <li><a href="<?=admin_url()?>rewards/view_rewards"><i class="fa fa-angle-right"></i> <?=$this->lang->line('performance_rewards')?></a></li>
                                    <li><a href="<?=admin_url()?>tracking/view_tracking"><i class="fa fa-angle-right"></i> <?=$this->lang->line('ad_trackers')?></a></li>
                                    <li><a href="<?=admin_url()?>coupons/view_coupons"><i class="fa fa-angle-right"></i> <?=$this->lang->line('coupon_codes')?></a></li>
								</ul>
                            </li> 
                            <li <?php if ($menu == 'e'):?>class="active"<?php endif;?>><a href="#"><i class="fa fa-envelope"></i><i class="fa fa-angle-double-down i-right"></i> <?=$this->lang->line('email_tools')?></a>
                            	<ul <?php if ($menu == 'e'):?>class="visible"<?php endif;?>>
									<li><a href="<?=admin_url()?>mailing_lists/view_mailing_lists"><i class="fa fa-angle-right"></i> <?=$this->lang->line('affiliate_mailing_lists')?></a></li>
                                    <li><a href="<?=admin_url()?>email_queue/view_email_queue"><i class="fa fa-angle-right"></i> <?=$this->lang->line('view_email_queue')?></a></li>
                                    <li><a href="<?=admin_url()?>email_archive/view_email_archive"><i class="fa fa-angle-right"></i> <?=$this->lang->line('view_email_archive')?></a></li>
                                    <li><a href="<?=admin_url()?>email_templates/view_email_templates/1"><i class="fa fa-angle-right"></i> <?=$this->lang->line('view_email_templates')?></a></li>
                                    <li><a href="<?=admin_url()?>modules/view_modules/0/0/0/module_type/mailing_list"><i class="fa fa-angle-right"></i> <?=$this->lang->line('third_party_email_lists')?></a></li>
								</ul>
                            </li> 
                            <li <?php if ($menu == 'x'):?>class="active"<?php endif;?>><a href="#"><i class="fa fa-download"></i><i class="fa fa-angle-double-down i-right"></i> <?=$this->lang->line('import_export_tools')?></a>
                            	<ul <?php if ($menu == 'x'):?>class="visible"<?php endif;?>>
                                	<li><a href="<?=admin_url()?>import/view_import_modules"><i class="fa fa-angle-right"></i> <?=$this->lang->line('data_import')?></a></li>
                                    <li><a href="<?=admin_url()?>export/view_export_modules/"><i class="fa fa-angle-right"></i> <?=$this->lang->line('data_export')?></a></li>
								</ul>
                            </li>
						</ul>
						<div class="clear"></div>
					</div>
				</div>
            </div>
            <div class="footer rows text-center">
				<small class="animated fadeInUpBig copyright">
                &copy; <?=date('Y')?> 
                <?php if (!defined('JAM_ENABLE_RESELLER_LINKS')): ?>
                <a href="http://www.jrox.com">JROX Technologies, Inc.</a>
                <?php else: ?>
                All Rights Reserved, Inc.
                <?php endif; ?> 
               </small>      
            </div>
        </div>
        <div class="right content-page">
            <div class="header content rows-content-header">
				<button class="button-menu-mobile show-sidebar">
					<i class="fa fa-bars"></i>
				</button>			
				<div class="navbar navbar-default capitalize" role="navigation">
					<div class="container">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
								<i class="fa fa-angle-double-down"></i>
							</button>
						</div>	
						<div class="navbar-collapse collapse">
                            <form action="<?=admin_url()?>search/general/" method="post" class="navbar-form navbar-left" role="search">
                                <div class="form-group">
                                  	<input type="text" name="search_term" class="form-control" placeholder="<?=$this->lang->line('enter_search_here')?>">
                                  	<select name="table" class="form-control">                  
                                    	<option value="commissions"><?=$this->lang->line('commissions')?></option> 
                                        <option value="members"><?=$this->lang->line('members')?></option>
                                	</select>
                                </div>
                                <button type="submit" class="btn btn-primary block-phone"><i class="fa fa-search"></i> </button>
                                <a href="<?=admin_url()?>search/advanced/"  class="btn btn-primary block-phone"><?=$this->lang->line('advanced')?></a>
                       		</form>	
                           <ul class="nav navbar-nav navbar-right">
                                <li class="dropdown">
									<a href="<?=base_url()?>" target="_blank"><small><i class="fa fa-external-link"></i> <span class="hidden-md hidden-sm"><?=$this->lang->line('view_site')?></span></small></a>
                                </li>
                                <li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown"><small><i class="fa fa-wrench"></i> <span class="hidden-md hidden-sm"><?=$this->lang->line('integration')?></span> <b class="caret"></b></small></a>
									<ul class="dropdown-menu animated half fadeInDown">
										<li><a href="<?=admin_url();?>integration/options"> <?=$this->lang->line('site_integration')?></a></li>
                                        <li><a href="<?=admin_url();?>integration/integration_profiles"> <?=$this->lang->line('integration_profiles')?></a></li>
                                        <li><a href="<?=admin_url();?>integration/api"> <?=$this->lang->line('automation_api')?></a></li>
									</ul>
								</li>
                                <li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown"><small><i class="fa fa-picture-o"></i> <span class="hidden-md hidden-sm"><?=$this->lang->line('design')?></span> <b class="caret"></b></small></a>
									<ul class="dropdown-menu animated half fadeInDown">
                                        <li><a href="<?=admin_url()?>themes/view_themes/"><?=$this->lang->line('site_layout')?></a></li>
                                        <li><a href="<?=admin_url()?>layout/generate_menu/1/"><?=$this->lang->line('menu_maker')?></a></li>
                                        <li><a href="<?=admin_url()?>programs/form_fields/1"><?=$this->lang->line('manage_form_fields')?></a></li>
									</ul>
								</li>
                                <li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown"><small><i class="fa fa-cogs"></i> <span class="hidden-md hidden-sm"><?=$this->lang->line('settings')?></span> <b class="caret"></b></small></a>
									<ul class="dropdown-menu animated half fadeInDown">
                                    	<li><a href="<?=admin_url()?>settings"><?=$this->lang->line('global_configuration')?></a></li>
                                        <li><a href="<?=admin_url()?>countries/view_countries/"><?=$this->lang->line('manage_countries')?></a></li>
                                        <li><a href="<?=admin_url()?>currencies/view_currencies/"><?=$this->lang->line('manage_currencies')?></a></li>
                                        <li><a href="<?=admin_url()?>languages/view_languages/"><?=$this->lang->line('manage_languages')?></a></li>
                                        <li><a href="<?=admin_url()?>modules/view_modules/"><?=$this->lang->line('installed_modules')?></a></li>
									</ul>
								</li>
							</ul>
                           	
                            
						</div><!-- End div .navbar-collapse -->
					</div><!-- End div .container -->
				</div>
				<!-- END NAVBAR CONTENT-->
            </div>
			<!-- END CONTENT HEADER -->
            
            <div class="body content rows scroll-y">
                
                <div class="content-body">
                <div class="crumb-row visible-md visible-lg">
					<div class="updates">
                    <?php if (!empty($jam_license_alert)):?>
					<?=$jam_license_alert?>
                    <?php else: ?>

                    <small class="text-muted text-capitalize"><?=$this->lang->line('last_login')?> <?=$this->session->userdata('ll_date')?> - <?=$this->session->userdata('ll_ip')?> | <a href="<?=admin_url()?>license" class="tip" data-toggle="tooltip" title="<?=$this->lang->line('view_license')?>"><?=$this->lang->line('version')?> <?=APP_VERSION ?></a> <?php if ($this->uri->segment(2)): ?>|  <a href="#help"><i class="fa fa-question-circle"></i></a> <?php endif; ?></small>
                	<?php endif; ?>
                    </div>
					<?=$bread_crumbs?>
                </div>  
                <div id="response">
					<?php if (!empty($error)): ?>
                    <?=_show_msg('error', $error)?>
                    <?php elseif ($this->session->flashdata('error')): ?>
                    <?=_show_msg('error', $this->session->flashdata('error'))?>
                    <?php elseif (!empty($success)): ?>
                    <?=_show_msg('success', $success)?>
					<?php elseif ($this->session->flashdata('success')): ?>
                    <?=_show_msg('success', $this->session->flashdata('success'))?>
                    <?php endif; ?>
				</div>