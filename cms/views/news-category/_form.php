<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use zxbodya\yii2\tinymce\TinyMce;
use zxbodya\yii2\elfinder\TinyMceElFinder;
?>
<?php $form = ActiveForm::begin(); ?>
    <?php
    $urlImg =$model->image;
    $class = !empty($urlImg) ? 'show' : 'hide';
    ?>
    <img style="max-width: 600px" class="show-img <?php echo $class ?>  m-b" src="<?php echo $urlImg ?>">
    <button class="btn-primary btn select-img  m-b" onclick="chooseImg();return false;">Chọn ảnh</button>
    <input type="file" accept="image/*" name="file" id="inputImage" class="hide" onchange="showImg(this)">
    <input type="hidden" name="image-data" id="img-bs64">
    <?php echo $form->field($model, 'title')->label(Yii::t('cms', 'Title'))->textInput(['maxlength' => 255]); ?>
    <?php echo $form->field($model, 'desc')->label(Yii::t('cms', 'Description'))->textInput(['maxlength' => 255]); ?>
    <?php echo $form->field($model, 'title_seo')->label(Yii::t('cms', 'Title SEO'))->textInput(['maxlength' => 255]); ?>
    <?php echo $form->field($model, 'description_seo')->label(Yii::t('cms', 'Description SEO'))->textInput(['maxlength' => 255]); ?>
    <?php echo $form->field($model, 'keyword')->label(Yii::t('cms', 'Key SEO'))->textInput(['maxlength' => 255]); ?>
    <?php echo $form->field($model, 'order')->label(Yii::t('cms', 'Order'))->textInput(['maxlength' => 500, 'type' => 'number']); ?>
    <?php echo $form->field($model, 'page_intro')->label(Yii::t('cms', 'Page Intro'))->widget(
        TinyMce::className(),
        [
            'options' => ['rows' => 10],
            'fileManager' => [
                'class' => TinyMceElFinder::className(),
                'connectorRoute' => 'el-finder/connector'
            ]
        ]
    ); ?>
    <div class="form-group">
        <div class="col-12">
            <label class="control-label" for="news-status">Câu hỏi thường gặp</label>
            <div class="form-group list-faq-item" style="border: 1px solid #ccc;min-height: 50px">
                <?php  $faqs = json_decode($model->faqs, true); ?>
                <?php if(empty($faqs)): ?>
                    <div class="faq-item faq-item-1">
                        <a href="javascript:" onclick="removeFaqItem(1);" class="remove-faq-item">Remove</a>
                        <input class="form-control" type="text" name="faqs[1][question]" placeholder="Nhập nội dung câu hỏi" value="">
                        <textarea class="form-control" name="faqs[1][answer]" placeholder="Nhập câu trả lời"></textarea>
                    </div>

                    <input type="hidden" id="number-faqs-item" value="2">
                <?php else:  $index = 0;?>
                    <?php foreach($faqs as $key => $faq): ?>
                        <div class="faq-item faq-item-<?=$key?>">
                            <a href="javascript:" onclick="removeFaqItem(<?=$key?>);" class="remove-faq-item">Remove</a>
                            <input class="form-control" type="text" name="faqs[<?=$key?>][question]" placeholder="Nhập nội dung câu hỏi" value="<?=$faq['question']?>">
                            <textarea class="form-control" name="faqs[<?=$key?>][answer]" placeholder="Nhập câu trả lời"><?=$faq['answer']?></textarea>
                        </div>
                        <?php  $index = $key; endforeach; ?>
                    <input type="hidden" id="number-faqs-item" value="<?=$index+1?>">
                <?php endif;?>
            </div>
        </div>
    </div>
    <a href="javascript:void(0);" onclick="addFaqs();" class="btn btn-outline-primary" style="margin-bottom: 10px" >Thêm câu hỏi</a>
    <?php echo $form->field($model, 'route')->label(Yii::t('cms', 'Đường dẫn'))->textInput(['maxlength' => 100]); ?>
    <?php echo $form->field($model, 'parent_id')->dropDownList(\yii\helpers\ArrayHelper::map($category, 'id', 'title'), ['prompt' => Yii::t('cms', 'select_category_parent')]) ?>
    <?php echo $form->field($model, 'active')->checkbox(['label' => Yii::t('cms', 'status')])->label(false) ?>
    <?php echo $form->field($model, 'is_hot')->checkbox(['label' => Yii::t('cms', 'HOT')])->label(false) ?>
    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? '<i class="fa fa-check" aria-hidden="true"></i> '.Yii::t('cms', 'create') : '<i class="fa fa-check" aria-hidden="true"></i> '.Yii::t('cms', 'update'), ['class' => $model->isNewRecord ? 'btn btn-outline-primary' : 'btn btn-outline-primary']); ?>
        <?php echo Html::resetButton('<i class="fa fa-ban" aria-hidden="true"></i> '.Yii::t('cms', 'app_cancel'), ['class' => 'btn btn-outline-primary']); ?>
    </div>
<?php ActiveForm::end(); ?>

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
    function addFaqs() {
        const index = parseInt($('#number-faqs-item').val());
        let item = `<div class="faq-item faq-item-${index}">
                        <a href="javascript:" onclick="removeFaqItem(${index});" class="remove-faq-item">Remove</a>
                        <input class="form-control" type="text" name="faqs[${index}][question]" placeholder="Nhập nội dung câu hỏi" value="">
                        <textarea class="form-control" name="faqs[${index}][answer]" placeholder="Nhập câu trả lời"></textarea>
                    </div>`;
        $('.list-faq-item').append(item);

        $('#number-faqs-item').val(index+1);
        return false;
    }
    function removeFaqItem(index){
        $(`.faq-item-${index}`).remove();
        return false;
    }
</script>

<style>
    .remove-faq-item{
        float: right;
        color: red;
    }
</style>