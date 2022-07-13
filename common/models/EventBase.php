<?php

namespace common\models;

use common\models\db\EventDB;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class EventBase extends EventDB
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'point', 'event_group_id', 'created_by', 'updated_by', 'reset'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 3000]
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = self::find();
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
            ->andFilterWhere(['LIKE', 'point', $this->point])
            ->andFilterWhere(['LIKE', 'event_group_id', $this->event_group_id])
            ->andFilterWhere(['LIKE', 'created_time', $this->created_time])
            ->andFilterWhere(['LIKE', 'updated_time', $this->updated_time])
            ->andFilterWhere(['LIKE', 'created_by', $this->created_by])
            ->andFilterWhere(['LIKE', 'updated_by', $this->updated_by])
            ->andFilterWhere(['LIKE', 'updated_by', $this->updated_by]);
        return $dataProvider;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('cms', 'ID'),
            'name' => Yii::t('cms', 'name'),
            'description' => Yii::t('cms', 'desc'),
            'status' => Yii::t('cms', 'status'),
            'point' => Yii::t('cms', 'point'),
            'event_group_id' => Yii::t('cms', 'event_group_id'),
            'created_time' => Yii::t('cms', 'created_time'),
            'updated_time' => Yii::t('cms', 'updated_time'),
            'created_by' => Yii::t('cms', 'created_by'),
            'updated_by' => Yii::t('cms', 'updated_by'),
            'reset' => Yii::t('cms', 'reset'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventGroup()
    {
        return $this->hasOne(EventGroupBase::className(), ['id' => 'event_group_id']);
    }
}