/*-----------------------Config System------------------*/
function makeCMD(configCMD) {
    $('#configCMD').val(configCMD);
    document.getElementById('formConfig').submit();
}
/*--------------------------------------------Group & User Permisstion-----------------------------------------*/
function selectAllPermission(checkbox) {
    var subfix = $(checkbox).val();
    if ($('#all_id_' + subfix).is(':checked')) {
        $('input.checkbox_action_' + subfix).prop('checked', true);
    } else {
        $('input.checkbox_action_' + subfix).prop('checked', false);
    }
}
function deleteAction(id) {
    var data = {'id' : id, 'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    jConfirm(
        'Bạn chắc chắn muốn xóa action đã chọn?',
        CONFIRM,
        function (r) {
            if (r == true) {
                $.ajax({
                    url :   CMS_HOST_PATH + 'admin-action/delete-action.html',
                    dataType : 'json',
                    type : 'POST',
                    data : data,
                    'beforeSend':function() {
                        $('.wait').show();
                    },
                    'success' : function(json) {
                        $('.wait').hide();
                        if (json.status == true) {
                            $('#admin-action-delete-' + id).hide();
                        }
                    }
                });
            }
        }
    );
}
function updatePermission(id, controller) {
    var data = {id : id, controller: controller, YII_CSRF_TOKEN : YII_CSRF_TOKEN};
    $.ajax({
        url : CMS_HOST_PATH + 'admin-action/update-permission.html',
        dataType : 'json',
        type : 'POST',
        data : data,
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function (json) {
            $('.wait').hide();
            if (json.status == 1) {
                if (controller == 'admin-group') {
                    window.location.href = CMS_HOST_PATH + 'admin-group/permission.html?id='+id;
                } else {
                    window.location.href = CMS_HOST_PATH + 'admin/permission.html?id='+id;
                }
            }
        }
    });
}
function updateDescAction(id, desc) {
    jPrompt(
        ACTION_DESC,
        desc,
        UPDATE,
        function (r) {
            if (r) {
                var data = {'id' : id, 'desc': r, 'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
                $.ajax({
                    url :   CMS_HOST_PATH + 'admin-action/change-desc-action.html',
                    dataType : 'json',
                    type : 'POST',
                    data : data,
                    'beforeSend':function() {
                        $('.wait').show();
                    },
                    'success' : function(json) {
                        $('.wait').hide();
                        if (json.status == true) {
                            $('#admin-action-desc-' + id).html(json.desc);
                        }
                    }
                });
            }
        }
    );
}
/*--------------------------------------------End Group & User Permisstion-----------------------------------------*/
/*--------------------------------------------Clip & Clip Category-----------------------------------------*/
function changeHome(id,itemStatus,action) {
    var csrfToken = $('#csrf-token').val();
    var data = {'id' : id, 'status' : itemStatus, 'YII_CSRF_TOKEN' : csrfToken};
    if (itemStatus == 1) {
        var msg = 'Bỏ kích hoạt ?';
    } else {
        var msg = 'Kích hoạt ?';
    }
    jConfirm(
        msg,
        CONFIRM,
        function (r) {
            if (r == true) {
                $.ajax({
                    url :   CMS_HOST_PATH + action +'.html',
                    dataType : 'json',
                    type : 'POST',
                    data : data,
                    'beforeSend':function() {
                        $('.wait').show();
                    },
                    'success' : function(json) {
                        jAlert(json.message, NOTICE);
                        if (json.status == true) {
                            $('.wait').hide();
                            if (itemStatus == 1) {
                                $('#item-home-status-' + id).html('');
                                $('#item-home-status-' + id).html('<img onclick="changeHome(\''+id+'\', 0,\''+action+'\');" class="app-active-status"  alt="'+json.value+'" title="'+json.value+'" src="' + CMS_HOST_PATH + 'themes/default/images/app/icon-32-stop.png" />');
                            } else {
                                $('#item-home-status-' + id).html('');
                                $('#item-home-status-' + id).html('<img onclick="changeHome(\''+id+'\', 1,\''+action+'\');" class="app-active-status" alt="'+json.value+'" title="'+json.value+'" src="' + CMS_HOST_PATH + 'themes/default/images/app/icon-32-check.png" />');
                            }
                        }
                    }
                });
            }
        }
    );
}

function changeIsHome(id, itemStatus, action) {
    var csrfToken = $('#csrf-token').val();
    var data = {'id' : id, 'is_home' : itemStatus, 'YII_CSRF_TOKEN' : csrfToken};
    if (itemStatus == 1) {
        var msg = 'Bỏ kích hoạt? Nếu không set 1 câu hỏi nào, hệ thống sẽ chọn câu hỏi mới nhất để làm câu hỏi hiển thị';
    } else {
        var msg = 'Kích hoạt?';
    }

    var buttonElm = '#item-active-status-' + id;
    if(action == 'notification/change-hot'){
        buttonElm = '#item-active-hot-' + id;
    }
    jConfirm(
        msg,
        CONFIRM,
        function (r) {
            if (r == true) {
                $.ajax({
                    url :   CMS_HOST_PATH + action+'.html',
                    dataType : 'json',
                    type : 'POST',
                    data : data,
                    'beforeSend':function() {
                        $('.wait').show();
                    },
                    'success' : function(json) {
                        jAlert(json.message, NOTICE);
                        console.log(json.error);
                        if (json.status == true) {
                            $('.wait').hide();
                            if (itemStatus == 1) {
                                $(buttonElm).html('');
                                $(buttonElm).html('<img onclick="changeIsHome(\''+id+'\', 0,\''+action+'\');" class="app-active-status"  alt="'+json.value+'" title="'+json.value+'" src="' + CMS_HOST_PATH + 'themes/default/images/app/icon-32-stop.png" />');
                            } else {
                                $(buttonElm).html('');
                                $(buttonElm).html('<img onclick="changeIsHome(\''+id+'\', 1,\''+action+'\');" class="app-active-status" alt="'+json.value+'" title="'+json.value+'" src="' + CMS_HOST_PATH + 'themes/default/images/app/icon-32-check.png" />');
                            }
                        }
                    }
                });
            }
        }
    );
}

function changeActive(id,itemStatus,action) {
    var csrfToken = $('#csrf-token').val();
    var data = {'id' : id, 'status' : itemStatus, 'YII_CSRF_TOKEN' : csrfToken};
    if (itemStatus == 1) {
        var msg = 'Bỏ kích hoạt ?';
    } else {
        var msg = 'Kích hoạt ?';
    }

    var buttonElm = '#item-active-status-' + id;
    if(action == 'notification/change-hot'){
        buttonElm = '#item-active-hot-' + id;
    }
    jConfirm(
        msg,
        CONFIRM,
        function (r) {
            if (r == true) {
                $.ajax({
                    url :   CMS_HOST_PATH + action+'.html',
                    dataType : 'json',
                    type : 'POST',
                    data : data,
                    'beforeSend':function() {
                        $('.wait').show();
                    },
                    'success' : function(json) {
                        jAlert(json.message, NOTICE);
                        console.log(json.error);
                        if (json.status == true) {
                            $('.wait').hide();
                            if (itemStatus == 1) {
                                $(buttonElm).html('');
                                $(buttonElm).html('<img onclick="changeActive(\''+id+'\', 0,\''+action+'\');" class="app-active-status"  alt="'+json.value+'" title="'+json.value+'" src="' + CMS_HOST_PATH + 'themes/default/images/app/icon-32-stop.png" />');
                            } else {
                                $(buttonElm).html('');
                                $(buttonElm).html('<img onclick="changeActive(\''+id+'\', 1,\''+action+'\');" class="app-active-status" alt="'+json.value+'" title="'+json.value+'" src="' + CMS_HOST_PATH + 'themes/default/images/app/icon-32-check.png" />');
                            }
                        }
                    }
                });
            }
        }
    );
}

function changeActiveHot(id,itemStatus,action) {
    var csrfToken = $('#csrf-token').val();
    var data = {'id' : id, 'status' : itemStatus, 'YII_CSRF_TOKEN' : csrfToken};
    if (itemStatus == 1) {
        var msg = 'Bỏ kích hoạt ?';
    } else {
        var msg = 'Kích hoạt ?';
    }

    var buttonElm = '#item-active-status-hot-' + id;
    jConfirm(
        msg,
        CONFIRM,
        function (r) {
            if (r == true) {
                $.ajax({
                    url :   CMS_HOST_PATH + action+'.html',
                    dataType : 'json',
                    type : 'POST',
                    data : data,
                    'beforeSend':function() {
                        $('.wait').show();
                    },
                    'success' : function(json) {
                        jAlert(json.message, NOTICE);
                        console.log(json.error);
                        if (json.status == true) {
                            $('.wait').hide();
                            if (itemStatus == 1) {
                                $(buttonElm).html('');
                                $(buttonElm).html('<img onclick="changeActiveHot(\''+id+'\', 0,\''+action+'\');" class="app-active-status"  alt="'+json.value+'" title="'+json.value+'" src="' + CMS_HOST_PATH + 'themes/default/images/app/icon-32-stop.png" />');
                            } else {
                                $(buttonElm).html('');
                                $(buttonElm).html('<img onclick="changeActiveHot(\''+id+'\', 1,\''+action+'\');" class="app-active-status" alt="'+json.value+'" title="'+json.value+'" src="' + CMS_HOST_PATH + 'themes/default/images/app/icon-32-check.png" />');
                            }
                        }
                    }
                });
            }
        }
    );
}
function changeMusicStatus(id,itemStatus,action) {
    var csrfToken = $('#csrf-token').val();
    var data = {'id' : id, 'status' : itemStatus, 'YII_CSRF_TOKEN' : csrfToken};
    if (itemStatus == 1) {
        var msg = 'Bỏ kích hoạt trên nhac.vn?';
    } else {
        var msg = 'Kích hoạt trên nhac.vn?';
    }

    var buttonElm = '#item-change-music-status-' + id;
    if(action == 'notification/change-hot'){
        buttonElm = '#item-active-hot-' + id;
    }
    jConfirm(
        msg,
        CONFIRM,
        function (r) {
            if (r == true) {
                $.ajax({
                    url :   CMS_HOST_PATH + action+'.html',
                    dataType : 'json',
                    type : 'POST',
                    data : data,
                    'beforeSend':function() {
                        $('.wait').show();
                    },
                    'success' : function(json) {
                        jAlert(json.message, NOTICE);
                        console.log(json.error);
                        if (json.status == true) {
                            $('.wait').hide();
                            if (itemStatus == 1) {
                                $(buttonElm).html('');
                                $(buttonElm).html('<img onclick="changeMusicStatus(\''+id+'\', 0,\''+action+'\');" class="app-active-status"  alt="'+json.value+'" title="'+json.value+'" src="' + CMS_HOST_PATH + 'themes/default/images/app/icon-32-stop.png" />');
                            } else {
                                $(buttonElm).html('');
                                $(buttonElm).html('<img onclick="changeMusicStatus(\''+id+'\', 1,\''+action+'\');" class="app-active-status" alt="'+json.value+'" title="'+json.value+'" src="' + CMS_HOST_PATH + 'themes/default/images/app/icon-32-check.png" />');
                            }
                        }
                    }
                });
            }
        }
    );
}

function changeClipLucky(id,itemStatus,action) {
    var csrfToken = $('#csrf-token').val();
    var data = {'id' : id, 'status' : itemStatus, 'YII_CSRF_TOKEN' : csrfToken};
    if (itemStatus == 1) {
        var msg = 'Bỏ kích hoạt ?';
    } else {
        var msg = 'Kích hoạt ?';
    }
    jConfirm(
        msg,
        CONFIRM,
        function (r) {
            if (r == true) {
                $.ajax({
                    url :   CMS_HOST_PATH + action+'.html',
                    dataType : 'json',
                    type : 'POST',
                    data : data,
                    'beforeSend':function() {
                        $('.wait').show();
                    },
                    'success' : function(json) {
                        jAlert(json.message, NOTICE);
                        if (json.status == true) {
                            $('.wait').hide();
                            if (itemStatus == 1) {
                                $('#item-lucky-status-' + id).html('');
                                $('#item-lucky-status-' + id).html('<img onclick="changeClipLucky(\''+id+'\', 0,\''+action+'\');" class="app-active-status"  alt="'+json.value+'" title="'+json.value+'" src="' + CMS_HOST_PATH + 'themes/default/images/app/icon-32-stop.png" />');
                            } else {
                                $('#item-lucky-status-' + id).html('');
                                $('#item-lucky-status-' + id).html('<img onclick="changeClipLucky(\''+id+'\', 1,\''+action+'\');" class="app-active-status" alt="'+json.value+'" title="'+json.value+'" src="' + CMS_HOST_PATH + 'themes/default/images/app/icon-32-check.png" />');
                            }
                        }
                    }
                });
            }
        }
    );
}

function changeHot(id,itemStatus,action) {
    var csrfToken = $('#csrf-token').val();
    var data = {'id' : id, 'status' : itemStatus, 'YII_CSRF_TOKEN' : csrfToken};
    if (itemStatus == 1) {
        var msg = 'Bỏ kích hoạt ?';
    } else {
        var msg = 'Kích hoạt ?';
    }
    jConfirm(
        msg,
        CONFIRM,
        function (r) {
            if (r == true) {
                $.ajax({
                    url :   CMS_HOST_PATH + action +'.html',
                    dataType : 'json',
                    type : 'POST',
                    data : data,
                    'beforeSend':function() {
                        $('.wait').show();
                    },
                    'success' : function(json) {
                        jAlert(json.message, NOTICE);
                        if (json.status == true) {
                            $('.wait').hide();
                            if (itemStatus == 1) {
                                $('#item-hot-status-' + id).html('');
                                $('#item-hot-status-' + id).html('<img onclick="changeHot(\''+id+'\', 0,\''+action+'\');" class="app-active-status"  alt="'+json.value+'" title="'+json.value+'" src="' + CMS_HOST_PATH + 'themes/default/images/app/icon-32-stop.png" />');
                            } else {
                                $('#item-hot-status-' + id).html('');
                                $('#item-hot-status-' + id).html('<img onclick="changeHot(\''+id+'\', 1,\''+action+'\');" class="app-active-status" alt="'+json.value+'" title="'+json.value+'" src="' + CMS_HOST_PATH + 'themes/default/images/app/icon-32-check.png" />');
                            }
                        }
                    }
                });
            }
        }
    );
}

function changeApi(id,itemStatus,action) {
    var csrfToken = $('#csrf-token').val();
    var data = {'id' : id, 'status' : itemStatus, 'YII_CSRF_TOKEN' : csrfToken};
    if (itemStatus == 1) {
        var msg = 'Bỏ kích hoạt ?';
    } else {
        var msg = 'Kích hoạt ?';
    }
    jConfirm(
        msg,
        CONFIRM,
        function (r) {
            if (r == true) {
                $.ajax({
                    url :   CMS_HOST_PATH + action +'.html',
                    dataType : 'json',
                    type : 'POST',
                    data : data,
                    'beforeSend':function() {
                        $('.wait').show();
                    },
                    'success' : function(json) {
                        jAlert(json.message, NOTICE);
                        if (json.status == true) {
                            $('.wait').hide();
                            if (itemStatus == 1) {
                                $('#item-api-status-' + id).html('');
                                $('#item-api-status-' + id).html('<img onclick="changeApi(\''+id+'\', 0,\''+action+'\');" class="app-active-status"  alt="'+json.value+'" title="'+json.value+'" src="' + CMS_HOST_PATH + 'themes/default/images/app/icon-32-stop.png" />');
                            } else {
                                $('#item-api-status-' + id).html('');
                                $('#item-api-status-' + id).html('<img onclick="changeApi(\''+id+'\', 1,\''+action+'\');" class="app-active-status" alt="'+json.value+'" title="'+json.value+'" src="' + CMS_HOST_PATH + 'themes/default/images/app/icon-32-check.png" />');
                            }
                        }
                    }
                });
            }
        }
    );
}


    $(document).on('change', '#inputText', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    $('#inputText').on('fileselect', function(event, numFiles, label) {

        var input = $(this).parents('.input-group').find('input[type="text"]'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;

        if( input.length ) {
            input.val(log);
        } else {
            if( log ) alert(log);
        }

    });

$(".sort-oder").change(function(){
    var $this = $(this);
    var id = $this.data('id');
    var url = $this.data('url');
    var val = $this.val();
    var data = {'id' : id, 'val' : val, 'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url : CMS_HOST_PATH+url,
        dataType : 'json',
        type : 'POST',
        data : data,
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(json) {
            $('.wait').hide();
            if (json.status == 1) {
                location.reload();
            } else {
                alert(json.message);
            }

        }
    });

});
function addCollection(id) {
    id=parseInt(id);
    var keys = $('#ajax_gridview').yiiGridView('getSelectedRows');
    var action = $('#action').val();
    var data = {'id' : id,'keys':keys,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'json',
        type : 'POST',
        data : data,
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(json) {
            $('.wait').hide();
            if (json.status == 1) {
                jAlert(json.message, NOTICE);
                location.reload()
            } else {
                alert(json.message);
            }
        }
    });
}


function approvedClip() {
    var keys = $('#ajax_gridview').yiiGridView('getSelectedRows');
    var action = 'clip/approveds.html';
    var data = {'keys':keys,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'json',
        type : 'POST',
        data : data,
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(json) {
            $('.wait').hide();
            if (json.status == 1) {
                jAlert(json.message, NOTICE);
                location.reload()
            } else {
                alert(json.message);
            }
        }
    });
}

