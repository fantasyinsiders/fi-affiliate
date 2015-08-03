<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?> 
<div class="row">
    <div class="col-md-4">
		<?=_generate_sub_headline('manage_member', 'ID ' . $this->validation->member_id)?>
    </div>
    <div class="col-md-8 text-right">
    	<?php if ($function == 'update_member'): ?>
        <?=_previous_next('previous', 'members', $this->validation->member_id);?>
    	<a href="<?=admin_url()?>members/add_member" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add_member')?></span></a>
        <a data-href="<?=admin_url()?>members/delete_member/<?=$this->validation->member_id;?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i> <span class="hidden-xs"><?=$this->lang->line('delete_member')?></span></a>
        <?php endif; ?>
        <a href="<?=admin_url()?>members/view_members" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('view_members')?></span></a>
   		<?php if ($function == 'update_member'): ?>
        <?=_previous_next('next', 'members', $this->validation->member_id);?>
        <?php endif; ?>
	</div>
</div>
<hr />
<div class="row">    
   	<div class="col-lg-2 text-center animated fadeInDown">
   		<div class="visible-xs">
            <h2 class="header"> <?=$this->validation->fname?> <?=$this->validation->lname ?> </h2>
            <h6><a href="<?=admin_url()?>email_send/member/<?=$this->validation->member_id?>"><i class="fa fa-envelope"></i> <?=$this->validation->primary_email?></a></h6>
     	</div>
        <div class="thumbnail">
            <img src="<?=$this->validation->member_photo_url;?>"  class="img-responsive" style="width: 100%"/>
            <div class="caption">
                <hr />
                <form method="post" action="<?=admin_url()?>members/upload_photo" role="form" enctype="multipart/form-data">
                <h4><?=$this->lang->line('update_member_photo')?></h4>
                <div class="form-group">
                    <input type="file" name="userfile" class="btn btn-default btn-block" title="<?=$this->lang->line('select_photo')?>">
                    <button type="submit" class="btn btn-default btn-block"><i class="fa fa-upload"></i> <?=$this->lang->line('upload')?></button>
                     <?php if ($this->validation->member_photo_delete): ?>
                    <a href="<?=admin_url()?>members/delete_photo/<?=$this->validation->raw_name?>/<?=$this->validation->member_id?>" class="btn btn-danger btn-block"><i class="fa fa-trash-o"></i> <span class="hidden-xs"><?=$this->lang->line('delete_photo')?></span></a>
                    <?php endif; ?>
                </div>
                <input type="hidden" name="member_id" value="<?=$this->validation->member_id?>" />
                </form>
                <div class="visible-xs">
                    <a href="<?=admin_url()?>members/login_member/<?=$this->validation->member_id?>" target="member-login" class="btn btn-default btn-sm btn-block" role="button"><i class="fa fa-unlock"></i> <?=$this->lang->line('login_to_members_area')?></a>
                    <a href="<?=admin_url()?>members/send_welcome_email/<?=$this->validation->member_id?>" class="btn btn-default btn-sm btn-block" role="button"><i class="fa fa-unlock"></i> <?=$this->lang->line('send_welcome_email')?></a>
                    <a href="<?=admin_url()?>email_send/member/<?=$this->validation->member_id?>" class="btn btn-default btn-sm btn-block" role="button"><i class="fa fa-envelope"></i> <?=$this->lang->line('send_email')?></a>                        
                    <a href="javascript:void(window.open('<?=admin_url()?>downline/view_downline/<?=$this->validation->member_id?>', 'popup', 'width=700,height=600, location=no, menubar=no, status=no,toolbar=no, scrollbars=yes, resizable=yes'))" class="btn btn-default btn-sm btn-block" role="button"><i class="fa fa-sitemap"></i> <?=$this->lang->line('view_downline')?>
                         </a>
                    <a href="<?=admin_url()?>commissions/view_commissions/0/0/0/member_id/<?=$this->validation->member_id?>" target="_blank" class="btn btn-default btn-sm btn-block" role="button"><i class="fa fa-money"></i> <?=$this->lang->line('view_commissions')?> </a>
                    <a a href="<?=admin_url()?>affiliate_payments/view_affiliate_payments/0/0/0/member_id/<?=$this->validation->member_id?>" class="btn btn-default btn-sm btn-block" role="button"><i class="fa fa-edit"></i> <?=$this->lang->line('view_affiliate_payments')?></a>
                    <?php if ($sts_tracking_enable_lifetime_tracking == 1): ?>
                    <a a href="<?=admin_url()?>tracking_log/view_logs/0/0/0/member_id/<?=$this->validation->member_id?>" class="btn btn-default btn-sm btn-block" role="button"><i class="fa fa-search-plus"></i> <?=$this->lang->line('view_lifetime_tracking_log')?></a>  
                    <?php endif; ?>      
                 </div>  
            </div>
        </div>
    </div><!-- end .col-md-2-->
    <div class="col-lg-7">
    	<form id="prod_form" class="form-horizontal" method="post" role="form" />
        <div class="box-info">
            <ul class="nav nav-tabs capitalize" role="tablist">
                <li class="active"><a href="#overview" role="tab" data-toggle="tab"><?=$this->lang->line('overview')?></a></li>
                <li><a href="#edit" role="tab" data-toggle="tab"><?=$this->lang->line('edit')?></a></li>
                <li><a href="#affinfo" role="tab" data-toggle="tab"><?=$this->lang->line('affiliate_info')?></a></li>
                <li><a href="#groups" role="tab" data-toggle="tab"><?=$this->lang->line('groups')?></a></li>
                <li><a href="#custom" role="tab" data-toggle="tab"><?=$this->lang->line('custom_fields')?></a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active fade in" id="overview">
                    <div class="hidden-xs">   
                       	<span class="pull-right">
                        	<?php if ($this->validation->status == 1): ?>
                        	<a href="<?=admin_url()?>members/login_member/<?=$this->validation->member_id?>" target="member-login" class="btn btn-info btn-sm" role="button"><i class="fa fa-key"></i> <?=$this->lang->line('login_to_members_area')?></a>
                            <?php else: ?>
                            <a href="<?=admin_url()?>members/update_status/<?=$this->validation->member_id?>" class="btn btn-sm btn-warning" role="button"><i class="fa fa-key"></i> <?=$this->lang->line('activate_user')?></a>
                            <?php endif; ?>
                        </span>
                        <span class="pull-right">
                        	<a href="<?=admin_url()?>members/send_welcome_email/<?=$this->validation->member_id?>" class="btn btn-default btn-sm" role="button"><i class="fa fa-unlock"></i> <?=$this->lang->line('send_welcome_email')?></a>&nbsp;
                        </span>
                        <?php if ($this->validation->facebook_id): ?> 
                        <span class="pull-right mem-social">
                        	<a href="http://facebook.com/<?=$this->validation->facebook_id?>" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-facebook"></i></a>
                        </span>
						<?php endif; ?>
                        <?php if ($this->validation->twitter_id): ?> 
                        <span class="pull-right mem-social">
                        	<a href="http://twitter.com/<?=$this->validation->twitter_id?>" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-twitter"></i></a>
                        </span>
						<?php endif; ?>
                        <?php if ($this->validation->linkedin_id): ?> 
                        <span class="pull-right mem-social">
                        	<a href="http://linkedin.com/in/<?=$this->validation->linkedin_id?>" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-linkedin"></i></a>
                    	</span>
						<?php endif; ?>
                        <h1 class="header"><?=$this->validation->fname?> <?=$this->validation->lname ?> </h1>
                        <h6><i class="fa fa-envelope"></i> <?=$this->validation->primary_email?></h6>
                        <h6><i class="fa fa-globe"></i> <?=_get_aff_link($this->validation->username)?></h6>
                        <?php if ($this->validation->sponsor):?>
                        <h6><i class="fa fa-user"></i> <?=$this->lang->line('sponsor')?>:  <a href="<?=admin_url()?>members/update_member/<?=$this->validation->sponsor_id?>"><?=$this->validation->sponsor?></a></h6>
                        <?php endif; ?>
                        <hr />
                    </div>
                    <div class="hidden-xs">
                      	<a href="javascript:void(window.open('<?=admin_url()?>downline/view_downline/<?=$this->validation->member_id?>', 'popup', 'width=700,height=600, location=no, menubar=no, status=no,toolbar=no, scrollbars=yes, resizable=yes'))" class="btn btn-default btn-sm" role="button"><i class="fa fa-sitemap"></i> <?=$this->lang->line('view_downline')?>
                             </a>
                       	<a href="<?=admin_url()?>commissions/view_commissions/0/0/0/member_id/<?=$this->validation->member_id?>" target="_blank" class="btn btn-default btn-sm" role="button"><i class="fa fa-money"></i> <?=$this->lang->line('commissions')?> </a>
                		<a a href="<?=admin_url()?>affiliate_payments/view_affiliate_payments/0/0/0/member_id/<?=$this->validation->member_id?>" class="btn btn-default btn-sm" role="button"><i class="fa fa-edit"></i> <?=$this->lang->line('affiliate_payments')?></a>
						<?php if ($sts_tracking_enable_lifetime_tracking == 1): ?>
                        <a a href="<?=admin_url()?>tracking_log/view_logs/0/0/0/member_id/<?=$this->validation->member_id?>" class="btn btn-default btn-sm" role="button"><i class="fa fa-search-plus"></i> <?=$this->lang->line('lifetime_tracking_log')?></a>  
                        <?php endif; ?>
                        <a href="<?=admin_url()?>email_send/member/<?=$this->validation->member_id?>" class="btn btn-default btn-sm" role="button"><i class="fa fa-envelope"></i> <?=$this->lang->line('send_email')?></a>
                    </div> 
                    <br />
                    <div class="overview-address">
                    <div class="row">
                        <div class="col-lg-4"> 
                            <h3 class="header capitalize"><i class="fa fa-map-marker capitalize"></i> <?=$this->lang->line('address')?></h3>
                            <h5><?=$this->validation->billing_address_1?></h5>
                            <h5><?=$this->validation->billing_address_2?></h5>
                            <h5><?=$this->validation->billing_city?> <?=$this->validation->billing_state?> <?=$this->validation->billing_postal_code?></h5>
                            <h5><?=$this->validation->billing_country_name?></h5>
                        </div>   
                        <div class="col-lg-4">
                        	<h3 class="header capitalize"><i class="fa fa-phone capitalize"></i> <?=$this->lang->line('phone')?></h3>
                            <?php if ($this->validation->home_phone ||$this->validation->work_phone || $this->validation->mobile_phone): ?>
							<?php if ($this->validation->home_phone): ?>
                            <h5><i class="fa fa-home"></i> <?=format_phone($this->validation->home_phone)?></h5>
							<?php endif; ?>
                            <?php if ($this->validation->work_phone): ?>
                            <h5><i class="fa fa-building-o"></i> <?=format_phone($this->validation->work_phone)?></h5>
          					<?php endif; ?>
                            <?php if ($this->validation->mobile_phone): ?>
                            <h5><i class="fa fa-mobile"></i> <?=format_phone($this->validation->mobile_phone)?></h5>
                            <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-4 capitalize">
                        	<h3 class="header"><i class="fa fa-lock capitalize"></i> <?=$this->lang->line('access')?></h3>
                            <h5><?=$this->lang->line('signup')?>: <?=$this->validation->signup_date?> </h5>
                            <h5><?=$this->lang->line('last_login')?>: <?=$this->validation->last_login_date?></h5>
                            <h5><?=$this->lang->line('from_ip')?>: <a href="<?=$geo_location_api_url?>/<?=$this->validation->last_login_ip?>" target="_blank"><?=$this->validation->last_login_ip?></a></h5>
                        </div>
                    </div>
                    </div>
                </div><!-- end #overview -->
                <div class="tab-pane fade in" id="edit">
             		<hr />
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('status')?></label>
                        <div class="col-lg-4">
                            <?=form_dropdown('status', array('1' => $this->lang->line('active'), '0' => $this->lang->line('inactive')), $this->validation->status, 'class="form-control"')?>
                        </div>
                        <label class="col-lg-2 control-label"><?=$this->lang->line('confirmed')?></label>
                        <div class="col-lg-4">
                            <?=form_dropdown('login_status', array('0' => $this->lang->line('yes'), '1' => $this->lang->line('no')), $this->validation->login_status, 'class="form-control"')?>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('first_name')?></label>
                        <div class="col-lg-4">
                            <input name="fname" id="fname" type="text"  value="<?=$this->validation->fname?>" class="form-control required"  />
                        </div>
                        <label class="col-lg-2 control-label"><?=$this->lang->line('last_name')?></label>
                        <div class="col-lg-4">
                            <input name="lname" id="lname" type="text"  value="<?=$this->validation->lname?>" class="form-control"  />
                        </div>
                    </div> 
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('email')?></label>
                        <div class="col-lg-4">
                            <input name="primary_email" id="primary_email" type="text"  value="<?=$this->validation->primary_email?>" class="form-control required email"  />
                        </div>
                        <label class="col-lg-2 control-label"><?=$this->lang->line('username')?></label>
                        <div class="col-lg-4">
                            <input name="username" id="username" type="text"  value="<?=$this->validation->username?>" class="form-control"  />
                        </div>
                    </div>
                    <hr />
					<div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('password')?></label>
                        <div class="col-lg-4">
                            <input name="password" id="password" type="password" class="form-control"  />
                        </div>
                        <label class="col-lg-2 control-label"><?=$this->lang->line('confirm_password')?></label>
                        <div class="col-lg-4">
                            <input name="passconf" id="passconf" type="password" class="form-control"  />
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('address_1')?></label>
                        <div class="col-lg-4">
                            <input name="billing_address_1" id="billing_address_1" type="text"  value="<?=$this->validation->billing_address_1?>" class="form-control"  />
                        </div>
                        <label class="col-lg-2 control-label"><?=$this->lang->line('address_2')?></label>
                        <div class="col-lg-4">
                            <input name="billing_address_2" id="billing_address_2" type="text"  value="<?=$this->validation->billing_address_2?>" class="form-control"  />
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('city')?></label>
                        <div class="col-lg-4">
                            <input name="billing_city" id="billing_city" type="text"  value="<?=$this->validation->billing_city?>" class="form-control"  />
                        </div>
                        <label class="col-lg-2 control-label"><?=$this->lang->line('state')?></label>
                        <div class="col-lg-4">
                            <input name="billing_state" id="billing_state" type="text"  value="<?=$this->validation->billing_state?>" class="form-control"  />
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('postal_code')?></label>
                        <div class="col-lg-4">
                            <input name="billing_postal_code" id="billing_postal_code" type="text"  value="<?=$this->validation->billing_postal_code?>" class="form-control"  />
                        </div>
                        <label class="col-lg-2 control-label"><?=$this->lang->line('country')?></label>
                        <div class="col-lg-4">
                            <?=form_dropdown('billing_country', $country_options, $this->validation->billing_country, 'class="form-control"')?>
                        </div>    
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('company')?></label>
                        <div class="col-lg-4">
                            <input name="company" id="company" type="text"  value="<?=$this->validation->company?>" class="form-control"  />
                        </div>
                        <label class="col-lg-2 control-label"><?=$this->lang->line('home_phone')?></label>
                        <div class="col-lg-4">
                            <input name="home_phone" id="home_phone" type="text"  value="<?=$this->validation->home_phone?>" class="form-control" />
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('work_phone')?></label>
                        <div class="col-lg-4">
                            <input name="work_phone" id="work_phone" type="text"  value="<?=$this->validation->work_phone?>" class="form-control"  />
                        </div>
                        <label class="col-lg-2 control-label"><?=$this->lang->line('mobile_phone')?></label>
                        <div class="col-lg-4">
                            <input name="mobile_phone" id="mobile_phone" type="text"  value="<?=$this->validation->mobile_phone?>" class="form-control"  />
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('fax')?></label>
                        <div class="col-lg-4">
                            <input name="fax" id="fax" type="text"  value="<?=$this->validation->fax?>" class="form-control"  />
                        </div>
                    </div>
                    <hr />
                    <div class="text-right"><button class="btn btn-success btn-lg" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button></div>
                </div><!-- end #edit --> 
                <div class="tab-pane fade in" id="affinfo">
            		<hr />
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('sponsor')?></label>
                        <div class="col-lg-4">
                            <input name="sponsor" id="sponsor" type="text"  value="<?=$this->validation->sponsor?>" class="form-control"  />
                        </div>
                        <label class="col-lg-2 control-label"><?=$this->lang->line('view_hidden_programs')?></label>
                        <div class="col-lg-4">
                            <?=form_dropdown('view_hidden_programs', array('0' => $this->lang->line('no'), '1' => $this->lang->line('yes')), $this->validation->view_hidden_programs, 'class="form-control"')?>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                    	<label class="col-lg-2 control-label"><?=$this->lang->line('enable_custom_url')?></label>
                        <div class="col-lg-4">
                            <?=form_dropdown('enable_custom_url', array('0' => $this->lang->line('no'), '1' => $this->lang->line('yes')), $this->validation->enable_custom_url, 'class="form-control"')?>
                        </div>
                        <label class="col-lg-2 control-label"><?=$this->lang->line('custom_url_link')?></label>
                        <div class="col-lg-4">
                            <input name="custom_url_link" id="custom_url_link" type="text"  value="<?=$this->validation->custom_url_link?>" class="form-control"  />
                        </div>                        
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('website')?></label>
                        <div class="col-lg-4">
                            <input name="website" id="website" type="text"  value="<?=$this->validation->website?>" class="form-control"  />
                        </div>
                        <label class="col-lg-2 control-label"><?=$this->lang->line('facebook_id')?></label>
                        <div class="col-lg-4">
                            <input name="facebook_id" id="facebook_id" type="text"  value="<?=$this->validation->facebook_id?>" class="form-control"  />
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('twitter_id')?></label>
                        <div class="col-lg-4">
                            <input name="twitter_id" id="twitter_id" type="text"  value="<?=$this->validation->twitter_id?>" class="form-control"  />
                        </div>
                        <label class="col-lg-2 control-label"><?=$this->lang->line('linkedin_id')?></label>
                        <div class="col-lg-4">
                            <input name="linkedin_id" id="linkedin_id" type="text"  value="<?=$this->validation->linkedin_id?>" class="form-control"  />
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('payment_name')?></label>
                        <div class="col-lg-4">
                            <input name="payment_name" id="payment_name" type="text"  value="<?=$this->validation->payment_name?>" class="form-control"  />
                        </div>
                        <label class="col-lg-2 control-label"><?=$this->lang->line('payment_preference_amount')?></label>
                        <div class="col-lg-4">
                            <input name="payment_preference_amount" id="payment_preference_amount" type="text"  value="<?=$this->validation->payment_preference_amount?>" class="form-control"  />
                        </div>
                    </div>
                    <hr />
                     <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('payment_address_1')?></label>
                        <div class="col-lg-4">
                            <input name="payment_address_1" id="payment_address_1" type="text"  value="<?=$this->validation->payment_address_1?>" class="form-control"  />
                        </div>
                        <label class="col-lg-2 control-label"><?=$this->lang->line('payment_address_2')?></label>
                        <div class="col-lg-4">
                            <input name="payment_address_2" id="payment_address_2" type="text"  value="<?=$this->validation->payment_address_2?>" class="form-control"  />
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('payment_city')?></label>
                        <div class="col-lg-4">
                            <input name="payment_city" id="payment_city" type="text"  value="<?=$this->validation->payment_city?>" class="form-control"  />
                        </div>
                        <label class="col-lg-2 control-label"><?=$this->lang->line('payment_state')?></label>
                        <div class="col-lg-4">
                            <input name="payment_state" id="payment_state" type="text"  value="<?=$this->validation->payment_state?>" class="form-control"  />
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('payment_postal_code')?></label>
                        <div class="col-lg-4">
                            <input name="payment_postal_code" id="payment_postal_code" type="text"  value="<?=$this->validation->payment_postal_code?>" class="form-control"  />
                        </div>
                        <label class="col-lg-2 control-label"><?=$this->lang->line('payment_country')?></label>
                        <div class="col-lg-4">
                            <?=form_dropdown('payment_country', $country_options, $this->validation->payment_country, 'class="form-control"')?>
                        </div>    
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('paypal_id')?></label>
                        <div class="col-lg-4">
                            <input name="paypal_id" id="paypal_id" type="text"  value="<?=$this->validation->paypal_id?>" class="form-control"  />
                        </div>
                        <label class="col-lg-2 control-label"><?=$this->lang->line('moneybookers_id')?></label>
                        <div class="col-lg-4">
                            <input name="moneybookers_id" id="moneybookers_id" type="text"  value="<?=$this->validation->moneybookers_id?>" class="form-control"  />
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('dwolla_id')?></label>
                        <div class="col-lg-4">
                            <input name="dwolla_id" id="dwolla_id" type="text"  value="<?=$this->validation->dwolla_id?>" class="form-control"  />
                        </div>
                        <label class="col-lg-2 control-label"><?=$this->lang->line('coinbase_id')?></label>
                        <div class="col-lg-4">
                            <input name="coinbase_id" id="coinbase_id" type="text"  value="<?=$this->validation->coinbase_id?>" class="form-control"  />
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('payza_id')?></label>
                        <div class="col-lg-4">
                            <input name="payza_id" id="payza_id" type="text"  value="<?=$this->validation->payza_id?>" class="form-control"  />
                        </div>
                        <label class="col-lg-2 control-label"><?=$this->lang->line('custom_id')?></label>
                        <div class="col-lg-4">
                            <input name="custom_id" id="custom_id" type="text"  value="<?=$this->validation->custom_id?>" class="form-control"  />
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('bank_transfer')?></label>
                        <div class="col-lg-10">
                            <textarea name="bank_transfer" id="bank_transfer"  class="form-control" rows="5"><?=$this->validation->bank_transfer?></textarea>
                        </div>
                    </div>
                    <hr />
                    <div class="text-right"><button class="btn btn-success btn-lg" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button></div>
                </div><!-- end #alerts -->
                <div class="tab-pane fade in" id="groups">
                	<hr />
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('alert_downline_signup')?></label>
                        <div class="col-lg-4">
                            <?=form_dropdown('alert_downline_signup', array('2' => $this->lang->line('global'), '0' => $this->lang->line('disable'), '1' => $this->lang->line('enable')), $this->validation->alert_downline_signup, 'class="form-control"')?>
                        </div>
                        <label class="col-lg-2 control-label"><?=$this->lang->line('alert_new_commission')?></label>
                        <div class="col-lg-4">
                            <?=form_dropdown('alert_new_commission', array('2' => $this->lang->line('global'), '0' => $this->lang->line('disable'), '1' => $this->lang->line('enable')), $this->validation->alert_new_commission, 'class="form-control"')?>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('alert_payment_sent')?></label>
                        <div class="col-lg-4">
                            <?=form_dropdown('alert_payment_sent', array('2' => $this->lang->line('global'), '0' => $this->lang->line('disable'), '1' => $this->lang->line('enable')), $this->validation->alert_payment_sent, 'class="form-control"')?>
                        </div>
                        <label class="col-lg-2 control-label"><?=$this->lang->line('allow_downline_view')?></label>
                        <div class="col-lg-4">
                            <?=form_dropdown('allow_downline_view', array('2' => $this->lang->line('global'), '0' => $this->lang->line('disable'), '1' => $this->lang->line('enable')), $this->validation->allow_downline_view, 'class="form-control"')?>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('allow_downline_email')?></label>
                        <div class="col-lg-4">
                            <?=form_dropdown('allow_downline_email', array('2' => $this->lang->line('global'), '0' => $this->lang->line('disable'), '1' => $this->lang->line('enable')), $this->validation->allow_downline_email, 'class="form-control"')?>
                        </div>
                        <label class="col-lg-2 control-label"><?=$this->lang->line('custom_affiliate_group')?></label>
                        <div class="col-lg-4">
                            <?=$this->validation->affiliate_groups;?>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?=$this->lang->line('mailing_lists')?></label>
                        <div class="col-lg-10">
                            <?=$this->validation->email_mailing_lists;?>
                        </div>
                    </div>
                    <hr />
                    <div class="text-right"><button class="btn btn-success btn-lg" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button></div>
                </div><!-- end #groups -->	
                <div class="tab-pane fade in" id="custom">
                	<hr />
                    <?php for($i=1; $i<=20;$i++): ?>
                    <div class="form-group">
	                    <?php $field = 'program_custom_field_' . $i?>
    	                <label class="col-lg-4 control-label"><?=_check_form_fields($field, $language_fields)?></label>
        	            <div class="col-lg-6">
            	        	<input name="<?=$field?>" id="<?=$field?>" type="text"  value="<?=$this->validation->$field?>" class="form-control"  />
                		</div>
                    </div>	
                    <hr />
                   	<?php endfor; ?>
                    
                    <div class="text-right"><button class="btn btn-success btn-lg" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button></div>
                </div><!-- end #custom -->
            </div><!-- end .tab-content -->
   		</div><!-- end .box-info -->
        </form>
    </div><!-- end .col-lg-7 -->
    <div class="col-lg-3 capitalize">
		<div class="box-info animated fadeInRight">
            <div class="text-box">
                <h2><i class="fa fa-calendar pull-right"></i> <?=date('F Y', _generate_timestamp())?> <?=$this->lang->line('stats')?></h2>
                <h3 class="pull-right"><?=$month_clicks?></h3>
                <p><?=$this->lang->line('affiliate_clicks')?></p>
                <h3 class="pull-right"><?=format_amounts($month_comm, $num_options)?></h3>
                <p><?=$this->lang->line('commissions')?></p>
                <h3 class="pull-right"><?=$total_referrals?></h3>
                <p><?=$this->lang->line('referrals')?></p>
            </div>
            <div class="clear"></div>
        </div>
        <div class="box-info animated fadeInRight">
            <div class="text-box">
                <h2 class="capitalize"><i class="fa fa-bar-chart-o pull-right"></i> <?=$this->lang->line('total_affiliate_stats')?></h2>
                <h3 class="pull-right"><?=$total_clicks?></h3>
                <p><?=$this->lang->line('total_clicks')?></p>
                <h3 class="pull-right"><?=format_amounts($total_comm, $num_options)?></h3>
                <p><?=$this->lang->line('total_commissions')?></p>
                <h3 class="pull-right"><?=$total_referrals?></h3>
                <p><?=$this->lang->line('total_referrals')?></p>
                <h3 class="pull-right"><?=format_amounts($total_payments, $num_options)?></h3>
                <p><?=$this->lang->line('payments_made')?></p>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<div class="row">
	<div class="col-md-12">
		<?php if ($sts_admin_enable_member_graphs == 1): ?>
        <div class="box-info"> 
            <div class="text-box">
            	<h2><?=date('F Y', _generate_timestamp())?></h2>
            	<div class="memberGraph">
            		<div class="pull-right"><span style="background-color:#DB887F;" class="label"><?=$this->lang->line('clicks')?></span> <span style="background-color:#4572A7;" class="label"><?=$this->lang->line('commissions')?></span></div>
            		<iframe src ="<?=admin_url()?>reports/user_quick_stats/<?=$this->validation->member_id?>" class="column" scrolling="no" frameborder="0" width="900" height="312"></iframe>
            	</div>
            </div>
        </div>
        <?php endif; ?>
	</div>
</div>
<script language="JavaScript" type="text/javascript" src="<?=base_url('js')?>js/autocomplete/jquery.autocomplete.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#sponsor").autocomplete("<?=admin_url()?>search/ajax_members");
});
</script>