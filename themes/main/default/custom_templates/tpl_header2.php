<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html>
<html><head>
	<title><?=ucwords($this->lang->line($page_title))?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="">
    
    <link href="<?=base_url('js')?>themes/main/<?=$default_theme?>/css/bootstrap.css" rel="stylesheet">
    <link href="<?=base_url('js')?>themes/main/<?=$default_theme?>/css/bootstrap-theme.css" rel="stylesheet">
	<link href="<?=base_url('js')?>themes/fonts/font-awesome/css/font-awesome.css" rel="stylesheet" > 
    <link href="<?=base_url('js')?>js/datepicker/css/datepicker.css" rel="stylesheet">
	<link href="<?=base_url('js')?>themes/main/animate.css" rel="stylesheet">
    <link href="<?=base_url('js')?>themes/flags/flags.css" rel="stylesheet">
    <link href="<?=base_url('js')?>themes/main/<?=$default_theme?>/css/style.css" rel="stylesheet">
	
    <script src="<?=base_url('js')?>js/jquery.js"></script>
	<script src="<?=base_url('js')?>js/bootstrap.js"></script>
	<script src="<?=base_url('js')?>js/jquery.validate.js"></script> 
    <script src="<?=base_url('js')?>js/jquery.form.js"></script> 
    <script src="<?=base_url('js')?>js/bootstrap.file-input.js"></script> 
	<script src="<?=base_url('js')?>themes/main/<?=$default_theme?>/js/theme.js"></script>

	<link rel="shortcut icon" href="<?=base_url('js')?>favicon.ico" type="image/x-icon" />
</head>
<body>
<div id="page">
	<div id="container" class="container">
    	<div id="top-menu" class="text-capitalize">
       		<nav class="navbar navbar-default" role="navigation">
           		<div class="container-fluid">
                	<div class="navbar-header">
                      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </button>
                    </div>
                    <div class="collapse navbar-collapse" id="collapse-1">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="<?=site_url()?><?=PROGRAM_ROUTE?>/<?=$prg_signup_link?>"><?=$this->lang->line('how_it_works')?></a></li>
                            <li><a href="<?=site_url()?><?=FAQ_ROUTE?>/<?=$prg_signup_link?>"><?=$this->lang->line('faqs')?></a></li> 
							<?php if ($sts_site_enable_language_selector == 1): ?>
                            <?php if (!empty($languages)): ?>
                            <li class="dropdown">
                            	<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$this->lang->line('select_language')?> <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                	<?php foreach ($languages as $v):?>
                                    <li><a href="<?=base_url('js')?>switch_language/<?=$v['name']?>"><i class="flag-<?=$v['image']?>"></i> <?=$v['name']?></a></li>
                                    <?php endforeach;?>
                                </ul>
                            </li>
                            <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($this->session->userdata('userid') || $this->config->item('fb_session_enabled')) :?>
                            <li><a href="<?=base_url('members')?>"><?=$this->lang->line('dashboard')?></a></li> 
                            <li class="dropdown">
                            	<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$this->lang->line('profile')?> <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="<?=base_url('members')?>account/"><?=$this->lang->line('account_profile')?></a></li>
                                    <li><a href="<?=base_url('members')?>account/reset_password"><?=$this->lang->line('reset_password')?></a></li>
                                </ul>
                            </li>
                            <?php if ($this->session->userdata('userid')): ?>
                            <li><a href="<?=base_url('members')?>logout/<?=$prg_signup_link?>"><?=$this->lang->line('logout')?></a></li> 
                            <?php endif; ?>
                            <?php else: ?>
                            <li><a href="<?=site_url()?>login/<?=$prg_signup_link?>"><?=$this->lang->line('login')?></a></li> 
                            <li><a href="<?=site_url()?>registration/<?=$prg_signup_link?>"><?=$this->lang->line('create_an_account')?></a></li> 
                            <?php endif;?>
                        </ul>
                    </div>
              </div>
            </nav>
        </div>
		<div id="header" class="row">
        	<div class="col-lg-12">
                <div id="logo" class="logo">
                	<h3><a href="<?=base_url()?>">
                    <?php if (!empty($prg_program_logo)): ?>
                    <img src="<?=base_url('js')?>images/programs/<?=$prg_program_logo?>" />
                    <?php else: ?>
                    <?=$sts_site_name?>
                    <hr />
                    <?php endif; ?>
                    </a></h3>
                </div>
            </div>
        </div>
        <?php if (!empty($top_menu)): ?>
       	<div id="menu" class="row">
        	<div class="col-lg-12">
            	<nav class="navbar navbar-default" role="navigation">
                    <div class="container-fluid">
                    	<div class="navbar-header">
                          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#collapse-2">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                          </button>
                        </div>
                        <div class="collapse navbar-collapse" id="collapse-2">
                    		<?=$top_menu?>
                    	</div>
                    </div>
                </nav>
        	</div>
        </div>
    	<?php endif; ?> 
    	<?php if (!empty($bread_crumbs)): ?>
   		<?=$bread_crumbs?>
    	<?php endif; ?>
        <div id="content" class="row ">
        	<div id="response">
				<?php if (!empty($error)): ?>
                <?=_show_msg('error', $error)?>
                <?php elseif ($this->session->flashdata('success')): ?>
                <?=_show_msg('success', $this->session->flashdata('success'))?>
                <?php endif; ?>
            </div>