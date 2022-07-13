<style>
#news-time_active{
	width : 250px;
}
</style>
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="news-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => 255]); ?>
    <?php echo $form->field($model, 'description')->textInput(['maxlength' => 500]); ?>

    <?= $form->field($model, 'content')->widget(\mihaildev\ckeditor\CKEditor::className(), [
        'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions([
            'elFinder',
        ],[
            'preset' => 'full',
            'inline' => false,
        ])
    ]) ?>

    <?php echo $form->field($model, 'status')->checkbox(['label' => Yii::t('cms', 'Trạng thái')])->label(false) ?>
    <?php echo $form->field($model, 'order_no')->textInput(['type' => 'number']); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-check" aria-hidden="true"></i> '.Yii::t('cms', 'app_create') : '<i class="fa fa-check" aria-hidden="true"></i> '.Yii::t('cms','app_update'), ['class' => $model->isNewRecord ? 'btn btn-outline-primary' : 'btn btn-outline-primary']) ?>
        <?= Html::resetButton('<i class="fa fa-ban" aria-hidden="true"></i> '.Yii::t('cms', 'app_cancel'), ['class' => 'btn btn-outline-primary']); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
