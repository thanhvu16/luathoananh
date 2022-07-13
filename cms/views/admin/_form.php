<?php
    use cms\models\Cp;
    use yii\helpers\Html;
    use cms\models\AdminGroup;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
?>
<div class="group-form">
    <?php $form = ActiveForm::begin(); ?>
    <?php echo $form->field($model, 'username')->textInput(['title' => Yii::t('cms', 'username_notice'), 'class' => 'form-control']); ?>
    <?php
        if (Yii::$app->controller->action->id == 'update') {
            //echo $form->field($model, 'password')->passwordInput(['class' => 'app-tooltip-field form-control', 'readonly' => 'true']);
        } else {
            echo $form->field($model, 'password')->passwordInput(['title' => Yii::t('cms', 'password_notice'), 'class' => 'app-tooltip-field form-control']);
        }
    ?>
    <?php echo $form->field($model, 'status')->checkbox(['label' => ''])->label(Yii::t('cms', 'status')) ?>
    <?php echo $form->field($model, 'fullname')->textInput(['title' => Yii::t('cms', 'fullname_notice'), 'class' => 'app-tooltip-field form-control']); ?>
    <?php echo $form->field($model, 'phonenumber')->textInput(['title' => Yii::t('cms', 'phonenumber_notice'), 'class' => 'app-tooltip-field form-control']); ?>
    <?php echo $form->field($model, 'email')->textInput(['title' => Yii::t('cms', 'email_notice'), 'class' => 'app-tooltip-field form-control']); ?>
    <?php echo $form->field($model, 'admin_group_id')->dropDownList(ArrayHelper::map(AdminGroup::find()->all(), 'id', 'group_name')); ?>
    <?php echo $form->field($model, 'cp_id')->dropDownList(ArrayHelper::map(Cp::find()->all(), 'id', 'name'), ['prompt' => Yii::t('cms', 'select_cp')])->label('CP'); ?>
    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? '<i class="fa fa-check" aria-hidden="true"></i> '.Yii::t('cms', 'create') : '<i class="fa fa-check" aria-hidden="true"></i> '.Yii::t('cms', 'update'), ['class' => !$model->isNewRecord ? 'btn btn-outline-primary' : 'btn btn-outline-primary']); ?>
        <?php echo Html::resetButton('<i class="fa fa-ban" aria-hidden="true"></i> '.Yii::t('cms', 'app_cancel'), ['class' => 'btn btn-outline-primary']); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>