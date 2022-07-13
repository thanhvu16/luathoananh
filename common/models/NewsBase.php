<?php

namespace common\models;

use cms\models\Admin;
use cms\models\AdminGroup;
use cms\models\NewsCategory;
use Yii;
use yii\data\ActiveDataProvider;
use yii\base\Model;


class NewsBase extends \common\models\db\NewsDB{
    const NEWS_CANCEL = 0;
    const NEWS_ACTIVE = 1;
    const NEWS_DRAFT = 2;
    const NEWS_SEND = 3;
    const NEWS_PV_CONFIRM_EDIT = 4;
    const NEWS_BTV_CONFIRM_EDIT = 5;
    const NEWS_CONFIRM_ACTIVE = 6;
    const NEWS_ACCEPT_ACTIVE = 7;
    const NEWS_BACK = 8;
    const NEWS_PV_ACCEPT_EDIT = 9;
    const NEWS_BTV_ACCEPT_EDIT = 10;

    const DELETED = 1;

    protected static $statusNews = [
        self::NEWS_ACTIVE => 'Xuất bản',
        self::NEWS_CONFIRM_ACTIVE => 'Chờ duyệt',
        self::NEWS_ACCEPT_ACTIVE => 'Chỉnh sửa',
    ];

    /**
     * @params: $params: Mảng dữ liệu hiển thị
     * @function: Hàm này xử lý phần hiển thị danh sách của nội dung
     */
    public function search($params) {
        $query = NewsBase::find()
            ->andFilterWhere(['<>', 'deleted', self::DELETED])
            ->addOrderBy('created_time DESC');

        if(Yii::$app->user->identity->admin_group_id == AdminGroup::GROUP_CTV){
            $query->andFilterWhere(['created_by' => Yii::$app->user->id]);
        }
        if(!empty($params['time'])){
            $arr = explode(' - ', $params['time']);
            if(!empty($arr[0]))
                $query->andFilterWhere(['>=', 'created_time', $arr[0] . ' 00:00:00']);
            if(!empty($arr[1]))
                $query->andFilterWhere(['<=', 'created_time', $arr[1] . ' 23:59:59']);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 12]
        ]);
        if(!empty($params['title']))
            $this->title = $params['title'];
        if(!empty($params['id']))
            $this->id = $params['id'];
        if(!empty($params['news_category_id']))
            $this->news_category_id = $params['news_category_id'];
        if (($this->load($params) && !$this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['LIKE', 'title', $this->title])
            ->andFilterWhere(['LIKE', 'brief', $this->brief])
            ->andFilterWhere(['LIKE', 'keyword', $this->keyword])
            ->andFilterWhere(['=', 'status', $this->status])
            ->andFilterWhere(['=', 'created_by', $this->created_by])
            ->andFilterWhere(['=', 'updated_by', $this->updated_by])
            ->andFilterWhere(['=', 'news_category_id', $this->news_category_id]);
        return $dataProvider;
    }

    public function searchDelete($params) {
        $query = NewsBase::find()
            ->andFilterWhere(['deleted' => self::DELETED])
            ->addOrderBy('created_time DESC');

        if(Yii::$app->user->identity->admin_group_id == AdminGroup::GROUP_CTV){
            $query->andFilterWhere(['created_by' => Yii::$app->user->id]);
        }
        if(!empty($params['time'])){
            $arr = explode(' - ', $params['time']);
            if(!empty($arr[0]))
                $query->andFilterWhere(['>=', 'created_time', $arr[0] . ' 00:00:00']);
            if(!empty($arr[1]))
                $query->andFilterWhere(['<=', 'created_time', $arr[1] . ' 23:59:59']);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 12]
        ]);
        if(!empty($params['title']))
            $this->title = $params['title'];
        if (($this->load($params) && !$this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['LIKE', 'title', $this->title])
            ->andFilterWhere(['LIKE', 'brief', $this->brief])
            ->andFilterWhere(['LIKE', 'keyword', $this->keyword])
            ->andFilterWhere(['=', 'status', $this->status])
            ->andFilterWhere(['=', 'created_by', $this->created_by])
            ->andFilterWhere(['=', 'updated_by', $this->updated_by])
            ->andFilterWhere(['=', 'news_category_id', $this->news_category_id]);
        return $dataProvider;
    }

    public function getCategory()
    {
        return $this->hasOne(NewsCategory::className(), ['id' => 'news_category_id']);
    }
    public function getCreatedBy()
    {
        return $this->hasOne(Admin::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(Admin::className(), ['id' => 'updated_by']);
    }

    public function getNameStatus($status) {
        return self::$statusNews[$status] ?? false;
    }

    public function checkAdmin(){
        if (isset(Yii::$app->user->identity->username) && ((Yii::$app->user->identity->username === 'admin')) || Yii::$app->user->identity->admin_group_id == AdminGroup::GROUP_ADMIN)
            return true;
        return false;
    }

    public function checkPermissionStatus(){
        if($this->checkAdmin()) {
            return true;
        }
        $listStatus = $this->getPermisstionStatus();
        if(empty($listStatus)){
            return false;
        }
        return in_array($this->status, $this->getPermisstionStatus());
    }

    public function statusCreate() {
        return [
            self::NEWS_ACTIVE,
            self::NEWS_DRAFT,
            self::NEWS_SEND,
			self::NEWS_CONFIRM_ACTIVE,
            self::NEWS_ACCEPT_ACTIVE
        ];
    }

    public function getPermisstionStatus() {
        if($this->checkAdmin()) {
            return array_keys(self::$statusNews);
        }
        $groupId = Yii::$app->user->identity->admin_group_id;
        $status = [];
        switch ($groupId) {
            case AdminGroup::GROUP_CTV:
                $status = [
                    self::NEWS_CONFIRM_ACTIVE,
                    self::NEWS_ACCEPT_ACTIVE
                ];
                break;
            case AdminGroup::GROUP_BTV:
                $status = [
                    self::NEWS_CONFIRM_ACTIVE,
                    self::NEWS_ACCEPT_ACTIVE
                ];
                break;
            case AdminGroup::GROUP_CVCC:
                $status = [
                    self::NEWS_CONFIRM_ACTIVE,
                    self::NEWS_ACCEPT_ACTIVE
                ];
                break;
            case AdminGroup::GROUP_TBT:
                $status = [
					self::NEWS_ACTIVE,
                    self::NEWS_CONFIRM_ACTIVE,
                    self::NEWS_ACCEPT_ACTIVE
                ];
                break;
            default:
                break;
        }
        return  $status;
    }
}
