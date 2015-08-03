<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="col-lg-12">
	<?php if (empty($rows)): ?>
    <div class="alert alert-warning text-capitalize animated shake">
    	<h3><i class="fa fa-exclamation-triangle"></i> <?=$this->lang->line('no_programs_found')?></h3>
    	<p><a href="javascript:history.go(-1)" class="btn btn-warning"><i class="fa fa-chevron-left"></i> <?=$this->lang->line('go_back')?></a></p>
    </div>
	<?php else: ?>
    <?php foreach ($rows as $v): ?>
    <div class="panel panel-default animated fadeInUp">
    	<div class="panel-body program-details">
        	<h3><?=$v['program_name']?> </h3> 
            <hr />
        	<div class="row">
                <div class="col-md-3">
                    <img src="<?=base_url('js') . $v['program_photo']?>" class="img-thumbnail img-responsive"/>
                </div>
                <div class="col-md-6">
                    <p><strong><?=$this->lang->line('program_link')?>:</strong> <a href="<?=_get_aff_program_link($v, $this->session->userdata('m_username'))?>" target="_blank"><?=_get_aff_program_link($v, $this->session->userdata('m_username'))?></a>  																					
                    </p>
                    <p>
                    <?php if ($v['commission_levels'] != 1): ?>
                    <strong><?=$this->lang->line('commission_levels')?>:</strong> <?=$v['commission_levels']?>
                    <?php else: ?>
                    <strong><?=$this->lang->line('payout')?>:</strong> 
					<?php if ($v['commission_type'] == 'percent'):?>
                   	<?=sprintf("%.2f%%",  $v['commission_level_1'] * 100)?>
                    <?php else: ?>
					<?=format_amounts($v['commission_level_1'], $num_options)?>
                    <?php endif; ?>
					<?php endif; ?>
                    <?php if ($v['enable_pay_per_action'] == 1):?>
                    <strong><?=$this->lang->line('per_action')?>:</strong> <?=$v['commission_type']?>
                    
                    <?php if ($v['enable_pay_per_click'] == 1):?>
                    <strong><?=$this->lang->line('per_click')?>:</strong> <?=$v['ppc_amount']?>
                    <?php endif; ?>
                    
                    <?php if ($v['enable_cpm'] == 1):?>
                    <strong><?=$this->lang->line('cpm')?>:</strong> <?=$v['cpm_amount']?>
                    <?php endif; ?>
                    
                    </p>
                    <?php if ($v['commission_levels'] != 1): ?>
                    <p>
                    <strong><?=$this->lang->line('payout')?>:</strong>
                    <br />
                    <?=$v['payout']?>
                    <?php endif; ?>
                    <?php endif; ?>
                	</p>
                </div>
                <div class="col-md-3 text-right">
                	<a class="btn btn-info bnt-sm" data-toggle="collapse" data-target="#<?=$v['program_id']?>"><i class="fa fa-search"></i> <?=$this->lang->line('view_details')?></a>
                    <a href="<?=site_url('members')?>/marketing/view/<?=$v['program_id']?>" class="btn btn-default bnt-sm"><i class="fa fa-wrench"></i> <?=$this->lang->line('get_tools')?></a>
                </div>
            </div>
            <div id="<?=$v['program_id']?>" class="row collapse fade">
            	<div class="col-md-12">
                	<hr />
                    <ul class="nav nav-tabs capitalize">
                        <li class="active"><a href="#main<?=$v['program_id']?>" data-toggle="tab"><?=$this->lang->line('program_description')?></a></li>
                        <?php if (!empty($v['terms_of_service'])): ?>
                        <li><a href="#terms<?=$v['program_id']?>" data-toggle="tab"><?=$this->lang->line('terms_of_service')?></a></li>
                        <?php endif; ?>
                        <?php if (!empty($v['privacy_policy'])): ?>
                        <li><a href="#privacy<?=$v['program_id']?>" data-toggle="tab"><?=$this->lang->line('privacy_policy')?></a></li>
                        <?php endif; ?>
                    </ul>
                    <div class="tab-content">
                        <div id="main<?=$v['program_id']?>" class="tab-pane fade in active">
                        <?=$v['program_description']?>
                        </div>
                        <?php if (!empty($v['terms_of_service'])): ?>
                        <div id="terms<?=$v['program_id']?>" class="tab-pane fade in">
                        <?=$v['terms_of_service']?>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($v['privacy_policy'])): ?>
                        <div id="privacy<?=$v['program_id']?>" class="tab-pane fade in">
                        <?=$v['privacy_policy']?>
                        </div>
                        <?php endif; ?>
                    </div>
            	</div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
    <div class="text-center"><?=$pagination_rows?></div>
</div>