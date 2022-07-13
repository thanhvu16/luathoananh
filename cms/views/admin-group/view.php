<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use cms\models\AdminGroup;

$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'mnu_admin_group'),
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
        'group_name',
        'group_desc',
        [
            'label' => Yii::t('cms', 'status'),
            'value' => Html::encode(AdminGroup::getGroupStatusText($model->status))
        ],
        'created_time',
    ],
]) ?>
</div>