function removeOnCollection(collectionId,contentId,contentType) {
    var action = 'collection-content/remove.html';
    var data = {'collectionId':collectionId,'contentId':contentId,'contentType':contentType,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'json',
        type : 'POST',
        data : data,
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(json) {
            $('.wait').hide();
            if (json.status == 1) {
                jAlert(json.message, NOTICE);
                location.reload()
            } else {
                alert(json.message);
            }
        }
    });
}
function deletedClip() {
    var keys = $('#ajax_gridview').yiiGridView('getSelectedRows');
    var action = 'clip/delete-selected.html';
    var data = {'keys':keys,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    if(keys==''){
        jAlert('Bạn chưa chọn item nào', NOTICE);return;
    }
    jConfirm(
        'Bạn chắc chắn muốn xóa clip đã chọn?',
        CONFIRM,
        function (r) {
            if (r == true) {
                $.ajax({
                    url :   CMS_HOST_PATH + action,
                    dataType : 'json',
                    type : 'POST',
                    data : data,
                    'beforeSend':function() {
                        $('.wait').show();
                    },
                    'success' : function(json) {
                        $('.wait').hide();
                        if (json.status == 1) {
                            jAlert(json.message, NOTICE);
                            location.reload()
                        } else {
                            jAlert(json.message, NOTICE);
                        }
                    }
                });
            }
        }
    );
}

$('#thumb-list img.thumb-item').click(function () {
    $('.thumbnail_img').attr('src', $(this).attr('src'));
    $('#thumbnail').val($(this).attr('name'));
    $('#status').html('Updating ...');
    var thumbnail = $('#thumbnail').val();
    var action = $('#action').val();
    var clipId = $('#clipid').val();
    var data = {'id' :clipId,'thumbnail':thumbnail,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'json',
        type : 'POST',
        data : data,
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(json) {
            $('.wait').hide();
            if (json.status == 1) {
                $('#status').html(json.message);
            } else {
                $('#status').html(json.message);
            }
        },
        error:function(){

        }
    });
});

