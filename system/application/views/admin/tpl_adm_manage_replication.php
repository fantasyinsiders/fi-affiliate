<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form" name="form" method="post" action="<?=admin_url()?>replication/upload_file" enctype="multipart/form-data" role="form">
<div class="row">
    <div class="col-md-4">
    	<?=_generate_sub_headline('webpage_replication')?>
    </div>
    <div class="col-md-8 text-right">
    </div>
</div>
<hr />
<div class="row data-row">    
   	<div class="col-md-12">
    	<div class="box-info">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width:75%"><?=$this->lang->line('uploaded_replication_files')?></th>
                        <th style="width:25%"></th>
                    </tr>    
                </thead>
                <tbody>
                    <?php foreach ($files as $v): ?>
                    <tr>
                        <td><?=$v?></td>
                        <td class="text-right">
                            <a data-href="<?=admin_url()?>replication/delete_file/<?=$v?>" data-toggle="modal" data-target="#confirm-delete" href="#" class="md-trigger btn btn-danger"><i class="fa fa-trash-o"></i></a>
                            <a href="<?=admin_url()?>replication/download_file/<?=$v?>" class="btn btn-default" title="<?=$this->lang->line('download')?>"><i class="fa fa-download"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>  
                <tfoot>
                    <tr>
                        <td colspan="2">
                            <input type="file" name="userfile" class="btn btn-default" title="<?=$this->lang->line('select_file_to_upload')?>"/> 
                            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> <?=$this->lang->line('upload_file')?></button>
                        </td>
                    </tr>
                </tfoot>  
            </table>
        </div>
	</div>
</div>
</form>
<div class="alert alert-warning" style="overflow: auto;">
	<p><?=$this->lang->line('desc_replication_1')?></p>
	<p><?=$this->lang->line('desc_replication_2')?></p>
	<h4 class="text-center" style="padding: 1em 0; text-transform: none"><?=PUBPATH?>/system/application/views/main/replication/</h4>
	<p><?=$this->lang->line('desc_replication_3')?></p>
</div>