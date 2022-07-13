<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\components\CFunction;
use cms\models\Admin;
use cms\models\AdminGroup;
use yii\widgets\DetailView;

$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'mnu_admin_group'),
        'url' => ['admin']
    ],
    [
        'label' => Yii::t('cms', 'permission'),
        'template' => "<li>{link}</li>\n"
    ]
];

$this->params['title'] = Html::encode(Yii::t('cms', 'group_permission'));
?>
<div class="box-header with-border">
    <?= Html::a('<i class="fa fa-list" aria-hidden="true"></i> '.Yii::t('cms', 'app_list'), ['admin'], ['class' => 'btn btn-outline-primary']) ?>
</div>
<div class="box-body">
<h4 style="padding-bottom: 6px;"><?php echo Yii::t('cms', 'app_detail'); ?></h4>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'group_name',
        'group_desc',
        [
            'label' => Yii::t('cms', 'status'),
            'value' => Html::encode(AdminGroup::getGroupStatusText($model->status))
        ],
        'created_time',
    ],
]) ?>
<h4 style="padding-bottom: 6px; margin-top: 10px;"><?php echo Yii::t('cms', 'list_user_group'); ?></h4>
<?php Pjax::begin(); ?>
<?php echo GridView::widget([
    'dataProvider' => $adminDataProvider,
    'id' => 'admin-grid',
    'columns' => [
        [
            'class' => 'yii\grid\CheckboxColumn',
            'options' => ['width' => '40px'],
            'headerOptions' => ['style'=>'text-align: center;'],
            'contentOptions'=>['style'=>'text-align: center;']
        ],
        [
            'attribute' => 'username',
            'filter' => true,
            'options' => ['width' => '220px'],
        ],
        [
            'attribute' => 'status',
            'filter' => ArrayHelper::map(Admin::getAdminStatusFilter(), 'value', 'status'),
            'format' => 'raw',
            'options' => ['width' => '120px'],
            'value' => function ($data) {
                if ($data->status == 1) {
                    return '<span id="item-active-status-'.$data->id.'">'.Html::img(CFunction::getImageBaseUrl().'app/icon-32-check.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_inactive'), 'onclick' => 'changeStatusItems('.$data->id.', 1, \'admin/change-status\')']).'</span>';
                } else {
                    return '<span id="item-active-status-'.$data->id.'">'.Html::img(CFunction::getImageBaseUrl().'app/icon-32-stop.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_active'), 'onclick' => 'changeStatusItems('.$data->id.', 0, \'admin/change-status\')']).'</span>';
                }
            },
            'headerOptions' => ['style'=>'text-align: center;'],
            'contentOptions'=>['style'=>'text-align: center;']
        ],
        [
            'attribute' => 'admin_group_id',
            'filter' => ArrayHelper::map(AdminGroup::find()->all(), 'id', 'group_name'),
            'value' => function ($data) {
                return ($data->group) ? $data->group->group_name:'';
                //return Html::encode(AdminGroup::getGroupNameByID($data->admin_group_id));
            },
            'options' => ['width' => '140px'],
            'headerOptions' => ['style'=>'text-align: center;'],
            'contentOptions'=>['style'=>'text-align: center;']
        ],
        [
            'attribute' => 'created_time',
            'filter' => false,
            'options' => ['width' => '140px'],
            'headerOptions' => ['style'=>'text-align: center;'],
            'contentOptions'=>['style'=>'text-align: center;']
        ],
        [
            'attribute' => 'created_by',
            'value' => function ($data) {
                return ($data->createdBy) ? $data->createdBy->username:'';
                //return Html::encode(Admin::getAdminNameByID($data->created_by));
            },
            'filter' => false,
            'options' => ['width' => '140px'],
            'headerOptions' => ['style'=>'text-align: center;'],
            'contentOptions'=>['style'=>'text-align: center;']
        ],
        [
            'attribute' => 'phonenumber',
            'filter' => false,
            'headerOptions' => ['style'=>'text-align: center;'],
            'contentOptions'=>['style'=>'text-align: center;']
        ],
        [
            'attribute' => 'email',
            'filter' => false,
            'headerOptions' => ['style'=>'text-align: center;'],
            'contentOptions'=>['style'=>'text-align: center;']
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}{update}{delete}{permission}',
            'header' => Yii::t('cms', 'action'),
            'options' => ['width' => '160px'],
            'headerOptions' => ['style'=>'text-align: center;'],
            'contentOptions'=>['style'=>'text-align: center;'],
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Yii::$app->urlManager->createUrl(['admin/view', 'id' => $model->id]), [
                        'title' => Yii::t('cms', 'view'),
                        'class'=>'btn btn-outline-primary btn-xs btn-app',
                        'data-pjax' => '0',
                    ]);
                },
                'update' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['admin/update', 'id' => $model->id]), [
                        'title' => Yii::t('cms', 'update'),
                        'class'=>'btn btn-outline-primary btn-xs btn-app',
                        'data-pjax' => '0',
                    ]);
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['admin/delete', 'id' => $model->id]), [
                        'title' => Yii::t('cms', 'delete'),
                        'class'=>'btn btn-outline-primary btn-xs btn-app',
                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'data-method' => 'post',
                        'data-pjax' => '0'
                    ]);
                },
                'permission' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-user"></span>', Yii::$app->urlManager->createUrl(['admin/permission', 'id' => $model->id]), [
                        'title' => Yii::t('cms', 'permission'),
                        'class'=>'btn btn-outline-primary btn-xs btn-app',
                        'data-pjax' => '0',
                    ]);
                },
            ]
        ]
    ]
]); ?>
<?php Pjax::end(); ?>
<?php echo Yii::$app->view->render('_form_permission', ['arrayDataProvider' => $arrayDataProvider, 'id' => $model->id]); ?>
</div>