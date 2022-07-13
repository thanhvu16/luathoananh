// CloudZoom
$( window ).load(function() {
    $(function(){
        var windowWidth = $(window).width();
        CloudZoom.quickStart({
            autoInside: true,
        });

        if (windowWidth <= 1024) {
            $('.lSSlideWrapper ul li img').removeClass('cloudzoom')
        }
// Bind a click event to a Cloud Zoom instance.
    });
});
// Slide - Single product
$(document).ready(function() {
    var btnSlider = $('#image-gallery').lightSlider({
        gallery:true,
        item:1,
        mode: 'fade',
        controls: false,
        vertical:true,
        verticalHeight:320,
        vThumbWidth:80,
        thumbItem:6,
        thumbMargin:5,
        slideMargin: 0,
        speed:500,
        auto: false,
        currentPagerPosition:'left',
        loop:true,
        //autoWidth:true,
        onSliderLoad: function(el) {
            el.lightGallery({
                selector: '#image-gallery .lslide'
            });
            $('#image-gallery').removeClass('cS-hidden');
        } ,
    });

    $('#goToPrevSlide').click(function(){
        btnSlider.goToPrevSlide();
    });
    $('#goToNextSlide').click(function(){
        btnSlider.goToNextSlide();
    });

});