( function( $ ) {
$( document ).ready(function() {
$('.menu-tree > ul > li > a:nth-child(2)').click(function() {
  $('.menu-tree li').removeClass('active');
  $(this).closest('li').addClass('active');	
  var checkElement = $(this).next();
  if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
    $(this).closest('li').removeClass('active');
    checkElement.slideUp('normal');
  }
  if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
    $('.menu-tree ul ul:visible').slideUp('normal');
    checkElement.slideDown('normal');
  }
  if($(this).closest('li').find('ul').children().length == 0) {
    return true;
  } else {
    return false;	
  }		
});
});
} )( jQuery );

$(document).ready(function(){
  $( ".menu-tree > ul > li").removeClass("active");
  $( ".menu-tree > ul > li").has("ul").addClass("has-sub");
});