<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

/**
 * @var \cms\models\MagazineContent $model
 * @var \cms\models\Magazine $magazine
 * @var \yii\web\View $this
 */
$video = '';
if (!empty($model->content)) {
    $content = unserialize($model->content);
}

if (!empty($content['video'])) {
    if(strpos($content['video'], '?v=')){
        $explode = explode('?v=', $content['video']);
        $video = $explode[1];
    }else {
        $video = Html::encode($content['video']);
    }
}


if (!empty($video)) {
    ?>
    <div class="VCSortableInPreviewMode <?= !empty($caption)?'':'noCaption' ?> clearfix" type="VideoStream" >
        <iframe width="100%" height="auto" src="<?= 'https://www.youtube.com/embed/'.$video ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
    </div>
<?php } ?>