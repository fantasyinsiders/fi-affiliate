<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form" method="post" action="<?=admin_url()?>programs/update_fields/1" class="form-horizontal" role="form" >
<div class="row">
    <div class="col-md-4">
    	<?=_generate_sub_headline('enable_disable_form_fields')?>
    </div>
    <div class="col-md-8 text-right">
    </div>
</div>
<hr />
<div class="row data-row">    
   	<div class="col-md-12">
    	<div class="box-info">
            <div class="alert alert-warning"><small><i class="fa fa-exclamation-triangle"></i> <?=$this->lang->line('fname_email_required')?></small></div>
            <div class="row">
                <?php foreach ($form_fields as $k => $v): ?>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-sm-6 control-label"><?=_check_form_fields($k, $language_fields)?></label>
                        <div class="col-sm-6">
                            <?php
                            $options = array('0' => $this->lang->line('hidden'), '1' => $this->lang->line('required'), '2' => $this->lang->line('optional'));
                            if ($k == 'enable_fname' || $k == 'enable_primary_email')
                            {
                                $options = array('1' => $this->lang->line('required'));
                            }
                            elseif ($k == 'show_tos')
                            {
                                 $options = array('0' => $this->lang->line('hidden'), '1' => $this->lang->line('required'));
                            }                       
                            echo form_dropdown($k, $options, $this->validation->$k, 'class="form-control"');
                            ?>
                        </div>
                    </div>
                    <hr />
                </div>
                <?php endforeach; ?>
            </div>
            <div class="row">
                <div class="col-md-12 text-right">
                    <button class="btn btn-success btn-lg" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>