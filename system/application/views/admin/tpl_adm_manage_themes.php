<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($themes)): ?>
<div class="alert alert-warning">
	<h2 class="text-warning"><?=$this->lang->line('no_themes_found')?></h2>
    <p><a href="javascript:history.go(-1)" class="btn btn-warning btn-lg"><?=$this->lang->line('go_back')?></a></p>
</div>
<?php else: ?>
<div class="row">
    <div class="col-lg-8">
    <?=_generate_sub_headline('site_layout')?>
    </div>
    <div class="col-lg-4 text-right"> 
		<?php if (!empty($customizer_theme_customization_url)): ?>
        <a href="<?=$customizer_theme_customization_url?>" target="_blank" class="btn btn-primary"><i class="icon-download"></i> <?=$this->lang->line('download_new_themes')?></a>
        <?php endif; ?>
        <button name="toggle" id="toggle" class="btn btn-primary"><i class="fa fa-upload"></i> <?=$this->lang->line('upload_theme')?></button>
    </div>
</div>
<div id="add_block" class="row capitalize" style="display:none">
    <form action="<?=admin_url()?>themes/upload_theme" method="post" id="quick_add_form" enctype="multipart/form-data" style="display:inline">
    	 <div class="col-lg-12">
        	<br />
            <div class="box-info">
                <h5><?=$this->lang->line('theme_zip_file')?></h5>
                <div class="alert alert-warning text-warning"><?=$this->lang->line('theme_upload_via_ftp')?> <strong><?=PUBPATH?>/themes/main</strong></div>
                <div>
                    <div class="col-lg-5">
                    <input type="file" name="zip_file" class="btn btn-default" title="<?=$this->lang->line('browse_for_zip')?>"/>
                    </div>
                    <button name="member_button" id="member_button" class="btn btn-primary" type="submit"><?=$this->lang->line('upload_theme')?></button>
                </div>
            </div>
        </div>
    </form>
