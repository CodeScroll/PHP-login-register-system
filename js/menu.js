$( document ).ready(function() {
 
 loginRegisterFrom();
 loginregisterFromCss();
});

function loginRegisterFrom(){
 $('#loginregister-form').click(function(){

  if($('#loginregister-section').is(':visible')){
  	$('#loginregister-section').fadeOut();
  	unlockPageMenu();
  }else{
  	$('#loginregister-section').fadeIn(1000);
  	lockPageMenu();
  }
 });
}

function loginregisterFromCss(){
	if($(window).width()<768){
	 $('#loginregister-section').removeClass('divcentermenu');
	}
}

function lockPageMenu(){

	if(!$('.overlay').is(':visible')){

	  $("body").prepend("<div class=\"overlay\"></div>");
	  $(".overlay").css({
	  "position": "absolute", 
	  "width": $(window).width(), 
	  "height": $(document).height(),
	  "z-index": 10, 
	  "background-color":'black',
	  }).fadeTo(0, 0.8);

	  $('#loginregister-form-arrow').attr('class','glyphicon glyphicon-triangle-top');
	}
}
function unlockPageMenu(){
 
 $('#loginregister-form-arrow').attr('class','glyphicon glyphicon-triangle-bottom');
 $(".overlay").remove();
}