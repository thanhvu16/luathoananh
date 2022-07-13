<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel cms\models\search\LeagueSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = $this->params['title'] = 'Giải đấu';
$this->params['breadcrumbs'][] = $this->title;
/*$this->params['menu'] = [
    ['label' => 'Create', 'url' => ['create'], 'options' => ['class' => 'btn btn-primary']],
    ['label' => 'Delete', 'url' => 'javascript:void(0)', 'options' => ['class' => 'btn btn-danger', 'onclick' => 'deleteAllItems()']]
];*/
?>
<?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<div class="box-body">
    <?php Pjax::begin(['id' => 'admin-grid-view']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                    'class' => 'yii\grid\CheckboxColumn',
                'contentOptions'=>['style'=>'text-align: center; vertical-align: middle;'],
            ],

            //'league_id',
            [
                'header' => Yii::t('cms', 'Logo'),
                'format' => 'raw',
                'options' => ['width' => '80px'],
                'headerOptions' => ['style'=>'text-align: center;'],
                'contentOptions'=>['style'=>'text-align: center; vertical-align: middle;'],
                'value' => function($data) {
                    $imagesUrl =\common\components\Utility::getImageFb($data->logo);
                    return ($imagesUrl) ? Html::img($imagesUrl,['height'=>'60', 'title' => $data->{'name'}]) : null;
                }
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'contentOptions'=>['style'=>'vertical-align: middle;'],
                'value' => function ($data) {
                    return '<input class="form-control" id="l_name_'.$data->league_id.'" value="'.$data->name.'" />';
                },
            ],
            [
                'attribute' => 'custom_name',
                'header' => 'Tên ',
                'format' => 'raw',
                'contentOptions'=>['style'=>' vertical-align: middle;'],
                'value' => function ($data) {
                    return '<input class="form-control" id="l_custom_name_'.$data->league_id.'" value="'.$data->custom_name.'" />';
                },
            ],
            [
                'attribute' => 'custom_short_name',
                'header' => 'Tên Ngắn',
                'format' => 'raw',
                'contentOptions'=>['style'=>' vertical-align: middle;'],
                'value' => function ($data) {
                    return '<input class="form-control" id="l_custom_short_name_'.$data->league_id.'" value="'.$data->custom_short_name.'" />';
                },
            ],[
                'attribute' => 'slug',
                'format' => 'raw',
                'contentOptions'=>['style'=>' vertical-align: middle;'],
                'value' => function ($data) {
                    return '<input class="form-control" id="l_slug_'.$data->league_id.'" value="'.$data->slug.'" />';
                },
            ],
            [
                'attribute' => 'sort_order',
                'contentOptions'=>['style'=>'vertical-align: middle; width: 100px;'],
                'header' => 'Sắp xếp',
                'format' => 'raw',
                'filter' => false,
                'value' => function ($data) {
                    return '<input class="form-control" id="l_sort_order_'.$data->league_id.'" value="'.$data->sort_order.'" />';
                },
            ],
            [
                'attribute' => 'country',
                'label' => 'Quốc gia',
                'format' => 'raw',
                'contentOptions'=>['style'=>' vertical-align: middle;'],
                'value' => function ($data) {
                    $html = '<input class="form-control" id="l_country_'.$data->league_id.'" value="'.(!empty($data->country_vn)?$data->country_vn:$data->country).'" />';
                    return $html;
                },
            ],
            [
                'attribute' => 'type',
                'contentOptions'=>['style'=>' vertical-align: middle;'],
                'header' => 'Loại',
                'filter' => [
                    \cms\models\League::TYPE_CUP => 'CUP',
                    \cms\models\League::TYPE_LEAGUE => 'LEAGUE',
                ],
                'value' => function ($data) {
                    if ($data->type == \cms\models\League::TYPE_CUP) {
                        return 'CUP';
                    } else {
                        return 'LEAGUE';
                    }
                },
            ],
            //'short_name',
            // 'type',
            // 'sub_league_name',
            // 'status',
            // 'color',
            // 'logo',
            // 'created_time',
            // 'created_by',
            // 'updated_time',
            // 'updated_by',
            // 'totalRound',
            // 'currentRound',
            // 'currentSeason',
            // 'countryId',
            // 'country',
            // 'countryLogo',
            // 'areaId',
            // 'isHot',
            // 'sort_order',
            [
                'attribute' => 'isHot',
                'contentOptions'=>['style'=>' text-align: center;vertical-align: middle;'],
                'filter' => [
                        0 => 'No',
                        1 => 'Yes',
                ],
                'format' => 'raw',
                'options' => ['width' => '120px'],
                'value' => function ($data) {
                    if ($data->isHot == 1) {
                        return '<span id="item-active-status-'.$data->league_id.'">'.Html::img(\common\components\CFunction::getImageBaseUrl().'app/icon-32-check.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_inactive'), 'onclick' => 'changeStatusItems('.$data->league_id.', 1, \'league/change-status\')']).'</span>';
                    } else {
                        return '<span id="item-active-status-'.$data->league_id.'">'.Html::img(\common\components\CFunction::getImageBaseUrl().'app/icon-32-stop.png', ['class' => 'app-active-status', 'alt' => Yii::t('cms', 'app_status_inactive'), 'title' => Yii::t('cms', 'app_status_active'), 'onclick' => 'changeStatusItems('.$data->league_id.', 0, \'league/change-status\')']).'</span>';
                    }
                },
                'headerOptions' => ['style'=>'text-align: center;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                //'template' => '{update}{delete}',
                'template' => '{save}{update}',
                'header' => Yii::t('cms', 'action'),
                'headerOptions' => ['style' => 'text-align: center;'],
                'contentOptions' => ['style' => 'text-align: center;vertical-align: middle;'],
                'buttons' => [
                    'save' => function ($url, $data) {
                        return Html::button('<span class="glyphicon glyphicon-floppy-saved"></span>', [
                            'title' => 'Save',
                            'onclick' => 'saveAjaxUpdate('.$data->league_id.', this)',
                            'class' => 'btn btn-success btn-xs btn-app',
                            'data-pjax' => '0',
                        ]);
                    },'update' => function ($url) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => 'Update',
                            'class' => 'btn btn-primary btn-xs btn-app',
                            'data-pjax' => '0',
                        ]);
                    },
                    'delete' => function ($url) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => 'Delete',
                            'class' => 'btn btn-primary btn-xs btn-app',
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => 'w0'
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
<script>
    function saveAjaxUpdate(leagueId, el) {
        var nameLeague = $('#l_name_'+leagueId).val();
        var slug = $('#l_slug_'+leagueId).val();
        var btn = $(el);
        if($.trim(nameLeague).length == 0){
            jAlert('Tên giải đấu không được để trống.', NOTICE);
            $('#l_name_'+leagueId).focus();
            return false;
        }

        if($.trim(slug).length == 0){
            jAlert('Slug không được để trống.', NOTICE);
            $('#l_slug_'+leagueId).focus();
            return false;
        }
        btn.prop('disabled', true);
        var datas = {
            id : leagueId,
            name: nameLeague,
            custom_name: $('#l_custom_name_'+leagueId).val(),
            custom_short_name: $('#l_custom_short_name_'+leagueId).val(),
            sort_order: $('#l_sort_order_'+leagueId).val(),
            country_vn: $('#l_country_'+leagueId).val(),
            slug: slug
        };
        var url = '<?= \yii\helpers\Url::toRoute(['league/ajax-update']) ?>';
        $.post(url , datas, function (res) {
            console.log(res);
            if(res.errorCode == 0){
                jAlert(res.message, NOTICE);
            }else{
                jAlert(res.message, NOTICE);
            }
            btn.prop('disabled', false);
        }, 'json');
    }
</script>