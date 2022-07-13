<?php

namespace cms\models;

use Yii;
use yii\data\ActiveDataProvider;
use cms\models\News;


class LogNews extends \common\models\LogNewsBase{
    public function search($id) {
        $query = self::find()
            ->andFilterWhere(['news_id' => $id])
            ->addOrderBy('action_time DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 12]
        ]);
        return $dataProvider;
    }

    public function getCreatedBy()
    {
        return $this->hasOne(Admin::className(), ['id' => 'user_id']);
    }

    public function getNews()
    {
        return $this->hasOne(News::className(), ['id' => 'news_id']);
    }

}