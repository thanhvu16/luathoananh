<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel cms\models\search\TeamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = $this->params['title'] = 'Teams';
$this->params['breadcrumbs'][] = $this->title;
/*$this->params['menu'] = [
    ['label' => 'Create', 'url' => ['create'], 'options' => ['class' => 'btn btn-primary']],
    ['label' => 'Delete', 'url' => 'javascript:void(0)', 'options' => ['class' => 'btn btn-danger', 'onclick' => 'deleteAllItems()']]
];*/
?>

<?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<div class="box-body">
    <?php Pjax::begin(['id' => 'admin-grid-view']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            [
                'attribute' => 'teamId',
                'header' => Yii::t('cms', 'Id'),
                'format' => 'raw',
                'options' => ['width' => '100px']
            ],
            [
                'header' => Yii::t('cms', 'Logo'),
                'format' => 'raw',
                'options' => ['width' => '100px'],
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'value' => function ($data) {
                    $imagesUrl = \common\components\Utility::getImageFb($data->logo);
                    return ($imagesUrl) ? Html::img($imagesUrl, ['height' => '60', 'title' => $data->{'name'}]) : null;
                }
            ],
            /*[
                'attribute' => 'leagueId',
                'header' => Yii::t('cms', 'Giải đấu'),
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'value' => function ($data) {
                    if (!empty($data->custom_name)) {
                        return $data->custom_name;
                    }
                    return $data->name;
                },
                'filter' => Select2::widget(
                    [
                        'model' => $searchModel,
                        'attribute' => 'leagueId',
                        'data' => $leagues,
                        'options' => ['placeholder' => ' --Filter by user id-- '],
                        'language' => 'en',
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]
                ),
            ],*/[
                'attribute' => 'name',
                'header' => Yii::t('cms', 'Tên đội bóng'),
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'value' => function ($data) {
                    if (!empty($data->custom_name)) {
                        return $data->custom_name;
                    }
                    return $data->name;
                }
            ],

            // 'foundingDate',
            // 'address',
            // 'area',
            // 'venue',
            // 'capacity',
            // 'coach',
            [
                'attribute' => 'website',
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
            ],
            // 'created_time',
            // 'updated_time',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'header' => Yii::t('cms', 'action'),
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center;'],
                'buttons' => [
                    'update' => function ($url) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => 'Update',
                            'class' => 'btn btn-primary btn-xs btn-app',
                            'data-pjax' => '0',
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>