<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\widgets\Pjax;
use yii2mod\moderation\enums\Status;

use cms\models\NewsCategory;
use cms\models\News;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \yii2mod\comments\models\search\CommentSearch */
/* @var $commentModel \yii2mod\comments\models\CommentModel */

$this->title = Yii::t('yii2mod.comments', 'Quản lý Bình luận');
$this->params['breadcrumbs'][] = $this->title;

$dataNews = [];
$users = [];
?>
<div class="box-body">
<div class="comments-index">

    <h1><?php echo Html::encode($this->title); ?></h1>
    <?php Pjax::begin(['timeout' => 10000]); ?>
    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
				'headerOptions' => ['style'=>'text-align: center;max-width: 50px;'],
                'contentOptions' => ['style' => 'max-width: 50px;'],
				'filter' => false,
            ],
            [
                'attribute' => 'content',
                'contentOptions' => ['style' => 'max-width: 350px;'],
                'value' => function ($model) {
                    return StringHelper::truncate($model->content, 100);
                },
				'label' => 'Nội dung',
            ],
            //'attribute' => 'relatedTo',
			[
                'attribute' => 'relatedTo',
				'label' => 'Bài viết',
                'value' => function ($model)  use(&$dataNews) {
					if(strpos($model->relatedTo, 'wap\models\News')==0){
						$newId = str_replace('wap\models\News:', '', $model->relatedTo);
						if(empty($dataNews[$newId])){
							$dataNews[$newId] = News::findOne($newId);
						}
						$data = $dataNews[$newId];
						$slugCate = NewsCategory::getCategory($data['news_category_id']);
						$url = 'https://luathoanganh.vn/' . $slugCate['route'] . '/' . $data['slug'] . '-lha' . $data['id'] . '.html#wrap-comment';
						return '<a target="_blank" href="'.$url.'" >'.$data->title.'</a>';
					}
					return $model->relatedTo;
                    //return $model->getAuthorName();
                },
                //'filter' => $commentModel::getAuthors(),
				'format' => 'raw',
                'filterInputOptions' => ['prompt' => Yii::t('yii2mod.comments', 'Select Author'), 'class' => 'form-control'],
				'filter' => false,
            ],
            [
                'attribute' => 'createdBy',
                'value' => function ($model) use(&$users) {
					if(empty($users[$model->createdBy])){
						$users[$model->createdBy] = \Yii::$app->db->createCommand('select * from comment_users where id=:id', [
							':id' => $model->createdBy
						])->queryOne();
					}
					if(empty($users[$model->createdBy]['name'])) return '';
					
					
					return $users[$model->createdBy]['name'];
                    //return $model->getAuthorName();
                },
                'filter' => false,
                'filterInputOptions' => ['prompt' => Yii::t('yii2mod.comments', 'Select Author'), 'class' => 'form-control'],
				'label' => 'Người tạo'
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return Status::getLabel($model->status);
                },
                'filter' => Status::listData(),
                'filterInputOptions' => ['prompt' => Yii::t('yii2mod.comments', 'Select Status'), 
				'class' => 'form-control'],
				'label' => 'Trạng thái'
            ],
            [
                'attribute' => 'createdAt',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDatetime($model->createdAt);
                },
                'filter' => false,
				'label' => 'Thời gian'
            ],
            [
                'header' => 'Hành động',
                'class' => 'yii\grid\ActionColumn',
                //'template' => '{view}{update}{delete}',
                'template' => '{update}{delete}',
                'buttons' => [
					'update' => function ($url, $data) {
                        //if(!$data->isUpdate()) return '';
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('cms', 'update'),
                            'class' => 'btn btn-outline-primary btn-xs',
                            'data-pjax' => '0',
                        ]);
                    },
                    'delete' => function ($url, $data) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('cms', 'delete'),
                            'class' => 'btn btn-outline-primary btn-xs',
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',
                            'data-pjax' => 'w0',
							'style' => 'margin-left: 3px;'
                        ]);
                    },
                    'view' => function ($url, $model, $key) {
                        $title = Yii::t('yii2mod.comments', 'View');
                        $options = [
                            'title' => $title,
                            'aria-label' => $title,
                            'data-pjax' => '0',
                            'target' => '_blank',
                        ];
                        $icon = Html::tag('span', '', ['class' => 'glyphicon glyphicon-eye-open']);
                        $url = $model->getViewUrl();

                        if (!empty($url)) {
                            return Html::a($icon, $url, $options);
                        }

                        return null;
                    },
                ],
            ],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>
</div>
