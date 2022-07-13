<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\components\Utility;

$this->params['breadcrumbs'][] = ['label' => Yii::t('cms', 'mnu_news'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Html::encode(Yii::t('cms', 'app_detail'));


$this->title = Yii::$app->name.' - '.Yii::t('cms', 'app_detail');
$this->params['title'] = Html::encode(Yii::t('cms', 'app_detail'));
?>
<div class="box-header with-border">
    <?= Html::a('<i class="fa fa-list" aria-hidden="true"></i> '.Yii::t('cms', 'app_list'), ['index'], ['class' => 'btn btn-outline-primary']) ?>
</div>
<div class="collection-type-view box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            [
                'label' => Yii::t('cms', 'image'),
                'format'=>'raw',
                'value' => Html::img( Utility::makeImgNews($model,'news_img_options_small'),['width'=>'80'])
            ],

            [
                'label' => Yii::t('cms', 'category'),
                'value' => ($model->category) ? $model->category->{"title"} : null
            ],
            //[
            //    'label' => Yii::t('cms', 'sort'),
            //    'value' => $model->sort
            //],
            [
                'label' => Yii::t('cms', 'created_by'),
                'value' => ($model->createdBy) ? $model->createdBy->fullname : null
            ],
            [
                'label' => Yii::t('cms', 'updated_by'),
                'value' => ($model->updatedBy) ? $model->updatedBy->fullname : null
            ],
            [
                'label' => Yii::t('cms', 'created_time'),
                'value' => $model->created_time
            ],
            [
                'label' => Yii::t('cms', 'updated_time'),
                'value' => $model->updated_time
            ],
        ],
    ]) ?>
</div>