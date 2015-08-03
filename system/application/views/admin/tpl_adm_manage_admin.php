<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row">
   	<div class="col-md-4">
    <?php if ($function == 'update_admin'): ?>
    <?=_generate_sub_headline('manage_admin', $this->validation->admin_id)?>
    <?php else: ?>
    <?=_generate_sub_headline('add_admin')?>
    <?php endif; ?>
    </div>
    <div class="col-md-8 text-right">
		<?php if ($function == 'update_admin'): ?>
        <?=_previous_next('previous', 'admin_users', $this->validation->admin_id);?>
        <?php if ($this->validation->admin_id > 1): ?>
        <a data-href="<?=admin_url()?>admin_users/delete_admin/<?=$this->validation->admin_id?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i> <span class="hidden-xs"><?=$this->lang->line('delete')?></span></a>
        <?php endif; ?>
		<?php endif; ?>
        <a href="<?=admin_url()?>admin_users/view_admins" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_administrators')?></span></a>
        <?php if ($function == 'update_admin'): ?>
        <?=_previous_next('next', 'admin_users', $this->validation->admin_id);?>
        <?php endif; ?>
    </div>
</div>
<hr />
<div class="box-info">
<div class="row">
    <div class="col-md-2 text-center animated fadeIn">
        <div class="thumbnail">
			<?php if ($this->validation->admin_photo): ?>
            <img src="<?=base_url('js')?>images/admins/<?=$this->validation->admin_photo;?>" class="img-thumbnail"/>
            <?php else: ?>
            <img src="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/img/profile.png" />
            <?php endif; ?>  
            <div class="caption capitalize">
                <?php if ($function == 'update_admin'): ?>
                <hr />
                <h4><?=$this->lang->line('update_admin_photo')?></h4>
                <form method="post" action="<?=admin_url()?>admin_users/upload_photo/<?=$this->validation->admin_id?>" role="form" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="file" name="userfile" class="btn btn-default" title="<?=$this->lang->line('select')?>">
                        <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-upload"></i> <?=$this->lang->line('upload')?></button>
                    </div>                    
                </form>
                <hr />
                <?php if (!empty($this->validation->last_login_ip)): ?>
				<p class="text-center">
                	<?=$this->lang->line('last_login_ip')?><br />
					<a class="btn btn-default btn-sm" href="http://whatismyipaddress.com/<?=$this->validation->last_login_ip?>" target="_blank">
						<?=$this->validation->last_login_ip?>
                	</a>
                </p>
                <?php endif; ?>
                <?php else: ?>
                <h4><?=$this->lang->line('add_administrator')?></h4>
				<?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-9">
    <form role="form" method="post" class="form-horizontal"> 
    	<ul class="nav nav-tabs capitalize" role="tablist">
            <li class="active"><a href="#edit" role="tab" data-toggle="tab"><?=$this->lang->line('edit_datails')?></a></li>
            <li><a href="#alerts" role="tab" data-toggle="tab"><?=$this->lang->line('admin_alerts')?></a></li>
            <?php if ($this->validation->admin_id > 1): ?>
            <li><a href="#perms" role="tab" data-toggle="tab"><?=$this->lang->line('security')?></a></a></li>
    		<?php endif; ?>
        </ul>
    	<div class="tab-content">
      		<div class="tab-pane fade in active" id="edit">
           		<div class="hidden-xs">
				<?php if ($function == 'update_admin'): ?>
                <?php if (!empty($this->validation->last_login_date)): ?>
                <a class="btn btn-default pull-right btn-sm"><?=$this->lang->line('last_login_date')?>:
                <?=_show_date($this->validation->last_login_date, true)?></a>
                <?php endif; ?>
                
                    <h3 class="header capitalize"><?=$this->validation->fname?> <?=$this->validation->lname ?> </h3>
                    <h6><i class="fa fa-envelope"></i> <?=$this->validation->primary_email?></h6>
                <?php else: ?>
                    <h3 class="header capitalize"><?=$this->lang->line('new_admin_details')?></h3>
                <?php endif; ?>
                </div>
              	<hr />
                <div class="form-group">
                    <label for="status" class="col-sm-3 control-label"><?=$this->lang->line('status')?></label>
                    <div class="col-lg-5">
                        <?=form_dropdown('status', array('active' => $this->lang->line('active'), 'inactive' => $this->lang->line('inactive')), $this->validation->status, 'class="form-control"')?>
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label for="fname" class="col-sm-3 control-label"><?=$this->lang->line('first_name')?></label>
                    <div class="col-lg-5">
                        <input name="fname" id="fname" type="text"  value="<?=$this->validation->fname?>" class="form-control" />
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label for="lname" class="col-sm-3 control-label"><?=$this->lang->line('last_name')?></label>
                    <div class="col-lg-5">
                        <input name="lname" id="lname" type="text"  value="<?=$this->validation->lname?>" class="form-control" />
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label for="username" class="col-sm-3 control-label"><?=$this->lang->line('username')?></label>
                    <div class="col-lg-5">
                        <input name="username" id="username" type="text"  value="<?=$this->validation->username?>" class="form-control" />
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label for="primary_email" class="col-sm-3 control-label"><?=$this->lang->line('primary_email')?></label>
                    <div class="col-lg-5">
                        <input name="primary_email" id="primary_email" type="text"  value="<?=$this->validation->primary_email?>" class="form-control" />
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label for="apassword" class="col-sm-3 control-label"><?=$this->lang->line('password')?></label>
                    <div class="col-lg-5">
                        <input name="apassword" id="apassword" type="password"  class="form-control" />
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label for="passconf" class="col-sm-3 control-label"><?=$this->lang->line('confirm_password')?></label>
                    <div class="col-lg-5">
                        <input name="passconf" id="passconf" type="password" class="form-control" />
                    </div>
                </div>
                <hr />
                <?php if ($function == 'update_admin'): ?>
                <div class="form-group">
                    <label for="admin_photo" class="col-sm-3 control-label"><?=$this->lang->line('admin_photo')?></label>
                    <div class="col-lg-5">
                        <input name="admin_photo" id="admin_photo" type="text"  value="<?=$this->validation->admin_photo?>" class="form-control" />
                    </div>
                </div>
                <hr />
				<?php endif; ?>
                <div class="form-group">
                    <label for="rows_per_page" class="col-sm-3 control-label"><?=$this->lang->line('rows_per_page')?></label>
                    <div class="col-lg-5">
                        <?=form_dropdown('rows_per_page',array(12 => 12, 24 => 24, 48 => 48, 96 => 96), $this->validation->rows_per_page, 'class="form-control"')?>   
                    </div>
                </div>              
      		</div>
            <div class="tab-pane fade in" id="alerts">
                <h2 class="header"> <?=$this->lang->line('administrative_alerts')?></h2>
                <h6><?=$this->lang->line('administrative_alerts_desc')?></h6>
                <hr />
                <div class="form-group">
                    <label for="alert_affiliate_signup" class="col-sm-3 control-label"><?=$this->lang->line('alert_on_new_affiliate_signup')?></label>
                    <div class="col-lg-5">
                        <?=form_dropdown('alert_affiliate_signup', array('1' => $this->lang->line('yes'), '0' => $this->lang->line('no')), $this->validation->alert_affiliate_signup, 'class="form-control"')?>
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label for="alert_affiliate_commission" class="col-sm-3 control-label"><?=$this->lang->line('alert_on_new_affiliate_commission')?></label>
                    <div class="col-lg-5">
                        <?=form_dropdown('alert_affiliate_commission', array('1' => $this->lang->line('yes'), '0' => $this->lang->line('no')), $this->validation->alert_affiliate_commission, 'class="form-control"')?>
                    </div>
                </div>
            </div>
            <?php if ($this->validation->admin_id > 1): ?>
            <div class="tab-pane fade in" id="perms">
                <h2 class="header"> <?=$this->lang->line('security_permissions')?></h2>
                <h6><?=$this->lang->line('select_permissions_for_admin_desc')?></h6>
                <hr />
                <div class="form-group">
                    <label for="form_permissions" class="col-sm-3 control-label"><?=$this->lang->line('ctrl_click_select')?></label>
                    <div class="col-lg-5">
                        <?=$this->validation->form_permissions?>
                    </div>
                </div>
      		</div>
            <?php endif; ?>
    	</div>
        <div class="row">
            <div class="col-sm-offset-3 col-sm-2 text-right">
                <button class="btn btn-success btn-lg" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button>
            </div>                           
        </div>        
        </form>
    </div>
</div>
</div>
<br />