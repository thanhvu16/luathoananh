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
$common = [];
if (!empty($model->content)) {
    $content = unserialize($model->content);
	if (!empty($content['common']))
		$common = $content['common'];
}

?>
<div class="block-magazine">
	<?php foreach ($common as $key => $v):
		echo $this->render('common', ['common' => $v]);
	endforeach; ?>
	<div class="block-question">
	<?php if (!empty($content['content'])): ?>
	<?php foreach ($content['content'] as $v): ?>
		<div class="block-content-magazine block-content-magazine-price row-<?= $content['block-column'] ?>">
			<?= $v ?>
		</div>
	<?php endforeach; ?>
	<?php endif; ?>
	</div>
</div>