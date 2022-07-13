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

$news = new \cms\models\News();
$status = $news->genStatus();
$status[-1] = 'Tất cả trạng thái';
$listCate = \cms\models\NewsCategory::getAllCate();
$listAdmin = \cms\models\Admin::getListAdmin();
?>
<div class="box-header with-border" style="margin-top: 20px">
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
                'header' => Yii::t('cms', 'Nội dung'),
                'value' => function ($data) {
                    if ($data->action_flag != \cms\models\LogNews::ACTION_UPDATE)
                        return $data->action_message;
                    $content = json_decode($data->action_message, true);
                    $change = '';
                    $modelNews = new \common\models\NewsBase();
                    foreach ($content as $k => $v) {
                        if($k == 'updated_time' || $k == 'content') {
                            continue;
                        }
                        if($k === 'news_category_id') {
                            $categoryNews = NewsCategory::findOne($v['news']);
                            $categoryOld = NewsCategory::findOne($v['old']);
                            $change .= '<p>' . 'Category cũ: ' . $categoryOld->title . '</p>';
                            $change .= '<p style="margin-bottom: 5px">'.'Category mới: ' . $categoryNews->title . '</p>';
                            continue;
                        }
                        if($k === 'status') {
                            $change .= '<p>'.'Trạng thái cũ: ' . $modelNews->getNameStatus($v['old']) . '</p>';
                            $change .= '<p style="margin-bottom: 5px">'. 'Trạng thái mới: ' . $modelNews->getNameStatus($v['news']) . '</p>';
                            continue;
                        }
                        if($k === 'deleted') {
                            $change = '<p>Xóa bài</p>';
                            continue;
                        }
                        $change .= '<p>'.ucfirst($k) . ' cũ: ' . $v['old'] . '</p>';
                        $change .= '<p style="margin-bottom: 5px">'.ucfirst($k) . ' mới: ' . $v['news'] . '</p>';
                    }
                    return $change;
                },
                'format' => 'raw',
                'headerOptions' => ['style'=>'text-align: center;'],
            ],
            [
                'header' => Yii::t('cms', 'Hành động'),
                'value' => function ($data) {
                    if($data->action_flag == \cms\models\LogNews::ACTION_INSERT) {
                        return 'Thêm mới';
                    }
                    if($data->action_flag == \cms\models\LogNews::ACTION_UPDATE) {
                        return 'Thay đổi';
                    }
                    if($data->action_flag == \cms\models\LogNews::ACTION_DELETE) {
                        return 'Xóa';
                    }
                },
                'format' => 'raw',
                'options' => ['width' => '100px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center; vertical-align: middle;']
            ],
            [
                'attribute' => 'created_by',
                'header' => Yii::t('cms', 'Người thay đổi'),
                'value' => function ($data) {
                    return $data->createdBy->fullname ?? '';
                },
                'format' => 'raw',
                'options' => ['width' => '100px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center; vertical-align: middle;']
            ],
            [
                'attribute' => 'action_time',
                'header' => Yii::t('cms', 'Thời gian'),
                'format' => 'raw',
                'options' => ['width' => '100px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center; vertical-align: middle;']
            ],
        ],
    ]); ?>
</div>