<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="prod_form" name="prod_form" method="post" role="form" class="form-horizontal">
<div class="row">
    <div class="col-md-4">
    	<?=_generate_sub_headline('manage_language', $name)?>
    </div>
    <div class="col-md-8 text-right">
        <a href="<?=admin_url()?>languages/view_languages" class="btn btn-primary"><i class="fa fa-search"></i> <?=$this->lang->line('view_languages')?></a>
    </div>
</div>
<hr />
<div class="row data-row">  
	<div class="col-lg-12">  
		<?php if ($writeable == false): ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> <?=$type?> <?=$this->lang->line('language_file_not_writeable')?></div>
        <?php else: ?>
        <div class="alert alert-success"><?=$this->lang->line('language_file_writeable')?></div>
        <?php endif; ?>
        <?php foreach ($lang_file as $k => $v): ?>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?=str_replace('_', ' ', $k)?></label>
            <div class="col-lg-5">
                <input name="<?=$k?>" type="text" id="<?=$k?>" value="<?=stripslashes(htmlentities($v))?>" class="form-control" />
            </div>
        </div>
        <hr />
        <?php endforeach; ?>
        <div class="form-group">
            <div class="col-md-offset-3 col-sm-9">
                <button class="btn btn-success block-phone" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button>        
            </div>
        </div>
    </div>
</div>
</form>