<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\Utility;
use kartik\file\FileInput;
use yii\helpers\Url;
?>
<div class="news-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?php echo $form->field($model, 'name')->textInput(['maxlength' => 255]); ?>
    <?php
    $initialPreview = [];
    $initialPreviewConfig = [];
    if ($model->updated_time >0) {
        $initialPreview[] = Html::img(Utility::getThumbnail($model,'sender_avatar'), ['class' => 'file-preview-image']);
        $initialPreviewConfig[] = ['width' => '120px', 'url' => Url::toRoute(['delete-image', 'id' => $model->id]), 'key' => "fileId-{$model->id}"];
    }

    echo $form->field($model, 'thumb_upload')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*'
        ],
        'pluginOptions' => [
            'showPreview' => true,
            'showCaption' => true,
            'showRemove' => false,
            'showUpload' => false,
            'browseClass' => 'btn btn-primary',
            'initialPreview' => $initialPreview,
            'initialPreviewConfig' => $initialPreviewConfig
        ]
    ])->label(Yii::t('cms', 'Image thumb'));
    ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-check" aria-hidden="true"></i> '.Yii::t('cms', 'app_create') : '<i class="fa fa-check" aria-hidden="true"></i> '.Yii::t('cms','app_update'), ['class' => $model->isNewRecord ? 'btn btn-outline-primary' : 'btn btn-outline-primary']) ?>
        <?= Html::resetButton('<i class="fa fa-ban" aria-hidden="true"></i> '.Yii::t('cms', 'app_cancel'), ['class' => 'btn btn-outline-primary']); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

