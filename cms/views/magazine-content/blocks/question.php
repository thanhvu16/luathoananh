<?php

use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/**
 * @var \cms\models\MagazineContent $model
 * @var ActiveForm $form
 */

if (!empty($model->content)) {
    $content = unserialize($model->content);
}

if (!empty($model->content_mobile)) {
    $content_mobile = unserialize($model->content_mobile);
}
?>
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#content_web" aria-controls="home" role="tab" data-toggle="tab">Question</a>
    </li>
    <li role="presentation"><a href="#content_background" aria-controls="profile" role="tab" data-toggle="tab">Background</a>
    </li>
</ul>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="content_web">
        <div class="col-12">
			<label class="control-label" for="news-status">Số hàng hiển thị</label>
			<div class="form-group form-group-check">
				<input class="position-image-text" type="radio" name="content[question-column]"
					   value="2" <?= (!empty($content['question-column']) && $content['question-column'] == 2) || empty($content['question-column']) ? 'checked' : '' ?> />
				<label for="background-blank" style="margin-right: 10px"> 2 </label>

				<input class="position-image-text" type="radio" name="content[question-column]"
					   value="3" <?= !empty($content['question-column']) && $content['question-column'] == 3 ? 'checked' : '' ?> />
				<label for="background-image"  style="margin-right: 10px"> 3 </label>
				
				<input class="position-image-text" type="radio" name="content[question-column]"
					   value="4" <?= !empty($content['question-column']) && $content['question-column'] == 4 ? 'checked' : '' ?> />
				<label for="background-linear"> 4 </label>
			</div>
		</div>
		<div class="col-12">
			<?= $this->render('block-common', ['content' => $content, 'name' => 'title', 'label' => 'Tiêu đề']) ?>
			<?= $this->render('block-common', ['content' => $content, 'name' => 'brief', 'label' => 'Mô tả']) ?>
			<label class="control-label" for="news-status">Câu hỏi:</label>
			<div class="form-group list-faq-item" style="border: 1px solid #ccc;min-height: 50px">
				<?php  $faqs = !empty($content['faqs']) ? $content['faqs'] : []; ?>
				<?php if(empty($faqs)): ?>
					<div class="faq-item faq-item-1">
						<a href="javascript:" onclick="removeFaqItem(1);" class="remove-faq-item">Remove</a>
						<input class="form-control" type="text" name="content[faqs][1][question]" placeholder="Nhập nội dung câu hỏi" value="">
						<textarea class="form-control" name="content[faqs][1][answer]" placeholder="Nhập câu trả lời"></textarea>
					</div>
					<input type="hidden" id="number-faqs-item" value="2">
				<?php else:  $index = 0;?>
					<?php foreach($faqs as $key => $faq): ?>
						<div class="faq-item faq-item-<?=$key?>">
							<a href="javascript:" onclick="removeFaqItem(<?=$key?>);" class="remove-faq-item">Remove</a>
							<input class="form-control" type="text" name="content[faqs][<?=$key?>][question]" placeholder="Nhập nội dung câu hỏi" value="<?=$faq['question']?>">
							<textarea class="form-control" name="content[faqs][<?=$key?>][answer]" placeholder="Nhập câu trả lời"><?=$faq['answer']?></textarea>
						</div>
						<?php  $index = $key; endforeach; ?>
					<input type="hidden" id="number-faqs-item" value="<?=$index+1?>">
				<?php endif;?>
			</div>
		</div>
		<a href="javascript:void(0);" onclick="addFaqs();" class="btn btn-outline-primary" style="margin-bottom: 10px" >Thêm câu hỏi</a>
    </div>
    <div role="tabpanel" class="tab-pane" id="content_background">
        <div class="form-group" style="margin-top: 10px">
			<?= $this->render('bg-linear', ['content' => $content]) ?>
		</div>
	</div>
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
    function addFaqs() {
        const index = parseInt($('#number-faqs-item').val());
        let item = `<div class="faq-item faq-item-${index}">
                        <a href="javascript:" onclick="removeFaqItem(${index});" class="remove-faq-item">Remove</a>
                        <input class="form-control" type="text" name="content[faqs][${index}][question]" placeholder="Nhập nội dung câu hỏi" value="">
                        <textarea class="form-control" name="content[faqs][${index}][answer]" placeholder="Nhập câu trả lời"></textarea>
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