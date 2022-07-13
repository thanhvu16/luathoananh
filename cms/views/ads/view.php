<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Chi tiết';
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'Danh sách Quảng cáo'),
        'url' => ['index']
    ],
    [
        'label' => Yii::t('cms', 'app_detail'),
        'template' => "<li>{link}</li>\n"
    ]
];

?>
<div class="box-header with-border">
    <?= Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i> '.Yii::t('cms', 'app_list'), ['index'], ['class' => 'btn btn-outline-primary']) ?>
</div>
<div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => Yii::t('cms', 'Tên Banner'),
                'value' => function($model){
                    $type = $model::$position[$model->name] ?? '';
                    return $type;
                }
            ],
            [
                'label' => Yii::t('cms', 'Url'),
                'value' => function($model){
                    $type = $model::$type[$model->url] ?? '';
                    return $type;
                }
            ],
            [
                'label' => Yii::t('cms', 'status'),
                'value' => function($model){
                    return $model->status == 1 ? 'Active' : 'Inactive';
                }
            ],
            'created_time',
            'updated_time'
        ],
    ]);
    ?>
</div>