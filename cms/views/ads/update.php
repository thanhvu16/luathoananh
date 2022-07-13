<?php
use yii\helpers\Html;
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'List Quảng cáo'),
        'url' => ['index']
    ],
    [
        'label' => Yii::t('cms', 'app_update'),
        'template' => "<li>{link}</li>\n"
    ]
];

$this->title = Yii::$app->name.' - '.Yii::t('cms', 'app_update');
$this->params['title'] = Html::encode(Yii::t('cms', 'app_update'));
?>
<div class="box-header with-border">
    <?= Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i> '.Yii::t('cms', 'app_back'), ['index'], ['class' => 'btn btn-outline-primary']) ?>
</div>
<div class="menu-create box-body">
    <?php echo $this->render('_form', [
        'model' => $model
    ]) ?>
</div>
