<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model cms\models\Team */

$this->title = 'Create Team';
$this->params['breadcrumbs'][] = ['label' => 'Teams', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['title'] = 'Update';
$this->params['menu'] = [
    ['label'=>'Back', 'url' => ['index'], 'options' => ['class' => 'btn btn-primary']],
];
?>
<div class="team-update">
    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>
</div>

