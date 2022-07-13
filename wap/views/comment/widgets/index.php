<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $this \yii\web\View */
/* @var $commentModel \yii2mod\comments\models\CommentModel */
/* @var $maxLevel null|integer comments max level */
/* @var $encryptedEntity string */
/* @var $pjaxContainerId string */
/* @var $formId string comment form id */
/* @var $commentDataProvider \yii\data\ArrayDataProvider */
/* @var $listViewConfig array */
/* @var $commentWrapperId string */
?>
<?php
$this->registerJs('
    
        setTimeout(function(){
            console.log($(\'html\').height());
            parent.loadIframeHeight($(\'html\').height());
        }, 500);
    ');
?>
<div class="comment-wrapper" id="<?php echo $commentWrapperId; ?>">
    <?php Pjax::begin([
        //'enablePushState' => false, 
        'timeout' => 20000,
        'id' => $pjaxContainerId
    ]); ?>
    <div class="comments row">
        <div class="col-md-12 col-sm-12">
            <div class="title-block clearfix" style="clear: both; overflow: hidden;">
                <h3 class="h3-body-title">
                    <?php echo Yii::t('yii2mod.comments', 'Comments ({0})', $commentModel->getCommentsCount()); ?>
                </h3>
                <div class="title-separator"></div>
            </div>
            <?php //if (!Yii::$app->user->isGuest) : 
            ?>
            <?php echo $this->render('_form', [
                'commentModel' => $commentModel,
                'formId' => $formId,
                'encryptedEntity' => $encryptedEntity,
            ]); ?>
            <?php //endif; 
            ?>
            <?php echo ListView::widget(ArrayHelper::merge(
                [
                    'dataProvider' => $commentDataProvider,
                    'layout' => "{items}\n{pager}",
                    'itemView' => '_list',
                    'viewParams' => [
                        'maxLevel' => $maxLevel,
                    ],
                    'options' => [
                        'tag' => 'ol',
                        'class' => 'comments-list',
                    ],
                    'itemOptions' => [
                        'tag' => false,
                    ],
                ],
                $listViewConfig
            )); ?>
        </div>
    </div>
    <?php Pjax::end(); ?>
</div>
<style>
    .comments .comment-content {
        padding: 10px;
    }

    .comments-list .comment {
        margin: 0 0 15px 0;
    }

    .comment-box .help-block {
        color: red;
        font-size: 12px;
        font-style: italic;
    }

    .comments .comment-author-avatar img {
        border-radius: 50%;
        max-width: 50px;
    }

    .comments .comment-details {
        padding-left: 65px;
    }

    .comments .comment-date {
        font-size: 12px;
    }

    #cancel-reply {
        font-size: 13px;
    }
</style>