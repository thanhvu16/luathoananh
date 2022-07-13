<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use common\components\CFunction;
use common\components\Language;

$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'mnu_system_menu'),
        'url' => ['admin']
    ],
    [
        'label' => Yii::t('cms', 'app_list'),
        'template' => "<li>{link}</li>\n"
    ]
];
$this->params['menu'] = [
    ['label'=>Yii::t('cms', 'app_create'), 'url' => ['create'], 'options' => ['class' => 'btn btn-outline-primary']],
    ['label'=>Yii::t('cms', 'app_delete'), 'url' => 'javascript:void(0)', 'options' => ['class' => 'btn btn-outline-primary', 'onclick' => 'deleteAllItems(\'menu/delete-all\', \'menu/admin\')']]
];
$this->params['title'] = Html::encode(Yii::t('cms', 'mnu_system_menu'));
?>
<?php Pjax::begin(['id' => 'menu-grid-view', 'enablePushState' => false]); ?>
    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'options' => ['width' => '40px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;']
            ],
            [
                'attribute' => 'title_'.Language::language(),
                'format' => 'raw',
                'filter' => false,
                'value' => function($data) {
                    $language = Language::language();
                    return '<div class="menu-category-'.$data->level.'">'.'<span>'.Html::encode($data->{"title_$language"}).'</span>'.'</div>';
                },
                'options' => ['width' => '350px'],
            ],
            [
                'header' => Yii::t('cms', 'image'),
                'format' => 'raw',
                'options' => ['width' => '80px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;'],
                'value'=>function($data){
                    $imagesUrl =\common\components\Utility::makeThumbnail((object)$data,'menu_icon',1);
                    return ($imagesUrl) ?
                        '<a href="'.\yii\helpers\Url::toRoute(['menu/update', 'id' => $data['id']]).'">'.
                        Html::img($imagesUrl,['height'=>'60','style'=>' background: #999', 'title' => $data['title_'.Language::language()]]).'</a>' : null;
                },
            ],

            [
                'attribute' => 'desc_'.Language::language(),
                'filter' => false,
                'format' => 'raw',
                'options' => ['width' => '200px'],
            ],
            [
                'attribute' => 'type',
                'filter' => \yii\helpers\ArrayHelper::map(\cms\models\Menu::getMenuStatusFilter(), 'value', 'type'),
                'value' => function ($data) {
                    return CFunction::getParamsArray('menu_type', $data->type);
                },
                'format' => 'raw',
                'options' => ['width' => '100px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;']
            ],
            [
                'attribute' => 'active',
                'format' => 'raw',
                'options' => ['width' => '80px'],
                'filter' => false,
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
                    $url = Yii::$app->urlManager->createUrl(['menu/sort']);
                    if ($data->{'title_'.Language::language()} != 'Root') {
                        return '<a href="javascript:;">'.Html::img(CFunction::getImageBaseUrl().'app/up.png', ['title' => Yii::t('cms', 'up'), 'onclick' => 'ajaxGridSort("menu-grid-view", '.$data->id.', "'.$url.'", "up")']).'</a>'
                        .'&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:;">'.Html::img(CFunction::getImageBaseUrl().'app/down.png', ['title' => Yii::t('cms', 'down'), 'onclick' => 'ajaxGridSort("menu-grid-view", '.$data->id.', "'.$url.'", "down")']).'</a>';
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
                'options' => ['width' => '120px'],
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