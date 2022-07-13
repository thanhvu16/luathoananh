<?php
use yii\bootstrap\Modal;
use common\components\Utility;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use common\components\CFunction;


$title=Yii::t('cms','List News');

Modal::begin([
    'id' => 'myModal2',
    'header' => '<h4 class="modal-title">'.$title.'</b></h4>',
]);
?>
    <div class="modal-body no-padding">

        <div class="col-lg-12" style="margin-top: 20px">
            <?php echo Html::input('text', 'title',  isset($_REQUEST['title']) ? ArrayHelper::getValue($_REQUEST, 'title', null) : null, ['class' => 'form-control txt-title', 'placeholder'=>Yii::t('cms', 'Nhập title')]); ?>
            <div style="margin-top: 10px">
                <?php echo Html::submitButton('<span class="fa fa-search"></span> '.Yii::t('cms', 'search'), ['class'=>'btn btn-outline-primary', 'id' => 'btn-search-popup']); ?>
            </div>
        </div>
        <?php Pjax::begin(); ?>
        <div id="grid-ajax-news">
            <?= GridView::widget([
                'id' => 'list-collection-grid-view',
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
                            $imagesUrl =Utility::makeImgNews($data,'news_img_options_base');
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
        <?php Pjax::end(); ?>
    </div>
    <div class="modal-footer">
        <a id="clear_trailer" class="btn btn-outline-primary" href="javascript:void(0)" onclick="addNewsToCollection(<?php echo $collectionId ?>)" ><?php echo Yii::t('cms','Save') ?></a>
        <button type="button" class="btn btn-outline-primary" data-dismiss="modal"><?php echo Yii::t('cms','close_popup') ?></button>
    </div>


<?php
Modal::end();
?>

    <script>
      $(document).on("click", ".pagination a", function(e){

        e.preventDefault();

        e.stopPropagation();

        var url = $(this).attr("href")
        url = url.replace('/collection/popup.html', '/collection/popup-pagination.html')
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
        url = '/collection/popup-pagination.html?title=' + $('.txt-title').val();
        $.get(url)
          .done(function (data) {
            $("#grid-ajax-news").html(data);
          })
          .fail(function () {
            console.log("Ajax fail: ");
          });
        return false;
      });
    </script>


<?php
