<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\components\CFunction;
use cms\models\Admin;
use cms\models\AdminGroup;

$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'mnu_admin_user'),
        'url' => ['admin']
    ],
    [
        'label' => Yii::t('cms', 'app_list'),
        'template' => "<li>{link}</li>\n"
    ]
];
$this->params['title'] = Html::encode(Yii::t('cms', 'mnu_admin_user'));

?>
<div class="box-header with-border" style="margin-top: 20px">
        <div class="col-lg-3">
            <?= Html::a('<span class="fa fa-plus"></span> '.Yii::t('cms', 'app_create'), ['create'], ['class' => 'btn btn-outline-primary']) ?>
            <a class="btn btn-outline-primary" href="javascript:void(0)" onclick="deleteAllItems('admin/delete-all', 'admin/admin')"><?= '<span class="fa fa-trash"></span> '.Yii::t('cms', 'app_delete') ?></a>
        </div>
    </div>
<div class="box-body">
<?php Pjax::begin(['id' => 'admin-grid-view', 'enablePushState' => false]); ?>
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
                'attribute' => 'username',
                'filter' => true,
                'options' => ['width' => '200px'],
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
                    return Html::encode(AdminGroup::getGroupNameByID($data->admin_group_id));
                },
                'options' => ['width' => '140px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;']
            ],
            [
                'attribute' => 'created_time',
                'filter' => false,
                'options' => ['width' => '160px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;']
            ],
            [
                'attribute' => 'created_by',
                'value' => function ($data) {
                    return Html::encode(Admin::getAdminNameByID($data->created_by));
                },
                'filter' => false,
                'options' => ['width' => '140px'],
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
                'attribute' => 'cp_id',
                'header' => 'CP',
                'value' => function ($data) {
                    return $data->cp_id ? Html::encode(\cms\models\Cp::getCpNameByID($data->cp_id)) : null;
                },
                'filter' => ArrayHelper::map(\cms\models\Cp::find()->all(), 'id', 'name'),
                'options' => ['width' => '140px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}{delete}{permission}',
                'header' => Yii::t('cms', 'action'),
                'options' => ['width' => '200px'],
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
                    'permission' => function ($url) {
                        return Html::a('<span class="glyphicon glyphicon-user"></span>', $url, [
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
</div>