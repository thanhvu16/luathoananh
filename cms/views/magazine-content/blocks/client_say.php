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
$blockColumn = !empty($content['block-column']) ? $content['block-column'] : 2;
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
			<label class="control-label" for="news-status">Số hàng hiển thị</label>
			<div class="form-group form-group-check">
				<input class="position-image-text" type="radio" name="content[block-column]"
					   value="2" <?= (!empty($content['block-column']) && $content['block-column'] == 2) || empty($content['block-column']) ? 'checked' : '' ?> />
				<label for="background-blank" style="margin-right: 10px"> 2 </label>

				<input class="position-image-text" type="radio" name="content[block-column]"
					   value="3" <?= !empty($content['block-column']) && $content['block-column'] == 3 ? 'checked' : '' ?> />
				<label for="background-image"  style="margin-right: 10px"> 3 </label>
				
				<input class="position-image-text" type="radio" name="content[block-column]"
					   value="4" <?= !empty($content['block-column']) && $content['block-column'] == 4 ? 'checked' : '' ?> />
				<label for="background-linear"> 4 </label>
			</div>
		</div>
        <div class="col-12">
			<?= $this->render('block-common', ['content' => $content, 'name' => 'title', 'label' => 'Tiêu đề']) ?>
			<?= $this->render('block-common', ['content' => $content, 'name' => 'brief', 'label' => 'Mô tả']) ?>
		</div>
		<div class="frm-block" style="display: flex;justify-content: space-between; flex-wrap: wrap">
			<?php for ($i = 1; $i <= 4; $i++): ?>
			<div class="form-group frm-client" id="form-<?= $i ?>">
				<div class="form-group">
					<label>Ảnh</label>
					<?= \cms\widgets\elfinder\InputFile::widget([
						'name' => 'content[image][]',
						'value' => !empty($content['image'][$i - 1]) ? $content['image'][$i - 1] : '',
						'language' => 'vi',
						'controller' => 'elFinder', // вставляем название контроллера, по умолчанию равен elfinder
						'path' => 'image', // будет открыта папка из настроек контроллера с добавлением указанной под деритории
						'filter' => 'image',    // фильтр файлов, можно задать массив фильтров https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#wiki-onlyMimes
						//'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
						'options' => ['class' => 'form-control'],
						'buttonOptions' => ['class' => 'btn btn-default'],
						'multiple' => false       // возможность выбора нескольких файлов
					]); ?>
					<div class="form-group field-magazine-title required">
						<label class="control-label" for="magazine-title">Mô tả</label>
						<input type="text" class="form-control" name="content[brief][]" value="<?= !empty($content[brief][$i - 1]) ? $content[brief][$i - 1] : '' ?>" maxlength="500" aria-required="true">
					</div>
					<div class="form-group field-magazine-title required">
						<label class="control-label" for="magazine-title">Thông tin cá nhân</label>
						<input type="text" class="form-control" name="content[info][]" value="<?= !empty($content[info][$i - 1]) ? $content[info][$i - 1] : '' ?>" maxlength="255" aria-required="true">
					</div>

					<div class="form-group field-magazine-title required">
						<label class="control-label" for="magazine-title">Vị trí</label>
						<input type="text" class="form-control" name="content[department][]" value="<?= !empty($content[department][$i - 1]) ? $content[department][$i - 1] : '' ?>" maxlength="255" aria-required="true">
					</div>
				</div>
			</div>
			<?php endfor; ?>
		</div>
    </div>
    <div role="tabpanel" class="tab-pane" id="content_background">
        <div class="form-group" style="margin-top: 10px">
			<?= $this->render('bg-linear', ['content' => $content]) ?>
		</div>
	</div>
</div>

<?php $stringJs = '
	refreshContent('.$blockColumn.');
	$(document).ready(function() {
		$("input[name=\"content[block-column]\"]").on("change", function () {
			const v = $("input[name=\"content[block-column]\"]:checked").val()
			refreshContent(+v);
		})
	})
	
	function refreshContent(i) {
		for (let j = 1; j <= 4; j++){
			if (i >= j) {
				$("#form-" + j).attr({style: "display:block"});
			} else {
				$("#form-" + j).attr({style: "display:none"});
			}
		}
	}
';
$this->registerJs($stringJs);
?>
<style>
.frm-block > div {
	width: calc(50% - 10px);
	border: 1px solid #3c8dbc;
	padding: 10px;
}
</style>