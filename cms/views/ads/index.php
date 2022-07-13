<?php
use yii\helpers\Html;
use yii\grid\GridView;
use common\components\CFunction;
use common\components\Utility;
use cms\models\NewsCategory;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\daterange\DateRangePicker;


$this->title = Yii::t('cms', 'Quản lý Quảng cáo');
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'Quản lý Quảng cáo'),
        'url' => ['index']
    ],
    [
        'label' => Yii::t('cms', 'app_list'),
        'template' => "<li>{link}</li>\n"
    ]
];

$this->params['title'] = Html::encode(Yii::t('cms', 'Quản lý Quảng cáo'));
$this->params['menu'] = [
    ['label'=>Yii::t('cms', 'app_create'), 'url' => ['create'], 'options' => ['class' => 'btn btn-outline-primary']],
];
$this->params['title'] = Html::encode(Yii::t('cms', 'Quản lý Quảng cáo'));
?>

<div class="box-body">
    <?= GridView::widget([
        'id'=>'ajax_gridview',
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'grid-view table-responsive'],
        'columns' => [
            'name',
            'url',
			[
				'header' => Yii::t('cms', 'Ảnh'),
				'format' => 'raw',
				'options' => ['width' => '80px'],
				'headerOptions' => ['style'=>'text-align: center;'],
				'contentOptions'=>['style'=>'text-align: center; vertical-align: middle;'],
				'value' => function($data) {
					$imagesUrl =$data->image;
					return ($imagesUrl) ? Html::img($imagesUrl,['height'=>'60', 'title' => $data->{'name'}]) : null;
				}
			],
            'updated_time',
            [
                'attribute' => 'active',
                'format' => 'raw',
                'options' => ['width' => '80px'],
                'filter' => false,
                'value' => function ($data) {
                    if ($data['status'] == 1) {
                        return '<span id="item-active-status-'.$data['id'].'">'.Html::img(CFunction::getImageBaseUrl().'app/icon-32-check.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_inactive'), 'onclick' => 'changeStatusItems('.$data['id'].', 1, \'ads/change-status\')']).'</span>';
                    } else {
                        return '<span id="item-active-status-'.$data['id'].'">'.Html::img(CFunction::getImageBaseUrl().'app/icon-32-stop.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_active'), 'onclick' => 'changeStatusItems('.$data['id'].', 0, \'ads/change-status\')']).'</span>';
                    }
                },
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}{delete}',
                'header' => Yii::t('cms', 'action'),
                'options' => ['width' => '130px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;'],
                'buttons' => [
                    'view' => function ($url) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('cms', 'view'),
                            'class'=>'btn btn-outline-primary btn-xs btn-app',
                            'data-pjax' => '0',
                        ]);
                    },
                    'update' => function ($url) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('cms', 'update'),
                            'class'=>'btn btn-outline-primary btn-xs btn-app',
                            'data-pjax' => '0',
                        ]);
                    },
                    'delete' => function ($url) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('cms', 'delete'),
                            'class'=>'btn btn-outline-primary btn-xs btn-app',
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => 'w0'
                        ]);
                    },
                ]
            ]
        ],
    ]); ?>
</div>

<style>
    .select2-container{
        width: 100% !important;
    }
</style>