<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\models\Team */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="team-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //echo $form->field($model, 'teamId')->textInput() ?>

    <?php //echo $form->field($model, 'leagueId')->textInput() ?>
    <?php echo $form->field($model, 'name')->textInput(['maxlength' => 255, 'disabled' => true]) ?>

    <?php //echo $form->field($model, 'logo')->textInput(['maxlength' => 255]) ?>


    <?php echo $form->field($model, 'custom_name')->textInput(['maxlength' => 255])->label('Tên đội bóng') ?>

    <?php //echo $form->field($model, 'foundingDate')->textInput(['maxlength' => 255]) ?>

    <?php //echo $form->field($model, 'address')->textInput(['maxlength' => 255]) ?>

    <?php //echo $form->field($model, 'area')->textInput(['maxlength' => 255]) ?>

    <?php //echo $form->field($model, 'venue')->textInput(['maxlength' => 255]) ?>

    <?php //echo $form->field($model, 'capacity')->textInput(['maxlength' => 255]) ?>

    <?php echo $form->field($model, 'coach')->textInput(['maxlength' => 255]) ?>

    <?php echo $form->field($model, 'website')->textInput(['maxlength' => 255]) ?>

    <?php //echo $form->field($model, 'created_time')->textInput() ?>

    <?php //echo $form->field($model, 'updated_time')->textInput() ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
        <?php echo Html::resetButton('Reset', ['class' => 'btn btn-default']); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
