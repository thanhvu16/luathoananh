<?php

namespace cms\models;

use cms\components\AdminPermission;
use common\behaviors\ChangedBehavior;
use common\behaviors\LogNewsBehavior;
use common\behaviors\TimestampBehavior;
use common\components\CFunction;
use Yii;
use common\models\NewsBase;
use yii\behaviors\BlameableBehavior;
use yii\data\ActiveDataProvider;


class News extends NewsBase
{
    public $timeStartMatchFormat;
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
                'class' => LogNewsBehavior::className(),
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

    public function getListPopup($colId, $params) {
        $query = self::find()->addOrderBy('created_time DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 12]
        ]);
        $listNewsId = CollectionNews::getListNewsByCollection($colId);
        if(!empty($params['title']))
            $this->title = $params['title'];
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['LIKE', 'title', $this->title])
            ->andFilterWhere(['NOT IN', 'id', array_column($listNewsId, 'news_id')])
            ->andFilterWhere(['!=', 'deleted', 1]);
        return $dataProvider;
    }


    public function getListNewsCollection($params) {
        $query = self::find()->addOrderBy('created_time DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 12]
        ]);
        $query->select('news.*, cn.order')
            ->andFilterWhere(['id' => $this->id])
            ->innerJoin('collection_news as cn', 'cn.news_id = news.id')
            ->andFilterWhere(['cn.collection_id' => $params])
            ->andFilterWhere(['!=', 'deleted', 1])
            ->orderBy('cn.order DESC');
        return $dataProvider;
    }

    public function __toString(){
        return $this->title;
    }

    public function genStatus($isCreate = true){
        $arr = $this->getPermisstionStatus($isCreate);

        if($isCreate) {
            foreach ($arr as $k => $v){
                if(!in_array($v, $this->statusCreate())){
                    unset($arr[$k]);
                }
            }
        }

        $data = [];
        foreach ($arr as $v) {
            if($this->getNameStatus($v))
                $data[$v] = $this->getNameStatus($v);
        }
        return $data;
    }

    public function checkPermissionActive(){
        if($this->checkAdmin()) {
            return true;
        }
        $groupAdmin = Yii::$app->user->identity->admin_group_id;
        switch ($groupAdmin){
            case AdminGroup::GROUP_CTV:
                $status = false;
                break;
            case AdminGroup::GROUP_BTV:
                $status = false;
                break;
            case AdminGroup::GROUP_CVCC:
                $status = false;
                break;
            case AdminGroup::GROUP_TBT:
                $status = true;
                break;
            default:
                $status = false;
                break;
        }
        return $status && $this->checkPermissionUpdate();
    }

    public function checkPermissionChangeHot(){
        if($this->checkAdmin()) {
            return true;
        }
        $groupAdmin = Yii::$app->user->identity->admin_group_id;
        switch ($groupAdmin){
            case AdminGroup::GROUP_CTV:
                $status = false;
                break;
            case AdminGroup::GROUP_BTV:
                $status = false;
                break;
            case AdminGroup::GROUP_CVCC:
                $status = false;
                break;
            case AdminGroup::GROUP_TBT:
                $status = true;
                break;
            default:
                $status = false;
                break;
        }
        return $status && $this->checkPermissionUpdate();
    }

    public function checkPermissionEdit() {
        if($this->checkAdmin()) {
            return true;
        }
        $groupAdmin = Yii::$app->user->identity->admin_group_id;
        $adminCurrent = Yii::$app->user->id;
        switch ($groupAdmin){
            case AdminGroup::GROUP_CTV:
                $status = $this->created_by == $adminCurrent;
                break;
            case AdminGroup::GROUP_BTV:
                $status = true;
                break;
            case AdminGroup::GROUP_TBT:
                $status = true;
                break;
            default:
                $status = false;
                break;
        }
        return $status && $this->checkPermissionStatusEdit();
    }

    public function checkPermissionStatusEdit()
    {
        if($this->checkAdmin()) {
            return true;
        }
        $groupAdmin = Yii::$app->user->identity->admin_group_id;
        if($groupAdmin == AdminGroup::GROUP_TBT) {
            return true;
        }
        if($groupAdmin == AdminGroup::GROUP_BTV) {
            if($this->status == self::NEWS_ACCEPT_ACTIVE){
                return false;
            }
            if($this->status == self::NEWS_ACTIVE){
                return false;
            }
            return true;
        }
        if($groupAdmin == AdminGroup::GROUP_CTV) {
            if($this->status == self::NEWS_DRAFT || $this->status == self::NEWS_PV_ACCEPT_EDIT){
                return true;
            }
            return false;
        }
    }

    public function checkPermissionDelete() {
        if($this->checkAdmin()) {
            return true;
        }
        $groupAdmin = Yii::$app->user->identity->admin_group_id;
        $adminCurrent = Yii::$app->user->id;
        switch ($groupAdmin){
            case AdminGroup::GROUP_CTV:
                $status = $this->created_by == $adminCurrent;
                break;
            case AdminGroup::GROUP_BTV:
                $status = $this->created_by == $adminCurrent;
                break;
            case AdminGroup::GROUP_TBT:
                $status = true;
                break;
            default:
                $status = false;
                break;
        }
        return $status;
    }
}
