<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

/**
 * @var \cms\models\MagazineContent $model
 * @var \cms\models\Magazine $magazine
 * @var \yii\web\View $this
 */
$image = '';
$text = '';
$class = 'alignCenterOverflow';
$content = null;
$width = 100;
$common = [];
if (!empty($model->content)) {
    $content = unserialize($model->content);
	$width = round(100 / $content['question-column']);
	if (!empty($content['common']))
		$common = $content['common'];
}
?>
<div class="block-magazine">
	<?php foreach ($common as $key => $v):
		echo $this->render('common', ['common' => $v]);
	endforeach; ?>
	<div class="block-question">
	<?php if (!empty($content['faqs'])): ?>
	<?php $i = 1; ?>
	<?php foreach ($content['faqs'] as $key => $faq): ?>
		<div class="question-answer">
			<div class="stt">
				<?= $i++ ?>
			</div>
			<h3 class="question"><?= $faq['question'] ?></h3>
			<p class="answer"><?= $faq['answer'] ?></p>
		</div>
	<?php endforeach; ?>
	<?php endif; ?>
	</div>
</div>

<style>
.block-magazine {
	padding: 30px 0px;
}
.title-block-magazine {
	color: #F44025 !important;
	font-size: 22px;
	text-align: center;
}
.brief-block-magazine {
	font-size: 14px;
	text-align: center;
}
.block-question {
	margin-top: 15px;
	display: flex;
	justify-content: space-between;
	flex-wrap: wrap;
}
.question-answer {
	width: calc(<?= $width ?>% - 5px);
	position: relative;
	padding-left: 50px;
	min-height: 100px;
}
.stt {
	position: absolute;
	width: 40px;
	height: 40px;
	left: 0px;
	top: 5px;
	background: #0A67E9;
	color: #fff;
	text-align: center;
	line-height: 40px;
	font-size: 18px;
}
.question {
	color: #05224A !important;
	font-size: 20px;
	font-weight: 600;
}

.answer {
	font-size: 14px;
	padding: 0px;
	background: none;
	margin: 0px 0 0 0 !important;
}
@media screen and (max-width: 768px) {
	.question-answer {
		width: 100%;
	}
}
</style>
