<?php

namespace common\models;

use Yii;
use yii\base\Model;
use common\models\db\CpDB;
use yii\data\ActiveDataProvider;

class CpBase extends CpDB
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['status', 'code', 'priority', 'created_by', 'updated_by', 'order'], 'integer'],
            [['sharing_rate'], 'number'],
            [['created_time', 'updated_time'], 'safe'],
            [['name', 'email'], 'string', 'max' => 255]
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
        $query = CpBase::find()->addOrderBy('order');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 12]
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['LIKE', 'name', $this->name])
            ->andFilterWhere(['LIKE', 'status', $this->status])
            ->andFilterWhere(['LIKE', 'created_time', $this->created_time])
            ->andFilterWhere(['LIKE', 'created_by', $this->created_by]);
        return $dataProvider;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('cms', 'ID'),
            'name' => Yii::t('cms', 'Name'),
            'description' => Yii::t('cms', 'Description'),
            'status' => Yii::t('cms', 'Status'),
            'email' => Yii::t('cms', 'Email'),
            'code' => Yii::t('cms', 'Code'),
            'priority' => Yii::t('cms', 'Priority'),
            'sharing_rate' => Yii::t('cms', 'Sharing Rate'),
            'created_time' => Yii::t('cms', 'Created Time'),
            'updated_time' => Yii::t('cms', 'Updated Time'),
            'created_by' => Yii::t('cms', 'Created By'),
            'updated_by' => Yii::t('cms', 'Updated By'),
            'order' => Yii::t('cms', 'Order'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdmins()
    {
        return $this->hasMany(AdminBase::className(), ['cp_id' => 'id']);
    }
}