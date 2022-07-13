<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\components\Language;
use common\components\Image;
use common\components\Utility;
use zxbodya\yii2\tinymce\TinyMce;
use zxbodya\yii2\elfinder\TinyMceElFinder;
?>
<div class="form">
    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'name')->label(Yii::t('cms', 'Name'))->textInput(['maxlength' => 255]); ?>
    <?php echo $form->field($model, 'description')->label(Yii::t('cms', 'Description'))->textInput(['maxlength' => 255]); ?>
    <?php echo $form->field($model, 'status')->dropDownList(\common\components\CFunction::getParams('status'))->label(Yii::t('cms', 'status')); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-check" aria-hidden="true"></i> '.Yii::t('cms', 'app_create') : '<i class="fa fa-check" aria-hidden="true"></i> '.Yii::t('cms','app_update'), ['class' => $model->isNewRecord ? 'btn btn-outline-primary' : 'btn btn-outline-primary']) ?>
        <?= Html::resetButton('<i class="fa fa-ban" aria-hidden="true"></i> '.Yii::t('cms', 'app_cancel'), ['class' => 'btn btn-outline-primary']); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
