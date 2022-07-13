<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cms\models\Magazine;
use cms\models\MagazineContent;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use mihaildev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model cms\models\Magazine */
/* @var $form yii\widgets\ActiveForm */

$actionId = Yii::$app->controller->action->id;
$isUpdate = $actionId == 'update' ? true : false;
$tab = Yii::$app->request->get('tab');

$typeContents = MagazineContent::getAllTypes();
?>

<div class="magazine-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php if ($isUpdate) { ?>
        <ul class="nav nav-tabs">
            <li class="<?= ($tab != 'content') ? 'active' : '' ?>"><a data-toggle="tab" href="#magazin_info">Thông
                    tin</a></li>
            <li class="<?= ($tab == 'content') ? 'active' : '' ?>"><a data-toggle="tab" href="#magazin_content">Nội
                    dung</a></li>
        </ul>
    <?php } ?>
    <?php if ($isUpdate){ ?>
    <div class="tab-content">
        <div id="magazin_info" class="tab-pane fade <?= ($tab != 'content') ? 'in active' : '' ?>"
             style="padding: 20px 0;">
            <?php } ?>
            <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

            <?= $form->field($model, 'sapo')->textarea(['maxlength' => 500, 'rows' => 3]) ?>

            <?php //echo $form->field($model, 'image')->textInput(['maxlength' => 255]) ?>
            <?= $form->field($model, 'image')->widget(\cms\widgets\elfinder\InputFile::className(), [
                'language' => 'vi',
                'controller' => 'elFinder', // вставляем название контроллера, по умолчанию равен elfinder
                'path' => 'image', // будет открыта папка из настроек контроллера с добавлением указанной под деритории
                'filter' => 'image',    // фильтр файлов, можно задать массив фильтров https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#wiki-onlyMimes
                'options' => ['class' => 'form-control'],
                'buttonOptions' => ['class' => 'btn btn-default'],
                'multiple' => false       // возможность выбора нескольких файлов
            ])->label('Ảnh đại diện'); ?>
            <div class="row">
                <div class="col-sm-6"><?= $form->field($model, 'image_cover_web')->widget(\cms\widgets\elfinder\InputFile::className(), [
                        'language' => 'vi',
                        'controller' => 'elFinder', // вставляем название контроллера, по умолчанию равен elfinder
                        'path' => 'image', // будет открыта папка из настроек контроллера с добавлением указанной под деритории
                        'filter' => 'image',    // фильтр файлов, можно задать массив фильтров https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#wiki-onlyMimes
                        'options' => ['class' => 'form-control'],
                        'buttonOptions' => ['class' => 'btn btn-default'],
                        'multiple' => false       // возможность выбора нескольких файлов
                    ])->label('Ảnh Corver WEB')->hint('Ảnh cover trong bài viết chi tiết trên web'); ?></div>
                <div class="col-sm-6"><?= $form->field($model, 'image_cover_wap')->widget(\cms\widgets\elfinder\InputFile::className(), [
                        'language' => 'vi',
                        'controller' => 'elFinder', // вставляем название контроллера, по умолчанию равен elfinder
                        'path' => 'image', // будет открыта папка из настроек контроллера с добавлением указанной под деритории
                        'filter' => 'image',    // фильтр файлов, можно задать массив фильтров https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#wiki-onlyMimes
                        'options' => ['class' => 'form-control'],
                        'buttonOptions' => ['class' => 'btn btn-default'],
                        'multiple' => false       // возможность выбора нескольких файлов
                    ])->label('Ảnh Corver WAP')->hint('Ảnh cover trong bài viết chi tiết trên Mobile'); ?></div>
            </div>
			
            <div class="row">
				<div class="col-sm-6">
					<?= $form->field($model, 'text_cover')->textInput(['maxlength' => 255]) ?>
				</div>
				<div class="col-sm-6">
					<?= $form->field($model, 'link_cover')->textInput(['maxlength' => 255]) ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label">Nội dung Cover</label>
				<?php
				echo CKEditor::widget([
					'name' => 'Magazine[content_cover]',
					'value' => !empty($model->content_cover) ? $model->content_cover : '',
					'editorOptions' => [
						'toolbar' => [
							['Source'],
							['Cut','Copy','Paste','PasteText','PasteFromWord','Undo','Redo'],
							['Bold','Italic','Underline','Strike','Subscript','Superscript'],
							['CopyFormatting','RemoveFormat'],
							['NumberedList','BulletedList'],
							['Outdent','Indent','Blockquote','CreateDiv'],
							['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
							['Styles','Format','Font','FontSize','TextColor','BGColor','Maximize','ShowBlocks']
						]
					]
				]);
				?>
			</div>
            <?= $form->field($model, 'background')->textInput(['maxlength' => 255])->label('Màu nền') ?>
            <div class="row">
                <div class="col-sm-6"><?= $form->field($model, 'source')->textInput(['maxlength' => 255])->label('Nguồn') ?></div>
                <div class="col-sm-6"><?= $form->field($model, 'source_link')->textInput(['maxlength' => 255])->label('Link Nguồn') ?></div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'designer')->textInput(['maxlength' => 255]) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'author')->textInput(['maxlength' => 255]) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'author_image')->textInput(['maxlength' => 255]) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'clip')->textInput(['maxlength' => 255]) ?>
                </div>
            </div>

            <?= $form->field($model, 'seo_keywords')->textarea(['maxlength' => 500, 'rows' => 3]) ?>

            <?= $form->field($model, 'seo_description')->textarea(['maxlength' => 500, 'rows' => 3]) ?>

            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'status')->dropDownList(Magazine::getListStatus()) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'public_time')->widget(\kartik\datetime\DateTimePicker::className(), [
                        'options' => ['style' => 'width: 200px;'],
                        'pluginOptions' => ['autoclose' => true, 'format' => 'yyyy-mm-dd hh:ii:ss']
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    $relatedNews = [];
                    if(!empty($model->rel_ids)){
                        $dataRels = explode(',', $model->rel_ids);
                        $relatedNews = \api\models\News::find()->where(['id' => $dataRels])->all();
                    }
                    ?>
                    <div class="form-group panel panel-primary">
                        <div class="panel-heading">Bài viết liên quan</div>
                        <div class="panel-body">
                            <div class="form-group" style="border: 1px solid #ccc;min-height: 50px">
                                <ul class="frm-rel">
                                    <?php
                                    if(!empty($relatedNews)){
                                    foreach ($relatedNews as $rel) { ?>
                                        <li style="padding: 5px;margin:5px;background: #cccccc;position: relative">
                                            <input class="ip_rel" name="rel_ids[]" type="hidden" value="<?php echo $rel['id'] ?>">
                                            <?php echo $rel['title'] ?>
                                            <span class="fa fa-remove" onclick="removeItemRel(this)" style="color: red;cursor:pointer;position: absolute;top: -6px;right: -4px"></span>
                                        </li>
                                    <?php } ?>
                                    <?php } ?>
                                </ul>
                            </div>
                            <a href="javascript:void(0);" class="btn btn-outline-primary" onclick="showPopupNews('news/popup', 1);return false;">Thêm bài viết liên quan</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php if ($isUpdate){ ?>
    </div>
    <div id="magazin_content" class="tab-pane fade <?= ($tab == 'content') ? 'in active' : '' ?>"
         style="padding: 20px 0;">
        <div class="row">
            <div class="col-sm-12 wrap-magazine-block">
                <ul id="magazine-blocks" class="list-group" id="magazine-blocks">
                    <?php
                    $blockContents = MagazineContent::find()->where(['magazine_id' => $model->id])->orderBy('sort_order')->all();
                    if (!empty($blockContents)) {
                        foreach ($blockContents as $blockContent) {
                            if (empty($typeContents[$blockContent->block_type])) continue;
                            $typeContent = $typeContents[$blockContent->block_type];
                            echo $this->render('//magazine-content/create', [
                                'typeContent' => $typeContent,
                                'model' => $blockContent,
                                'magazine' => $model
                            ]);
                        }
                    } ?>
                </ul>
                <div>
                    <button type="button" class="btn btn-primary pull-right"
                            onclick="showModalBlocks('<?= \yii\helpers\Url::toRoute(['magazine/block-type', 'id' => $model->id]) ?>')">
                        <i class="fa fa-plus"></i> Thêm khối nội dung
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Thêm mới' : 'Cập nhật', ['class' => 'btn btn-outline-primary']) ?>
    <?php //echo Html::resetButton('Reset', ['class' => 'btn btn-default']); ?>
</div>

<?php ActiveForm::end(); ?>

</div>
<style>
    .wrap-magazine-block .dropdown-menu {
        right: 0;
        left: unset;
    }

    @media (min-width: 1400px) {
        .modal-lg {
            width: 1280px;
        }
    }

    #magazine-blocks li {
        cursor: move;
    }

    img {
        max-width: 100%;
    }
</style>
<?php
$strJs = '
        $(\'#magazine-blocks\').sortable();
    ';
$this->registerJs($strJs);
?>
<script>
    function removeItemRel(e) {
        e.closest('li').remove();
    }
</script>
