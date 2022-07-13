<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\models\search\LeagueSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="league-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'league_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'custom_name') ?>

    <?= $form->field($model, 'custom_short_name') ?>

    <?= $form->field($model, 'short_name') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'sub_league_name') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'color') ?>

    <?php // echo $form->field($model, 'logo') ?>

    <?php // echo $form->field($model, 'created_time') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_time') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'totalRound') ?>

    <?php // echo $form->field($model, 'currentRound') ?>

    <?php // echo $form->field($model, 'currentSeason') ?>

    <?php // echo $form->field($model, 'countryId') ?>

    <?php // echo $form->field($model, 'country') ?>

    <?php // echo $form->field($model, 'countryLogo') ?>

    <?php // echo $form->field($model, 'areaId') ?>

    <?php // echo $form->field($model, 'isHot') ?>

    <?php // echo $form->field($model, 'sort_order') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
