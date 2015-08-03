<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="col-lg-12">
	<?php if (empty($tool_rows)): ?>
    <div class="alert alert-warning text-capitalize animated shake">
    	<h3><i class="fa fa-exclamation-triangle"></i> <?=$this->lang->line('no_tools_found')?></h3>
    	<p><a href="javascript:history.go(-1)" class="btn btn-warning"><i class="fa fa-chevron-left"></i> <?=$this->lang->line('go_back')?></a></p>
    </div>
	<?php else: ?>
	<div class="panel panel-default animated fadeInDown">
        <div class="panel-heading text-capitalize">
        	<div class="additional-btn">
           		<div class="btn-group">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <?=$this->lang->line('select_marketing_tools')?> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                   		<?php foreach ($tools as $v): ?>
                        <li><a href="<?=site_url('members')?>/marketing/tools/0/0/0/module_id/<?=$v['module_id']?>/program_id/<?=$program_id?>"><?=$this->lang->line($v['module_file_name'])?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
			<h4><?=$page_title?></h4>
        </div>
        <div class="panel-body">
        	<div class="row text-capitalize">
            	<div class="col-md-1 hidden-xs hidden-sm text-center"><h4><?=$this->lang->line('pid')?></h4></div>
                <div class="col-md-6"><h4><?=$this->lang->line('tool_name')?></h4></div>
                <div class="col-md-1 hidden-xs hidden-sm text-center"><h4><?=$this->lang->line('clicks')?></h4></div>
                <div class="col-md-2 hidden-xs hidden-sm text-center"><h4><?=$this->lang->line('commissions')?></h4></div>
                <div class="col-md-1 hidden-xs hidden-sm text-center"><h4><?=$this->lang->line('sales')?></h4></div>
                <div class="col-md-1"></div>
            </div>
            <hr />
        	<?php foreach ($tool_rows as $v): ?>
            <div class="row">
                <div class="col-md-1 hidden-xs hidden-sm text-center">
                    <?=$v['program_id']?>
                </div>
                <div class="col-md-6">
                    <h5><a class="collapsed" data-toggle="collapse" data-target="#<?=$v['id']?>"><?=$v['name']?></a></h5>
                </div>
                <div class="col-md-1 hidden-xs hidden-sm text-center">
                    <?=$v['clicks']?>
                </div>
                <div class="col-md-2 hidden-xs hidden-sm text-center">
                    <?=$v['s_commissions']?>
                </div>
                <div class="col-md-1 hidden-xs hidden-sm text-center">
                    <?=$v['s_sales']?>
                </div>
                <div class="col-md-1 text-right">
                    <a href="<?=site_url('members')?>/marketing/download/<?=$module_file_name?>/<?=$v['id']?>" id="show-<?=$v['id']?>" class="btn btn-default"><i class="fa fa-download"></i></a>
                </div>
            </div>
            <div id="<?=$v['id']?>" class="row collapse fade">
            	<div class="col-lg-12">
                	<hr />
                	<div class="well">
                        <p class="text-capitalize"><small><?=$this->lang->line('note_code')?></small></p>
                        <textarea class="form-control" rows="3" onclick="this.select()"><?=$v['tool_code']?></textarea>
                        <hr />
                        <div class="text-center"><?=$v['p_tool_code']?></div>
                    </div>
                </div>
            </div>
            <hr />
            <?php endforeach; ?>
			<div class="text-center"><?=$pagination_rows?></div>
    	</div>                
    </div>                
	<?php endif; ?>              
</div>