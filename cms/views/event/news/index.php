<?php
use yii\helpers\Html;
use yii\grid\GridView;
use common\components\CFunction;
use common\components\Utility;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('cms', 'mnu_news');
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'mnu_news'),
        'url' => ['index']
    ],
    [
        'label' => Yii::t('cms', 'app_list'),
        'template' => "<li>{link}</li>\n"
    ]
];

$this->params['title'] = Html::encode(Yii::t('cms', 'mnu_news'));

$status = \cms\models\News::genStatus();
$status[3] = 'Tất cả trạng thái';
$listCate = \cms\models\NewsCategory::getAllCate();
?>
<div class="box-header with-border" style="margin-top: 20px">

    <ul class="btn btn-outline-primary"><li class="dropdown">
            <span class="dropdown-toggle" data-toggle="dropdown"><?php echo Yii::t('cms', 'action'); ?>&nbsp;<b class="caret"></b></span>
            <ul class="dropdown-menu">
                <li class="dropdown-header no-padding"><a href="javascript:void(0)" onclick="deleteAllItems('news/delete-multi', 'news/index');"  >Xóa</a></li>
            </ul>
    </ul>
    <?= Html::a('<span class="fa fa-plus"></span> '.Yii::t('cms', 'app_create'), ['create'], ['class' => 'btn btn-outline-primary']) ?>

        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => ['news/index'],
        ]); ?>
        <div class="col-lg-12" style="margin-top: 20px">

            <div class="col-lg-3">
                <?php echo Html::input('text', 'title',  isset($_REQUEST['title']) ? ArrayHelper::getValue($_REQUEST, 'title', null) : null, ['class' => 'form-control', 'placeholder'=>Yii::t('cms', 'title')]); ?>
            </div>
            <div class="col-lg-3">
                <?php echo Html::input('text', 'tags',  isset($_REQUEST['tags']) ? ArrayHelper::getValue($_REQUEST, 'tags', null) : null, ['class' => 'form-control', 'placeholder'=>Yii::t('cms', 'tags')]); ?>
            </div>
            <div class="col-lg-2">
                <?php echo Html::dropDownList('status', (int)ArrayHelper::getValue($_REQUEST, 'status', 3), $status, [ 'class' => 'form-control m-b']); ?>
            </div>
            <div class="col-lg-2">
                <?php echo Html::dropDownList('news_category_id', (int)ArrayHelper::getValue($_REQUEST, 'news_category_id', 0), $listCate, [ 'class' => 'form-control m-b']); ?>
            </div>
            <div class="col-lg-1">
                <?php echo Html::submitButton('<span class="fa fa-search"></span> '.Yii::t('cms', 'search'), ['class'=>'btn btn-outline-primary']); ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
</div>

<div class="box-body">
<?= GridView::widget([
    'id'=>'ajax_gridview',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'class' => 'yii\grid\CheckboxColumn',
            'options' => ['width' => '40px'],
            'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
            'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;']
        ],
        [
            'attribute' => 'title',
            'header' => Yii::t('cms', 'title'),
            'format' => 'raw',
            'options' => ['width' => '180px'],
            'contentOptions'=>['style'=>'vertical-align: middle;'],
        ],
        [
            'header' => Yii::t('cms', 'image_category'),
            'format' => 'raw',
            'options' => ['width' => '80px'],
            'headerOptions' => ['style'=>'text-align: center;'],
            'contentOptions'=>['style'=>'text-align: center; vertical-align: middle;'],
            'value' => function($data) {
                $imagesUrl =Utility::makeImgNews($data,'news_img_options_large');
                return ($imagesUrl) ? Html::img($imagesUrl,['height'=>'60', 'title' => $data->{'title'}]) : null;
            }
        ],
        [
            'attribute' => 'news_category_id',
            'header' => Yii::t('cms', 'category'),
            'value' => function ($data) {
                return ($data->category)?$data->category->{'title'}:null;
            },
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
</div>
