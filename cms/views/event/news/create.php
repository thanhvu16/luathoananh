<?php
use yii\helpers\Html;

$this->title = Yii::t('cms', 'app_create');
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'mnu_news'),
        'url' => ['index']
    ],
    [
        'label' => Yii::t('cms', 'app_create'),
        'template' => "<li>{link}</li>\n"
    ]
];

$this->title = Yii::$app->name.' - '.Yii::t('cms', 'app_create');
$this->params['title'] = Html::encode(Yii::t('cms', 'app_create'));
?>
<div class="box-header with-border">
    <?= Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i> '.Yii::t('cms', 'app_back'), ['index'], ['class' => 'btn btn-outline-primary']) ?>
</div>
<div class="box-body">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>