<?php
use yii\helpers\Html;
use yii\grid\GridView;
use common\components\CFunction;
use common\components\Utility;

$this->title = Yii::t('cms', 'Collection');
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'Collection'),
        'url' => ['index']
    ],
    [
        'label' => Yii::t('cms', 'app_list'),
        'template' => "<li>{link}</li>\n"
    ]
];

$this->params['title'] = Html::encode(Yii::t('cms', 'Collection'));
?>
<div class="box-header with-border" style="margin-top: 20px">
    <?= Html::a('<span class="fa fa-plus"></span> '.Yii::t('cms', 'app_create'), ['create'], ['class' => 'btn btn-outline-primary']) ?>
</div>
<div class="box-body">
    <?= GridView::widget([
        'id'=>'ajax_gridview',
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'id',
                'header' => Yii::t('cms', 'id'),
                'options' => ['width' => '20px'],
                'contentOptions'=>['style'=>'vertical-align: middle;'],
            ],
            [
                'attribute' => 'name',
                'header' => Yii::t('cms', 'name'),
                'format' => 'raw',
                'options' => ['width' => '100px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center; vertical-align: middle;']
            ],
            [
                'attribute' => 'description',
                'header' => Yii::t('cms', 'description'),
                'format' => 'raw',
                'options' => ['width' => '100px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center; vertical-align: middle;']
            ],
            [
                'attribute' => 'active',
                'header' => Yii::t('cms', 'status'),
                'format' => 'raw',
                'options' => ['width' => '80px'],
                'value' => function ($data) {
                    if ($data->status == 1) {
                        return '<span id="item-active-status-'.$data->id.'">'.Html::img(CFunction::getImageBaseUrl().'app/icon-32-check.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_inactive'), 'onclick' => 'changeActive('.$data->id.', 1,"news/change-status")']).'</span>';
                    } else {
                        return '<span id="item-active-status-'.$data->id.'">'.Html::img(CFunction::getImageBaseUrl().'app/icon-32-stop.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_active'), 'onclick' => 'changeActive('.$data->id.', 0,"news/change-status")']).'</span>';
                    }
                },
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center; vertical-align: middle;']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{add}{list}{view}{update}',
                'header' => Yii::t('cms', 'action'),
                'options' => ['width' => '130px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;'],
                'buttons' => [
                    'add' => function ($url,$data) {
                        $content = '<a title="Add News" href="javascript:void(0)" class="btn btn-outline-primary btn-xs btn-app" onclick=showPopupAdd("'.$url.'",'.$data->id.')><span class="glyphicon glyphicon-plus"></span></a>,';
                        return $content;
                    },
                    'list' => function ($url) {
                        return Html::a('<span class="glyphicon glyphicon-th-list"></span>', $url, [
                            'title' => Yii::t('cms', 'List News'),
                            'class'=>'btn btn-outline-primary btn-xs btn-app',
                            'data-pjax' => '0',

                        ]);
                    },
                    'view' => function ($url) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('cms', 'view'),
                            'class'=>'btn btn-outline-primary btn-xs btn-app',
                            'data-pjax' => '0',

                        ]);
                    },
                    'update' => function ($url) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('cms', 'update'),
                            'class'=>'btn btn-outline-primary btn-xs btn-app',
                            'data-pjax' => '0',
                        ]);
                    },
                ]
            ]
        ],
    ]); ?>
</div>