function deleteCommnent() {
    var keys = $('#ajax_gridview').yiiGridView('getSelectedRows');
    var action = 'clip-comment/delete-comment.html';
    var data = {'keys':keys,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'json',
        type : 'POST',
        data : data,
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(json) {
            $('.wait').hide();
            if (json.status == 1) {
                jAlert(json.message, NOTICE);
                location.reload()
            } else {
                alert(json.message);
            }
        }
    });
}
function activeCommnent() {
    var keys = $('#ajax_gridview').yiiGridView('getSelectedRows');
    var action = 'clip-comment/active-comment.html';
    var data = {'keys':keys,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'json',
        type : 'POST',
        data : data,
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(json) {
            $('.wait').hide();
            if (json.status == 1) {
                jAlert(json.message, NOTICE);
                location.reload()
            } else {
                alert(json.message);
            }
        }
    });
}

function addPlaylistClip() {
    var action = 'clip/add-to-collection.html';
    var type = 'add_playlist';
    var keys = $('#ajax_gridview').yiiGridView('getSelectedRows');
    if(keys==''){
        alert('Bạn cần chọn ít nhất 1 clip ');return;
    }
    var data = {'keys' : keys,'type':type,'action':action,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'html',
        type : 'POST',
        data : data,
        'beforeSend':function() {
            //$('.wait').show();
        },
        'success' : function(html) {
            $('#myModal2').remove();
            //$('.wait').hide();
            document.getElementById('page-wrapper').innerHTML += html;
            $('#myModal2').modal('show');
        }
    });
}
function addLuckyTBClip(type) {
    var action = 'clip-lucky-unsubscribe/add-clip.html';
    var keys = $('#ajax_gridview').yiiGridView('getSelectedRows');
    if(keys==''){
        alert('Bạn cần chọn ít nhất 1 clip ');return;
    }
    var data = {'keys' : keys,'type' : type,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'json',
        type : 'POST',
        data : data,
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(json) {
            $('.wait').hide();
            if (json.status == 1) {
                jAlert(json.message, NOTICE);
                setTimeout(function () {
                    location.reload();
                },2000)
            }else{
                alert(json.message);
            }
        }

    });
}
function createdCollection(type) {
    //document.getElementById("created-collection").submit();
    var action = 'playlist-collection/popupcreate.html'; // Type=4 Playlist ; 3:show
    if(type==3){
        action = 'show-collection/popupcreate.html';
    }
    var data = $('form#created-collection').serialize();
    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'json',
        type : 'POST',
        data : data,
        'success' : function(json) {
            if (json.status == 1) {
                jAlert(json.message, NOTICE);
                document.getElementById('created-collection').reset();
                $('#popup-search').empty();
                $('#popup-search').load(CMS_HOST_PATH+'playlist-collection/playlist-list.html').fadeIn('fast');
            } else {
                alert(json.message);
            }
        }
    });
}

