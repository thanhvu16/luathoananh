<?php
use yii\helpers\Html;
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'Gamification'),
        'url' => ['admin']
    ],
    [
        'label' => Yii::t('cms', 'mnu_event'),
        'template' => "<li>{link}</li>\n"
    ],
    [
        'label' => Yii::t('cms', 'app_create'),
        'template' => "<li>{link}</li>\n"
    ]
];
$this->params['menu'] = [
    ['label'=>Yii::t('cms', 'app_back'), 'url' => ['index'], 'options' => ['class' => 'btn btn-outline-primary']],
];
$this->title = Yii::$app->name.' - '.Yii::t('cms', 'app_create');
$this->params['title'] = Html::encode(Yii::t('cms', 'app_create'));
?>
<div class="event-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>