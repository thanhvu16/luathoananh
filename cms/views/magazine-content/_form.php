<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model cms\models\MagazineContent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="magazine-content-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $this->render('//magazine-content/blocks/'.$model->block_type, [
            'model' => $model,
            'form' => $form,
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>


<style>
.block-common {
	margin-bottom: 5px;
	display: flex;
	justify-content: space-between;
}
.preview-common {
	margin-top: 5px !important;
	min-height: 40px;
	width: 100%;
	padding: 5px;
	border: 1px solid #ccc;
}
.preview-common > * {
	line-height: 30px;
	margin-bottom: 0px;
	margin-top: 0px !important;
}
.common-title {
	display: flex;
}
.hidden-common {
	position: absolute;
	top: 0px;
	right: 0px;
	width: 0px;
	background: none;
	border: none;
	visibility: hidden;
}
.button-common {
	margin: 0 2px;
	background: #3c8dbc;
	border: #3c8dbc;
	height: 30px;
	color: #fff;
	border-radius: 5px;
}
.dropdown-menu {
    border: medium none;
    box-shadow: 0 0 3px rgb(86 96 117 / 70%);
    display: none;
    float: left;
    font-size: 12px;
    left: 0;
    list-style: none outside none;
    padding: 0;
    position: absolute;
    text-shadow: none;
    top: 100%;
    z-index: 1000;
    border-radius: 2px;
}
.dropdown-menu .dropdown-item {
    display: block;
    padding: 5px;
    color: #000;
}
.dropdown-menu .dropdown-item.active {
	background: #3c8dbc;
	color: #fff;
}
</style>

<script>
function setColor(name) {
	$('#color-' + name).click();
}
function previewCommon(name) {
	let color = $('#color-' + name).val();
	let v = $('#' + name + '-block').val();
	let position = $('#position-' + name).val();
	let tag = $('#tag-' + name).val();
	let size = $('#size-' + name).val();
	let html = '<' + tag + ' style="font-size: ' + size + 'px;color: ' + color + ';text-align: '+ position +'">' + v + '</' + tag + '>';
	$('#preview-common-'+ name).html(html);
}
function changePosition(position, name) {
	$('#' + name + '-position-left').removeClass('active')
	$('#' + name + '-position-right').removeClass('active')
	$('#' + name + '-position-center').removeClass('active')
	$('#' + name + '-position-' + position).addClass('active')
	console.log('#' + name + '-position-left', '#' + name + '-position-' + position)
	$('#position-' + name).val(position);
}
function changeSize(size, name) {
	$('.drop-size-' + name).removeClass('active');
	$('#' + name + '-size-' + size).addClass('active');
	$('#size-' + name).val(size);
}
function changeTag(tag, name) {
	$('.drop-tag-' + name).removeClass('active');
	$('#' + name + '-tag-' + tag).addClass('active');
	$('#tag-' + name).val(tag);
}
</script>