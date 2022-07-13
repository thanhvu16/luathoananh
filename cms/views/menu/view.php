<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Chi tiết';
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'mnu_system_menu'),
        'url' => ['admin']
    ],
    [
        'label' => Yii::t('cms', 'app_detail'),
        'template' => "<li>{link}</li>\n"
    ]
];

$language = \common\components\Language::language();
?>
<div class="box-header with-border">
    <?= Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i> '.Yii::t('cms', 'app_list'), ['admin'], ['class' => 'btn btn-outline-primary']) ?>
</div>
<div class="box-body">
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'title_'.\common\components\Language::language(),
        [
            'label' => Yii::t('cms', 'status'),
            'format' => 'raw',
            'value' => $model->{"desc_$language"},
        ],
        'route',
        [
            'label' => Yii::t('cms', 'status'),
            'value' => Html::encode(\cms\models\Menu::getMenuStatusText($model->active))
        ],
        [
            'label' => Yii::t('cms', 'parent_id_category'),
            'value' => Html::encode(\cms\models\Menu::getNameParent($model->parent_id))
        ],
        ///'level'
    ],
]);
?>
</div>