<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

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
        'label' => Yii::t('cms', 'app_detail'),
        'template' => "<li>{link}</li>\n"
    ]
];
$this->params['menu'] = [
    ['label'=>Yii::t('cms', 'app_list'), 'url' => ['index'], 'options' => ['class' => 'btn btn-outline-primary']]
];
$this->params['title'] = Html::encode(Yii::t('cms', 'app_detail'));
?>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'name',
        'description',
        [
            'label' => Yii::t('cms', 'status'),
            'value' => ($model->status == 1) ? 'ACTIVE': 'INACTIVE'
        ],
        'created_time',
        [
            'label' => Yii::t('cms', 'created_by'),
            'value' => \cms\models\Admin::getAdminNameByID($model->created_by)
        ]
    ],
]) ?>