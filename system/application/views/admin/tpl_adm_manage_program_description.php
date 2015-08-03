<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<div id="fragment-3">
    <fieldset><legend><?=$this->lang->line('program_description')?></legend>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" id="program-table" class="form">
          <tr>
            <td align="center"><?=$this->validation->program_description?>
            
            <div style="margin:1em 0 0 0; float: right">
           <a class="button negative" href="javascript:ConfirmDelete('<?=$this->validation->program_id;?>')">
                  <img src="<?=base_url();?>themes/admin/<?=$sts_admin_layout_theme?>/images/cross.png" alt=""/><?=$this->lang->line('delete')?>
            </a>
         <button name="program_button" id="program_button" type="submit" class="button positive">
              <img src="<?=base_url();?>themes/admin/<?=$sts_admin_layout_theme?>/images/add1.png" alt=""/>
              <span><?=$this->lang->line('submit')?></span>
         </button>  
        </div>
            </td>
          </tr>
        </table>

        </fieldset>
    
   <fieldset><legend><?=$this->lang->line('privacy_policy')?></legend>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" id="program-table" class="form">
          <tr>
            <td align="center"><?=$this->validation->privacy_policy?>
            
            <div style="margin:1em 0 0 0; float: right">
           <a class="button negative" href="javascript:ConfirmDelete('<?=$this->validation->program_id;?>')">
                  <img src="<?=base_url();?>themes/admin/<?=$sts_admin_layout_theme?>/images/cross.png" alt=""/><?=$this->lang->line('delete')?>
            </a>
         <button name="program_button" id="program_button" type="submit" class="button positive">
              <img src="<?=base_url();?>themes/admin/<?=$sts_admin_layout_theme?>/images/add1.png" alt=""/>
              <span><?=$this->lang->line('submit')?></span>
         </button>  
        </div>
            </td>
          </tr>
        </table>

        </fieldset>
  
   <fieldset><legend><?=$this->lang->line('terms_of_service')?></legend>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" id="program-table" class="form">
          <tr>
            <td align="center"><?=$this->validation->terms_of_service?>
            <div style="margin:1em 0 0 0; float: right">
           <a class="button negative" href="javascript:ConfirmDelete('<?=$this->validation->program_id;?>')">
                  <img src="<?=base_url();?>themes/admin/<?=$sts_admin_layout_theme?>/images/cross.png" alt=""/><?=$this->lang->line('delete')?>
            </a>
         <button name="program_button" id="program_button" type="submit" class="button positive">
              <img src="<?=base_url();?>themes/admin/<?=$sts_admin_layout_theme?>/images/add1.png" alt=""/>
              <span><?=$this->lang->line('submit')?></span>
         </button>  
        </div>
            </td>
          </tr>
        </table>

        </fieldset> 
         <div class="clear"></div> 
    </div>