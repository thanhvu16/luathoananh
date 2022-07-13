<?php echo \yii2mod\comments\widgets\Comment::widget([
    'model' => $model,
    'commentView' => '@app/views/comment/widgets/index',
    'maxLevel' => 2,
      'dataProviderConfig' => [
          'pagination' => [
              'pageSize' => 10
          ],
      ],
      'listViewConfig' => [
          'emptyText' => Yii::t('app', 'Bạn hãy là người đầu tiên đặt câu hỏi'),
      ],
]);
