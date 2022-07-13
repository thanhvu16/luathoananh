<?php
use yii\bootstrap\Modal;
use common\components\Utility;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use common\components\CFunction;
use yii\helpers\ArrayHelper;


$title=Yii::t('cms','List News');

Modal::begin([
    'id' => 'myModal2',
    'header' => '<h4 class="modal-title">'.$title.'</b></h4>',
]);
?>

    <div class="col-lg-12" style="margin-top: 20px">
            <?php echo Html::input('text', 'title',  isset($_REQUEST['title']) ? ArrayHelper::getValue($_REQUEST, 'title', null) : null, ['class' => 'form-control txt-title', 'placeholder'=>Yii::t('cms', 'Nhập title')]); ?>
            <div style="margin-top: 10px">
            <?php echo Html::submitButton('<span class="fa fa-search"></span> '.Yii::t('cms', 'search'), ['class'=>'btn btn-outline-primary', 'id' => 'btn-search-popup']); ?>
            </div>
    </div>
    <div class="modal-body no-padding">
        <div id="grid-ajax-news">
        <?= GridView::widget([
            'id' => 'list-collection-grid-view1',
            'dataProvider' => $data,
            'pager' => [
                'prevPageLabel' => '&larr; Previous',
                'nextPageLabel' => '&rarr; Next'
            ],
            //$layout = "{summary}\n{items}\n{pager}",
            'columns' => [
                [
                    'class' => 'yii\grid\CheckboxColumn',
                    'options' => ['width' => '40px'],
                    'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                    'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;']
                ],
                [
                    'attribute'=>'id',
                    'header' =>Yii::t('cms','ID'),
                    'format' =>'raw',
                    'value' => function($data){
                        return $data->id;
                    },
                ],
                [
                    'attribute' => 'title',
                    'header' => Yii::t('cms', 'title'),
                    'format' => 'raw',
                    'options' => ['width' => '180px'],
                    'contentOptions'=>['style'=>'vertical-align: middle;'],
                ],
                [
                    'header' => Yii::t('cms', 'image_category'),
                    'format' => 'raw',
                    'options' => ['width' => '80px'],
                    'headerOptions' => ['style'=>'text-align: center;'],
                    'contentOptions'=>['style'=>'text-align: center; vertical-align: middle;'],
                    'value' => function($data) {
                        $imagesUrl =Utility::makeImgNews($data,'news_img_options_large');
                        return ($imagesUrl) ? Html::img($imagesUrl,['height'=>'60', 'title' => $data->{'title'}]) : null;
                    }
                ],
                [
                    'attribute' => 'news_category_id',
                    'header' => Yii::t('cms', 'category'),
                    'value' => function ($data) {
                        return ($data->category)?$data->category->{'title'}:null;
                    },
                    'format' => 'raw',
                    'options' => ['width' => '100px'],
                    'headerOptions' => ['style'=>'text-align: center;'],
                    'contentOptions'=>['style'=>'text-align: center; vertical-align: middle;']
                ],
                [
                    'attribute' => 'active',
                    'header' => Yii::t('cms', 'status'),
                    'format' => 'raw',
                    'options' => ['width' => '80px'],
                    'value' => function ($data) {
                        if ($data->status == 1) {
                            return '<span id="item-active-status-'.$data->id.'">'.Html::img(CFunction::getImageBaseUrl().'app/icon-32-check.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_inactive'), 'onclick' => 'changeActive('.$data->id.', 1,"news/change-status")']).'</span>';
                        } else {
                            return '<span id="item-active-status-'.$data->id.'">'.Html::img(CFunction::getImageBaseUrl().'app/icon-32-stop.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_active'), 'onclick' => 'changeActive('.$data->id.', 0,"news/change-status")']).'</span>';
                        }
                    },
                    'headerOptions' => ['style'=>'text-align: center;'],
                    'contentOptions'=>['style'=>'text-align: center; vertical-align: middle;']
                ],
            ],
            'rowOptions'=>function($model, $key, $index, $grid){
                $class=$index%2?'odd':'even';
                return array('id'=>'f'.$model->id);
            },
            'tableOptions'=>['class'=>'table table-striped table-bordered','id'=>'film_list_gridview'],

        ]); ?>
    </div>
    </div>
    <div class="modal-footer">
        <a class="btn btn-outline-primary" href="javascript:void(0)" onclick="addItemNewsRel();closeModal();" ><?php echo Yii::t('cms','Save') ?></a>
        <button id="close-popup" type="button" class="btn btn-outline-primary" data-dismiss="modal"><?php echo Yii::t('cms','close_popup') ?></button>
    </div>

<?php
Modal::end();
?>

<script>
    $(document).on("click", ".pagination a", function(e){

        e.preventDefault();

        e.stopPropagation();

        var url = $(this).attr("href")
        url = url.replace('/news/popup.html', '/news/popup-pagination.html')
        $.get(url)

            .done(function (data) {
                $("#grid-ajax-news").html(data);

            })

            .fail(function () {

                console.log("Ajax fail: ");

            });
        return false;

    });

    $(document).on("click", "#btn-search-popup", function(e){
        e.preventDefault();
        e.stopPropagation();
        url = '/news/popup-pagination.html?title=' + $('.txt-title').val();
        $.get(url)
            .done(function (data) {
                $("#grid-ajax-news").html(data);
            })
            .fail(function () {
                console.log("Ajax fail: ");
            });
        return false;
    });
    function addItemNewsRel() {
        var item = getCheckedItems();
        var data = {'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
        $.ajax({
            url :   CMS_HOST_PATH + 'news/get-list-news-select.html',
            dataType : 'html',
            type : 'POST',
            data : {'data':data, 'ids':item},
            'beforeSend':function() {
                $('.wait').show();
            },
            'success' : function(res) {
                res = JSON.parse(res);
                for(var i= 0;i<res.length;i++){
                    var flag = true;
                    $('.ip_rel').each(function() {
                        if(res[i]["id"] == $(this).val()) {
                            flag = false;
                        }
                    })
                    if(flag) {
                        var str = '<li style="padding: 5px;margin:5px;background: #cccccc;position: relative"><input class="ip_rel" name="rel_ids[]"  type="hidden" value="' + res[i]["id"] + '">' + res[i]["title"] + '<span class="fa fa-remove" onclick="removeItemRel(this)" style="color: red;cursor:pointer;position: absolute;top: -6px;right: -4px"></span></li>';
                        $('.frm-rel').append(str);
                    }
                }
            }
        });
    }

    function closeModal() {
        $('#close-popup').click();
    }
</script>