function searchCollection(val,collectiontype) {
    console.log(val);
    var action = 'playlist-collection/search-by-keyword.html';
    if(collectiontype==3){
        action = 'show-collection/search-by-keyword.html';
    }
    var type = 'search-by-keyword';
    if (val != '') {
        document.getElementById('search-collection').className ='form-control loading';
        var data = {'keyword' : val,'collectiontype':collectiontype,'action':action,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
        $.ajax({
            url :   CMS_HOST_PATH + action,
            dataType : 'html',
            type : 'POST',
            data : data,
            'success' : function(html) {
                $('#popup-search').empty();
                $('#popup-search').html(html);
                document.getElementById('search-collection').className ='form-control';
            }
        });
    } else {
        document.getElementById('search-collection').className ='form-control';
    }
    return '';
}
function addvideotoPlaylist(id){
    var action = 'playlist-collection/add-video.html';
    var keys = $('#keys').val();
    var data = {'keys' : keys,'id' : id,'action':action,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'json',
        type : 'POST',
        data : data,
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(json) {
            $('.wait').hide();
            if (json.status == 1) {
                jAlert(json.message, NOTICE);
            }else{
                alert(json.message);
            }
        }

    });

}

function addvideotoShow(id){
    var action = 'show-collection/add-video.html';
    var keys = $('#keys').val();
    var data = {'keys' : keys,'id' : id,'action':action,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'json',
        type : 'POST',
        data : data,
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(json) {
            $('.wait').hide();
            if (json.status == 1) {
                jAlert(json.message, NOTICE);
            }else{
                alert(json.message);
            }
        }

    });

}

function addShowClip() {
    var action = 'clip/add-to-collection.html';
    var type = 'add_show';
    var keys = $('#ajax_gridview').yiiGridView('getSelectedRows');
    if(keys==''){
        alert('Bạn cần chọn ít nhất 1 clip ');return;
    }
    var data = {'keys' : keys,'type':type,'action':action,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'html',
        type : 'POST',
        data : data,
        'beforeSend':function() {
            //$('.wait').show();
        },
        'success' : function(html) {
            $('#myModal2').remove();
            //$('.wait').hide();
            document.getElementById('page-wrapper').innerHTML += html;
            $('#myModal2').modal('show');
        }
    });
}

