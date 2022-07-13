<?php
use yii\helpers\Html;
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'mnu_film_actor'),
        'url' => ['index']
    ],
    [
        'label' => Yii::t('cms', 'app_update'),
        'template' => "<li>{link}</li>\n"
    ]
];
$this->params['menu'] = [
    ['label'=>Yii::t('cms', 'app_back'), 'url' => ['index'], 'options' => ['class' => 'btn btn-outline-primary']],
];
$this->title = Yii::$app->name.' - '.Yii::t('cms', 'app_update');
$this->params['title'] = Html::encode(Yii::t('cms', 'app_update'));
?>
<div class="actor-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
