<?php

namespace common\models;

use Yii;
use yii\base\Model;
use common\models\db\UserDB;
use yii\data\ActiveDataProvider;

class UserBase extends UserDB
{
    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = CpBase::find()->addOrderBy('created_time DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 12]
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['LIKE', 'fullname', $this->fullname])
            ->andFilterWhere(['LIKE', 'email', $this->email])
            ->andFilterWhere(['LIKE', 'created_time', $this->created_time]);
        return $dataProvider;
    }
}