/*--------------------------------------------End Clip & Clip Category-----------------------------------------*/
/*--------------------------------------------Film form edit-----------------------------------------*/
var config = {
    '.chosen-select'           : {},
    '.chosen-select-deselect'  : {allow_single_deselect:true},
    '.chosen-select-no-single' : {disable_search_threshold:10},
    '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
    '.chosen-select-width'     : {width:"95%"}
}
for (var selector in config) {
    $(selector).chosen(config[selector]);
}
function getSelectValues(select) {
    var result = [];
    var options = select && select.options;
    var opt;
    var selected = $('#actor_select option:selected');
    for (var i=0, iLen=options.length; i<iLen; i++) {
        opt = options[i];
        if (opt.selected) {
            result.push(opt.value || opt.text);
        }
    }
    return result;
}
function getSelectLabel(select) {
    var result = [];
    var options = select && select.options;
    var opt;
    for (var i=0, iLen=options.length; i<iLen; i++) {
        opt = options[i];
        if (opt.selected) {
            result.push(opt.text);
        }
    }
    return result;
}
function changeDirector(select,addid) {
    var id      =getSelectValues(select);
    if (addid != 'null') {
        document.getElementById(addid).value = id;
    }
    return;
}
function cmsPopupPlay(filmid,clipid) {
    var action = 'film/popup.html';
    var type = 'popupPlay';
    var data = {'clipid' : clipid,'filmid' : filmid,'type':type,'action':action,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
        $.ajax({
            url :   CMS_HOST_PATH + action,
            dataType : 'html',
            type : 'POST',
            data : data,
            'beforeSend':function() {
                //$('#myModal2').remove();
                var element = document.getElementById("myModal2");
                element.outerHTML = "";
                document.getElementById("myModal2").remove();
                $('.wait').show();
            },
            'success' : function(html) {
                $('.wait').hide();
                document.getElementById('page-wrapper').innerHTML += html;
                $('#myModal2').modal('show');
            }
        });
}
function cmsPlay(id) {
    var action = 'clip/play.html';
    var type = 'playVideo';
    var data = {'id' : id,'type':type,'action':action,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
        $.ajax({
            url :   CMS_HOST_PATH + action,
            dataType : 'html',
            type : 'POST',
            data : data,
            'beforeSend':function() {
                $('.wait').show();
            },
            'success' : function(html) {
                $('#myModal2').remove();
                $('.wait').hide();
                document.getElementById('page-wrapper').innerHTML += html;
                $('#myModal2').modal('show');
            }
        });
}

function resyncClip(id) {
    var action = 'clip/resync.html';
    var type = 'resync';
    var data = {'id' : id,'type':type,'action':action,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    jConfirm(
        'Re-sync video to Viclip ?',
        CONFIRM,
        function (r) {
            if (r == true) {
                $.ajax({
                    url :   CMS_HOST_PATH + action,
                    dataType : 'json',
                    type : 'POST',
                    data : data,
                    'beforeSend':function() {
                        $('.wait').show();
                    },
                    'success' : function(json) {
                        $('.wait').hide();
                        if (json.status == 1) {
                            jAlert(json.message, NOTICE);
                        } else {
                            jAlert(json.message, NOTICE);
                        }
                        $('.wait').hide();
                        return;
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        jAlert('Bạn không được phép truy cập chức năng này !', NOTICE);
                        $('.wait').hide();
                        return;
                    }
                });

            }
        }
    );

}

function addVideoFilm(filmid) {
    var action = 'film/popup.html';
    var type = 'addVideoFilm';
    var data = {'filmid' : filmid,'type':type,'action':action,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'html',
        type : 'POST',
        data : data,
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(html) {
        $('#myModal2').remove();
        $('.wait').hide();
        document.getElementById('page-wrapper').innerHTML += html;
        $('#myModal2').modal('show');
    }
    });
}

function addToFilm(clipid) {
    var action = 'clip/popup.html';
    var type = 'addcliptofilm';
    var data = {'clipid' : clipid,'type':type,'action':action,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'html',
        type : 'POST',
        data : data,
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(html) {
            $('#myModal2').remove();
            $('.wait').hide();
            document.getElementById('page-wrapper').innerHTML += html;
            $('#myModal2').modal('show');
        }
    });
}

function deleteClipFilm(filmid,clipid) {
    var action = 'film/delete-clip-film.html';
    var type = 'deleteClipFilm';
    var data = {'filmid' : filmid,'clipid' : clipid,'type':type,'action':action,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};

    jConfirm('Bạn chắc chắn muốn xóa clip đã chọn?', 'File Exist', function(r) {
        if (r==true) {
            $.ajax({
                url :   CMS_HOST_PATH + action,
                dataType : 'json',
                type : 'POST',
                data : data,
                'beforeSend':function() {
                    $('.wait').show();
                },
                'success' : function(json) {
                    $('.wait').hide();
                    if (json.status == true) {
                        $('#f' + filmid+'_c'+clipid).hide();
                        jAlert(json.message, NOTICE);
                    }
                }
            });
        } else {
            return '';
        }
    });
}
function changeOderFilm(val,filmid,clipid) {
    var action = 'film/order-clip-film.html';
    var type = 'order-clip-film';
    var data = {'filmid' : filmid,'clipid' : clipid,'type':type,'order':val,'action':action,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'json',
        type : 'POST',
        data : data,
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(json) {
            $('.wait').hide();
            if (json.status == true) {
                jAlert(json.message, NOTICE);
            }
        }
    });
}
function changeTrailerFilm(filmid,clipid) {
    var action = 'film/trailer-clip-film.html';
    var type = 'trailer-clip-film';
    var data = {'filmid' : filmid,'clipid' : clipid,'type':type,'action':action,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'json',
        type : 'POST',
        data : data,
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(json) {
            $('.wait').hide();
            if (json.status == true) {
                jAlert(json.message, NOTICE);
            }
        }
    });

}



function removeAllTrailer(id) {
    var action = 'film/clear-trailer.html';
    var type = 'clear-trailer';
    var data = {'filmid' :id,'type' : type,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'json',
        type : 'POST',
        data : data,
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(json) {
            $('.wait').hide();
            if (json.status == true) {
                //var inputradio = document.querySelector('input[name="radio1"]:checked');
                $('input[class=radio_trailer]').attr('checked', false);
                jAlert(json.message, NOTICE);
            }
        }
    });
}


function searchClipOnKeyUp(val) {
    if (val != '') {
    } else {
        $('.popup-search-result').hide();
    }
}



