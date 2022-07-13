<?php
use yii\bootstrap\Modal;
use common\components\Utility;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use common\components\CFunction;
use yii\helpers\ArrayHelper;


$title=Yii::t('cms','List Category');

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
                        return $data['id'];
                    },
                ],
                [
                    'attribute' => 'title',
                    'header' => Yii::t('cms', 'title'),
                    'format' => 'raw',
                    'contentOptions'=>['style'=>'vertical-align: middle;'],
                    'value' => function($data) {
                        if(empty($data['level'])) {
                            return  $data['title'];
                        }
                        return '<div class="menu-category-'.$data['level'].'"><span>'.Html::encode($data['title']).'</span></div>';
                    },
                ],
            ],
            'rowOptions'=>function($model, $key, $index, $grid){
                $class=$index%2?'odd':'even';
                return array('id'=>'f'.$model['id']);
            },
            'tableOptions'=>['class'=>'table table-striped table-bordered','id'=>'film_list_gridview'],

        ]); ?>
    </div>
    </div>
    <div class="modal-footer">
        <a class="btn btn-outline-primary" href="javascript:void(0)" onclick="addItem();closeModal();" ><?php echo Yii::t('cms','Save') ?></a>
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
        url = url.replace('/permission-category/popup.html', '/permission-category/popup-pagination.html')
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
        url = '/permission-category/popup-pagination.html?title=' + $('.txt-title').val();
        $.get(url)
            .done(function (data) {
                $("#grid-ajax-news").html(data);
            })
            .fail(function () {
                console.log("Ajax fail: ");
            });
        return false;
    });
    function addItem() {
        var item = getCheckedItems();
        var data = {'YII_CSRF_TOKEN' : YII_CSRF_TOKEN};
        $.ajax({
            url :   CMS_HOST_PATH + 'permission-category/add-item.html',
            dataType : 'html',
            type : 'POST',
            data : {'data':data, 'ids':item, 'accountId': <?php echo $accountId ?>},
            'beforeSend':function() {
                $('.wait').show();
            },
            'success' : function(res) {
                res = JSON.parse(res)
                closeModal();
                jAlert(res.msg, NOTICE);
            }
        });
    }

    function closeModal() {
        $('#close-popup').click();
    }
</script>