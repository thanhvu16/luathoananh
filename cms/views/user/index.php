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


$this->title = Yii::t('cms', 'Quản lý Danh sách yêu cầu');
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'Quản lý Danh sách yêu cầu'),
        'url' => ['index']
    ],
    [
        'label' => Yii::t('cms', 'app_list'),
        'template' => "<li>{link}</li>\n"
    ]
];

$this->params['title'] = Html::encode(Yii::t('cms', 'Quản lý Danh sách yêu cầu'));
?>

<div class="box-body">
    <?= GridView::widget([
        'id'=>'ajax_gridview',
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'grid-view table-responsive'],
        'columns' => [
            'fullname',
            'email',
            'phone',
            'note',
            [
                'attribute' => 'file',
                'format' => 'raw',
                'options' => ['width' => '80px'],
                'filter' => false,
                'value' => function ($data) {
					if(!empty($data->file)){
						return '<a target="_blank" href="'.$data->file.'">'.$data->file.'</a>';
					}
					return '';
                },
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;']
            ],
            'created_time',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'header' => Yii::t('cms', 'action'),
                'options' => ['width' => '130px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;'],
                'buttons' => [
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