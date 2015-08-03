<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="prod_form"  method="post" class="form-horizontal"  role="form">
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline($module)?>
    </div>
    <div class="col-md-8 text-right">  
        <a href="<?=admin_url()?>rewards/view_rewards" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('view_performance_rewards')?></span></a>
    </div>
</div>
<hr />
<div class="row">   
	<div class="col-md-12">
    	<div class="box-info">
            <h4 class="header"><i class="fa fa-cog"></i> <?=$this->lang->line('update_reward')?></h4>
            <div class="form-group">
                <label class="col-lg-1 control-label"><?=$this->lang->line('if')?></label>
                <div class="col-sm-3">
                <?php
                $options = array('total_amount_of_commissions' => $this->lang->line('total_amount_of_commissions'), 
                      'total_amount_of_sales' => $this->lang->line('total_amount_of_sales'),
                      'amount_of_commission' => $this->lang->line('amount_of_commission'),
                      'amount_of_sale' => $this->lang->line('amount_of_sale'),
                      'total_amount_of_referrals' => $this->lang->line('total_amount_of_referrals'),
                      );
                echo form_dropdown('sale_type', $options, $this->validation->sale_type, 'id="sale_type" class="form-control"');
                ?>
                </div>
           		 <?php if ($this->validation->sale_type == 'total_amount_of_commissions' || $this->validation->sale_type == 'total_amount_of_sales'  || $this->validation->sale_type == 'total_amount_of_referrals') { $display = 'style=""'; } else { $display = 'style="display:none"'; } ?>
                <div id="time_limit" <?=$display?>><label class="col-lg-1 control-label"><?=$this->lang->line('for')?></label>
                <div class="col-sm-3">
                	<?php 
					$options = array('all_time' => $this->lang->line('all_time'),
								   'current_month' => $this->lang->line('current_month'), 
									'current_year' => $this->lang->line('current_year'),
									'last_month' => $this->lang->line('last_month'),
									'last_year' => $this->lang->line('last_year'),
									);
					echo form_dropdown('time_limit', $options, $this->validation->time_limit , 'class="form-control"');
					?>
					</div>
                </div>
            </div>
            <hr />
            <div class="form-group">
            	<label class="col-lg-1 control-label"><?=$this->lang->line('is')?></label>
                <div class="col-sm-3">
					<?php 
                    $options = array('greater_than' => $this->lang->line('greater_than'),
                                   'less_than' => $this->lang->line('less_than'), 
                                    'equal_to' => $this->lang->line('equal_to'),
                                    );
                    echo form_dropdown('greater_than', $options, $this->validation->greater_than , 'class="form-control"');
                    ?>
                </div>
                <label class="col-lg-1 control-label"><?=$this->lang->line('amount')?></label>
                <div class="col-sm-3">
				 	<input name="sale_amount" type="text" id="sale_amount" value="<?=$this->validation->sale_amount?>" class="form-control" />
                </div>
            </div>
            <hr />
            <div class="form-group">
            	<label class="col-lg-1 control-label"><?=$this->lang->line('then')?></label>
                <div class="col-sm-3">
					<?php 
                    $options = array('issue_bonus_commission' => $this->lang->line('issue_bonus_commission'),
                                   'assign_affiliate_group' => $this->lang->line('assign_affiliate_group'), 
                                    );
                    echo form_dropdown('action', $options, $this->validation->action, 'id="change-status" class="form-control"');
                    ?>
                </div>
                <label class="col-lg-1 control-label"><?=$this->lang->line('amount')?></label>
                <div class="col-sm-3">
					<input name="bonus_amount" type="text" id="bonus_amount" value="<?=$this->validation->bonus_amount?>" class="form-control" <?php if ($this->validation->action == 'assign_affiliate_group') echo 'style="display: none"' ?> />
            		<?php $display = $this->validation->action == 'issue_bonus_commission' ? 'display: none' : '' ?>
					<?=form_dropdown('group_id', $groups, $this->validation->group_id, 'id="group_id" class="form-control" style="' . $display . '"')?>
                </div>
            </div>
            <hr />
            <div class="form-group">
                <div class="col-md-8 text-right">
                    <button class="btn btn-success block-phone" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('save_changes')?></button>
                </div>
            </div>
        </div>
	</div>        
</div>        
        
    		
</form>
<script type="text/javascript">
$(function() {
	
	var options = {target: '#response'};
	
	$('#prod_form').ajaxForm(options);
	
    $("#change-status").change
    (
      function()
      {
        var selectedValue = $(this).val();

		if(selectedValue == "issue_bonus_commission")
        {
          $("#bonus_amount").show();
		  $("#group_id").hide();
        } 
		if(selectedValue == "assign_affiliate_group")
        {
          $("#group_id").show();
		   $("#bonus_amount").hide();
        } 
      }   
    );
	
	$("#sale_type").change
    (
      function()
      {
        var selectedValue = $(this).val();
	
		if(selectedValue == "total_amount_of_commissions")
        {
          $("#time_limit").show();
        }
		else if(selectedValue == "total_amount_of_sales")
        {
          $("#time_limit").show();
        } 
		else if(selectedValue == "total_amount_of_referrals")
        {
          $("#time_limit").show();
        } 
		else
        {
		   $("#time_limit").hide();
        } 
      }   
    );

});
</script>