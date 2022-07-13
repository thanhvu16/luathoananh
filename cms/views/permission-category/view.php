<?php
use yii\helpers\Html;
use yii\grid\GridView;
use cms\models\AdminGroup;


$this->title = Yii::t('cms', 'Phân quyền Category');
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'Phân quyền Category'),
        'url' => ['index']
    ],
    [
        'label' => Yii::t('cms', 'app_list'),
        'template' => "<li>{link}</li>\n"
    ]
];

$this->params['title'] = Html::encode(Yii::t('cms', 'Phân quyền Category'));
?>
<div class="box-header with-border" style="margin-top: 20px">
    <a class="btn btn-outline-primary" onclick="deleteNewsToCollection('permission-category/delete.html', <?php echo $accountId ?>)">Delete</a>
</div>

<div class="box-body">
    <?= GridView::widget([
        'id'=>'list-collection-grid-view',
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'options' => ['width' => '40px'],
                'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;']
            ],
            [
                'header' => 'Name',
                'value' => function ($data) {
                    return $data->category->name ?? '';
                },
                'options' => ['width' => '140px'],
                'headerOptions' => ['style'=>'text-align: center;'],
            ],
            [
                'header' => 'Title',
                'value' => function ($data) {
                    return $data->category->title ?? '';
                },
                'options' => ['width' => '140px'],
                'headerOptions' => ['style'=>'text-align: center;'],
            ],
            [
                'attribute' => 'created_time',
                'options' => ['width' => '100px'],
                'headerOptions' => ['style'=>'text-align: center;'],
            ],
            [
                'header' => 'Created By',
                'options' => ['width' => '80px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'value' => function ($data) {
                    return $data->createdBy->fullname;
                }
            ]
        ],
    ]); ?>
</div>