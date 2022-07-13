<?php
use yii\helpers\Html;
use yii\grid\GridView;
use common\components\CFunction;
use common\components\Utility;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use cms\models\NewsCategory;
use yii\helpers\Url;
use kartik\daterange\DateRangePicker;


$this->title = Yii::t('cms', 'Sender List');
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'Sender List'),
        'url' => ['index']
    ],
    [
        'label' => Yii::t('cms', 'app_list'),
        'template' => "<li>{link}</li>\n"
    ]
];

$this->params['title'] = Html::encode(Yii::t('cms', 'Sender List'));
?>
<div class="box-header with-border" style="margin-top: 20px">

    <ul class="btn btn-outline-primary"><li class="dropdown">
            <span class="dropdown-toggle" data-toggle="dropdown"><?php echo Yii::t('cms', 'action'); ?>&nbsp;<b class="caret"></b></span>
            <ul class="dropdown-menu">
                <li class="dropdown-header no-padding"><a href="javascript:void(0)" onclick="deleteAllItems('sender/delete-multi', 'sender/index');"  >Xóa</a></li>
            </ul>
    </ul>
    <?= Html::a('<span class="fa fa-plus"></span> '.Yii::t('cms', 'app_create'), ['create'], ['class' => 'btn btn-outline-primary']) ?>
</div>

<div class="box-body">
<?= GridView::widget([
    'id'=>'ajax_gridview',
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'class' => 'yii\grid\CheckboxColumn',
            'options' => ['width' => '40px'],
            'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
            'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;']
        ],
        'name',
        'code',
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