function searchByKeyword(val,filmid) {
    console.log(val);
    var action = 'film/search-by-keyword.html';
    var type = 'search-by-keyword';
    if (val != '') {
        //$('#search-clip-film').attr('class','form-control loading');
        document.getElementById('search-clip-film').className ='form-control loading';
        var data = {'keyword' : val,'filmid':filmid,'action':action,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
        $.ajax({
            url :   CMS_HOST_PATH + action,
            dataType : 'html',
            type : 'POST',
            data : data,
            'beforeSend':function() {
                $('.wait').show();
            },
            'success' : function(html) {
                $('.wait').hide();
                $('.popup-search-result').html(html);
                $('.popup-search-result').show();
                document.getElementById('search-clip-film').className ='form-control';
            }
        });

    } else {
        document.getElementById('search-clip-film').className ='form-control';
        $('.popup-search-result').hide();
    }
    return '';
}
function searchFilmByKeyword(val,clipid) {
    var action = 'clip/search-film-by-keyword.html';
    var type = 'search-film-by-keyword';
    if (val != '') {
        //$('#search-clip-film').attr('class','form-control loading');
        document.getElementById('search-film').className ='form-control loading';
        var data = {'type':type,'keyword' :val,'clipid':clipid,'action':action,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
        $.ajax({
            url :   CMS_HOST_PATH + action,
            dataType : 'html',
            type : 'POST',
            data : data,
            'beforeSend':function() {
                $('.wait').show();
            },
            'success' : function(html) {
                $('.wait').hide();
                $('.popup-search-result').html(html);
                $('.popup-search-result').show();
                document.getElementById('search-film').className ='form-control';
            }
        });

    } else {
        document.getElementById('search-film').className ='form-control';
        $('.popup-search-result').hide();
    }
    return '';
}


function addcliptofilm(clipid, filmid) {
    if(clipid != '' && filmid!=''){
        var action = 'film/add-clip-to-film.html';
        var type = 'search-by-keyword';
        var data = {'type' : type,'filmid':filmid,'clipid':clipid,'action':action,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
        $.ajax({
            url :   CMS_HOST_PATH + action,
            dataType : 'json',
            type : 'POST',
            data : data,
            'beforeSend':function() {
                $('.wait').show();
            },
            'success' : function(json) {
                $('.wait').hide();
                if (json.status == true) {
                    jAlert(json.message, NOTICE);
                    document.getElementById('film_list_gridview').innerHTML += json.content;
                }
            }
        });

    } else {
        $('.popup-search-result').hide();
    }
}
function deletedFilm() {
    var keys = $('#ajax_gridview').yiiGridView('getSelectedRows');
    var action = 'film/delete-selected.html';
    var data = {'keys':keys,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};

    if(keys==''){
        jAlert('Bạn chưa chọn item nào', NOTICE);return;
    }
    jConfirm(
        'Bạn chắc chắn muốn xóa item đã chọn?',
        CONFIRM,
        function (r) {
            if (r == true) {
                $.ajax({
                    url :   CMS_HOST_PATH + action,
                    dataType : 'json',
                    type : 'POST',
                    data : data,
                    'beforeSend':function() {
                        $('.wait').show();
                    },
                    'success' : function(json) {
                        $('.wait').hide();
                        if (json.status == 1) {
                            jAlert(json.message, NOTICE);
                            location.reload()
                        } else {
                            alert(json.message);
                        }
                    }
                });
            }
        }
    );

}
/*--------------------------------------------End Film-----------------------------------------*/

/*--------------------------------------------Crop image----------------------------------------*/

function checkTxt(elm) {
    var txtFile = $(elm).find('#inputText');
    if ( txtFile.val() == ''){
        alert('Chưa chọn file');
        return false;
    }else{
        $('.overlay-loading').show();
        return true;
    }

}

function checkImage(val) {


    var $image = $(".image-crop > img"),
        $dataX = $("#dataX"),
        $dataY = $("#dataY"),
        $dataHeight = $("#dataHeight"),
        $dataWidth = $("#dataWidth");
        console.log($image.cropper("getDataURL"));
        var elem = document.getElementById('image-data');
        elem.value =$image.cropper("getDataURL");

}
$(document.body).on('hidden.bs.modal', function () {
    var elements = document.getElementsByClassName('play_popup_video');
    if(elements){
        $(elements).empty();
    }
});
$(document).ready(function(){
    $("img").error(function () {
        $(this).hide();
    });
    $(".copy-target").focus(function() { $(this).select(); } );
});
$(document).ready(function(){

    var $image = $(".image-crop > img"),
        $dataX = $("#dataX"),
        $dataY = $("#dataY"),
        $dataHeight = $("#dataHeight"),
        $dataWidth = $("#dataWidth");
    $($image).cropper({
        aspectRatio: $('#images-width').val()/$('#images-height').val(),
        preview: ".img-preview",
        data: {
            x: 480,
            y: 60,
            width: 640,
            height: 360
        },
        done: function(data) {
            data: {
                $dataX.val(Math.round(data.x));
                $dataY.val(Math.round(data.y));
                $dataHeight.val(Math.round(data.height));
                $dataWidth.val(Math.round(data.width));
            }
        }
    });

    var $inputImage = $("#inputImage");
    if (window.FileReader) {
        $inputImage.change(function() {
            var fileReader = new FileReader(),
                files = this.files,
                file;

            if (!files.length) {
                return;
            }

            file = files[0];

            if (/^image\/\w+$/.test(file.type)) {
                fileReader.readAsDataURL(file);
                fileReader.onload = function () {
                    $inputImage.val("");
                    $image.cropper("reset", true).cropper("replace", this.result);
                };
            } else {
                showMessage("Please choose an image file.");
            }
        });
    } else {
        $inputImage.addClass("hide");
    }

    $("#download").click(function() {
        window.open($image.cropper("getDataURL"));
    });

    $("#zoomIn").click(function() {
        $image.cropper("zoom", 0.1);
    });

    $("#zoomOut").click(function() {
        $image.cropper("zoom", -0.1);

    });

    $("#rotateLeft").click(function() {
        $image.cropper("rotate", 45);
        console.log($image.cropper("getDataURL"));
        var elem = document.getElementById('image-data');
        elem.value =$image.cropper("getDataURL");
    });

    $("#rotateRight").click(function() {
        $image.cropper("rotate", -45);
        console.log($image.cropper("getDataURL"));
        var elem = document.getElementById('image-data');
        elem.value =$image.cropper("getDataURL");
    });

    $("#setDrag").click(function() {
        $image.cropper("setDragMode", "crop");
    });


});
function approvedFilm() {
    var keys = $('#ajax_gridview').yiiGridView('getSelectedRows');
    var action = 'film/approveds.html';
    var data = {'keys':keys,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'json',
        type : 'POST',
        data : data,
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(json) {
            $('.wait').hide();
            if (json.status == 1) {
                jAlert(json.message, NOTICE);
                location.reload()
            } else {
                alert(json.message);
            }
        }
    });
}
/*-------------------------------------------- END Crop image----------------------------------------*/
/*-------------------------------------------- BEGIN serach suggestion----------------------------------------*/
function addtosearchsugget(type,content,action) {
    var csrfToken = $('#csrf-token').val();
    var data = {'type' : type, 'content' : content, 'YII_CSRF_TOKEN' : csrfToken};
    var msg = 'Thêm "'+content+'" vào search suggestion ?';
    jConfirm(
        msg,
        CONFIRM,
        function (r) {
            if (r == true) {
                $.ajax({
                    url :   CMS_HOST_PATH + action,
                    dataType : 'json',
                    type : 'POST',
                    data : data,
                    'beforeSend':function() {
                        $('.wait').show();
                    },
                    'success' : function(json) {
                        jAlert(json.message, NOTICE);
                        if (json.status == true) {
                            $('.wait').hide();
                            $('#addtosearchsugget_'+type).hide();

                        }
                    }
                });
            }
        }
    );
}
/*-------------------------------------------- END serach suggestion----------------------------------------*/
/*-------------------------------------------- BEGIN ----------------------------------------*/
function resetNotify(id) {
    var action = 'user-notify/popup.html';
    var type = 'resetNotify';
    var data = {'id' : id,'type':type,'action':action,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'html',
        type : 'POST',
        data : data,
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(html) {
            $('#myModal2').remove();
            $('.wait').hide();
            document.getElementById('page-wrapper').innerHTML += html;
            $('#myModal2').modal('show');
        }
    });
}

function onchangeTypeMenu(type) {
    var action = 'menu/get-select-box.html';
    var val = 'type';
    var data = {'type' : type,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'html',
        type : 'GET',
        data : data,
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(html) {
            $('.wait').hide();
            if (html != '') {
                //removeOptions(document.getElementById("menu-parent_id"));
                $('.field-menu-parent_id').html(html);
            }
        }
    });
}
function rePush(id,os) {
    var action = 'user-notify/re-push.html';
    var type = 'rePush';
    var data = {'id' : id,'os':os,'type':type,'action':action,'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'json',
        type : 'POST',
        data : data,
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(json) {
            $('.wait').hide();
            jAlert(json.message, NOTICE);
            if (json.status == 1) {
                $('#' + os).hide();
            }
        }
    });
}
/*-------------------------------------------- END serach suggestion----------------------------------------*/


