/*----------------Lấy toàn bộ items đã chọn trong Griview-----------------*/
function getCheckedItems() {
    var s = [];
    var c = false;
    $('input[name *= selection]').each(function() {
        if (this.checked) {
            s.push(this.value);
            var response=$.parseJSON(this.value);
            if(typeof response =='object') {
                c = true;
            }
        }
    });
    if(c) return s;
    else return s.join(",");
}
/*-------------------------Xóa tất cả items đã chọn------------------------*/
function deleteAllItems(actionPath, actionLocation) {
    var items = getCheckedItems();
    if (items) {
        var data = {ids : items, YII_CSRF_TOKEN : YII_CSRF_TOKEN};
        jConfirm(
            'Bạn chắc chắn muốn xóa những mục đã chọn?',
            CONFIRM,
            function (r) {
                if (r == true) {
                    $.ajax({
                        url : CMS_HOST_PATH + actionPath + '.html',
                        dataType : 'json',
                        type : 'POST',
                        data : data,
                        success : function (json) {
                            if (json.status == 1) {
                                window.location = CMS_HOST_PATH + actionLocation + '.html'
                            } else {
                                jAlert(json.message, NOTICE);
                            }
                        }
                    });
                }
            }
        );
    } else {
        jAlert('Chưa có mục nào được chọn', NOTICE);
    }
}
/*--------------------------------------Thay đổi trạng thái của items-----------------------------------*/
function changeStatusItems(id, status, actionPath) {
    var data = {'id' : id, 'status' : status, 'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    if (status == 1) {
        var msg = 'Bỏ kích hoạt mục này?';
    } else {
        var msg = 'Kích hoạt mục này?';
    }
    jConfirm(
        msg,
        CONFIRM,
        function (r) {
            if (r == true) {
                $.ajax({
                    url : CMS_HOST_PATH  + actionPath + '.html',
                    dataType : 'json',
                    type : 'POST',
                    data : data,
                    success : function(json) {
                        jAlert(json.message, NOTICE);
                        if (json.status == true) {
                            if (status == 1) {
                                $('#item-active-status-' + id).html('');
                                $('#item-active-status-' + id).html('<img onclick="changeStatusItems('+ id +', 0, \''+ actionPath +'\');" class="app-active-status" title="'+json.value+'" src="' + CMS_HOST_PATH + 'themes/default/images/app/icon-32-stop.png" />');
                            } else {
                                $('#item-active-status-' + id).html('');
                                $('#item-active-status-' + id).html('<img onclick="changeStatusItems('+ id +', 1, \''+ actionPath +'\');" class="app-active-status" title="'+json.value+'" src="' + CMS_HOST_PATH + 'themes/default/images/app/icon-32-check.png" />');
                            }
                        }
                    }
                });
            }
        }
    );
}
/*--------------------------Đổi vị trí của từng danh mục---------------------------------*/
function ajaxGridSort(grid, id, url, moveTo, order) {
    $.ajax({
        'url':url,
        'type':'POST',
        'dataType':'JSON',
        'cache':false,
        'data':{
            'id':id,
            'sort':moveTo,
            'order':order,
            'YII_CSRF_TOKEN':YII_CSRF_TOKEN
        }, 'beforeSend':function() {
            $('.wait').show();
        }, 'success':function(data) {
            if (data.status == 'success') {
                $('.wait').hide();
                $.pjax.reload({container:'#' + grid});
            }
        }
    });
}
function ajaxGridSortCollection(grid, id, collectionId, url, moveTo, order) {
    $.ajax({
        'url':url,
        'type':'POST',
        'dataType':'JSON',
        'cache':false,
        'data':{
            'id':id,
            'sort':moveTo,
            'collectionId':collectionId,
            'order':order,
            'YII_CSRF_TOKEN':YII_CSRF_TOKEN
        }, 'beforeSend':function() {
            $('.wait').show();
        }, 'success':function(data) {
            if (data.status == 'success') {
                $('.wait').hide();
                $.pjax.reload({container:'#' + grid});
            }
        }
    });
}
$(document).ready(function () {
    $('#log-event-user-button').on('click', function () {
        var phoneno = /^\d{10,12}$/;
        if ($('#log-event-user-input').val().trim() == '') {
            jAlert('Bạn chưa nhập số điện thoại', NOTICE);
            return false;
        } else if (isNaN($('#log-event-user-input').val().trim()) || !$('#log-event-user-input').val().trim().match(phoneno)) {
            jAlert('Số điện thoại không hợp lệ', NOTICE);
            return false;
        } else {
            return true;
        }
    });
    $('#video-stats-daily').on('click', function () {
        if ($('#video-stats-daily-input').val().trim() == '') {
            jAlert('Khoảng thời gian thống kê không được để trống', NOTICE);
            return false;
        } else {
            return true;
        }
    });

    $('#video-detail-stats-daily').on('click', function () {
        if ($('#video-detail-stats-daily-input').val().trim() == '') {
            jAlert('ID của video không được để trống', NOTICE);
            return false;
        } else if ($('#video-detail-stats-daily-date').val().trim() == '') {
            jAlert('Khoảng thời gian thống kê không được để trống', NOTICE);
            return false;
        } else {
            return true;
        }
    });
    /*------------------------Call Tooltip Bootstrap------------*/
    $('[data-toggle="tooltip"]').tooltip();
    /*------------------------Call Dropdown Bootstrap------------*/
    $('.dropdown-toggle').dropdown();
    /*-------------------------Poshytip----------------------*/
    $('.app-tooltip-field').poshytip({
        className: 'app-input-tooltip',
        showOn: 'focus',
        alignTo: 'target',
        alignX: 'center',
        alignY: 'top',
        offsetX: 5,
        showTimeout: 100
    });
});
$(window).bind("load", function () {
    if ($("body").hasClass('fixed-sidebar')) {
        $('.sidebar-collapse').slimScroll({
            height: '100%',
            railOpacity: 0.9
        });
    }
});