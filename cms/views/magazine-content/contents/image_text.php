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
if (!empty($model->content)) {
    $content = unserialize($model->content);
}
if(!empty($content['type']) && !empty($content['type']) && $content['type']=='left'){
    $class = 'alignLeftOverflow ';
}elseif(!empty($content['type']) && !empty($content['type']) && $content['type']=='right'){
    $class = 'alignRightOverflow ';
}
if(!empty($content['image'])){
    $image = '<div class="VCSortableInPreviewMode '.$class.' noCaption" type=Photo >
        <div>
            <img src="'.$content['image'].'"
                  alt="'.Html::encode($magazine->title).'"
                  title="'.Html::encode($magazine->title).'"
                  style="max-width: 500px"
                  />
        </div>
        <div class="PhotoCMS_Caption"></div>
    </div>';
}
if(!empty($content['text'])){
    $text = HtmlPurifier::process($content['text']);
}
if(!empty($content['type']) && !empty($content['type']) && $content['type']=='under') {
    echo $text;
    echo $image;
}else{
    echo $image;
    echo $text;
}
?>
