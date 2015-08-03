<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?=$editor_path?>
<div class="row">
   	<div class="col-md-6">
    <?=_generate_sub_headline($page_title)?>
    </div>
    <div class="col-md-6 text-right">
		<?php if ($function == 'update_program'): ?>
        <?=_previous_next('previous', 'programs', $this->validation->id);?>
         <a data-href="<?=admin_url()?>programs/delete_program/<?=$this->validation->id?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i> <span class="hidden-xs"><?=$this->lang->line('delete')?></span></a>
        <a href="<?=admin_url()?>programs/add_program" class="btn btn-primary"><i class="fa fa-plus"></i> <span class="hidden-xs"><?=$this->lang->line('add')?></span></a>
		<?php endif; ?>
        
        <a href="<?=admin_url()?>programs/view_programs" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_programs')?></span></a>
        <?php if ($function == 'update_program'): ?>
        <?=_previous_next('next', 'programs', $this->validation->id);?>
        <?php endif; ?>
    </div>
</div>
<hr />
<div class="row">   
	<div class="col-md-12">
        <div class="row">
            <div class="col-lg-2 col-md-3">
                 <div class="thumbnail text-center">
                    <form method="post" action="<?=admin_url()?>programs/upload_photo/<?=$this->validation->id?>" role="form" enctype="multipart/form-data">							 
                    <?php if (!empty($photos)): ?>
                    <?php foreach ($photos as $v): ?>
                    <img src="<?=$this->validation->program_photo?>" class="img-responsive" />
                    <?php endforeach; ?>
                    <?php else: ?>
                    <img src="<?=base_url('js')?>themes/admin/default/img/offers.png" class="img-responsive" />
                    <?php endif; ?>
                    <div class="caption capitalize">
                        <hr />
                        <?php if ($function == 'update_program'): ?>
                        <h5><?=$this->lang->line('upload_photo')?></h5>
                        <div class="form-group">
                            <input type="file" name="userfile" class="btn btn-default btn-block" title="<?=$this->lang->line('select_photo')?>">
                            <button type="submit" class="btn btn-default btn-block"><i class="fa fa-upload"></i> <?=$this->lang->line('upload')?></button>
                        </div>
                        <?php else: ?>
                        <h5><?=$this->lang->line('add_program_offer')?></h5>
                        <?php endif; ?>
                    </div>
                    	<input type="hidden" name="program_id" value="<?=$this->validation->id?>" />
                    </form>
                </div>
            </div>
            <div class="col-lg-10 col-md-9">
            	<div class="box-info">
                    <form id="form"  method="post" class="form-horizontal"  role="form">
                    <ul class="nav nav-tabs capitalize responsive">
                        <li class="active"><a href="#info" data-toggle="tab"><?=$this->lang->line('program_info')?></a></li>
                        <li><a href="#desc" data-toggle="tab"><?=$this->lang->line('description')?></a></li>
                        <?php if ($function == 'update_program'): ?>
                        <li><a href="#remote" data-toggle="tab"><?=$this->lang->line('remote_affiliate_links')?></a></li>
                        <?php endif; ?>
                    </ul>
                    <div class="tab-content responsive">
                        <div id="info" class="tab-pane fade in active">
                          	<?php if ($this->validation->id == '1'): ?>
                            
                            <input type="hidden" name="program_status" value="1" />
                            <?php else: ?>
                          	<hr />
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=$this->lang->line('program_status')?></label>
                                <div class="col-lg-5">
                                    <?=form_dropdown('program_status', array('1' => $this->lang->line('enable'), '0' => $this->lang->line('disable')), $this->validation->program_status, 'class="form-control"')?>
                                </div>
                            </div>
                            <?php endif; ?>
                            <hr />
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=$this->lang->line('program_name')?></label>
                                <div class="col-lg-5">
                                    <input name="program_name" id="program_name" type="text"  value="<?=$this->validation->program_name?>" class="form-control"  />
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=$this->lang->line('signup_link')?></label>
                                <div class="col-lg-5">
                                    <input name="signup_link" id="signup_link" type="text"  value="<?=$this->validation->signup_link?>" class="form-control"  />
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=$this->lang->line('landing_page_url')?></label>
                                <div class="col-lg-5">
                                    <input name="url_redirect" id="url_redirect" type="text"  value="<?=$this->validation->url_redirect?>" placeholder="<?=base_url()?>" class="form-control"  />
                                </div>
                            </div>
                            <?php if ($this->validation->id != '1'): ?>
                            <hr />
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=$this->lang->line('hidden_program')?></label>
                                <div class="col-lg-5">
                                    <?=form_dropdown('hidden_program', array('0' => $this->lang->line('no'), '1' => $this->lang->line('yes')), $this->validation->hidden_program, 'class="form-control"')?>
                                </div>
                            </div>
                            <?php endif; ?>
                            <hr />
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=$this->lang->line('commission_payout_group')?></label>
                                <div class="col-lg-5">
                                    <?=form_dropdown('group_id', $aff_groups, $this->validation->group_id, 'class="form-control"')?>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=$this->lang->line('commission_levels')?></label>
                                <div class="col-lg-5">
                                    <?=form_dropdown('commission_levels', $levels, $this->validation->commission_levels, 'class="form-control"')?>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=$this->lang->line('enable_pay_per_action')?></label>
                                <div class="col-lg-5">
                                    <?=form_dropdown('enable_pay_per_action', array('1' => $this->lang->line('yes'),'0' => $this->lang->line('no')), $this->validation->enable_pay_per_action, 'class="form-control"')?>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=$this->lang->line('enable_pay_per_click')?></label>
                                <div class="col-lg-5">
                                    <?=form_dropdown('enable_pay_per_click', array('0' => $this->lang->line('no'), '1' => $this->lang->line('yes')), $this->validation->enable_pay_per_click, 'class="form-control show-ppc"')?>
                                </div>
                            </div>
                            <hr />
                            <div id="div-ppc" <?php if ($this->validation->enable_pay_per_click == 0):?> style="display:none"<?php endif; ?>>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=$this->lang->line('ppc_interval')?></label>
                                <div class="col-lg-5">
                                    <input name="ppc_interval" id="ppc_interval" type="text"  value="<?=$this->validation->ppc_interval?>" class="form-control"  />
                                </div>
                            </div>
                            <hr />
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=$this->lang->line('enable_cpm')?></label>
                                <div class="col-lg-5">
                                    <?=form_dropdown('enable_cpm', array('0' => $this->lang->line('no'), '1' => $this->lang->line('yes')), $this->validation->enable_cpm, 'class="form-control show-cpm"')?>
                                </div>
                            </div>
                            <hr />
                            <div id="div-cpm" <?php if ($this->validation->enable_cpm == 0):?> style="display:none"<?php endif; ?>>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=$this->lang->line('cpm_unique_ip')?></label>
                                <div class="col-lg-5">
                                    <?=form_dropdown('cpm_unique_ip', array('0' => $this->lang->line('no'), '1' => $this->lang->line('yes')), $this->validation->cpm_unique_ip, 'class="form-control"')?>
                                </div>
                            </div>
                            <hr />
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=$this->lang->line('commission_frequency')?></label>
                                <div class="col-lg-5">
                                	<div class="input-group">
                                    	<input name="commission_frequency" id="commission_frequency" type="number"  value="<?=$this->validation->commission_frequency?>" class="form-control"  />						<span class="input-group-addon"><?=$this->lang->line('days')?></span>
                                	</div>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=$this->lang->line('restrict_recur_commissions')?></label>
                                <div class="col-lg-5">
                                    <input name="restrict_recur_commissions" id="restrict_recur_commissions" type="number"  value="<?=$this->validation->restrict_recur_commissions?>" class="form-control"  />
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=$this->lang->line('new_commission_option')?></label>
                                <div class="col-lg-5">
                                    <?=form_dropdown('new_commission_option',  array('no_pending' => $this->lang->line('pending_no_email'),'alert_pending' => $this->lang->line('pending_send_email'),'no_unpaid' => $this->lang->line('unpaid_no_email'), 'alert_unpaid' => $this->lang->line('unpaid_send_email')), $this->validation->new_commission_option, 'class="form-control"')?>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=$this->lang->line('auto_approve_commissions')?></label>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <input name="auto_approve_commissions" id="auto_approve_commissions" type="number"  value="<?=$this->validation->auto_approve_commissions?>" class="form-control"  />						<span class="input-group-addon"><?=$this->lang->line('days')?></span>
                                    </div>
                                </div>
                            </div>
                            <hr />
                        </div>
                        <div id="desc" class="tab-pane fade in">
                            <hr />
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=$this->lang->line('program_description')?></label>
                                <div class="col-lg-7 html-editor">
                                    <?=$this->validation->program_description?>
                                </div>
                            </div>
                            <hr />
                            <?php if ($this->validation->id == 1): ?>
                            <div class="alert alert-warning text-warning"><?=$this->lang->line('default_tos_privacy')?></div>
                            <?php endif; ?>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=$this->lang->line('privacy_policy')?></label>
                                <div class="col-lg-7 html-editor">
                                   <?=$this->validation->privacy_policy?>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=$this->lang->line('terms_of_service')?></label>
                                <div class="col-lg-7 html-editor">
                                    <?=$this->validation->terms_of_service?>
                                </div>
                            </div>
                            <hr />
                        </div>
                        <?php if ($function == 'update_program'): ?>
                        <div id="remote" class="tab-pane fade in">
                            <hr />
                            <div class="alert alert-warning text-warning"><?=$this->lang->line('desc_remote_affiliate_link_warning')?></div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=$this->lang->line('use_remote_domain_link')?></label>
                                <div class="col-lg-5">
                                    <input name="use_remote_domain_link" id="use_remote_domain_link" type="text" placeholder="http://www.domain.com/track.php?u={USERNAME}" value="<?=$this->validation->use_remote_domain_link?>" class="form-control"  />
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=$this->lang->line('remote_domain_name')?></label>
                                <div class="col-lg-5">
                                    <input name="remote_domain_name" id="remote_domain_name" type="text"  value="<?=$this->validation->remote_domain_name?>" class="form-control"  />
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=$this->lang->line('program_cookie_name')?></label>
                                <div class="col-lg-5">
                                    <input name="program_cookie_name" id="program_cookie_name" type="text"  value="<?=$this->validation->program_cookie_name?>" class="form-control"  />
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=$this->lang->line('postback_url')?></label>
                                <div class="col-lg-5">
                                    <input name="postback_url" id="postback_url" type="text"  value="<?=$this->validation->postback_url?>" class="form-control"  />
                                </div>
                            </div>
                            <hr />
                            <?php if ($this->validation->use_remote_domain_link): ?>
                            <hr />
                            <div class="form-group">
                                
                                <div class="col-lg-5 col-lg-offset-3">
                                    <a href="<?=admin_url()?>programs/remote_link/<?= $this->validation->id?>"><?=$this->lang->line('click_download_track')?></a>
                                </div>
                            </div>
                            <hr />
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-5 col-md-offset-3"><button class="btn btn-success btn-lg" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button></div>
                    </form>   
                </div>
	        </div>
   		</div>
	</div>
</div>   
<script>
$("select.show-ppc").change(function(){
	$( "select.show-ppc option:selected").each(function(){
		if($(this).attr("value")=="1"){
			$("#div-ppc").show(300);
		}
		if($(this).attr("value")=="0"){
			$("#div-ppc").hide(300);
		}
	});
}).change();
$("select.show-cpm").change(function(){
	$( "select.show-cpm option:selected").each(function(){
		if($(this).attr("value")=="1"){
			$("#div-cpm").show(300);
		}
		if($(this).attr("value")=="0"){
			$("#div-cpm").hide(300);
		}
	});
}).change();
</script> 