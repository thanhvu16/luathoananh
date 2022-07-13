<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    use common\models\EventGroupBase;
?>
<div class="group-form">
    <?php $form = ActiveForm::begin(); ?>
    <?php echo $form->field($model, 'name')->input('text')->label(Yii::t('cms', 'name')); ?>
    <?php echo $form->field($model, 'description')->textarea(['rows' => 4])->label(Yii::t('cms', 'desc')); ?>
    <?php echo $form->field($model, 'event_group_id')->dropDownList(ArrayHelper::map(EventGroupBase::find()->all(), 'id', 'name'), ['prompt' => '--Chọn nhóm sự kiện--'])->label(Yii::t('cms', 'event_group_id')); ?>
    <?php echo $form->field($model, 'status')->checkbox(['label' => ''])->label(Yii::t('cms', 'status')) ?>
    <?php echo $form->field($model, 'point')->input('text')->label(Yii::t('cms', 'point')); ?>
    <?php echo $form->field($model, 'reset')->checkbox(['label' => '<span style="font-weight: normal; padding-left: 2px;">Xóa toàn bộ điểm của khách hàng</span>', 'style' => 'vertical-align: -2px;'], true)->label(Yii::t('cms', 'reset')) ?>
    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? Yii::t('cms', 'create') : Yii::t('cms', 'update'), ['class' => $model->isNewRecord ? 'btn btn-outline-primary' : 'btn btn-outline-primary']); ?>
        <?php echo Html::resetButton(Yii::t('cms', 'app_cancel'), ['class' => 'btn btn-outline-primary']); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>