function updateLiveTime(elementId){
    var action = 'collection-content/update-time.html';
    var element = $('#'+elementId);
    var content_id = element.attr('data-content_id');
    var collection_id = element.attr('data-collection_id');
    var value = element.val();
    var data = {
        'content_id' : content_id,
        'collection_id':collection_id,
        'value':value,
        'YII_CSRF_TOKEN' : YII_CSRF_TOKEN
    };

    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'json',
        type : 'POST',
        data : data,
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(json) {
            $('.wait').hide();
            if (json.status == 1) {
                jAlert(json.message, NOTICE);
                location.reload()
            } else {
                alert(json.message);
            }
        }
    });
}

function copyToClipboard() {
    var elem = document.getElementById("copyTarget");
    // create hidden text element, if it doesn't already exist
    var targetId = "_hiddenCopyText_";
    var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
    var origSelectionStart, origSelectionEnd;
    if (isInput) {
        // can just use the original source element for the selection and copy
        target = elem;
        origSelectionStart = elem.selectionStart;
        origSelectionEnd = elem.selectionEnd;
    } else {
        // must use a temporary form element for the selection and copy
        target = document.getElementById(targetId);
        if (!target) {
            var target = document.createElement("textarea");
            target.style.position = "absolute";
            target.style.left = "-9999px";
            target.style.top = "0";
            target.id = targetId;
            document.body.appendChild(target);
        }
        target.textContent = elem.textContent;
    }
    // select the content
    var currentFocus = document.activeElement;
    target.focus();
    target.setSelectionRange(0, target.value.length);

    // copy the selection
    var succeed;
    try {
        succeed = document.execCommand("copy");
    } catch(e) {
        succeed = false;
    }
    // restore original focus
    if (currentFocus && typeof currentFocus.focus === "function") {
        currentFocus.focus();
    }

    if (isInput) {
        // restore prior selection
        elem.setSelectionRange(origSelectionStart, origSelectionEnd);
    } else {
        // clear temporary content
        target.textContent = "";
    }
    if (succeed){
        $('.copy-notice').text('copied').show();
        setTimeout(function(){$('.copy-notice').hide();}, 1500);
    }
    return succeed;
}

function getCheckedItemsPopup()
{
    var s = [];
    var c = false;
    $("#list-collection-grid-view input[name *= 'selection[]']").each(function() {
        if (this.checked) {
            s.push(this.value);

            var response = $.parseJSON(this.value);
            if (typeof response == 'object') {
                c = true;
            }
        }
    });
    if(c)
        return s;
    else
        return s.join(",");
}

