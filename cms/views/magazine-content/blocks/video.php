<?php

use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/**
 * @var \cms\models\MagazineContent $model
 * @var ActiveForm $form
 * @var \yii\web\View $this
 */

if (!empty($model->content)) {
    $content = unserialize($model->content);
}

$sources = [
        'youtube' => 'Youtube'
];
?>

<div class="form-group">
    <label class="control-label">Nguồn Video</label>
    <?= Html::dropDownList('content[source]',!empty($content['source']) ? $content['source'] : '', $sources, ['class' => 'form-control'] ) ?>
    <div class="help-block"></div>
</div>
<div class="form-group">
	<div class="form-group" style="margin-top: 10px">
		<label>Background</label>
		<div>
		<?= $this->render('bg-linear', ['content' => $content]) ?>
		</div>
	</div>
    <label>Link Video</label>
    <?= \mihaildev\elfinder\InputFile::widget([
        'name' => 'content[video]',
        'value' => !empty($content['video']) ? $content['video'] : '',
        'language' => 'vi',
        'controller' => 'elFinder', // вставляем название контроллера, по умолчанию равен elfinder
        'path' => 'image', // будет открыта папка из настроек контроллера с добавлением указанной под деритории
        'filter' => 'video',    // фильтр файлов, можно задать массив фильтров https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#wiki-onlyMimes
        //'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
        'options' => ['class' => 'form-control'],
        'buttonOptions' => ['class' => 'btn btn-default'],
        'multiple' => false       // возможность выбора нескольких файлов
    ]); ?>
</div>

