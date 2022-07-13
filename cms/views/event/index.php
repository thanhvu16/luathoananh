<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\components\CFunction;
use cms\models\Admin;

$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'Gamification'),
        'url' => ['index']
    ],
    [
        'label' => Yii::t('cms', 'mnu_event'),
        'template' => "<li>{link}</li>\n"
    ],
    [
        'label' => Yii::t('cms', 'app_list'),
        'template' => "<li>{link}</li>\n"
    ]
];
$this->params['title'] = Html::encode(Yii::t('cms', 'app_list'));
$this->params['menu'] = [
    ['label'=>Yii::t('cms', 'app_create'), 'url' => ['create'], 'options' => ['class' => 'btn btn-outline-primary']],
    ['label'=>Yii::t('cms', 'app_delete'), 'url' => 'javascript:void(0)', 'options' => ['class' => 'btn btn-outline-primary', 'onclick' => 'deleteAllItems(\'event/delete-all\', \'event/index\')']]
];
?>
<?php Pjax::begin(['enablePushState' => false]); ?>
    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'options' => ['width' => '40px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;']
            ],
            [
                'attribute' => 'name',
                'filter' => true,
                'options' => ['width' => '300px'],
            ],
            [
                'attribute' => 'description',
                'filter' => false,
                'options' => ['width' => '400px'],
            ],
            [
                'attribute' => 'status',
                'header' => Yii::t('cms', 'status'),
                'filter' => ArrayHelper::map([
                    ['value' => 1, 'status' => 'ACTIVE'],
                    ['value' => 0, 'status' => 'INACTIVE']
                ], 'value', 'status'),
                'format' => 'raw',
                'options' => ['width' => '110px'],
                'value' => function ($data) {
                    if ($data->status == 1) {
                        return '<span id="item-active-status-'.$data->id.'">'.Html::img(CFunction::getImageBaseUrl().'app/icon-32-check.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_inactive'), 'onclick' => 'changeStatusItems('.$data->id.', 1, \'event-group/change-status\')']).'</span>';
                    } else {
                        return '<span id="item-active-status-'.$data->id.'">'.Html::img(CFunction::getImageBaseUrl().'app/icon-32-stop.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_active'), 'onclick' => 'changeStatusItems('.$data->id.', 0, \'event-group/change-status\')']).'</span>';
                    }
                },
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;']
            ],
            [
				'header' => 'Nhóm sự kiện',
                'value' => function ($data) {
					return $data->eventGroup->name;
				},
                'filter' => false,
                'options' => ['width' => '200px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;']
            ],
            [
                'attribute' => 'point',
                'filter' => false,
                'options' => ['width' => '80px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;']
            ],
            [
                'attribute' => 'reset',
                'filter' => false,
                'options' => ['width' => '80px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;']
            ],
            [
                'attribute' => 'created_time',
                'filter' => false,
                'options' => ['width' => '200px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;']
            ],
            [
                'attribute' => 'created_by',
                'value' => function ($data) {
                    return Html::encode(Admin::getAdminNameByID($data->created_by));
                },
                'filter' => false,
                'options' => ['width' => '180px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}{delete}',
                'header' => Yii::t('cms', 'action'),
                'options' => ['width' => '168px'],
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
                    }
                ]
            ]
        ]
    ]); ?>
<?php Pjax::end(); ?>