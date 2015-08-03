$(document).ready(function(){
	
	//$(window).load(function() { $("#loading").fadeOut("slow"); })
	
	setTimeout(function(){ $('.alert-msg').fadeOut('slow'); }, 5000);
	
	$('#prod_form').ajaxForm( {
		target: '#response'
	});
  
	$('#confirm-delete').on('show.bs.modal', function(e) {
    	$(this).find('.danger').attr('href', $(e.relatedTarget).data('href'));
	});

	$(".check-all").click(function () {
	  if ($(".check-all").is(':checked')) {
		  $(".table input[type=checkbox]").each(function () {
			  $(this).prop("checked", true);
		  });
	
	  } else {
		  $(".table input[type=checkbox]").each(function () {
			  $(this).prop("checked", false);
		  });
	  }
	});
	
	$("select.show-block").change(function(){
		$( "select.show-block option:selected").each(function(){
			if($(this).attr("value")=="1"){
				$(".show-div").show(300);
			}
			if($(this).attr("value")=="0"){
				$(".show-div").hide(300);
	
			}
		});
	}).change();
	
	var url = document.location.toString();
	if (url.match('#')) {
		$('.nav-tabs a[href=#'+url.split('#')[1]+']').tab('show') ;
	} 
	
	// Change hash for page-reload
	$('.nav-tabs a').on('shown', function (e) {
		window.location.hash = e.target.hash;
	})
		
	//SLIM SCROLL
	$('.slimscroller').slimscroll({
		height: 'auto',
		size: '3px',
		railOpacity: 0.3,
		wheelStep: 5
	});



	$('.tip').tooltip();
	
	//RESPONSIVE SIDEBAR
	$("button.show-sidebar").click(function(){
	$("div.left").toggleClass("mobile-sidebar");
	$("div.right").toggleClass("mobile-content");
	$("div.logo-brand").toggleClass("logo-brand-toggle");
	});


	//SIDEBAR MENU
	$('#sidebar-menu > ul > li > a').click(function() {
		$('#sidebar-menu li').removeClass('selected');
		$(this).closest('li').addClass('selected');	
		var checkElement = $(this).next();
			if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
				$(this).closest('li').removeClass('selected');
				checkElement.slideUp('fast');
			}
			if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
				$('#sidebar-menu ul ul:visible').slideUp('fast');
				checkElement.slideDown('fast');
			}
			if($(this).closest('li').find('ul').children().length == 0) {
				return true;
				} else {
				return false;	
			}		
	});

	//SELECT
	//$('.selectpicker').selectpicker();
	//FILE INPUT
	$('input[type=file]').bootstrapFileInput();
	//ICHECK
	
	$('input').iCheck({
	checkboxClass: 'icheckbox_minimal-grey',
	radioClass: 'iradio_minimal-grey',
	increaseArea: '20%' // optional
	});
	
 	$('.check-all')
    .on('ifChecked', function(event) {
        $('input').iCheck('check');
    })
    .on('ifUnchecked', function() {
        $('input').iCheck('uncheck');
    });
	
	//GALLERY
	$('.gallery-wrap').each(function() { // the containers for all your galleries
		$(this).magnificPopup({
			delegate: 'a.zooming', // the selector for gallery item
			type: 'image',
			removalDelay: 300,
			mainClass: 'mfp-fade',
			gallery: {
			  enabled:true
			}
		});
	}); 

});