<?php
use yii\helpers\Html;
use yii\grid\GridView;
use common\components\CFunction;
use common\components\Utility;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use cms\models\NewsCategory;
use yii\helpers\Url;
use kartik\daterange\DateRangePicker;


$this->title = Yii::t('cms', 'Tin đã xóa');
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'mnu_news'),
        'url' => ['index']
    ],
    [
        'label' => Yii::t('cms', 'Tin đã xóa'),
        'template' => "<li>{link}</li>\n"
    ]
];

$this->params['title'] = Html::encode(Yii::t('cms', 'Tin đã xóa'));

$news = new \cms\models\News();
$status = $news->genStatus();
$status[-1] = 'Tất cả trạng thái';
$listCate = \cms\models\NewsCategory::getAllCate();
$listAdmin = \cms\models\Admin::getListAdmin();
?>
<div class="box-header with-border" style="margin-top: 20px">

    <?= Html::a('<span class="fa fa-plus"></span> '.Yii::t('cms', 'app_create'), ['create'], ['class' => 'btn btn-outline-primary']) ?>

    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['news/index'],
    ]); ?>
    <div class="col-lg-12" style="margin-top: 20px">

        <div class="col-lg-3">
            <?php echo Html::input('text', 'title',  isset($_REQUEST['title']) ? ArrayHelper::getValue($_REQUEST, 'title', null) : null, ['class' => 'form-control m-b', 'placeholder'=>Yii::t('cms', 'Nhập tiêu đề')]); ?>
        </div>
        <div class="col-lg-3">
            <?php echo Html::input('text', 'tags',  isset($_REQUEST['tags']) ? ArrayHelper::getValue($_REQUEST, 'tags', null) : null, ['class' => 'form-control m-b', 'placeholder'=>Yii::t('cms', 'Nhập từ khóa')]); ?>
        </div>
        <div class="col-lg-2">
            <?php echo Html::dropDownList('status', (int)ArrayHelper::getValue(Yii::$app->request->get(), 'status', -1), $status, [ 'class' => 'form-control m-b']); ?>
        </div>
        <div class="col-lg-2">
            <?php echo Html::dropDownList('created_by', (int)ArrayHelper::getValue($_REQUEST, 'created_by'), $listAdmin, [ 'class' => 'form-control m-b']); ?>
        </div>
        <div class="col-lg-2">
            <?php echo Html::dropDownList('news_category_id', (int)ArrayHelper::getValue($_REQUEST, 'news_category_id', 0), $listCate, [ 'class' => 'form-control m-b']); ?>
        </div>
        <div class="col-lg-3">
            <?php
            echo DateRangePicker::widget([
                'name' => 'time',
                'value' => ArrayHelper::getValue($_REQUEST, 'time', ''),
                'presetDropdown' => true,
                'hideInput' => true,
                'convertFormat' => true,
                'pluginOptions' => [
                    'format'=>'Y-m-d'
                ]
            ]);
            ?>
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
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'options' => ['width' => '40px'],
                'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;']
            ],
            [
                'attribute' => 'title',
                'header' => Yii::t('cms', 'Tiêu đề'),
                'format' => 'raw',
                'options' => ['width' => '180px'],
                'value' => function($data) {
                    $slugCate = NewsCategory::getCategory($data['news_category_id']);
                    if(empty($slugCate))
                        $url = 'https://bongdapro.vn/' . $data['slug'] . '-bdp' . $data['id'] . '';
                    else
                        $url = 'https://bongdapro.vn' . $slugCate['route'] . '/' . $data['slug'] . '-bdp' . $data['id'];

                    return '<a target="_blank" href="'.$url.'" >'.$data->title.'</a>';
                },
                'contentOptions'=>['style'=>'vertical-align: middle;'],
            ],
            [
                'header' => Yii::t('cms', 'Ảnh bìa'),
                'format' => 'raw',
                'options' => ['width' => '80px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center; vertical-align: middle;'],
                'value' => function($data) {
                    $imagesUrl =$data->image;
                    return ($imagesUrl) ? Html::img($imagesUrl,['height'=>'60', 'title' => $data->{'title'}]) : null;
                }
            ],
            [
                'attribute' => 'news_category_id',
                'header' => Yii::t('cms', 'Danh mục'),
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
                'header' => Yii::t('cms', 'Trạng thái'),
                'format' => 'raw',
                'options' => ['width' => '80px'],
                'value' => function ($data) {
                    return !empty($data->getNameStatus($data->status)) ? $data->getNameStatus($data->status) : '';
                },
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center; vertical-align: middle;']
            ],
            [
                'attribute' => 'created_by',
                'header' => Yii::t('cms', 'Tên người viết'),
                'value' => function ($data) {
                    return $data->createdBy->fullname ?? '';
                },
                'format' => 'raw',
                'options' => ['width' => '100px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center; vertical-align: middle;']
            ],
            [
                'attribute' => 'time_active',
                'header' => Yii::t('cms', 'Thời gian xuất bản'),
                'value' => function ($data) {
                    return $data->time_active;
                },
                'format' => 'raw',
                'options' => ['width' => '100px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center; vertical-align: middle;']
            ],
            [
                'attribute' => 'active',
                'header' => Yii::t('cms', 'Bài viết HOT'),
                'format' => 'raw',
                'options' => ['width' => '80px'],
                'value' => function ($data) {
                    if ($data->is_hot == 1) {
                        return '<span id="item-active-status-hot-'.$data->id.'">'.Html::img(CFunction::getImageBaseUrl().'app/icon-32-check.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_inactive'), 'onclick' => 'changeActiveHot('.$data->id.', 1,"news/change-status-hot")']).'</span>';
                    } else {
                        return '<span id="item-active-status-hot-'.$data->id.'">'.Html::img(CFunction::getImageBaseUrl().'app/icon-32-stop.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_active'), 'onclick' => 'changeActiveHot('.$data->id.', 0,"news/change-status-hot")']).'</span>';
                    }
                },
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center; vertical-align: middle;']
            ]
        ],
    ]); ?>
</div>