<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($action_commissions)): ?>
<div class="alert alert-warning">
	<h3 class="text-warning"><?=$this->lang->line('no_logs_found')?></h3>
    <p>
    	<a href="javascript:history.go(-1)" class="btn btn-warning"><?=$this->lang->line('go_back')?></a>
    </p>
</div>
<?php else: ?>
<form id="form" name="prod_form" method="post">
<div class="row">
    <div class="col-md-4">
    	<?=_generate_sub_headline('log_viewer')?>
    </div>
    <div class="col-md-8 text-right">
      
    </div>
</div>
<hr />
<div class="row data-row">    
   	<div class="col-md-12">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width:10%" class="hidden-xs text-center"><?=$this->lang->line('id')?></th>
                    <th style="width:20%" class="text-center"><?=$this->lang->line('date')?></th>
                    <th style="width:70%"><?=$this->lang->line('notes')?></th>
                </tr>    
        	</thead>
            <tbody>
				<?php foreach($logs as $v):?>
				<?php if (trim($v['0']) == 'ERROR'): ?>
                <tr class="danger">
				<?php elseif (trim($v['0']) == 'DEBUG'): ?>
                <tr class="info">
                <?php else: ?>
                <tr>
                <?php endif; ?>
			    	<td  class="text-center">
                    <?=$v['0']?>
                    </td>
                    <td class="text-center"><?=$v['1']?></td>
                    <td>
                    	<div style="overflow:auto; height: 40px;"><?=$v['2']?></div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>  
            <tfoot>
                <tr>
                    <td><?=form_dropdown('logs', $log_files, '', 'class="form-control"')?></td>

                    <td colspan="2" class="text-right">
						<button type="submit" class="btn btn-success"><i class="fa fa-refresh"></i> <?=$this->lang->line('go')?></button>
                    </td>
                </tr>
            </tfoot>  
		</table>
	</div>
</div>
</form>
<?php endif; ?>