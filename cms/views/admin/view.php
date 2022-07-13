<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use cms\models\Admin;
use cms\models\AdminGroup;

$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'mnu_admin_user'),
        'url' => ['admin']
    ],
    [
        'label' => Yii::t('cms', 'app_detail'),
        'template' => "<li>{link}</li>\n"
    ]
];

$this->params['title'] = Html::encode(Yii::t('cms', 'app_detail'));
?>
<div class="box-header with-border">
    <?= Html::a('<i class="fa fa-list" aria-hidden="true"></i> '.Yii::t('cms', 'app_list'), ['admin'], ['class' => 'btn btn-outline-primary']) ?>
</div>
<div class="box-body">
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'username',
        [
            'label' => Yii::t('cms', 'status'),
            'value' => Html::encode(Admin::getGroupStatusText($model->status))
        ],
        'fullname',
        'phonenumber',
        'email',
        'created_time',
        [
            'label' => Yii::t('cms', 'created_by'),
            'value' => Html::encode(Admin::getAdminNameByID($model->created_by))
        ],
        [
            'label' => Yii::t('cms', 'mnu_admin_group'),
            'value' => Html::encode(AdminGroup::getGroupNameByID($model->admin_group_id))
        ]
    ],
]) ?>
</div>