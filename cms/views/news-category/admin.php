<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use common\components\CFunction;

$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'mnu_news_category'),
        'url' => ['admin']
    ],
    [
        'label' => Yii::t('cms', 'app_list'),
        'template' => "<li>{link}</li>\n"
    ]
];
$this->params['menu'] = [
    ['label'=>Yii::t('cms', 'app_create'), 'url' => ['create'], 'options' => ['class' => 'btn btn-outline-primary']],
    ['label'=>Yii::t('cms', 'app_delete'), 'url' => 'javascript:void(0)', 'options' => ['class' => 'btn btn-outline-primary', 'onclick' => 'deleteAllItems(\'news-category/delete-all\', \'news-category/admin\')']]
];
$this->params['title'] = Html::encode(Yii::t('cms', 'mnu_news_category'));
?>
<?php Pjax::begin(['id' => 'news-category-grid-view']); ?>
    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'options' => ['width' => '40px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;']
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function($data) {
                    return '<div class="menu-category-">'.'<span>'.Html::encode($data->{"title"}).'</span>'.'</div>';
                },
                'options' => ['width' => '400px'],
            ],
            [
                'attribute' => 'desc',
                'filter' => false,
                'format' => 'raw',
                'options' => ['width' => '200px'],
            ],
            [
                'attribute' => 'active',
                'format' => 'raw',
                'options' => ['width' => '80px'],
                'value' => function ($data) {
                    if ($data->active == 1) {
                        return '<span id="item-active-status-'.$data->id.'">'.Html::img(CFunction::getImageBaseUrl().'app/icon-32-check.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_inactive'), 'onclick' => 'changeStatusItems('.$data->id.', 1, \'menu/change-status\')']).'</span>';
                    } else {
                        return '<span id="item-active-status-'.$data->id.'">'.Html::img(CFunction::getImageBaseUrl().'app/icon-32-stop.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_active'), 'onclick' => 'changeStatusItems('.$data->id.', 0, \'menu/change-status\')']).'</span>';
                    }
                },
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;']
            ],
            [
                'header' => Yii::t('cms', 'sort'),
                'content' => function($data) {
                    $url = Yii::$app->urlManager->createUrl(['news-category/sort']);
                    if ($data->{'title'} != 'Root') {
                        return '<a href="javascript:;">'.Html::img(CFunction::getImageBaseUrl().'app/up.png', ['title' => Yii::t('cms', 'up'), 'onclick' => 'ajaxGridSort("news-category-grid-view", '.$data->id.', "'.$url.'", "up")']).'</a>'
                        .'&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:;">'.Html::img(CFunction::getImageBaseUrl().'app/down.png', ['title' => Yii::t('cms', 'down'), 'onclick' => 'ajaxGridSort("news-category-grid-view", '.$data->id.', "'.$url.'", "down")']).'</a>';
                    }
                },
                'options' => ['width' => '60px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}{delete}{permission}',
                'header' => Yii::t('cms', 'action'),
                'options' => ['width' => '80px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;'],
                'buttons' => [
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
                    'delete' => function ($url) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('cms', 'delete'),
                            'class'=>'btn btn-outline-primary btn-xs btn-app',
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => 'w0'
                        ]);
                    },
                ]
            ]
        ]
    ]); ?>
<?php Pjax::end(); ?>