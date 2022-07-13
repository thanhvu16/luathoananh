<?php

use cms\models\News;
use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    use common\components\Language;
    use common\components\Image;
    use common\components\Utility;
    use zxbodya\yii2\tinymce\TinyMce;
    use zxbodya\yii2\elfinder\TinyMceElFinder;
    use kartik\datetime\DateTimePicker;

$relatedIds = json_decode($model->rel_ids);
$relatedNews = [];
if(!empty($relatedIds)) {
    $relatedNews = News::find()
        ->select('id, title')
        ->where(['IN', 'id', $relatedIds])
        ->andWhere(['!=', 'deleted', News::DELETED])
        ->asArray()->all();
}
?>
<div class="news-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'onsubmit'=>'return checkImage();']]); ?>
        <?php
        $urlImg =Utility::makeImgNews($model,'news_img_options_medium');
        if($urlImg){
            echo '<div class="form-group field-collection-image">';
            echo Html::img($urlImg,['width'=>Yii::$app->params['img_url']['news_img_options_large']['width']]);
            echo '</div>';
        }
            $options = [
                'width' => '',//Yii::$app->params['img_url']['news_img_options_large']['width'],
                'height' => ''//Yii::$app->params['img_url']['news_img_options_large']['height']
            ];
            echo Image::Image(Yii::t('cms', 'image'), $options);
        ?>

        <?php echo $form->field($model, 'title')->label(Yii::t('cms', 'Title'))->textInput(['maxlength' => 255]); ?>
        <?php echo $form->field($model, 'brief')->label(Yii::t('cms', 'Brief'))->textInput(['maxlength' => 255]); ?>
        <?php echo $form->field($model, 'title_seo')->label(Yii::t('cms', 'Title Seo'))->textInput(['maxlength' => 255]); ?>
        <?php echo $form->field($model, 'description_seo')->label(Yii::t('cms', 'Description Seo'))->textInput(['maxlength' => 255]); ?>
        <?php echo $form->field($model, 'keyword')->label(Yii::t('cms', 'key SEO'))->textInput(['maxlength' => 255]); ?>
        <?php echo $form->field($model, 'content')->label(Yii::t('cms', 'Content'))->widget(
            TinyMce::className(),
            [
                'options' => ['rows' => 10],
                'fileManager' => [
                    'class' => TinyMceElFinder::className(),
                    'connectorRoute' => 'el-finder/connector'
                ]
            ]); ?>
        <?= $form->field($model, 'news_category_id')->dropDownList(ArrayHelper::map(\cms\models\NewsCategory::find()->all(), 'id', 'title'), ['prompt' => Yii::t('cms', 'select_category')])->label(Yii::t('cms', 'category')) ?>
        <?php echo $form->field($model, 'status')->dropDownList(\cms\models\News::genStatus())->label(Yii::t('cms', 'status')); ?>
        <div class="form-group">
            <div class="col-12">
                <label class="control-label" for="news-status">Bài viết liên quan</label>
                <div class="form-group" style="border: 1px solid #ccc;min-height: 50px">
                    <ul class="frm-rel">
                    <?php foreach ($relatedNews as $rel) { ?>
                        <li style="padding: 5px;margin:5px;background: #cccccc;position: relative">
                            <input class="ip_rel" name="rel_ids[]" type="hidden" value="<?php echo $rel['id'] ?>">
                            <?php echo $rel['title'] ?>
                            <span class="fa fa-remove" onclick="removeItemRel(this)" style="color: red;cursor:pointer;position: absolute;top: -6px;right: -4px"></span>
                        </li>
                    <?php } ?>
                    </ul>
                </div>
                <a href="javascript:void(0);" class="btn btn-outline-primary" onclick="showPopupNews('news/popup', 1);return false;">Thêm bài viết liên quan</a>
            </div>
        </div>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-check" aria-hidden="true"></i> '.Yii::t('cms', 'app_create') : '<i class="fa fa-check" aria-hidden="true"></i> '.Yii::t('cms','app_update'), ['class' => $model->isNewRecord ? 'btn btn-outline-primary' : 'btn btn-outline-primary']) ?>
            <?= Html::resetButton('<i class="fa fa-ban" aria-hidden="true"></i> '.Yii::t('cms', 'app_cancel'), ['class' => 'btn btn-outline-primary']); ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>


<script>
    function removeItemRel(e) {
        e.closest('li').remove();
    }
</script>
