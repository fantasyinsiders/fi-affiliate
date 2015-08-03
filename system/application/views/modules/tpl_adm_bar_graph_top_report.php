<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<script language="JavaScript" type="text/javascript" src="<?=base_url('js');?>js/highcharts/highcharts.js"></script>
<script type="text/javascript">
<?=$show_graph?>;
</script>
<div class="row">
	<?php if (!empty($error)): ?>
    <?=_show_msg('error', $error)?>
    <?php elseif ($this->session->flashdata('success')): ?>
    <?=_show_msg('success', $this->session->flashdata('success'))?>
    <?php endif; ?>
    <div class="col-lg-4">
    	<?=_generate_sub_headline($page_title)?>
    </div>
    <div class="col-lg-8 text-right">
    	<a href="<?=modules_url();?><?=$module_name?>/generate/<?=$cmonth?>/<?=$cyear?>/archive" class="btn btn-primary"><i class="fa fa-save"></i> <span class="hidden-xs"><?=$this->lang->line('archive_report')?></span></a>
    	<a href="<?=admin_url()?>reports/view_reports" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_reports')?></span></a>
   </div>
</div>
<hr />
<div class="row">
	<div class="col-lg-2 col-lg-offset-10 text-right">
		<?=$select_months?>
	</div>
</div>
<br />
<div class="row">
	<div class="col-lg-12">
    	<div class="box-info">
        <div id="stats_graph"></div>
		</div>
    </div>
</div>
<div class="row capitalize">
	<div class="col-lg-12">
    	<div class="box-info">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 80%"><?=$this->lang->line($row_name)?></th>
                        <th style="width: 20%" class="text-center"><?=$this->lang->line($row_amount)?></th>
                    </tr>
                </thead>
                <tbody>       
                    <?php foreach($stats as $v): ?>
                    <tr>
                        <td><?=$v['name']?></td>
                        <td class="text-center">
                            <?php if (!empty($set_integer)): ?>
                            <?=$v['amount']?>
                            <?php else: ?>
                            <?=format_amounts($v['amount'], $num_options)?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php $total_amount = $total_amount + $v['amount']; ?>
                    <?php endforeach;  ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td><strong><?=$this->lang->line('totals')?></strong></td>
                        <td class="text-center">
                            <strong>
                            <?php if (!empty($set_integer)): ?>
                            <?=$total_amount?>
                            <?php else: ?>
                            <?=format_amounts($total_amount, $num_options)?>
                            <?php endif; ?>
                            </strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
            </div>
	</div>
</div>
<script>

$('#stats_graph').resize();
function ChangeReportStatus(select) {
  var index;

  for(index=0; index<select.options.length; index++)
	if(select.options[index].selected)
	  {
		if(select.options[index].value!="")
		  window.location.href="<?=modules_url();?><?=$module_name?>/generate/" + select.options[index].value;
		break;
	  }
}
</script>