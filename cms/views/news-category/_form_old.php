<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use common\components\Language;
    use cms\models\NewsCategory;
?>
<?php $form = ActiveForm::begin(); ?>
    <?php echo Language::languageTabs($model, $form, Yii::t('cms', 'title_category'), 'title'); ?>
    <?php echo Language::languageTabs($model, $form, Yii::t('cms', 'desc_category'), 'desc', 'tinymce'); ?>
    <?php echo $form->field($model, 'route')->input('route'); ?>
    <?php echo $form->field($model, 'icon')->input('icon'); ?>
    <?php echo $form->field($model, 'parent_id')->dropDownList(NewsCategory::getCategory('--'), ['prompt' => Yii::t('cms', 'select_category_parent')]); ?>
    <?php echo $form->field($model, 'active')->dropDownList(NewsCategory::getMenuStatus()); ?>
    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? Yii::t('cms', 'create') : Yii::t('cms', 'update'), ['class' => $model->isNewRecord ? 'btn btn-outline-primary' : 'btn btn-outline-primary']); ?>
        <?php echo Html::resetButton(Yii::t('cms', 'app_cancel'), ['class' => 'btn btn-outline-primary']); ?>
    </div>
<?php ActiveForm::end(); ?>