<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
<head>
	<title><?=$sts_site_name?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="robots" content="noindex, nofollow">

	<link href="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/css/bootstrap.css" rel="stylesheet">
	<link href="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/css/style.css" rel="stylesheet">
	<link href="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/css/style-responsive.css" rel="stylesheet">
	<link href="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/css/animate.css" rel="stylesheet">
    <link href="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/third/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/third/validator/bootstrapValidator.css" rel="stylesheet">
    <link rel="shortcut icon" href="<?=base_url('js')?>favicon.ico" type="image/x-icon" />
</head>
<body>
	<div class="container">
	    <div class="full-content-center animated fadeInUp">
			<div class="login-wrap">
				<?php if (!empty($processing)): ?>
                <div class="box-info capitalize text-center">
                	<?php if (!empty($customizer_reseller_logo)): ?>
        		    <img src="//<?=$customizer_reseller_logo?>" style="max-height:100px; max-width: 200px;" />
        		    <?php else: ?>
                    <?php if (defined('JAM_ENABLE_RESELLER_LINKS')): ?>
                    <h2><i class="fa fa-lock"></i> Admin Login</h2>
                    <?php else: ?>
        		    <img src="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/img/login-logo.png" alt="Logo" class="login-logo" />
		            <?php endif; ?>
                    <?php endif; ?>
                    <h3><?=$this->lang->line('please_wait')?></h3>
                    <h5><a href="<?=admin_url()?>" class=""><?=$this->lang->line('if_not_forwarded_click_here')?></a></h5>
                    <p><img src="<?=base_url();?>themes/admin/<?=$sts_admin_layout_theme?>/img/loading.gif" onload="window.location='<?=admin_url()?>'" alt="" /></p>
                </div>
                <?php else: ?>
                <?php if (!empty($timer_expired)): ?>
                <div class="alert alert-danger animated shake capitalize"><?=$this->lang->line('timer_expired')?></div>
                <?php endif; ?>
            	<?php if ($this->session->flashdata('error_msg')): ?>
				<div class="alert alert-danger animated shake"><?=$this->session->flashdata('error_msg'); ?></div>
                <?php endif; ?>
				<div class="box-info capitalize text-center">

                	<?php if (!empty($customizer_reseller_logo)): ?>
        		    <img src="//<?=$customizer_reseller_logo?>" style="max-height:150px; max-width: 250px;" />
        		    <?php else: ?>
                    <?php if (defined('JAM_ENABLE_RESELLER_LINKS')): ?>
                    <h2><i class="fa fa-lock"></i> Admin Login</h2>
                    <?php else: ?>
                        <img src="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/img/login-logo.png" alt="Logo" class="login-logo" />
                    <?php endif; ?>
		            <?php endif; ?>
					<form role="form" id="validateForm" action="<?=admin_url()?>login/" method="post">
						<div class="form-group login-input <?php if ($this->session->flashdata('error_msg')): ?> has-error <?php endif; ?>">
						<i class="fa fa-sign-in overlay"></i>
                        <input  name="<?=$admin_login_username_field?>" type="text" id="username" class="form-control text-input" placeholder="<?=$this->lang->line('username')?>" />
						</div>
						<div class="form-group login-input <?php if ($this->session->flashdata('error_msg')): ?> has-error <?php endif; ?>">
						<i class="fa fa-key overlay"></i>
                        <input name="<?=$admin_login_password_field?>" type="password" id="password" class="form-control text-input" placeholder="<?=$this->lang->line('password')?>"  />
						</div>
                        <div class="form-group">
                        <?php if (!empty($languages)): ?>
                        <?=form_dropdown('language', $languages, $sts_site_default_language, 'class="form-control"')?>
                        <?php endif; ?>
                        </div>
						<div class="row">
							<div class="col-sm-12">
							<button type="submit" class="btn btn-info btn-block"><i class="fa fa-unlock"></i> <?=$this->lang->line('login')?></button>
							</div>
						</div>
                        <?php if (!empty($page_redirect)): ?>
		                <input type="hidden" name="page_redirect" value="<?=$page_redirect?>" />
        		        <?php endif; ?>
					</form>
				</div>
                <p class="text-center">
                	<a  href="<?=base_url('js')?><?=ADMIN_LOGIN_ROUTE?>/reset_password/"><i class="fa fa-lock"></i> <?=$this->lang->line('forgot_password')?></a>
                </p>
        		<?php endif; ?>
    		</div>
		</div>
    </div>

	<script src="<?=path_url();?>themes/admin/<?=$sts_admin_layout_theme?>/js/jquery.js"></script>
	<script src="<?=path_url();?>themes/admin/<?=$sts_admin_layout_theme?>/js/bootstrap.min.js"></script>
    <script src="<?=path_url();?>themes/admin/<?=$sts_admin_layout_theme?>/third/validator/bootstrapValidator.min.js"></script>
	<script>
		$(document).ready(function() {
		$('#validateForm').bootstrapValidator({
			message: 'This value is not valid',
			fields: {
				<?=$admin_login_username_field?>: {
					message: 'The username is not valid',
					validators: {
						notEmpty: {
							message: '<?=$this->lang->line('username_required')?>'
						},
						stringLength: {
							min: 6,
							max: 20,
							message: '<?=$this->lang->line('field_minimum_characters')?>'
						}
					}
				},
				<?=$admin_login_password_field?>: {
					validators: {
						notEmpty: {
							message: '<?=$this->lang->line('password_required')?>'
						},
						stringLength: {
							min: 6,
							max: 20,
							message: '<?=$this->lang->line('field_minimum_characters')?>'
						}
					}
				}
			}
		});
	});
	</script>
</body>
</html><!-- Version <?=APP_VERSION?> -->