</div>
<hr />
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">
            <ul class="nav nav-tabs capitalize">
                <li class="active"><a href="#themes" data-toggle="tab"><?=$this->lang->line('manage_themes')?></a></li>
                <li><a href="#logo" data-toggle="tab"><?=$this->lang->line('upload_logo')?></a></li>
                <li><a href="#dash" data-toggle="tab"><?=$this->lang->line('dashboard_layout')?></a></li>
            </ul>
            <div class="tab-content">
                <div id="themes" class="tab-pane fade in active">
                	<div class="gallery-wrap">
					<?php foreach ($themes as $v): ?>
                    <?php if ($v['file_name'] == $default_theme): ?>
                    <div class="col-lg-3 col-md-4">
                        <div class="thumbnail">
                            <?php if (!empty($v['preview_image'])): ?>
                            <?php if (file_exists($base_physical_path .'/themes/main/' . $v['file_name'] . '/' . $v['preview_image'])): ?>
                            <a class="zooming" href="<?=base_url('js') . 'themes/main/' . $v['file_name'] . '/' . $v['preview_image'];?>">
                            <img src="<?=base_url('js') . 'themes/main/' . $v['file_name'] . '/' . $v['preview_image'];?>" alt="preview" class="img-responsive mfp-fade"/>
                            </a>
                            <?php else: ?>
                            <img src="<?=base_url('js')?>images/misc/no-photo.jpg" alt="preview" class="img-responsive"/>
                            <?php endif; ?>
                            <?php endif; ?>
                            <div class="caption">
                                 <h4><?=$v['name']?></h4>
                                 <p><small class="text-muted"><?=$v['description']?></small></p>
                                 <p class="text-center">
                                 <button class="btn btn-sm btn-success block-phone"><i class="fa fa-check"></i></button>
                                 <a href="<?=admin_url()?>themes/download_theme/<?=$v['file_name']?>" class="btn btn-primary btn-sm block-phone"><i class="fa fa-download"></i> <?=$this->lang->line('download_theme')?></a>
                                </p>	
                            </div>    
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    <?php foreach ($themes as $v): ?>
                    <?php if ($v['file_name'] != $default_theme): ?>
                    <div class="col-lg-3 col-md-4">
                        <div class="thumbnail">
                            <?php if (!empty($v['preview_image'])): ?>
                            <?php if (file_exists($base_physical_path .'/themes/main/' . $v['file_name'] . '/' . $v['preview_image'])): ?>
                            <a class="zooming" href="<?=base_url('js') . 'themes/main/' . $v['file_name'] . '/' . $v['preview_image'];?>">
                            <img src="<?=base_url('js') . 'themes/main/' . $v['file_name'] . '/' . $v['preview_image'];?>" alt="preview" class="img-responsive mfp-fade"/>
                            </a>
                            <?php else: ?>
                            <img src="<?=base_url('js')?>images/misc/no-photo.jpg" alt="preview" class="img-responsive"/>
                            <?php endif; ?>
                            <?php endif; ?>
                            <div class="caption">
                                 <h4><?=$v['name']?></h4>
                                 <p><small class="text-muted"><?=$v['description']?></small></p>
                                 <p class="text-center">
                                 <a href="<?=admin_url()?>themes/set_default/<?=$v['file_name']?>" class="btn btn-warning btn-sm block-phone tip" data-toggle="tooltip" title="<?=$this->lang->line('set_as_default')?>"><i class="fa fa-refresh"></i></a>
                                <a href="<?=admin_url()?>themes/download_theme/<?=$v['file_name']?>" class="btn btn-primary btn-sm block-phone"><i class="fa fa-download"></i> <?=$this->lang->line('download_theme')?></a>
                            </p>	
                            </div>    
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                </div>
                <div id="logo" class="tab-pane fade in">
                	<hr />
                	<form action="<?=admin_url()?>programs/upload_logo" method="post"  class="form-horizontal" enctype="multipart/form-data" role="form">
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=$this->lang->line('current_logo_file')?></label>
                        <div class="col-lg-5">
                            <input name="program_logo" id="program_logo" type="text"  value="<?=$this->validation->program_logo?>" class="form-control" />
                            <?php if ($this->validation->program_logo): ?> 
                            <?php if (file_exists(PUBPATH . '/images/programs/' . $this->validation->program_logo)): ?>
                            <hr />
                            <p><img src="<?=base_url('js')?>images/programs/<?=$this->validation->program_logo?>" /></p>
                            <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=$this->lang->line('upload_logo')?></label>
                        <div class="col-lg-5">
                            <input type="file" name="userfile" class="btn btn-default" title="<?=$this->lang->line('select_logo_file')?>"/>
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <div class="col-md-offset-3 col-sm-9">
                            <button class="btn btn-success block-phone" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button>
                            <?php if ($this->validation->program_logo): ?>   
                            <a href="<?=admin_url()?>programs/delete_logo/<?=$this->validation->program_logo?>" class="btn btn-danger block-phone"><i class="fa fa-trash-i"></i> <?=$this->lang->line('delete')?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <input type="hidden" name="program_id" value="<?=$this->validation->id?>" />
                	</form>
                </div>
                <div id="dash" class="tab-pane fade in">
                	<form action="<?=admin_url()?>layout/update_layout" method="post"  class="form-horizontal" role="form">	 
                    <hr />
                     <div class="form-group">
                        <label class="col-lg-3 control-label"><?=$this->lang->line('members_dashboard_layout')?></label>
                        <div class="col-lg-5">
                        <?php
                        $options = array('tpl_members_dashboard' => $this->lang->line('single_column_right'), 
                        'tpl_members_dashboard2' => $this->lang->line('single_column_left'),  'tpl_members_dashboard3' => $this->lang->line('no_dashboard_columns'));
                        echo form_dropdown('layout_theme_members_default_dashboard_template', $options, $layout_theme_members_default_dashboard_template, 'id="dash" class="form-control"');
                        ?>   
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <div class="col-lg-5 col-lg-offset-3">
                        <img id="dash1" src="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/img/dash1.jpg" <?php if ($layout_theme_members_default_dashboard_template != 'tpl_members_dashboard1'):?> style="display:none;" <?php endif; ?> class="img-thumbnail img-responsive" />  
                        <img id="dash2" src="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/img/dash2.jpg" <?php if ($layout_theme_members_default_dashboard_template != 'tpl_members_dashboard2'):?> style="display:none;" <?php endif; ?> class="img-thumbnail img-responsive" />    
                        <img id="dash3" src="<?=base_url('js')?>themes/admin/<?=$sts_admin_layout_theme?>/img/dash3.jpg" <?php if ($layout_theme_members_default_dashboard_template != 'tpl_members_dashboard3'):?> style="display:none;" <?php endif; ?> class="img-thumbnail img-responsive" />   
                        </div>
                    </div>
                    <hr />
                	<div class="col-md-5 col-md-offset-3">
                    	<button class="btn btn-success btn-lg" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
	</div>
</div>
<script>
$(document).ready(function() {		
	$("#toggle").click(function() {
	$("#add_block").toggle(400);
	});
}); 

$("select#dash").change(function(){
	$( "select#dash option:selected").each(function(){
		if($(this).attr("value")=="tpl_members_dashboard"){
			$("#dash1").show();
			$("#dash2").hide();
			$("#dash3").hide();
		}
		else if($(this).attr("value")=="tpl_members_dashboard2"){
			$("#dash1").hide();
			$("#dash2").show();
			$("#dash3").hide();
		}
		else if($(this).attr("value")=="tpl_members_dashboard3"){
			$("#dash1").hide();
			$("#dash2").hide();
			$("#dash3").show();
		}
	});
}).change();
</script>
<?php endif; ?>