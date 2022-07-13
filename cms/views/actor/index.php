<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;

$this->title = Yii::t('cms', 'mnu_film_actor');
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'mnu_film_actor'),
        'url' => ['index']
    ],
    [
        'label' => Yii::t('cms', 'app_list'),
        'template' => "<li>{link}</li>\n"
    ]
];
$this->params['title'] = Html::encode(Yii::t('cms', 'mnu_film_actor'));
$this->params['menu'] = [
    ['label'=>Yii::t('cms', 'app_create'), 'url' => ['create'], 'options' => ['class' => 'btn btn-outline-primary']]
];
?>
<?php Pjax::begin(['id' => 'admin-grid-view']);?> 
    <?= GridView::widget([
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
                'attribute' => 'name',
                'label' => Yii::t('cms', 'name'),
            ],
            [
                'attribute' => 'country',
                'header' => Yii::t('cms', 'country'),
                'value'=>'country.name',
                'options' => ['width' => '140px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'options' => ['width' => '120px'],
                'header' => Yii::t('cms', 'action'),
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center;'],
                'buttons' => [
                    'update' => function ($url) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('cms', 'Update'),
                            'class'=>'btn btn-outline-primary btn-xs btn-app',
                            'data-pjax' => '0',
                        ]);
                    },
                    'delete' => function ($url) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('cms', 'Delete'),
                            'class'=>'btn btn-outline-primary btn-xs btn-app',
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