<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

/**
 * @var \cms\models\MagazineContent $model
 * @var \yii\web\View $this
 */

if(!empty($model->content)){
    $content = unserialize($model->content);
}
if(!empty($content['content'])){
 echo '<div class="block-simple">'.$content['content'].'</div>';
}
?>

