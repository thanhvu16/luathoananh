<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

/**
 * @var \cms\models\MagazineContent $model
 * @var \cms\models\Magazine $magazine
 * @var \yii\web\View $this
 */
$image = '';
$title = '';
$caption = '';
$class = 'alignCenterOverflow';
if (!empty($model->content)) {
    $content = unserialize($model->content);
}
if (!empty($content['full_width'])) {
    $class = 'alignJustifyFull';
}
if (!empty($content['image'])) {
    $image = Html::encode($content['image']);
}
if (!empty($content['caption'])) {
    $caption = Html::encode($content['caption']);
}
if (!empty($content['title'])) {
    $title = Html::encode($content['title']);
}

if (!empty($image)) {
    ?>
    <div class="VCSortableInPreviewMode <?= $class ?> <?= !empty($caption)?'':'noCaption' ?>" type=Photo>
        <div>
            <img src="<?= $content['image'] ?>"
                 alt="<?= $title ?>"
                 title="<?= $title ?>"
                 style="max-width: 500px"
            />
        </div>
        <?php if (!empty($caption)) { ?>
            <div class="PhotoCMS_Caption"><?= $caption ?></div>
        <?php } ?>
    </div>
<?php } ?>