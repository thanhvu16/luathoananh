<?php
use yii\helpers\Html;
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
        'label' => Yii::t('cms', 'app_update'),
        'template' => "<li>{link}</li>\n"
    ]
];
$this->params['menu'] = [
    ['label'=>Yii::t('cms', 'app_back'), 'url' => ['index'], 'options' => ['class' => 'btn btn-outline-primary']],
];
$this->title = Yii::$app->name.' - '.Yii::t('cms', 'app_create');
?>
<div class="event-update">
    <h3><?php echo Html::encode(Yii::t('cms', 'app_update')); ?></h3><br />
    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>