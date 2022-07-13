<?php
use yii\helpers\Html;
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'mnu_admin_group'),
        'url' => ['admin']
    ],
    [
        'label' => Yii::t('cms', 'app_update'),
        'template' => "<li>{link}</li>\n"
    ]
];

$this->title = Yii::$app->name.' - '.Yii::t('cms', 'app_create');
?>
<div class="box-header with-border">
    <?= Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i> '.Yii::t('cms', 'app_back'), ['admin'], ['class' => 'btn btn-outline-primary']) ?>
</div>
<div class="menu-create box-body">
    <h3><?php echo Html::encode(Yii::t('cms', 'app_update')); ?></h3><br />
    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
