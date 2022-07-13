<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
?>
<div class="group-form">
    <?php $form = ActiveForm::begin(); ?>
    <?php echo $form->field($model, 'group_name')->input('group_name'); ?>
    <?php echo $form->field($model, 'status')->checkbox(['label' => ''])->label(Yii::t('cms', 'status')) ?>
    <?php echo $form->field($model, 'group_desc')->textarea(['rows' => 4]); ?>
    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? '<i class="fa fa-check" aria-hidden="true"></i> '.Yii::t('cms', 'create') : '<i class="fa fa-check" aria-hidden="true"></i> '.Yii::t('cms', 'update'), ['class' => !$model->isNewRecord ? 'btn btn-outline-primary' : 'btn btn-outline-primary']); ?>
        <?php echo Html::resetButton('<i class="fa fa-ban" aria-hidden="true"></i> '.Yii::t('cms', 'app_cancel'), ['class' => 'btn btn-outline-primary']); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>