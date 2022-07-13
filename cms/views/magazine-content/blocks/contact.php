<?php

use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/**
 * @var \cms\models\MagazineContent $model
 * @var ActiveForm $form
 */
$content = null;
if (!empty($model->content)) {
    $content = unserialize($model->content);
}
$address = !empty($content['address']) ? $content['address'] : 'Số 2/84 - Trần Quang Diệu - Phường Ô Chợ Dừa - Quận Đống Đa - TP Hà Nội';
$phone = !empty($content['phone']) ? $content['phone'] : '0908 308 123';
$mail = !empty($content['mail']) ? $content['mail'] : 'luatsu@luathoanganh.vn';
?>
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#content_web" aria-controls="home" role="tab" data-toggle="tab">Content</a>
    </li>
    <li role="presentation"><a href="#content_background" aria-controls="profile" role="tab" data-toggle="tab">Background</a>
    </li>
</ul>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="content_web">
        <div class="col-12">
			<?= $this->render('block-common', ['content' => $content, 'name' => 'title', 'label' => 'Tiêu đề']) ?>
			<?= $this->render('block-common', ['content' => $content, 'name' => 'brief', 'label' => 'Mô tả']) ?>
		</div>
		<div class="col-12">
			<div class="form-group field-magazine-title required">
				<label class="control-label">Địa điểm</label>
				<input type="text" class="form-control" name="content[address]" value="<?= $address ?>" maxlength="255" aria-required="true">
			</div>
		</div>
		<div class="col-12">
			<div class="form-group field-magazine-title required">
				<label class="control-label" for="magazine-title">Số điện thoại</label>
				<input type="text" class="form-control" name="content[phone]" value="<?= $phone ?>" maxlength="255" aria-required="true">
			</div>
		</div>
		<div class="col-12">
			<div class="form-group field-magazine-title required">
				<label class="control-label">Email</label>
				<input type="text" class="form-control" name="content[mail]" value="<?= $mail ?>" maxlength="255" aria-required="true">
			</div>
		</div>
    </div>
    <div role="tabpanel" class="tab-pane" id="content_background">
        <div class="form-group" style="margin-top: 10px">
			<?= $this->render('bg-linear', ['content' => $content]) ?>
		</div>
	</div>
</div>
