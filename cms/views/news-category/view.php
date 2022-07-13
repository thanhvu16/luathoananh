<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = Yii::t('cms', 'Chi tiết');
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'mnu_news_category'),
        'url' => ['admin']
    ],
    [
        'label' => Yii::t('cms', 'app_detail'),
        'template' => "<li>{link}</li>\n"
    ]
];

?>
<div class="box-header with-border">
    <?= Html::a('<i class="fa fa-list" aria-hidden="true"></i> '.Yii::t('cms', 'app_list'), ['admin'], ['class' => 'btn btn-outline-primary']) ?>
</div>
<div class="box-body">
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'title',
        [
            'label' => Yii::t('cms', 'status'),
            'format' => 'raw',
            'value' => $model->{"desc"},
        ],
        'route',
        [
            'label' => Yii::t('cms', 'status'),
            'value' => Html::encode(\cms\models\NewsCategory::getMenuStatusText($model->active))
        ],
        [
            'label' => Yii::t('cms', 'parent_id_category'),
            'value' => Html::encode(\cms\models\NewsCategory::getNameParent($model->parent_id))
        ],
    ],
]) ?>
</div>