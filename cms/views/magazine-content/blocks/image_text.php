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
            <label>Vị trí ảnh</label>
            <div>
                <input class="position-image-text" id="pimgt-left" type="radio" name="content[type]"
                       value="above" <?= !empty($content['type']) && $content['type'] == 'above' ? 'checked' : '' ?> />
                <label for="pimgt-left"> Trên </label>
                <input class="position-image-text" id="pimgt-right" type="radio" name="content[type]"
                       value="under" <?= !empty($content['type']) && $content['type'] == 'under' ? 'checked' : '' ?> />
                <label for="pimgt-right"> Dưới </label>
                <input class="position-image-text" id="pimgt-right" type="radio" name="content[type]"
                       value="left" <?= !empty($content['type']) && $content['type'] == 'left' ? 'checked' : '' ?> />
                <label for="pimgt-right"> Bên trái</label>
                <input class="position-image-text" id="pimgt-right" type="radio" name="content[type]"
                       value="right" <?= !empty($content['type']) && $content['type'] == 'right' ? 'checked' : '' ?> />
                <label for="pimgt-right"> Bên phải</label>
            </div>
        </div>
        <div id="it_size_image" class="form-group"
             style="<?= (!empty($content['type']) && ($content['type'] == 'right' || $content['type'] == 'right')) ? '' : 'display:none;' ?>">
            <label>Độ rộng ảnh</label>
            <input onchange="changeWidthImage(this)" name="content[image_width]" type="range" min="1" max="100"
                   value="<?= !empty($content['image_width']) ? $content['image_width'] : 50 ?>">
        </div>
        <div class="form-group">
            <label class="control-label">Nội dung</label>
            <div id="content-image_text" style="display: flex; overflow: hidden;">
                <div id="image_wrap"
                     style="padding-right: 10px; width: <?= !empty($content['image_width']) ? $content['image_width'] : 50 ?>%">
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
                <div id="text_wrap"
                     style="padding-left: 10px; width: <?= !empty($content['image_width']) ? 100 - $content['image_width'] : 50 ?>%">
                    <?php
                    echo CKEditor::widget([
                        'name' => 'content[text]',
                        'value' => !empty($content['text']) ? $content['text'] : '',
                        'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions([
                            'elFinder',
                        ])
                    ]);
                    ?>
                </div>
            </div>
            <div class="help-block"></div>
        </div>
        <?php
        $str = '
        $(\'.position-image-text\').change(function(){
            console.log($(this).val());
            var vType = $(this).val();
            if(vType==\'right\' || vType==\'left\'){
                $(\'#it_size_image\').show();
            }else{
                $(\'#it_size_image\').hide();
            }
        });
    ';
        $this->registerJs($str);

        ?>
    </div>

    <div role="tabpanel" class="tab-pane" id="content_mobile">
        <p style="margin-top: 10px;"><i>Không nhập sẽ lấy nội dung Desktop</i></p>
        <div class="form-group">
            <label class="control-label">Nội dung</label>
            <div id="content-image_text" style="display: flex; overflow: hidden;">
                <div id="image_wrap"
                     style="padding-right: 10px; width: <?= !empty($content['image_width']) ? $content['image_width'] : 50 ?>%">
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
                <div id="text_wrap"
                     style="padding-left: 10px; width: <?= !empty($content['image_width']) ? 100 - $content['image_width'] : 50 ?>%">
                    <?php
                    echo CKEditor::widget([
                        'name' => 'content_mobile[text]',
                        'value' => !empty($content_mobile['text']) ? $content_mobile['text'] : '',
                        'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions([
                            'elFinder',
                        ])
                    ]);
                    ?>
                </div>
            </div>
            <div class="help-block"></div>
        </div>
    </div>
	
    <div role="tabpanel" class="tab-pane" id="content_background">
        <div class="form-group" style="margin-top: 10px">
			<?= $this->render('bg-linear', ['content' => $content]) ?>
		</div>
	</div>
</div>
