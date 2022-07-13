<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
/**
* @var \cms\models\MagazineContent $model
 * @var ActiveForm $form
 * @var \yii\web\View $this
 */
if(!empty($model->content)){
    $content = unserialize($model->content);
}
?>
<div class="form-group">
    <label class="control-label">Số Ảnh trên 1 hàng</label>
    <input name="content[number_on_row]" value="<?= !empty($content['number_on_row']) ? $content['number_on_row'] : '2'; ?>" class="form-control" />
</div>
<div class="form-group">
	<div class="form-group" style="margin-top: 10px">
		<label>Background</label>
		<div>
		<?= $this->render('bg-linear', ['content' => $content]) ?>
		</div>
	</div>
    <label class="control-label">Hình ảnh</label>
    <div>
        <?= \cms\widgets\elfinder\MediaInput::widget([
            'name' => 'content[images]',
            'imagePreviews' => !empty($content['images']) ? $content['images'] : '',
            'language' => 'vi',
            'controller' => 'elFinder', // вставляем название контроллера, по умолчанию равен elfinder
            'path' => 'image', // будет открыта папка из настроек контроллера с добавлением указанной под деритории
            'filter' => 'image',    // фильтр файлов, можно задать массив фильтров https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#wiki-onlyMimes
            'options' => ['class' => 'form-control'],
            'buttonOptions' => ['class' => 'btn btn-default'],
            'multiple' => true       // возможность выбора нескольких файлов
        ]); ?>

    </div>
    <div class="help-block"></div>
</div>
