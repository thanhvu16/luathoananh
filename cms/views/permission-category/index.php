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

    <ul class="btn btn-outline-primary"><li class="dropdown">
            <span class="dropdown-toggle" data-toggle="dropdown"><?php echo Yii::t('cms', 'action'); ?>&nbsp;<b class="caret"></b></span>
            <ul class="dropdown-menu">
                <li class="dropdown-header no-padding"><a href="javascript:void(0)" onclick="deleteAllItems('news/delete-multi', 'news/index');"  >Xóa</a></li>
            </ul>
    </ul>
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
            'attribute' => 'username',
            'header' => Yii::t('cms', 'Username'),
            'format' => 'raw',
            'options' => ['width' => '180px'],
            'contentOptions'=>['style'=>'vertical-align: middle;'],
        ],
        [
            'attribute' => 'fullname',
            'header' => Yii::t('cms', 'Full name'),
            'format' => 'raw',
            'options' => ['width' => '80px'],
            'headerOptions' => ['style'=>'text-align: center;'],
            'contentOptions'=>['style'=>'text-align: center; vertical-align: middle;']
        ],
        [
            'attribute' => 'admin_group_id',
            'value' => function ($data) {
                return Html::encode(AdminGroup::getGroupNameByID($data->admin_group_id));
            },
            'options' => ['width' => '140px'],
            'headerOptions' => ['style'=>'text-align: center;'],
            'contentOptions'=>['style'=>'text-align: center;']
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{add}{view}{delete}',
            'header' => Yii::t('cms', 'action'),
            'options' => ['width' => '130px'],
            'headerOptions' => ['style'=>'text-align: center;'],
            'contentOptions'=>['style'=>'text-align: center;'],
            'buttons' => [
                'add' => function ($url,$data) {
                    $content = '<a title="Add News" href="javascript:void(0)" class="btn btn-outline-primary btn-xs btn-app" onclick=showPopupAdd("permission-category/popup.html",'.$data->id.')><span class="glyphicon glyphicon-plus"></span></a>,';
                    return $content;
                },
                'view' => function ($url) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                        'title' => Yii::t('cms', 'view'),
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