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
if (!empty($model->content_mobile)) {
    $content_mobile = unserialize($model->content_mobile);
}
?>
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#content_web" aria-controls="home" role="tab" data-toggle="tab">Desktop</a>
    </li>
    <li role="presentation"><a href="#content_mobile" aria-controls="profile" role="tab" data-toggle="tab">Mobile</a>
    </li>
    <li role="presentation"><a href="#content_background" aria-controls="profile" role="tab" data-toggle="tab">Background</a>
    </li>
</ul>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="content_web">
        <div class="form-group">
            <label>Ảnh</label>
            <?= \cms\widgets\elfinder\InputFile::widget([
                'name' => 'content[image]',
                'value' => !empty($content['image']) ? $content['image'] : '',
                'language' => 'vi',
                'controller' => 'elFinder', // вставляем название контроллера, по умолчанию равен elfinder
                'path' => 'image', // будет открыта папка из настроек контроллера с добавлением указанной под деритории
                'filter' => 'image',    // фильтр файлов, можно задать массив фильтров https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#wiki-onlyMimes
                //'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                'options' => ['class' => 'form-control'],
                'buttonOptions' => ['class' => 'btn btn-default'],
                'multiple' => false       // возможность выбора нескольких файлов
            ]); ?>
        </div>
        <div class="form-group">
            <label class="control-label">Tiêu đề ảnh</label>
            <input type="text" class="form-control" name="content[title]"
                   value="<?= !empty($content['caption']) ? $content['caption'] : '' ?>"/>
            <div class="help-block"></div>
        </div>
        <div class="form-group">
            <label class="control-label">Mô tả</label>
            <textarea class="form-control" name="content[caption]"
                      rows="3"><?= !empty($content['caption']) ? $content['caption'] : '' ?></textarea>
            <div class="help-block"></div>
        </div>

        <div class="form-group">
            <label class="control-label">Full width</label>
            <label>
                <input type="checkbox" value="1"
                       name="content[full_width]" <?= !empty($content['full_width']) ? 'checked' : '' ?> />
            </label>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="content_mobile">
        <p style="margin-top: 10px;"><i>Không nhập sẽ lấy nội dung Desktop</i></p>
        <div class="form-group">
            <label>Ảnh</label>
            <?= \cms\widgets\elfinder\InputFile::widget([
                'name' => 'content_mobile[image]',
                'value' => !empty($content_mobile['image']) ? $content_mobile['image'] : '',
                'language' => 'vi',
                'controller' => 'elFinder', // вставляем название контроллера, по умолчанию равен elfinder
                'path' => 'image', // будет открыта папка из настроек контроллера с добавлением указанной под деритории
                'filter' => 'image',    // фильтр файлов, можно задать массив фильтров https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#wiki-onlyMimes
                //'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                'options' => ['class' => 'form-control'],
                'buttonOptions' => ['class' => 'btn btn-default'],
                'multiple' => false       // возможность выбора нескольких файлов
            ]); ?>
        </div>
        <div class="form-group">
            <label class="control-label">Tiêu đề ảnh</label>
            <input type="text" class="form-control" name="content_mobile[title]"
                   value="<?= !empty($content_mobile['caption']) ? $content_mobile['caption'] : '' ?>"/>
            <div class="help-block"></div>
        </div>
        <div class="form-group">
            <label class="control-label">Mô tả</label>
            <textarea class="form-control" name="content_mobile[caption]"
                      rows="3"><?= !empty($content_mobile['caption']) ? $content_mobile['caption'] : '' ?></textarea>
            <div class="help-block"></div>
        </div>
    </div>
	
    <div role="tabpanel" class="tab-pane" id="content_background">
        <div class="form-group" style="margin-top: 10px">
			<?= $this->render('bg-linear', ['content' => $content]) ?>
		</div>
	</div>
</div>