<?php

use yii\bootstrap\Modal;
use common\components\Utility;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use common\components\CFunction;


$this->title = Yii::t('cms', 'List News');
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'Collection'),
        'url' => ['index']
    ],
    [
        'label' => Yii::t('cms', 'app_list'),
        'template' => "<li>{link}</li>\n"
    ]
];

$this->params['title'] = Html::encode(Yii::t('cms', 'Collection'));
?>
<div class="box-header with-border" style="margin-top: 20px">
    <a class="btn btn-outline-primary" onclick="deleteNewsToCollection(<?php echo $collectionId ?>)">Delete</a>
</div>
<div class="box-body">

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'id' => 'list-collection-grid-view',
        'dataProvider' => $data,
        'layout' => "{summary}\n{items}",
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'options' => ['width' => '40px'],
                'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;']
            ],
            [
                'attribute' => 'id',
                'header' => Yii::t('cms', 'ID'),
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->id;
                },
            ],
            [
                'attribute' => 'title',
                'header' => Yii::t('cms', 'title'),
                'format' => 'raw',
                'options' => ['width' => '180px'],
                'contentOptions' => ['style' => 'vertical-align: middle;'],
            ],
            [
                'header' => Yii::t('cms', 'image_category'),
                'format' => 'raw',
                'options' => ['width' => '80px'],
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'value' => function ($data) {
                    $imagesUrl = Utility::makeImgNews($data, 'news_img_options_large');
                    return ($imagesUrl) ? Html::img($imagesUrl, ['height' => '60', 'title' => $data->{'title'}]) : null;
                }
            ],
            [
                'attribute' => 'news_category_id',
                'header' => Yii::t('cms', 'category'),
                'value' => function ($data) {
                    return ($data->category) ? $data->category->{'title'} : null;
                },
                'format' => 'raw',
                'options' => ['width' => '100px'],
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;']
            ],
        ],
        'rowOptions' => function ($model, $key, $index, $grid) {
            $class = $index % 2 ? 'odd' : 'even';
            return array('id' => 'f' . $model->id);
        },
        'tableOptions' => ['class' => 'table table-striped table-bordered', 'id' => 'film_list_gridview'],

    ]); ?>
    <?php Pjax::end(); ?>
