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

$title = 'mnu_news';
$paramStatus = Yii::$app->request->get('status');
$modelNews = new \cms\models\News();
if(!empty($modelNews->getNameStatus($paramStatus))) {
    $title = 'Tin ' . $modelNews->getNameStatus($paramStatus);
}
$this->title = Yii::t('cms', $title);
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', $title),
        'url' => ['index']
    ],
    [
        'label' => Yii::t('cms', 'app_list'),
        'template' => "<li>{link}</li>\n"
    ]
];

$this->params['title'] = Html::encode(Yii::t('cms', $title));

$news = new \cms\models\News();
$status = $news->genStatus(false);
$status[-1] = 'Tất cả trạng thái';
$listCate = \cms\models\NewsCategory::getAllCate();
$listAdmin = \cms\models\Admin::getListAdmin();
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
				$url = 'https://luathoanganh.vn/' . $slugCate['route'] . '/' . $data['slug'] . '-lha' . $data['id'] . '.html?preview=1';
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
        ],

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{logs}{view}{update}{delete}{pls-fix}',
            'header' => Yii::t('cms', 'action'),
            'options' => ['width' => '130px'],
            'headerOptions' => ['style'=>'text-align: center;'],
            'contentOptions'=>['style'=>'text-align: center;'],
            'buttons' => [
                'logs' => function ($url) {
                    return Html::a('<span class="fa fa-history"></span>', $url, [
                        'title' => Yii::t('cms', 'Log'),
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
                'delete' => function ($url) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                        'title' => Yii::t('cms', 'delete'),
                        'class'=>'btn btn-outline-primary btn-xs btn-app',
                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'data-method' => 'post',
                        'data-pjax' => 'w0'
                    ]);
                },
                'pls-fix' => function ($url, $data){
                    if(Yii::$app->user->identity->admin_group_id != \cms\models\AdminGroup::GROUP_CTV && Yii::$app->user->identity->admin_group_id != \cms\models\AdminGroup::GROUP_BTV) {
                        return '';
                    }
                    return Html::a('<span class="fa fa-wrench"></span>', $url, [
                        'title' => Yii::t('cms', 'Xin sửa bài'),
                        'class'=>'btn btn-outline-primary btn-xs btn-app',
                        'data-method' => 'post',
                        'data-pjax' => 'w0'
                    ]);
                },
            ]
        ]
    ],
]); ?>
</div>