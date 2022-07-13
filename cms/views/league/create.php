<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model cms\models\League */

$this->title = 'Create League';
$this->params['breadcrumbs'][] = ['label' => 'Leagues', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['title'] = 'Update';
/*$this->params['menu'] = [
    ['label'=>'Back', 'url' => ['index'], 'options' => ['class' => 'btn btn-primary']],
];*/
?>
<div class="box-header with-border">
    <?= Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i> '.Yii::t('cms', 'app_back'), ['index'], ['class' => 'btn btn-outline-primary']) ?>
</div>
<div class="box-body">
<div class="league-update">
    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>
</div>
</div>

