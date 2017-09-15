$(document).ready(function(){
	$('input,textarea').focus(function(){
	   $(this).css({'border-color':'#FC6'});
	})
	$('input,textarea').blur(function(){
	   $(this).css({'border-color':'#d5d5d5'});
	})
});
