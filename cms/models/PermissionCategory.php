<?php

namespace cms\models;

use common\behaviors\ChangedBehavior;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class PermissionCategory extends \common\models\PermissionCategoryBase{
    const ACTIVE = 1;
    const INACTIVE = 0;
    const IS_DELETED = 1;
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
                'class' => \common\behaviors\TimestampBehavior::className(),
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
        $query = self::find()
            ->where(['account_id' => Yii::$app->request->get('id')])
            ->andWhere(['<>', 'is_deleted', self::IS_DELETED])
            ->addOrderBy('created_time DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 12]
        ]);
        if (($this->load($params) && !$this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['=', 'status', $this->status])
            ->andFilterWhere(['=', 'created_by', $this->created_by])
            ->andFilterWhere(['=', 'updated_by', $this->updated_by]);
        return $dataProvider;
    }

    public static function getListCategoryByAccountId($accountId) {
        $result = self::find()
            ->where(['account_id' => $accountId])
            ->andWhere(['status' => self::ACTIVE])
            ->andWhere(['<>', 'is_deleted', self::IS_DELETED])
            ->orderBy('created_time DESC')
            ->asArray()
            ->all();
        return $result;
    }
}