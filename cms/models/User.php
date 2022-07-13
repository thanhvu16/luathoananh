<?php

namespace cms\models;

use common\behaviors\ChangedBehavior;
use common\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use Yii;


class User extends \common\models\UserBase{

    public function search($params)
    {
        $query = self::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 100]
        ]);
        $query->orderBy('id DESC');
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}