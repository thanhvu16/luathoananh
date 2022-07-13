<?php
use yii\helpers\Html;
use yii\grid\GridView;
use common\components\CFunction;
use yii\widgets\Pjax;

$this->title = Yii::t('cms', 'Text Link List');
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'Text Link List'),
        'url' => ['index']
    ],
    [
        'label' => Yii::t('cms', 'app_list'),
        'template' => "<li>{link}</li>\n"
    ]
];

$this->params['title'] = Html::encode(Yii::t('cms', 'Text Link List'));
?>
<div class="box-header with-border" style="margin-top: 20px">
    <?= Html::a('<span class="fa fa-plus"></span> '.Yii::t('cms', 'app_create'), ['create'], ['class' => 'btn btn-outline-primary']) ?>
</div>

<div class="box-body">
<?php Pjax::begin(['id' => 'category-grid-view']); ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'class' => 'yii\grid\CheckboxColumn',
            'options' => ['width' => '40px'],
            'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
            'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;']
        ],
        'title',
        'title_seo',
        [
            'header' => Yii::t('cms', 'link'),
            'options' => ['width' => '120px'],
            'headerOptions' => ['style'=>'text-align: center;'],
            'contentOptions'=>['style'=>'text-align: center; vertical-align:middle;'],
            'content' => function($data) {
                return '<a href="'.$data->link.'" target="_blank">'.$data->link.'</a>';
            }
        ],
        [
            'header' => Yii::t('cms', 'sort'),
            'content' => function($data) {
                $url = Yii::$app->urlManager->createUrl(['text-link/sort']);
                return '<input name="order_no" min="0" onchange=ajaxGridSort("category-grid-view","'.$data['id'].'","'.$url.'","",this.value) style="width: 50px; padding: 5px" type="number" value="' . $data['order'] . '">';

            },
            'options' => ['width' => '120px'],
            'headerOptions' => ['style'=>'text-align: center;'],
            'contentOptions'=>['style'=>'text-align: center; vertical-align:middle;']
        ],
        [
            'attribute' => 'status',
            'header' => Yii::t('cms', 'Trạng thái'),
            'format' => 'raw',
            'options' => ['width' => '80px'],
            'value' => function ($data) {
                if ($data->status == 1) {
                    return '<span id="item-active-status-'.$data->id.'">'.Html::img(CFunction::getImageBaseUrl().'app/icon-32-check.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_inactive'), 'onclick' => 'changeActive('.$data->id.', 1,"text-link/change-status")']).'</span>';
                } else {
                    return '<span id="item-active-status-'.$data->id.'">'.Html::img(CFunction::getImageBaseUrl().'app/icon-32-stop.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_active'), 'onclick' => 'changeActive('.$data->id.', 0,"text-link/change-status")']).'</span>';
                }
            },
            'headerOptions' => ['style'=>'text-align: center;'],
            'contentOptions'=>['style'=>'text-align: center; vertical-align: middle;']
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}{update}{delete}',
            'header' => Yii::t('cms', 'action'),
            'options' => ['width' => '130px'],
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
    ],
]); ?>
<?php Pjax::end(); ?>
</div>
