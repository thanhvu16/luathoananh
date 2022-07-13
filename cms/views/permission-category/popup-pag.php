<?php
use yii\bootstrap\Modal;
use common\components\Utility;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use common\components\CFunction;

?>
<?= GridView::widget([
        'id' => 'list-collection-grid-view1',
        'dataProvider' => $data,
        'pager' => [
            'prevPageLabel' => '&larr; Previous',
            'nextPageLabel' => '&rarr; Next'
        ],
        //$layout = "{summary}\n{items}\n{pager}",
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'options' => ['width' => '40px'],
                'headerOptions' => ['style' => 'text-align: center; vertical-align: middle;'],
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;']
            ],
            [
                'attribute'=>'id',
                'header' =>Yii::t('cms','ID'),
                'format' =>'raw',
                'value' => function($data){
                    return $data['id'];
                },
            ],
            [
                'attribute' => 'title',
                'header' => Yii::t('cms', 'title'),
                'format' => 'raw',
                'value' => function($data) {
                    if(empty($data['level'])) {
                        return  $data['title'];
                    }
                    return '<div class="menu-category-'.$data['level'].'"><span>'.Html::encode($data['title']).'</span></div>';
                },
                'contentOptions'=>['style'=>'vertical-align: middle;'],
            ],
        ],
        'rowOptions'=>function($model, $key, $index, $grid){
            $class=$index%2?'odd':'even';
            return array('id'=>'f'.$model['id']);
        },
        'tableOptions'=>['class'=>'table table-striped table-bordered','id'=>'film_list_gridview'],

    ]); ?>