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
	$width = round(100 / $content['block-column']);
	if (!empty($content['common']))
		$common = $content['common'];
}

?>
<div class="block-magazine">
	<?php foreach ($common as $key => $v):
		echo $this->render('common', ['common' => $v]);
	endforeach; ?>
	<div class="block-question">
	<?php for ($i = 0; $i < $content['block-column']; $i++): ?>
		<div class="block-content-magazine row-<?= $content['block-column'] ?>">
			<div class="block-say">
				<div>
					<img class="img-circle" src="<?= $content['image'][$i] ?>" width="80" height="80" />
				</div>
				<p class="brief-say"><?= $content['brief'][$i] ?></p>
			</div>
			<p class="info-say"><?= $content['info'][$i] ?></p>
			<p class="department-say"><?= $content['department'][$i] ?></p>
		</div>
	<?php endfor; ?>
	</div>
</div>