<?php

namespace cms\models;

use common\behaviors\ChangedBehavior;
use common\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\data\ActiveDataProvider;
use Yii;


class Sport extends \common\models\SportBase{

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => ChangedBehavior::className(),
            ],
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_time', 'updated_time'],
                    self::EVENT_BEFORE_UPDATE => ['updated_time']
                ]
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }

    public function search($params) {
        $query = self::find()->addOrderBy('created_time DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 12]
        ]);
        if (($this->load($params) && !$this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id]);
        return $dataProvider;
    }
}
