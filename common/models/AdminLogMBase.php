<?php

namespace common\models;

use common\models\db\AdminLogMDB;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use Yii;
use yii\mongodb\Query;


class AdminLogMBase extends AdminLogMDB {
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
        $query = new Query;
        $query->from('admin_log')->orderBy('log_time DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 12]
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['_id' => $this->_id])
            ->andFilterWhere(['LIKE', 'admin_id', $this->admin_id])
            ->andFilterWhere(['LIKE', 'admin_username', $this->admin_username])
            ->andFilterWhere(['LIKE', 'controller', $this->controller])
            ->andFilterWhere(['LIKE', 'action', $this->action])
            ->andFilterWhere(['LIKE', 'object_id', $this->object_id])
            ->andFilterWhere(['LIKE', 'object_name', $this->object_name])
            ->andFilterWhere(['LIKE', 'params', $this->params])
            ->andFilterWhere(['LIKE', 'log_time', $this->log_time]);
        return $dataProvider;
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'admin_id' => 'Admin ID',
            'admin_username' => Yii::t('cms', 'admin_username'),
            'controller' => 'Controller',
            'action' => 'Action',
            'object_id' => 'Object ID',
            'object_name' => Yii::t('cms', 'object_name'),
            'params' => 'Params',
            'log_time' => Yii::t('cms', 'log_time'),
        ];
    }
}