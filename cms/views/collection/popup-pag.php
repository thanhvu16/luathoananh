<?php
use yii\bootstrap\Modal;
use common\components\Utility;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use common\components\CFunction;
?>

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
