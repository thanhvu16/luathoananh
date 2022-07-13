<?php
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'mnu_admin_user'),
        'url' => ['admin']
    ],
    [
        'label' => Yii::t('cms', 'mnu_admin_log'),
        'template' => "<li>{link}</li>\n"
    ]
];
$this->params['menu'] = [
    ['label' => Yii::t('cms', 'app_list'), 'url' => ['admin'], 'options' => ['class' => 'btn btn-outline-primary']]
];
$this->title = Yii::$app->name.' - '.Yii::t('cms', 'mnu_admin_log');
$this->params['title'] = \yii\helpers\Html::encode(Yii::t('cms', 'mnu_admin_log'));
?>
<?php Pjax::begin(['enablePushState' => false]); ?>
<?php echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'attribute' => 'admin_username',
            'label' => Yii::t('cms', 'admin_username'),
            'filter' => true,
            'headerOptions' => ['style'=>'text-align: center;'],
            'contentOptions'=>['style'=>'text-align: center;']
        ],
        [
            'attribute' => 'controller',
            'filter' => true,
            'headerOptions' => ['style'=>'text-align: center;'],
            'contentOptions'=>['style'=>'text-align: center;']
        ],
        [
            'attribute' => 'action',
            'filter' => true,
            'headerOptions' => ['style'=>'text-align: center;'],
            'contentOptions'=>['style'=>'text-align: center;']
        ],
        [
            'attribute' => 'object_name',
            'label' => Yii::t('cms', 'object_name'),
            'filter' => true,
            'headerOptions' => ['style'=>'text-align: center;'],
            'options' => ['width' => '320px']
        ],
        [
            'attribute' => 'log_time',
            'label' => Yii::t('cms', 'log_time'),
            'filter' => \yii\jui\DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'log_time',
                'dateFormat' => 'yyyy-MM-dd',
                'clientOptions' => [
                    'maxDate' => date('Y-m-d H:i:s', time()),
                ],
                'options' => [
                    'class' => 'form-control'
                ]
            ]),
            'headerOptions' => ['style'=>'text-align: center;'],
            'contentOptions'=>['style'=>'text-align: center;']
        ]
    ]
]); ?>
<?php Pjax::end(); ?>