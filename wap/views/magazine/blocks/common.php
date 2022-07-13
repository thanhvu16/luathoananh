<?php
if (empty($common)) {
	return;
}
$value = !empty($common['title']) ? $common['title'] : '';
$color = !empty($common['color']) ? $common['color'] : '#000';
$position = !empty($common['position']) ? $common['position'] : 'center';
$tag = !empty($common['tag']) ? $common['tag'] : 'h2';
$size = !empty($common['size']) ? $common['size'] : '16';

?>

<<?= $tag ?> style="font-size: <?= $size ?>px;color: <?= $color ?>;text-align: <?= $position ?>"><?= $value ?></<?= $tag ?>>