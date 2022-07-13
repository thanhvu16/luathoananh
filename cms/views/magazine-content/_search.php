<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\models\search\MagazineContentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="magazine-content-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'magazine_id') ?>

    <?= $form->field($model, 'block_type') ?>

    <?= $form->field($model, 'sort_order') ?>

    <?= $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'content_mobile') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'updated_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
