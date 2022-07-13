<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use common\components\CFunction;
use common\components\Language;
use common\components\CategoryTree;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\components\Utility;

$this->title = Yii::t('cms', 'Quản lý chuyên muc');
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'News Category'),
        'url' => ['index']
    ],
    [
        'label' => Yii::t('cms', 'app_list'),
        'template' => "<li>{link}</li>\n"
    ]
];
$this->params['title'] = Html::encode(Yii::t('cms', 'Quản lý chuyên muc'));

//$sys = new CategoryTree($category);
//$category = $sys->builArray(0);
//$category = $sys->selectboxArray($category);
?>
<div class="ibox-title" style="white-space: nowrap; height: auto; min-height: 68px;">
    <div class="col-lg-2">
        <?= Html::a('<span class="fa fa-plus"></span> '.Yii::t('cms','app_create'), ['news-category/create'], ['class' => 'btn btn-outline-primary']) ?>
    </div>
</div>


<div class="clip-index ibox-content">
<?php Pjax::begin(['id' => 'category-grid-view']); ?>
<?php echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'label' => 'name',
            'header'=> 'Name',
            'attribute' => 'name',
            'format' => 'raw',
            'value' => function($data) {
                return '<div class="menu-category-'.$data['level'].'"><span>'.Html::encode($data['title']).'</span></div>';
            },
            'options' => ['width' => '300px'],
            'contentOptions'=>['style'=>'vertical-align:middle;']
        ],
        [
            'label' => 'title',
            'header'=> 'Title',
            'attribute' => 'title',
            'options' => ['width' => '200px'],
            'contentOptions'=>['style'=>'vertical-align:middle;']
        ],
        [
            'attribute' => 'active',
            'header'=>Yii::t('cms', 'status'),
            'format' => 'raw',
            'options' => ['width' => '80px;', 'height' => '60px;'],
            'value' => function ($data) {
                if ($data['active'] == 1) {
                    return '<span id="item-active-status-'.$data['id'].'">'.Html::img(CFunction::getImageBaseUrl().'app/icon-32-check.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_inactive'), 'onclick' => 'changeActive('.$data['id'].', 1,"news-category/change-status")']).'</span>';
                } else {
                    return '<span id="item-active-status-'.$data['id'].'">'.Html::img(CFunction::getImageBaseUrl().'app/icon-32-stop.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_active'), 'onclick' => 'changeActive('.$data['id'].', 0,"news-category/change-status")']).'</span>';
                }
            },
            'headerOptions' => ['style'=>'text-align: center;'],
            'contentOptions'=>['style'=>'text-align: center; vertical-align:middle;']
        ],
        [
            'attribute' => 'active',
            'header' => Yii::t('cms', 'HOT'),
            'format' => 'raw',
            'options' => ['width' => '80px'],
            'value' => function ($data) {
                if ($data['is_hot'] == 1) {
                    return '<span id="item-active-status-hot-'.$data['id'].'">'.Html::img(CFunction::getImageBaseUrl().'app/icon-32-check.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_inactive'), 'onclick' => 'changeActiveHot('.$data['id'].', 1,"news-category/change-status-hot")']).'</span>';
                } else {
                    return '<span id="item-active-status-hot-'.$data['id'].'">'.Html::img(CFunction::getImageBaseUrl().'app/icon-32-stop.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_active'), 'onclick' => 'changeActiveHot('.$data['id'].', 0,"news-category/change-status-hot")']).'</span>';
                }
            },
            'headerOptions' => ['style'=>'text-align: center;'],
            'contentOptions'=>['style'=>'text-align: center; vertical-align: middle;']
        ],
        [
            'header' => Yii::t('cms', 'sort'),
            'content' => function($data) {
                    $url = Yii::$app->urlManager->createUrl(['news-category/sort']);
                        return '<input name="order_no" min="0" onchange=ajaxGridSort("category-grid-view","'.$data['id'].'","'.$url.'","",this.value) style="width: 50px; padding: 5px" type="number" value="' . $data['order'] . '">';

                },
            'options' => ['width' => '120px'],
            'headerOptions' => ['style'=>'text-align: center;'],
            'contentOptions'=>['style'=>'text-align: center; vertical-align:middle;']
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
    ]
]); ?>
<?php Pjax::end(); ?>
<input type="hidden" name="retun" id="retun" value="/clip-category/index.html" />
</div>