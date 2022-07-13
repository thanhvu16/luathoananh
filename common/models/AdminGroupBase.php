<?php
/**
 * @Function: Lớp mặc định của phần admin
 * @Author: trinh.kethanh@gmail.com
 * @Date: 08/01/2015
 * @System: Video 2.0
 */
namespace common\models;

use Yii;
use yii\base\Model;
use common\models\db\AdminGroupDB;
use yii\data\ActiveDataProvider;

class AdminGroupBase extends AdminGroupDB {
    const GROUP_INACTIVE = 'INACTIVE';
    const GROUP_ACTIVE = 'ACTIVE';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['group_name', 'group_desc', 'created_time', 'updated_time'], 'safe'],
        ];
    }
    /*
     * @params: NULL
     * @function: Gọi scenarios của lớp Model
     */
    public function scenarios() {
        return Model::scenarios();
    }
    /*
     * @params: $params: Mảng dữ liệu hiển thị
     * @function: Hàm này xử lý phần hiển thị danh sách của nội dung
     */
    public function search($params) {
        $query = AdminGroupBase::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 12]
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['LIKE', 'group_desc', $this->group_desc])
            ->andFilterWhere(['LIKE', 'status', $this->status])
            ->andFilterWhere(['LIKE', 'created_date', $this->created_time])
            ->andFilterWhere(['LIKE', 'group_name', $this->group_name]);
        return $dataProvider;
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_name' => Yii::t('cms', 'group_name'),
            'group_desc' => Yii::t('cms', 'group_desc'),
            'status' => Yii::t('cms', 'status'),
            'created_time' => Yii::t('cms', 'created_date'),
            'updated_time' => 'Updated Time',
            'created_by' => Yii::t('cms', 'created_by'),
            'updated_by' => 'Updated By',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdmins() {
        return $this->hasMany(AdminBase::className(), ['admin_group_id' => 'id']);
    }

    public function getGroupPermission()
    {
        return $this->hasMany(AdminGroupPermissionBase::className(), ['admin_group_id' => 'id']);
    }
}