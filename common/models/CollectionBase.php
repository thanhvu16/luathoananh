<?php

namespace common\models;

use common\models\db\AdminDB;
use common\models\db\CollectionDB;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class CollectionBase extends CollectionDB
{
    /**
     * @params: NULL
     * @function: Gọi scenarios của lớp Model
     */
    public function scenarios() {
        return Model::scenarios();
    }
    /**
     * @params: $params: Mảng dữ liệu hiển thị
     * @function: Hàm này xử lý phần hiển thị danh sách của nội dung
     */
    public function search($params) {
        $query = self::find()->addOrderBy('created_time DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 12]
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['LIKE', 'name', $this->name])
            ->andFilterWhere(['LIKE', 'description', $this->description])
            ->andFilterWhere(['LIKE', 'status', $this->status])
            ->andFilterWhere(['LIKE', 'created_time', $this->created_time])
            ->andFilterWhere(['LIKE', 'updated_time', $this->updated_time]);
        return $dataProvider;
    }

    public function getCreatedBy()
    {
        return $this->hasOne(AdminDB::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(AdminDB::className(), ['id' => 'updated_by']);
    }
}
