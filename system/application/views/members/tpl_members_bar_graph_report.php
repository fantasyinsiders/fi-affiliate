<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<?php if (empty($stats)): ?>
<div class="alert alert-warning text-capitalize animated shake">
	<h2><?=$this->lang->line('no_data_found')?></h2>
    <p><a href="javascript:history.go(-1)" class="btn btn-warning btn-lg"><?=$this->lang->line('go_back')?></a></p>
</div>
<?php else: ?>
<?php if (!empty($show_graph)): ?>
<script language="JavaScript" type="text/javascript" src="<?=base_url('js');?>js/highcharts/highcharts.js"></script>
<script type="text/javascript">
<?=$show_graph?>;
</script>
<?php endif; ?>
	<div class="col-lg-12">
    	<div class="panel panel-default animated fadeInDown">
            <div class="panel-heading text-capitalize">
                <div class="additional-btn">
                <?=$select_months?>
                </div>
                <h4><?=$page_title?></h4>
            </div>
            <div class="panel-body">
        		<div id="stats_graph"></div>
			</div>
        </div>
    </div>
</div>
<div class="row text-capitalize">
	<div class="col-lg-12">
    	<div class="panel panel-default">
        	<div class="panel-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 80%"><?=$this->lang->line('day_of_month')?></th>
                        <th style="width: 20%" class="text-center"><?=$this->lang->line('amount')?></th>
                    </tr>
                </thead>
                <tbody>       
                    <?php foreach($stats as $v): ?>
                    <tr>
                        <td><?=$v['s_date']?></td>
                        <td class="text-center"><?=$v['s_amount']?></td>
                    </tr>
                    <?php endforeach;  ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td><strong><?=$this->lang->line('totals')?></strong></td>
                        <td class="text-center">
                            <strong><?=$total_amount?></strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
            </div>
    	</div>
	</div>
</div>
<?php endif; ?>