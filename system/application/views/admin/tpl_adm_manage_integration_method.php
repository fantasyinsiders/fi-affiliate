<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form"  method="post" class="form-horizontal"  role="form">
<div class="row">
   	<div class="col-md-4">
    <h4 class="header"><i class="fa fa-cog"></i> <?=$name?></h4>
    </div>
    <div class="col-md-8 text-right">
        <?=_previous_next('previous', 'program_integration', $id);?>
        <a href="<?=admin_url()?>integration/options" class="btn btn-primary"><i class="fa fa-search"></i> <span class="hidden-xs"><?=$this->lang->line('website_integration')?></span></a>
        <?=_previous_next('next', 'program_integration', $id);?>
    </div>
</div>
<br />
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">
            <ul class="nav nav-tabs capitalize">
            	<li class="active"><a href="#main" data-toggle="tab"><?=$this->lang->line('commission_integration')?></a></li>
            	<?php if (!empty($api_description)): ?>
                <li><a href="#api" data-toggle="tab"><?=$this->lang->line('api_integration')?></a></li>
	    		<?php endif; ?>
            </ul>
        	<div class="tab-content">
        		<div id="main" class="tab-pane fade in active"> 
                	<?php if (!empty($img)): ?>
                	<?php if (file_exists($base_physical_path . '/images/integration/' . $img)): ?>
		    		<img src="<?=base_url('js') . 'images/integration/' . $img?>"  class="pull-right"/>
    				<?php endif; ?>
                    <?php endif; ?>
                	<?=$description?>   
                    <hr />
                    <div class="alert alert-warning">
                    <h5>SSL Pages Require SSL affiliate integration URLs</h5>
                    * If you are using SSL on the pages where the integration code is being added, make sure to change the affiliate URL on the integration code to use SSL (https://) as well.
                    </div>
                    <div class="panel-group" id="accordion">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5 class="panel-title capitalize">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                       <i class="fa fa-chevron-down"></i> <?=$this->lang->line('required_values_for_tracking')?>
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <ul class="list-group">
                                        <li class="list-group-item">amount - <?=$this->lang->line('integration_amount')?></li>
                                        <li class="list-group-item">trans_id - <?=$this->lang->line('integration_trans_id')?></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5 class="panel-title capitalize">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                        <i class="fa fa-chevron-down"></i> <?=$this->lang->line('option_values_for_tracking')?>
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse">
                                <div class="panel-body">
                               	  <div class="alert alert-danger">
                                	<h5><i class="fa fa-exclamation-triangle"></i> <?=$this->lang->line('advanced_configuration')?></h5>
                                    <p><?=$this->lang->line('desc_advanced_configuration')?></p>
                                	</div>
                                
                                    <ul class="list-group">
                                        <li class="list-group-item">invoice_id - <?=$this->lang->line('integration_invoice_id')?></li>
                                        <li class="list-group-item">program_id - <?=$this->lang->line('integration_program_id')?></li>
                                        <li class="list-group-item">order_id - <?=$this->lang->line('integration_order_id')?></li>
                                        <li class="list-group-item">customer_name - <?=$this->lang->line('integration_customer_name')?></li>
                                        <li class="list-group-item">action_comm - <?=$this->lang->line('integration_action_comm')?></li>
                                        <li class="list-group-item">custom_field_1 - <?=$this->lang->line('integration_custom_field')?></li>
                                        <li class="list-group-item">custom_field_2 - <?=$this->lang->line('integration_custom_field')?></li>
                                        <li class="list-group-item">custom_field_3 - <?=$this->lang->line('integration_custom_field')?></li>
                                        <li class="list-group-item">custom_field_4 - <?=$this->lang->line('integration_custom_field')?></li>
                                        <li class="list-group-item">custom_field_5 - <?=$this->lang->line('integration_custom_field')?></li>
                                        <li class="list-group-item">custom_field_6 - <?=$this->lang->line('integration_custom_field')?></li>
                                        <li class="list-group-item">custom_field_7 - <?=$this->lang->line('integration_custom_field')?></li>
                                        <li class="list-group-item">custom_field_8 - <?=$this->lang->line('integration_custom_field')?></li>
                                        <li class="list-group-item">custom_field_9 - <?=$this->lang->line('integration_custom_field')?></li>
                                        <li class="list-group-item">custom_field_10 - <?=$this->lang->line('integration_custom_field')?></li>
                                        <li class="list-group-item">custom_field_11 - <?=$this->lang->line('integration_custom_field')?></li>
                                        <li class="list-group-item">custom_field_12 - <?=$this->lang->line('integration_custom_field')?></li>
                                        <li class="list-group-item">custom_field_13 - <?=$this->lang->line('integration_custom_field')?></li>
                                        <li class="list-group-item">custom_field_14 - <?=$this->lang->line('integration_custom_field')?></li>
                                        <li class="list-group-item">custom_field_15 - <?=$this->lang->line('integration_custom_field')?></li>
                                        <li class="list-group-item">custom_field_16 - <?=$this->lang->line('integration_custom_field')?></li>
                                        <li class="list-group-item">custom_field_17 - <?=$this->lang->line('integration_custom_field')?></li>
                                        <li class="list-group-item">custom_field_18 - <?=$this->lang->line('integration_custom_field')?></li>
                                        <li class="list-group-item">custom_field_19 - <?=$this->lang->line('integration_custom_field')?></li>
                                        <li class="list-group-item">custom_field_20 - <?=$this->lang->line('integration_custom_field')?></li>
                                        <li class="list-group-item">lf_data - <?=$this->lang->line('integration_lf_data')?></li>
                                        <li class="list-group-item">self_restrict - <?=$this->lang->line('integration_self_restrict')?></li>
                                        <li class="list-group-item">product_identifier - <?=$this->lang->line('product_identifier_id')?></li>
                                    </ul>
                                  <hr />
                                    <h5 class="capitalize"><?=$this->lang->line('for_example')?>: </h5>
                                    <div class="well" style=" overflow: auto;"><?=$base_url?>sale/amount/&lt;?=$SALE?&gt;/trans_id/&lt;?=$ORDER_ID?&gt;/invoice_id/$INVOICE/customer_name/$CUSTOMER_NAME/custom_field_1/$CUSTOM_VALUE_1/custom_field_2/$CUSTOM_VALUE_2</div>
                                </div>
                            </div>
                        </div>
                    </div>   
    			</div>
                <?php if (!empty($api_description)): ?>
                <div id="api" class="tab-pane fade in">
                	<?=$api_description?>
                </div>
                <?php endif; ?>
            </div>
		</div>
    </div>
</div>
</form>