function showPopupAdd(url, id) {
    var action = url;
    var data = {'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'html',
        type : 'POST',
        data : {'data':data,'id':id},
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(html1) {
            $('#myModal2').remove();
            $('.wait').hide();
            $('#page-wrapper-1').eq(0).html(html1);
            $('#myModal2').modal('show');
            $(".modal-backdrop.in").hide();
        }
    });
}
function showPopupNews(url, data) {
    var data = {'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url :   CMS_HOST_PATH + url + '.html',
        dataType : 'html',
        type : 'POST',
        data : {'data':data},
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(html1) {
            $('#myModal2').remove();
            $('.wait').hide();
            $('#page-wrapper-1').eq(0).html(html1);
            $('#myModal2').modal('show');
            $(".modal-backdrop.in").hide();
        }
    });
}
function addNewsToCollection(collectionId){
    var data = getCheckedItemsPopup();
    $.ajax({
        url :   CMS_HOST_PATH + 'collection/add-news.html',
        dataType : 'json',
        type : 'POST',
        data : {'data':data,'collectionId':collectionId},
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(json) {
            $('.wait').hide();
            if (json.status == 1) {
                jAlert(json.message, NOTICE);
                location.reload()
            } else {
                $('#myModal2').modal('show');
                alert(json.message);
            }
        }
    });
}
function deleteNewsToCollection(url, collectionId) {
    var data = getCheckedItemsPopup();
    var r = confirm('Bạn có chắc chắn muốn xóa!')
    if(!r) return;
    $.ajax({
        url :   CMS_HOST_PATH + url,
        dataType : 'json',
        type : 'POST',
        data : {'data':data,'collectionId':collectionId},
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(json) {
            $('.wait').hide();
            if (json.status == 1) {
                jAlert(json.message, NOTICE);
                location.reload()
            } else {
                $('#myModal2').modal('show');
                alert(json.message);
            }
        }
    });
}
function showListNews(url, id){

    var action = 'collection/popup-list.html';
    var data = {'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
    $.ajax({
        url :   CMS_HOST_PATH + action,
        dataType : 'html',
        type : 'POST',
        data : {'data':data,'id':id},
        'beforeSend':function() {
            $('.wait').show();
        },
        'success' : function(html1) {
            $('#myModal2').remove();
            $('.wait').hide();
            $('#page-wrapper-1').eq(0).html(html1);
            $('#myModal2').modal('show');
            $(".modal-backdrop.in").hide();
        }
    });
}
$(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

    if($('#areaChart').length){
        // Get context with jQuery - using jQuery's .get() method.
        var areaChartCanvas = $('#areaChart').get(0).getContext('2d')
        // This will get the first returned node in the jQuery collection.
        var areaChart       = new Chart(areaChartCanvas)

        var areaChartData = {
            labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [
                {
                    label               : 'Electronics',
                    fillColor           : 'rgba(210, 214, 222, 1)',
                    strokeColor         : 'rgba(210, 214, 222, 1)',
                    pointColor          : 'rgba(210, 214, 222, 1)',
                    pointStrokeColor    : '#c1c7d1',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(220,220,220,1)',
                    data                : [65, 59, 80, 81, 56, 55, 40]
                },
                {
                    label               : 'Digital Goods',
                    fillColor           : 'rgba(60,141,188,0.9)',
                    strokeColor         : 'rgba(60,141,188,0.8)',
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(60,141,188,1)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data                : [28, 48, 40, 19, 86, 27, 90]
                }
            ]
        }



        var areaChartOptions = {
            //Boolean - If we should show the scale at all
            showScale               : true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines      : false,
            //String - Colour of the grid lines
            scaleGridLineColor      : 'rgba(0,0,0,.05)',
            //Number - Width of the grid lines
            scaleGridLineWidth      : 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines  : true,
            //Boolean - Whether the line is curved between points
            bezierCurve             : true,
            //Number - Tension of the bezier curve between points
            bezierCurveTension      : 0.3,
            //Boolean - Whether to show a dot for each point
            pointDot                : false,
            //Number - Radius of each point dot in pixels
            pointDotRadius          : 4,
            //Number - Pixel width of point dot stroke
            pointDotStrokeWidth     : 1,
            //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
            pointHitDetectionRadius : 20,
            //Boolean - Whether to show a stroke for datasets
            datasetStroke           : true,
            //Number - Pixel width of dataset stroke
            datasetStrokeWidth      : 2,
            //Boolean - Whether to fill the dataset with a color
            datasetFill             : true,
            //String - A legend template
            legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
            //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio     : true,
            //Boolean - whether to make the chart responsive to window resizing
            responsive              : true
        }

        //Create the line chart
        areaChart.Line(areaChartData, areaChartOptions)

        //-------------
        //- LINE CHART -
        //--------------
        var lineChartCanvas          = $('#lineChart').get(0).getContext('2d')
        var lineChart                = new Chart(lineChartCanvas)
        var lineChartOptions         = areaChartOptions
        lineChartOptions.datasetFill = false
        lineChart.Line(areaChartData, lineChartOptions)

        //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
        var pieChart       = new Chart(pieChartCanvas)
        var PieData        = [
            {
                value    : 700,
                color    : '#f56954',
                highlight: '#f56954',
                label    : 'Chrome'
            },
            {
                value    : 500,
                color    : '#00a65a',
                highlight: '#00a65a',
                label    : 'IE'
            },
            {
                value    : 400,
                color    : '#f39c12',
                highlight: '#f39c12',
                label    : 'FireFox'
            },
            {
                value    : 600,
                color    : '#00c0ef',
                highlight: '#00c0ef',
                label    : 'Safari'
            },
            {
                value    : 300,
                color    : '#3c8dbc',
                highlight: '#3c8dbc',
                label    : 'Opera'
            },
            {
                value    : 100,
                color    : '#d2d6de',
                highlight: '#d2d6de',
                label    : 'Navigator'
            }
        ]
        var pieOptions     = {
            //Boolean - Whether we should show a stroke on each segment
            segmentShowStroke    : true,
            //String - The colour of each segment stroke
            segmentStrokeColor   : '#fff',
            //Number - The width of each segment stroke
            segmentStrokeWidth   : 2,
            //Number - The percentage of the chart that we cut out of the middle
            percentageInnerCutout: 50, // This is 0 for Pie charts
            //Number - Amount of animation steps
            animationSteps       : 100,
            //String - Animation easing effect
            animationEasing      : 'easeOutBounce',
            //Boolean - Whether we animate the rotation of the Doughnut
            animateRotate        : true,
            //Boolean - Whether we animate scaling the Doughnut from the centre
            animateScale         : false,
            //Boolean - whether to make the chart responsive to window resizing
            responsive           : true,
            // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio  : true,
            //String - A legend template
            legendTemplate       : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        pieChart.Doughnut(PieData, pieOptions)

        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas                   = $('#barChart').get(0).getContext('2d')
        var barChart                         = new Chart(barChartCanvas)
        var barChartData                     = areaChartData
        barChartData.datasets[1].fillColor   = '#00a65a'
        barChartData.datasets[1].strokeColor = '#00a65a'
        barChartData.datasets[1].pointColor  = '#00a65a'
        var barChartOptions                  = {
            //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
            scaleBeginAtZero        : true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines      : true,
            //String - Colour of the grid lines
            scaleGridLineColor      : 'rgba(0,0,0,.05)',
            //Number - Width of the grid lines
            scaleGridLineWidth      : 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines  : true,
            //Boolean - If there is a stroke on each bar
            barShowStroke           : true,
            //Number - Pixel width of the bar stroke
            barStrokeWidth          : 2,
            //Number - Spacing between each of the X value sets
            barValueSpacing         : 5,
            //Number - Spacing between data sets within X values
            barDatasetSpacing       : 1,
            //String - A legend template
            legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
            //Boolean - whether to make the chart responsive
            responsive              : true,
            maintainAspectRatio     : true
        }

        barChartOptions.datasetFill = false
        barChart.Bar(barChartData, barChartOptions)

    }
  })