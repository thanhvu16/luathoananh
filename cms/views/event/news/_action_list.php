<?php
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * @desc
 * @author vietvt <vietvt@vnext.com.vn>
 * Nov 28, 2019 12:21:57 AM
 */
?>
<div class="dropdown grid-actions">
    <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:void(0);"><i class="fa fa-ellipsis-h fa-lg" aria-hidden="true"></i></a>
    <ul class="dropdown-menu pull-right">

                <li class="item">
                    <?= Html::a('<span class="glyphicon glyphicon-eye-open"> View</span>', ['view', 'id' => $model->id], [
                            'title' => Yii::t('cms', 'view'),
                            'class'=>'',
                            'data-pjax' => '0',
                        ]) ?>
                </li>
                <li class="item">
                    <?= Html::a('<span class="glyphicon glyphicon-pencil"> Update</span>', ['update', 'id' => $model->id], [
                            'title' => Yii::t('cms', 'update'),
                            'class'=>'',
                            'data-pjax' => '0',
                        ]) ?>
                </li>
                <li class="item text-danger text-bold" style="border-top: 1px solid #e7eaec;">
                    <?= Html::a('<span class="glyphicon glyphicon-trash"> Delete</span>', ['delete', 'id' => $model->id], [
                            'title' => Yii::t('cms', 'delete'),
                            'class'=>'',
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => 'w0'
                        ]) ?>
                </li>


    </ul>
</div>