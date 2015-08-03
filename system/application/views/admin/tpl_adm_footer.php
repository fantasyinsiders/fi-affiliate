<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>

                     <?=_generate_help_center()?>
            	</div> 
            </div> <!-- END CONTENT -->
    	</div> <!-- END CONTENT-PAGE -->
    </div> <!-- END CONTAINER -->
    
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body capitalize">
                	<h3><i class="fa fa-trash-o"></i> <?=$this->lang->line('confirm_deletion')?></h3>
                    <?=_confirm_deletion($function)?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('cancel')?></button>
                    <a href="#" class="btn btn-danger danger"><?=$this->lang->line('delete')?></a>
                </div>
            </div>
        </div>
	</div>
        
	<!-- Slimscroll js -->
	<script src="<?=path_url();?>themes/admin/<?=$sts_admin_layout_theme?>/third/slimscroll/jquery.slimscroll.min.js"></script>
	
	
	
	<!-- Sortable js -->
	<script src="<?=path_url();?>themes/admin/<?=$sts_admin_layout_theme?>/third/sortable/sortable.min.js"></script>
	
	<!-- Bootstrao selectpicker js -->
	<script src="<?=path_url();?>themes/admin/<?=$sts_admin_layout_theme?>/third/select/bootstrap-select.min.js"></script>
	
	<!-- Magnific popup js -->
	<script src="<?=path_url();?>themes/admin/<?=$sts_admin_layout_theme?>/third/magnific-popup/jquery.magnific-popup.min.js"></script> 
	
	<!-- Bootstrap file input js --> 
	<script src="<?=path_url();?>themes/admin/<?=$sts_admin_layout_theme?>/third/input/bootstrap.file-input.js"></script>
    
	<!-- Icheck js -->
	<script src="<?=path_url();?>themes/admin/<?=$sts_admin_layout_theme?>/third/icheck/icheck.min.js"></script>
	
	<script src="<?=path_url();?>themes/admin/<?=$sts_admin_layout_theme?>/third/validator/bootstrapValidator.js"></script>
    
    <script src="<?=path_url();?>themes/admin/<?=$sts_admin_layout_theme?>/third/tabdrop/js/tabdrop.js"></script>
    
    <script src="<?=path_url();?>themes/admin/<?=$sts_admin_layout_theme?>/third/form/jquery.form.js"></script>
    
	<!-- TEMPLATE JAVASCRIPT -->
	<script src="<?=path_url();?>themes/admin/<?=$sts_admin_layout_theme?>/js/theme.js"></script>
    
    <script>
	
	$('.nav-pills, .nav-tabs').tabdrop();
	
	$(function() {	
		$('.datepicker-input').datepicker({format: '<?=$format_date?>'});
	});
	
	</script>
    

</body>
</html>