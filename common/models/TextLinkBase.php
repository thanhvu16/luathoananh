<?php

namespace common\models;

use common\models\db\AdminDB;
use Yii;
use yii\data\ActiveDataProvider;


class TextLinkBase extends \common\models\db\TextLinkDB{

    public function search($params) {
        $query = self::find()->addOrderBy('created_time DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 12]
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->title])
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
