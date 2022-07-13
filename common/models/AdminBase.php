<?php

namespace common\models;

use common\components\CFunction;
use yii\caching\DbDependency;
use yii\data\ActiveDataProvider;
use common\models\db\AdminDB;
use yii\base\Model;
use Yii;


class AdminBase extends AdminDB {
    const ADMIN_INACTIVE = 'INACTIVE';
    const ADMIN_ACTIVE = 'ACTIVE';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_by', 'updated_by', 'admin_group_id', 'cp_id'], 'integer'],
            [['username', 'password', 'fullname', 'phonenumber', 'email', 'created_time', 'updated_time'], 'safe'],
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
        $query = AdminBase::find()->orderBy('username asc');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 12]
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['LIKE', 'username', $this->username])
            ->andFilterWhere(['LIKE', 'password', $this->password])
            ->andFilterWhere(['LIKE', 'fullname', $this->fullname])
            ->andFilterWhere(['LIKE', 'phonenumber', $this->phonenumber])
            ->andFilterWhere(['LIKE', 'email', $this->email])
            ->andFilterWhere(['LIKE', 'status', $this->status])
            ->andFilterWhere(['LIKE', 'created_time', $this->created_time])
            ->andFilterWhere(['LIKE', 'updated_time', $this->updated_time])
            ->andFilterWhere(['LIKE', 'created_by', $this->created_by])
            ->andFilterWhere(['LIKE', 'updated_by', $this->updated_by])
            ->andFilterWhere(['LIKE', 'admin_group_id', $this->admin_group_id])
            ->andFilterWhere(['LIKE', 'cp_id', $this->cp_id]);
        return $dataProvider;
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'username' => Yii::t('cms', 'username'),
            'password' => Yii::t('cms', 'password'),
            'fullname' => Yii::t('cms', 'fullname'),
            'phonenumber' => Yii::t('cms', 'phonenumber'),
            'email' => 'Email',
            'status' => Yii::t('cms', 'status'),
            'created_time' => Yii::t('cms', 'created_time'),
            'updated_time' => 'Updated Time',
            'created_by' => Yii::t('cms', 'created_by'),
            'updated_by' => 'Updated By',
            'admin_group_id' => Yii::t('cms', 'mnu_admin_group'),
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminGroup() {
        return $this->hasOne(AdminGroupBase::className(), ['id' => 'admin_group_id']);
    }

    public function getGroup()
    {
        return $this->hasOne(AdminGroupBase::className(), ['id' => 'admin_group_id']);
    }
    public function getCreatedBy()
    {
        return $this->hasOne(AdminBase::className(), ['id' => 'created_by']);
    }

    public static function getAdminFullName($id) {
        $dependency = new DbDependency(['sql' => 'SELECT MAX(updated_time) FROM admin']);
        $result = Yii::$app->cache->get('cache_admin_name'.$id);
        if ($result === false) {
            $result = AdminBase::find()->select('fullname')
                ->where('id = :id', [':id' => $id])
                ->asArray()
                ->one();
            Yii::$app->cache->set('cache_admin_name'.$id, $result, CFunction::getParams('cache_refresh'), $dependency);
        }
        if (!empty($result)) {
            return $result['fullname'];
        } else {
            return '';
        }
    }

}
