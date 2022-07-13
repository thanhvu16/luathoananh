<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cms\models\Team */

$this->title = 'Update Team: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Teams', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
$this->params['title'] = 'Update';
/*$this->params['menu'] = [
    ['label' => 'Back', 'url' => ['index'], 'options' => ['class' => 'btn btn-primary']],
];*/
?>
<div class="box-header with-border">
    <?= Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i> ' . Yii::t('cms', 'app_back'), ['index'], ['class' => 'btn btn-outline-primary']) ?>
</div>
<div class="box-body">
    <div class="team-update">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

    </div>
</div>
