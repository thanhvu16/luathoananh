jQuery(document).ready(function($) {
	$("body").on('click', '.box-faq .fa-minus, .box-faq .fa-plus', function(){
	    if ($(this).hasClass('fa-minus')) {
	    	$(this).closest('.faq').removeClass('active');
	    } else {
	    	$(this).closest('.faq').addClass('active');
	    }
	});
	$('.carousel').carousel({
		interval: 3000
	})
	$('.btn-back-top').on('click', function () {
		$("html, body").animate({ scrollTop: 0 }, "slow");
		return false;
	})
	
    $('#toggle-tree-menu').on('click', function () {
        $('.tree-menu ol').slideToggle();
    })
});

function showMorePageIntro() {
	$('#content-page-intro').addClass('show-all');
	$('.readmore_content_exists').remove();
}
$(window).scroll(function() {
	if($('#wrap-comment').length > 0){
		var hT = $('#wrap-comment').offset().top,
		wH = $(window).height(),
		wS = $(this).scrollTop();
		if (wS >= (hT-wH-100) && $('#myIframeComment').attr('src').length == 0){
			console.log($('#wrap-comment').data('src'));
			$('#myIframeComment').attr('src', $('#wrap-comment').data('src'));
		}
	}
	
 });