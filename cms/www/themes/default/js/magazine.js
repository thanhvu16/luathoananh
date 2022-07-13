function addMagazinBlock(valueId, typeBlock) {
    $.post('/magazine-content/create.html', {
        'id' : valueId,
        'type' : typeBlock
    }, function(res){
        if($.trim(res).length == 0){
            alert('Error');
        }else {
            $('#magazine-blocks').append(res);
        }
    });
    $('#addBlockMagazine').modal('hide');
}
function removeBlockContent(valueId) {
    $('#item_block_'+valueId).remove();
}
function showModalBlocks(urlLoad) {
    if($('#addBlockMagazine .list-group').length == 0){
        $('#content-addBlockMagazine').load(urlLoad);
    }
    $('#addBlockMagazine').modal('show');
}
function openEmbed(src, title, magazineId) {
    if(src.indexOf('?') === -1){
        src += '?embed=1';
    }else{
        src += '&embed=1';
    }
    $('#header-info-embed').text(title);
    $('#frame-embed').attr('src', src);
    $('#iframe_loading').show();
    $('#frame-embed').hide();
    /*setTimeout(function (){
       $('#frame-embed').height($('#frame-embed').contents().find('body').height()+30);
    }, 2500);*/
    $('#modalEmbed').unbind();
    $('#modalEmbed').modal('show');
    $('#modalEmbed').on('hidden.bs.modal', function () {
        $('#frame-embed').attr('src', 'about:blank');
        $('#frame-embed').height(600);
        $('#iframe_loading').show();
        $('#frame-embed').hide();
        if(magazineId) window.refreshBlockContent(magazineId);
    });
}

function resetHeighModalEmbed(){
    var heightX = $('#frame-embed').contents().find('body').height()+30;
    if(heightX > 750){
        heightX = 750;
    }
    $('#frame-embed').height(heightX);
}

window.closeModalEmbed = function(){
    $('#modalEmbed').unbind();
    $('#modalEmbed').modal('hide');
};
window.refreshParent = function(){
    $('#modalEmbed').unbind();
    $('#modalEmbed').modal('hide');
    location.reload();
};

window.refreshPjax = function(pjaxId){
    setTimeout(function () {
        $.pjax.reload({container: '#' + pjaxId, async: false});
    }, 2000);

};
window.parentToast = function (type, heading, message, hideSecond = 5000) {
    toast(type, heading, message, hideSecond);
};

window.refreshBlockContent = function (valueId) {
    $.get('/magazine-content/refresh.html?id='+valueId, function (res) {
        $('#item_block_'+valueId).html(res);
    });
}
function locdau(str) {
    str = str.toLowerCase();
    str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
    str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
    str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
    str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
    str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
    str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
    str = str.replace(/đ/g, "d");
    str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'|\‘|\’| |\"|\&|\#|\[|\]|~|\$|\”|\“|_/g, "-"); /* tìm và thay thế các kí tự đặc biệt trong chuỗi sang kí tự - */
    str = str.replace(/-+-/g, "-"); //thay thế 2- thành 1-
    str = str.replace(/^\-+|\-+$/g, ""); //cắt bỏ ký tự - ở đầu và cuối chuỗi
    return str;
}
function changeWidthImage(element) {
    var valuePercent = $(element).val();
    $('#image_wrap').css({'width':valuePercent+'%'});
    $('#text_wrap').css({'width':(100 - valuePercent)+'%'});
}