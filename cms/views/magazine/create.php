<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model cms\models\Magazine */

$this->title = 'Thêm mới Magazine';
$this->params['breadcrumbs'][] = ['label' => 'Magazines', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['title'] = $this->title;
$this->params['menu'] = [
    ['label'=>'Quay lại', 'url' => ['index'], 'options' => ['class' => 'btn btn-outline-primary']],
];
?>
<div class="box box-body">
<div class="magazine-update">
    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>
</div>
</div>

