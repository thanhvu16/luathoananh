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
            <label class="control-label">Nội dung</label>
            <?php
            echo CKEditor::widget([
                'name' => 'content[content]',
                'value' => !empty($content['content']) ? $content['content'] : '',
                'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions([
                    'elFinder',
                ])
            ]);
            ?>
            <div class="help-block"></div>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="content_mobile">
        <p style="margin-top: 10px;"><i>Không nhập sẽ lấy nội dung Desktop</i></p>
        <div class="form-group">
            <label class="control-label">Nội dung</label>
            <?php
            echo CKEditor::widget([
                'name' => 'content_mobile[content]',
                'value' => !empty($content_mobile['content']) ? $content_mobile['content'] : '',
                'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions([
                    'elFinder',
                ])
            ]);
            ?>
            <div class="help-block"></div>
        </div>
    </div>
	
    <div role="tabpanel" class="tab-pane" id="content_background">
        <div class="form-group" style="margin-top: 10px">
			<?= $this->render('bg-linear', ['content' => $content]) ?>
		</div>
	</div>
</div>
