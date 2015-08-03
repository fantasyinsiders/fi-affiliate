<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form"  method="post" class="form-horizontal"  role="form">
<div class="row">
   	<div class="col-md-4">
    <?=_generate_sub_headline($page_title)?>
    </div>
    <div class="col-md-8 text-right">
    </div>
</div>
<hr />
<div class="row">   
	<div class="col-md-12">
		<div class="box-info">

        	<div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('search_term')?></label>
        		<div class="col-lg-5">
        			<input name="search_term" type="text" class="form-control required" placeholder="<?=$this->lang->line('search_term')?>" />
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('search_table')?></label>
        		<div class="col-lg-5">
        			<?=form_dropdown('table', array('commissions' => $this->lang->line('commissions'), 'members' => $this->lang->line('members')), '', 'id="table" class="form-control"')?>
        		</div>
        	</div>
            <hr />
            <div id="member-fields" style="display:none">
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('search_fields')?></label>
        		<div class="col-lg-5">
        			<?=form_multiselect('search_fields_members[]', $mem_columns, '', 'class="form-control required"')?>
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('sort_column')?></label>
        		<div class="col-lg-5">
        			<?=form_dropdown('sort_column_members', $mem_columns, '', 'class="form-control required"')?>
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('sort_order')?></label>
        		<div class="col-lg-5">
        			<?=form_dropdown('sort_order_members', array('ASC' => $this->lang->line('ASC'), 'DESC' => $this->lang->line('DESC')), '', 'id="table" class="form-control"')?>
        		</div>
        	</div>
            <hr />
            </div>
            <div id="comm-fields">
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('search_fields')?></label>
        		<div class="col-lg-5">
        			<?=form_multiselect('search_fields_commissions[]', $comm_columns, '', 'class="form-control required"')?>
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('sort_column')?></label>
        		<div class="col-lg-5">
        			<?=form_dropdown('sort_column_commissions', $comm_columns, '', 'class="form-control required"')?>
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('sort_order')?></label>
        		<div class="col-lg-5">
        			<?=form_dropdown('sort_order_commissions', array('ASC' => $this->lang->line('ASC'), 'DESC' => $this->lang->line('DESC')), '', 'id="table" class="form-control"')?>
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('filter_by_date')?></label>
        		<div class="col-lg-5">
        			<?=form_dropdown('filter_by_date', array('0' => $this->lang->line('no'), '1' => $this->lang->line('yes')), '', 'id="filter-date" class="form-control"')?>
        		</div>
        	</div>
            <div id="date-range" style="display: none">
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('search_start_date_range')?></label>
        		<div class="col-lg-5">
        			 <input name="start_date" class="datepicker-input form-control" value="<?=_format_date(_generate_timestamp(), $format_date2)?>" placeholder="<?=$format_date?>"/>
        		</div>
        	</div>
            <hr />
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('search_end_date_range')?></label>
        		<div class="col-lg-5">
        			<input name="end_date" class="datepicker-input form-control" value="<?=_format_date(_generate_timestamp(), $format_date2)?>" placeholder="<?=$format_date?>"/>
        		</div>
        	</div>
            </div>
            <hr />
            </div>
            <div class="form-group">
    		    <label class="col-lg-3 control-label"><?=$this->lang->line('max_results')?></label>
        		<div class="col-lg-5">
        			<?=form_dropdown('rows', array('50' => '50', '100' => '100', '200' => '200'), '', 'id="table" class="form-control"')?>
        		</div>
        	</div>
            <hr />
            <div class="form-group">
                <div class="col-md-offset-3 col-sm-9">
                    <button class="btn btn-success block-phone" id="top" type="submit"><i class="fa fa-refresh"></i> <?=$this->lang->line('search')?></button>        
                </div>
            </div>
		</div>
    </div>
</div>  
</form>          
<script type="text/javascript">
$("select#table").change(function(){
	$( "select#table option:selected").each(function(){
		if($(this).attr("value")=="members"){
			$("#member-fields").show();
			$("#comm-fields").hide();
		}
		if($(this).attr("value")=="commissions"){
			$("#member-fields").hide();
			$("#comm-fields").show();
		}
	});
}).change();
$("select#filter-date").change(function(){
	$( "select#filter-date option:selected").each(function(){
		if($(this).attr("value")=="1"){
			$("#date-range").show();
		}
		if($(this).attr("value")=="0"){
			$("#date-range").hide();

		}
	});
}).change();
$("#form").validate({
  rules: {
    search_term: {
      required: true,
      minlength: 3
    }
  }
});
</script>