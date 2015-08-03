<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php if ($sts_affiliate_allow_upload_photos == 1): ?>
<div class="col-md-3">
	<div class="text-center animated fadeInDown">
        <div class="thumbnail">
			<?php if (!empty($member_photo)): ?>
            <img src="<?=$member_photo?>" class="img-thumbnail"/>
            <?php else: ?>
            <img src="<?=base_url('js')?>images/misc/default.png" />
            <?php endif; ?>  
            <div class="caption text-capitalize">
                <hr />
                <h5><?=$this->lang->line('update_photo')?></h5>
                <form action="<?=site_url('members')?>/account/update_photo" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="file" name="userfile" class="file-input-wrapper btn btn-default btn-sm btn-block" title="<?=$this->lang->line('select_photo')?>">
                        <button type="submit" class="btn btn-default btn-sm btn-block"><i class="fa fa-upload"></i> <?=$this->lang->line('upload')?></button>
						<?php if (!empty($member_photo)): ?>
                        <a href="<?=site_url('members')?>/account/delete_photo/<?=$member_photo_raw_name?>/<?=$this->session->userdata('userid')?>" class="btn btn-danger btn-sm btn-block"><i class="fa fa-trash-o"></i> <?=$this->lang->line('delete_photo')?></a>
						<?php endif; ?>
                    </div>                    
                </form>
                <hr />
                <?php if ($this->session->userdata('m_ll_ip') != $this->lang->line('none')): ?>
                 
				<p>
					<a class="btn btn-default btn-sm btn-block" href="http://whatismyipaddress.com/<?=$this->validation->last_login_ip?>" target="_blank">
						<?=$this->lang->line('last_login_ip')?>: 
						<?=$this->session->userdata('m_ll_ip')?>
                	</a>
                </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="col-md-9">
<?php else: ?>
<div class="col-md-12">
<?php endif; ?>
	<form id="form" class="form-horizontal" method="post" role="form">
	<div class="panel panel-default animated fadeInLeft">
    	<div class="panel-heading">
        	<h5 class="text-capitalize"><small class="pull-right">* <?=$this->lang->line('required_fields')?></small> <?=$this->lang->line('account_profile')?></h5>
        </div>
    	<div class="panel-body">
            <?php foreach ($form_fields as $k => $v): ?>
            <?php if ($v['form_name'] != 'username'): ?>            
        	<div class="form-group">
                <label class="col-lg-4 control-label text-capitalize"><?=$v['form_description']?> <?=$v['required']?></label>
                <div class="col-lg-5">
                    <?=$v['form_field']?>
                </div>
            </div>
            <hr />
            <?php endif; ?>
            <?php endforeach; ?>
            <div class="col-md-5 col-md-offset-4"><button class="btn btn-success btn-lg btn-block" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button></div>
    	</div>        
    </div>        
	</form>
</div>