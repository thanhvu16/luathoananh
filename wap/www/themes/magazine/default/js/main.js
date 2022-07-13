$(document).ready(function () {
    var n, t = $("body").width() + "px";
    $(".VCSortableInPreviewMode.alignJustifyFull").css("width", t);
    n = "-" + ($("body").width() - $(".sp-detail").innerWidth()) / 2 + "px";
    $(".VCSortableInPreviewMode.alignJustifyFull").css("margin-left", n);

    $('.LayoutAlbumRow').each(function(){
        var totalWidth = $(this).width();
        var itemWidth = 0;
        var images = $(this).find('img');
        if(images.length > 0){
            totalWidth = totalWidth - (images.length*4);
            itemWidth = totalWidth/images.length;
            images.each(function(){
                $(this).css({width: itemWidth+'px'});
            });
        }
    });

    $(".VCSortableInPreviewMode[type=VideoStream]").each(function () {
        var n = $(this);
        var height = n.width()*(9/16);
        n.find('iframe').css({height: height+'px'});
    });

});