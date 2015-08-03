$(document).ready(function(){
	
	//$(window).load(function() { $("#loading").fadeOut("slow"); })
	
	setTimeout(function(){ $('.alert-msg').fadeOut('slow'); }, 5000);
	
	$("#form").validate();
	
	$('#prod_form').ajaxForm( {
		target: '#response'
	});
	
	$('input[type=file]').bootstrapFileInput();
});