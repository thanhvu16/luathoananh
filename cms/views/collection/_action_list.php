<?php
use yii\helpers\Url;
use yii\helpers\Html;

?>
<div class="dropdown grid-actions">
    <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:void(0);"><i class="fa fa-ellipsis-h fa-lg" aria-hidden="true"></i></a>
    <ul class="dropdown-menu pull-right">
        <li class="item">
            <?= Html::a('<span class="glyphicon glyphicon-eye-open"> View</span>', ['view', 'id' => $model->id], [
                'title' => Yii::t('cms', 'view'),
                'class' => '',
                'data-pjax' => '0',
            ]) ?>
        </li>
        <li class="item">
            <?= Html::a('<span class="glyphicon glyphicon-pencil"> Update</span>', ['update', 'id' => $model->id], [
                'title' => Yii::t('cms', 'update'),
                'class' => '',
                'data-pjax' => '0',
            ]) ?>
        </li>
        <li class="item">
            <?= Html::a('<span class="glyphicon glyphicon glyphicon-plus">List News</span>', 'javascript:void(0)', [
                'title' => Yii::t('cms', 'List News'),
                'class'=>'',
                'data-pjax' => '0',
                'onclick' => 'addToFilm('.$model->id.');'
            ]) ?>
        </li>
    </ul>
</div>
