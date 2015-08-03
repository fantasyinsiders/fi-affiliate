<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
<head>
	<title><?=$page_title?></title>
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
                    <form id="validateForm" name="form1" role="form" method="post">
                    	<?php if (!empty($success)): ?>
                        <div class="alert alert-success"><small><?=$success?></small></div>
                        <?php elseif (!empty($error)): ?>
                        <div class="alert alert-danger"><small><?=$error?></small></div>
						<?php endif; ?>
                        <div class="form-group login-input">
                        <i class="fa fa-key overlay"></i>
                        <input type="password" name="cpass" class="form-control text-input" placeholder="<?=$this->lang->line('new_password')?>"/> 
                        </div>
                        <div class="form-group login-input">
						<i class="fa fa-unlock overlay"></i>
                        <input type="password" name="cpassconf" class="form-control text-input" placeholder="<?=$this->lang->line('confirm_password')?>"/> 
						</div>
                        <div class="row">
                            <div class="col-sm-12">
                            <button type="submit" class="btn btn-info btn-block"><i class="fa fa-refresh"></i> <?=$this->lang->line('reset_password')?></button>
                            <a class="btn btn-default btn-block" href="<?=base_url('js')?><?=ADMIN_LOGIN_ROUTE?>"><i class="fa fa-unlock"></i> <?=$this->lang->line('login')?></a>
                            </div>
                        </div>
                        <input type="hidden" name="confirm_id" value="<?=$confirm_id?>" />
                    </form>
                </div>
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
			feedbackIcons: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			fields: {
				cpass: {
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
				},
				cpassconf: {
					validators: {
						notEmpty: {
							message: '<?=$this->lang->line('confirm_password_required')?>'
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