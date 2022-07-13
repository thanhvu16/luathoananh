<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cms\models\Category;

?>
<?php  $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
	<?php
		$urlImg =$model->image;
		?>
		<img style="max-width: 600px" class="show-img  m-b" src="<?php echo $urlImg ?>">
		<button class="btn-primary btn select-img  m-b" onclick="chooseImg();return false;">Chọn ảnh</button>
		<input type="file" accept="image/*" name="file" id="inputImage" class="hide" onchange="showImg(this)">
		<input type="hidden" name="image-data" id="img-bs64">
    <?php echo $form->field($model, 'name')->textInput(['maxlength' => 255])->label('Tên Banner'); ?>
    <?php echo $form->field($model, 'url')->textInput(['maxlength' => 255])->label('Đường dẫn'); ?>
    <?php echo $form->field($model, 'status')->checkbox(['label' => Yii::t('cms', 'Active')])->label(false) ?>
    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? '<i class="fa fa-check" aria-hidden="true"></i> '.Yii::t('cms', 'create') : '<i class="fa fa-check" aria-hidden="true"></i> '.Yii::t('cms', 'update'), ['class' => !$model->isNewRecord ? 'btn btn-outline-primary' : 'btn btn-outline-primary']); ?>
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
                $('.show-img').attr('src', this.result);
				$('.show-img').show();
                $('#img-bs64').val(this.result);
            };
        } else {
            showMessage("Please choose an image file.");
        }
    }
</script>