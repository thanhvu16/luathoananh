$(document).on('click','.header-right .item',function(){
    if($(this).hasClass('active')){
        $('.header-right .item').removeClass('active');
        $(this).removeClass('active');
    }else{
        $('.header-right .item').removeClass('active');
        $(this).addClass('active');
    }
})
$('html').click(function(e) {
    //if clicked element is not your element and parents aren't your div
    if (e.target.id != 'header' && $(e.target).parents('#header').length == 0) {
        $('.header-right .item').removeClass('active');
        $('.header-menu .dropdown-menu').removeClass('toshow');
    }
});

$('.sub-id').hide();
$(document).on('click','.header-menu .menu-parent', function(){
    data_id = $(this).data('id');
    $('.header-menu .sub-id').hide();
    $('.header-menu .sub-id-'+data_id).show();
    $('.header-menu .dropdown-menu').addClass('toshow');
})

$('.owl-carousel-1').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    autoplay:true,
    autoplayTimeout:5000,
    responsive:{
        0:{
            items:1
        },
        300:{
            items:2
        },
        800:{
            items:3
        },
        2000:{
            items:3
        }
    },
})
$(document).on('click', '.slider-post .click-right', function(){
    $('.owl-carousel-1').trigger('next.owl.carousel')
})
$(document).on('click', '.slider-post .click-left', function(){
    $('.owl-carousel-1').trigger('prev.owl.carousel')
})

$('.owl-carousel-2').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    autoplay:true,
    autoplayTimeout:5000,
    responsive:{
        0:{
            items:2
        },
        300:{
            items:2
        },
        600:{
            items:5
        },
        2000:{
            items:5
        }
    },
})
$(document).on('click', '.slider-post-2 .click-right', function(){
    $('.owl-carousel-2').trigger('next.owl.carousel')
})
$(document).on('click', '.slider-post-2 .click-left', function(){
    $('.owl-carousel-2').trigger('prev.owl.carousel')
})

$('.owl-carousel-3').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    autoplay:true,
    autoplayTimeout:5000,
    responsive:{
        0:{
            items:2
        },
        600:{
            items:4
        },
        2000:{
            items:4
        }
    },
})
$(document).on('click', '.slider-post-3 .click-right', function(){
    $('.owl-carousel-3').trigger('next.owl.carousel')
})
$(document).on('click', '.slider-post-3 .click-left', function(){
    $('.owl-carousel-3').trigger('prev.owl.carousel')
})

$('.owl-carousel-auth').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    autoplay:false,
    autoplayTimeout:5000,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:1
        },
        2000:{
            items:1
        }
    },
})
