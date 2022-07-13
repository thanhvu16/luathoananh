<?php

namespace common\models;

use common\models\db\CollectionNewsDB;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class CollectionNewsBase extends CollectionNewsDB
{
    /**
     * @params: NULL
     * @function: Gọi scenarios của lớp Model
     */
    public function scenarios() {
        return Model::scenarios();
    }

    public function search($params) {
        $query = self::find()->addOrderBy('created_time DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 12]
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['LIKE', 'created_time', $this->created_time])
            ->andFilterWhere(['LIKE', 'updated_time', $this->updated_time]);
        return $dataProvider;
    }
}
