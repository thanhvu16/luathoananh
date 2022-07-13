<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use common\components\CFunction;
use yii\grid\GridView;
use common\components\Utility;

/* @var $this yii\web\View */
/* @var $searchModel cms\models\search\MagazineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = $this->params['title'] = 'Magazines';
$this->params['breadcrumbs'][] = $this->title;
$this->params['menu'] = [
    ['label'=>'Create', 'url' => ['create'], 'options' => ['class' => 'btn btn-outline-primary']],
    ['label'=>'Delete', 'url' => 'javascript:void(0)', 'options' => ['class' => 'btn btn-outline-primary', 'onclick' => 'deleteAllItems()']]
];
?>
<?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<div class="box box-body">
<?php Pjax::begin(['id' => 'admin-grid-view']);?> 
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            'id',
			[
				'attribute' => 'title',
				'header' => Yii::t('cms', 'Title'),
				'format' => 'raw',
				'options' => ['width' => '280px'],
				'value' => function ($data) {
					$urlReview = 'https://luathoanganh.vn/magazine/' . rtrim(Utility::rewrite($data->title), '-') .'-' . $data->id .'.html';
					return '<a href="'.$urlReview.'" target="_blank">'.$data->title.'</a>';
				},
			],
            'sapo',
			[
				'attribute' => 'active',
				'header' => Yii::t('cms', 'Bài viết HOT'),
				'format' => 'raw',
				'options' => ['width' => '80px'],
				'value' => function ($data) {
					if ($data->is_hot == 1) {
						return '<span id="item-active-status-hot-'.$data->id.'">'.Html::img(CFunction::getImageBaseUrl().'app/icon-32-check.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_inactive'), 'onclick' => 'changeActiveHot('.$data->id.', 1,"magazine/change-status-hot")']).'</span>';
					} else {
						return '<span id="item-active-status-hot-'.$data->id.'">'.Html::img(CFunction::getImageBaseUrl().'app/icon-32-stop.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_active'), 'onclick' => 'changeActiveHot('.$data->id.', 0,"magazine/change-status-hot")']).'</span>';
					}
				},
				'headerOptions' => ['style'=>'text-align: center;'],
				'contentOptions'=>['style'=>'text-align: center; vertical-align: middle;']
			],
            'status',
            'public_time',
            // 'image',
            // 'created_time',
            // 'updated_time',
            // 'created_by',
            // 'updated_by',
            // 'seo_keywords',
            // 'seo_description',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'header' => Yii::t('cms', 'action'),
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;'],
                'buttons' => [
                    'update' => function ($url) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => 'Update',
                            'class'=>'btn btn-primary btn-xs btn-app',
                            'data-pjax' => '0',
                        ]);
                    },
                    'delete' => function ($url) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => 'Delete',
                            'class'=>'btn btn-primary btn-xs btn-app',
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => 'w0'
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>
<?php Pjax::end();?>
</div>