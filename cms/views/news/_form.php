<style>
#news-time_active{
	width : 250px;
}
</style>
<?php

use cms\models\News;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\components\Utility;
use cms\models\NewsCategory;
use common\components\CategoryTree;

$relatedIds = !empty($model->rel_ids) ? explode(',', $model->rel_ids) : [];
$relatedNews = [];
if(!empty($relatedIds)) {
    $relatedNews = News::find()
        ->select('id, title')
        ->where(['IN', 'id', $relatedIds])
        ->andWhere(['!=', 'deleted', News::DELETED])
        ->asArray()->all();
}

$category = new NewsCategory();
$category = $category->getListCategoryByPermission();
$sys = new CategoryTree($category);
$selectBox = $sys->builArray(0);
$selectBox = $sys->selectboxArray($selectBox, 'title');
if(empty($selectBox)) {
    $selectBox = $category;
}
?>
<div class="news-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?php
    $urlImg =$model->image;
    $class = !empty($urlImg) ? 'show' : 'hide';
    ?>
    <img style="max-width: 600px" class="show-img <?php echo $class ?>  m-b" src="<?php echo $urlImg ?>">
    <button class="btn-primary btn select-img  m-b" onclick="chooseImg();return false;">Chọn ảnh</button>
    <input type="file" accept="image/*" name="file" id="inputImage" class="hide" onchange="showImg(this)">
    <input type="hidden" name="image-data" id="img-bs64">

    <?php echo $form->field($model, 'title')->textInput(['maxlength' => 255])->label('Tiêu đề'); ?>
    <?php echo $form->field($model, 'brief')->textInput(['maxlength' => 255])->label('Mô tả'); ?>
    <?php echo $form->field($model, 'title_seo')->textInput(['maxlength' => 255]); ?>
    <?php echo $form->field($model, 'description_seo')->textInput(['maxlength' => 255]); ?>
    <?php echo $form->field($model, 'pseudonym')->textInput(['maxlength' => 255])->label('Người viết'); ?>
<!--    --><?php //echo $form->field($model, 'keyword')->textInput()->label('Từ khóa (ví dụ: Ronaldo, Liverpool, Mane)'); ?>
    <?= $form->field($model, 'content')->label('Nội dung')->widget(\mihaildev\ckeditor\CKEditor::className(), [
        'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions([
            'elFinder',
        ],[
            'preset' => 'full',
            'inline' => false,
        ])
    ]) ?>
    <?php echo $form->field($model, 'status')->label('Tình trạng xuất bản')->dropDownList($model->genStatus($model->isNewRecord)); ?>
	<?= $form->field($model, 'news_category_id')->dropDownList(ArrayHelper::map($selectBox, 'id', 'name'), ['prompt' => Yii::t('cms', 'Chọn danh mục')]) ?>
    <?php echo $form->field($model, 'is_hot')->checkbox(['label' => Yii::t('cms', 'HOT')])->label(false) ?>
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

<!--    --><?php //echo $form->field($model, 'match_id')->textInput(['maxlength' => 255, 'type' => 'number'])->label('ID trận đấu'); ?>

    <?php echo $form->field($model,'time_active')->widget(\kartik\datetime\DateTimePicker::className(),[
        'options' => ['style' => 'width: 200px;'],
        'pluginOptions' => ['autoclose' => true,'format' => 'yyyy-mm-dd hh:ii:ss']
    ])?>

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
    function chooseImg() {
        $('#inputImage').trigger('click');
    }
    function showImg(e) {
        var fileReader = new FileReader(),
            files = e.files,
            file;

        if (!files.length) {
            return;
        }

        file = files[0];

        if (/^image\/\w+$/.test(file.type)) {
            fileReader.readAsDataURL(file);
            fileReader.onload = function () {
                $('#inputImage').val('');
                $('.show-img').attr('src', this.result)
                $('#img-bs64').val(this.result)
            };
        } else {
            showMessage("Please choose an image file.");
        }
    }
</script>
<style>
#news-time_start_match{
	width: 250px;
}
.remove-faq-item{
    float: right;
    color: red;
}
</style>