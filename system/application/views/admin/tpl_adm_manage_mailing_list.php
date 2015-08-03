<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form"  method="post" class="form-horizontal"  role="form">
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline($module)?>
    </div>
    <div class="col-md-8 text-right">        
        <a href="<?=admin_url()?>mailing_lists/view_mailing_lists" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_mailing_lists')?></span></a>
    </div>
</div>
<hr />
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">
        <h4 class="header"><i class="fa fa-cog"></i> <?=$this->lang->line($function)?></h4>
        	<div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('mailing_list_name')?></label>
        		<div class="col-lg-5">
        			<input name="mailing_list_name" id="mailing_list_name" type="text"  value="<?=$this->validation->mailing_list_name?>" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
                <div class="col-md-offset-3 col-sm-9">
                    <button class="btn btn-success block-phone" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button>
                    
                </div>
            </div>
		</div>
    </div>      
</div>           
</form>