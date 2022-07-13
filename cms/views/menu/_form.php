<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use cms\models\Menu;
    use common\components\Language;
    use common\components\CFunction;
    use kartik\file\FileInput;
    use common\components\Utility;
?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?php echo Language::languageTabs($model, $form, Yii::t('cms', 'title_category'), 'title'); ?>
    <?php echo Language::languageTabs($model, $form, Yii::t('cms', 'desc_category'), 'desc', 'tinymce'); ?>
    <?php echo $form->field($model, 'route')->input('route'); ?>
    <?php echo $form->field($model, 'icon')->input('icon'); ?>
    <?php echo $form->field($model, 'type')->dropDownList(CFunction::getParams('menu_type'), array('onchange'=>'onchangeTypeMenu(this.value)')); ?>
    <?php //echo $form->field($model, 'parent_id')->dropDownList(Menu::getCategory('--'), ['prompt' => Yii::t('cms', 'select_category_parent')]); ?>
    <?php echo $form->field($model, 'parent_id')->dropDownList(\yii\helpers\ArrayHelper::map($selectBox, 'id', 'name'), ['prompt' => Yii::t('cms', 'select_category_parent')]) ?>
    <?php echo $form->field($model, 'active')->checkbox(['label' => ''])->label(Yii::t('cms', 'status')); ?>

    <label for="collection-order" class="control-label" style="width: 100%;"><?php echo Yii::t('cms', 'icon_image'); ?></label>
    <?php
        $urlImg = Utility::makeThumbnail($model, 'menu_icon', true);
        if ($urlImg) {
            echo '<div class="form-group field-collection-image">';
            echo Html::img($urlImg, ['width' => Yii::$app->params['img_url']['menu_icon']['width']]);
            echo '</div>';
        }
        echo '<div class="well well-small">';
        echo FileInput::widget([
            'name' => 'icon_image',
            'pluginOptions' => [
                'showPreview' => false,
                'showCaption' => false,
                'elCaptionText' => '#customCaption',
                'allowedFileExtensions'=>['jpg','gif','png']
            ],
            'options' => ['accept' => 'image/*']
        ]);
        echo '<span id="customCaption1" class="text-success">No file selected</span>';
        echo '</div>';
    ?>
    <?php echo $form->field($model, 'content_id')->input('content_id')->label(Yii::t('cms', 'content_id_menu')); ?>
    <?php echo $form->field($model, 'content_type')->input('content_type')->label(Yii::t('cms', 'content_type_menu')); ?>
    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? '<i class="fa fa-check" aria-hidden="true"></i> '.Yii::t('cms', 'create') : '<i class="fa fa-check" aria-hidden="true"></i> '.Yii::t('cms', 'update'), ['class' => !$model->isNewRecord ? 'btn btn-outline-primary' : 'btn btn-outline-primary']); ?>
        <?php echo Html::resetButton('<i class="fa fa-ban" aria-hidden="true"></i> '.Yii::t('cms', 'app_cancel'), ['class' => 'btn btn-outline-primary']); ?>
    </div>
<?php ActiveForm::end(); ?>