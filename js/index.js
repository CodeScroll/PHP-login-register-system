$( document ).ready(function() {
 
});

function lockPage(){

  $("body").prepend("<div class=\"overlay\"></div>");

  $(".overlay").css({
  "position": "absolute", 
  "width": $(window).width(), 
  "height": $(document).height(),
  "z-index": 10, 
  "background-color":'black',
  }).fadeTo(0, 0.8);

}
function unlockPage(){
  $(".overlay").remove();
}