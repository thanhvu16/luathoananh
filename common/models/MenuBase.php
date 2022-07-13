<?php

namespace common\models;

use Yii;
use yii\base\Model;
use cms\models\MenuQuery;
use common\models\db\MenuDB;
use common\components\MyBehavior;
use yii\data\ActiveDataProvider;
use cms\components\nestedsets\NestedSetsBehavior;

class MenuBase extends MenuDB
{
    const MENU_ACTIVE = '1';
    const MENU_INACTIVE = '0';
    const MENU_TYPE_WAP = '3';
    const MENU_TYPE_WEB = '2';
    const MIN_LEVEL = 0;
    const MAX_LEVEL = 9;

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new MenuQuery(get_called_class());
    }

    /*-------------------------Cấu hình Nestedsets End------------------------*/
    /**
     * @params: NULL
     * @function: Gọi scenarios của lớp Model
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * @params: $params: Mảng dữ liệu hiển thị
     * @function: Hàm này xử lý phần hiển thị danh sách của nội dung
     */
    public function search($params)
    {
        $query = MenuBase::find()->addOrderBy('lft');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 200]
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['LIKE', 'title_1', $this->title_1])
            ->andFilterWhere(['LIKE', 'title_2', $this->title_2])
            ->andFilterWhere(['LIKE', 'title_3', $this->title_3])
            ->andFilterWhere(['LIKE', 'desc_1', $this->desc_1])
            ->andFilterWhere(['LIKE', 'desc_2', $this->desc_2])
            ->andFilterWhere(['LIKE', 'desc_3', $this->desc_3])
            ->andFilterWhere(['LIKE', 'route', $this->route])
            ->andFilterWhere(['LIKE', 'active', $this->active])
            ->andFilterWhere(['LIKE', 'type', $this->type])
            ->andFilterWhere(['LIKE', 'parent_id', $this->parent_id])
            ->andFilterWhere(['LIKE', 'root', $this->root])
            ->andFilterWhere(['LIKE', 'lft', $this->lft])
            ->andFilterWhere(['LIKE', 'rgt', $this->rgt])
            ->andFilterWhere(['LIKE', 'level', $this->level])
            ->andFilterWhere(['LIKE', 'icon', $this->icon]);
        return $dataProvider;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('cms', 'ID'),
            'title_1' => Yii::t('cms', 'title_category'),
            'title_2' => Yii::t('cms', 'title_category'),
            'title_3' => Yii::t('cms', 'title_category'),
            'desc_1' => Yii::t('cms', 'desc_category'),
            'desc_2' => Yii::t('cms', 'desc_category'),
            'desc_3' => Yii::t('cms', 'desc_category'),
            'route' => Yii::t('cms', 'route_category'),
            'active' => Yii::t('cms', 'status_category'),
            'type' => Yii::t('cms', 'type_category'),
            'parent_id' => Yii::t('cms', 'parent_id_category'),
            'root' => Yii::t('cms', 'Root'),
            'lft' => Yii::t('cms', 'Lft'),
            'rgt' => Yii::t('cms', 'Rgt'),
            'level' => Yii::t('cms', 'Level'),
            'icon' => Yii::t('cms', 'Icon')
        ];
    }
}