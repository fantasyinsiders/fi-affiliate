<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form"  action="<?=modules_url()?>module_data_import_jam/do_import/17"method="post" class="form-horizontal"  role="form">
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline('data_import')?>
    </div>
    <div class="col-md-8 text-right">        
        <a href="<?=admin_url()?>import/view_import_modules" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('data_import_modules')?></span></a>
    </div>
</div>
<hr />
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">
        <h4 class="header"><i class="fa fa-cog"></i> <?=$this->lang->line($function)?></h4>
			<div class="alert alert-danger text-danger">
            <p>This module will import your JAM version 1 database into this version of JAM.   If you have a large JAM database, you will have to edit the memory limit in your JAM config.php file.</p>
            <p><strong>Your JAM database must be a new installation only or current data will be overwritten. If you have a huge database, you will have to use segmented migration.  Please check the docs for more details on it.</strong></p>
            </div>
        	<div class="form-group"> 
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('module_data_import_jam_server')?></label>
        		<div class="col-lg-5">
        			<input name="module_data_import_jam_server" id="module_data_import_jam_server" type="text"  value="<?=$this->validation->module_data_import_jam_server?>" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group"> 
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('module_data_import_jam_database')?></label>
        		<div class="col-lg-5">
        			<input name="module_data_import_jam_database" id="module_data_import_jam_database" type="text"  value="<?=$this->validation->module_data_import_jam_database?>" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group"> 
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('module_data_import_jam_username')?></label>
        		<div class="col-lg-5">
        			<input name="module_data_import_jam_username" id="module_data_import_jam_username" type="text"  value="<?=$this->validation->module_data_import_jam_username?>" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group"> 
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('module_data_import_jam_password')?></label>
        		<div class="col-lg-5">
        			<input name="module_data_import_jam_password" id="module_data_import_jam_password" type="text"  value="<?=$this->validation->module_data_import_jam_password?>" class="form-control" />
        		</div>
        	</div>
            <hr />
            <div class="form-group"> 
    		    <label class="col-lg-3 control-label">Import Commissions</label>
        		<div class="col-lg-5">
        			<input type="checkbox" name="import_commissions" id="import_commissions" checked="checked" value="1"/>
        		</div>
        	</div>
            <hr />
            <div class="form-group"> 
    		    <label class="col-lg-3 control-label">Import Marketing Tools<</label>
        		<div class="col-lg-5">
        			<input type="checkbox" name="import_tools" id="import_tools" checked="checked" value="1"/>
        		</div>
        	</div>
            <hr />
            <div class="form-group"> 
    		    <label class="col-lg-3 control-label">Import Traffic</label>
        		<div class="col-lg-5">
        			<input type="checkbox" name="import_traffic" id="import_traffic" checked="checked" value="1"/>
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