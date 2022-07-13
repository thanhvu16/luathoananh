<?php

namespace cms\models;

use common\components\CategoryTree;
use common\models\NewsCategoryBase;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveRecord;
use yii\db\Expression;

class NewsCategory extends NewsCategoryBase
{

    public function behaviors()
    {
        return [
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'timestamp' => [
                'class' => 'common\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_time', 'updated_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_time'],
                ],
            ],
        ];
    }


    public static function find()
    {
        return new NewsCategoryQuery(get_called_class());
    }
    /*-------------------------Cấu hình Nestedsets End------------------------*/
    /**
     * @params: NULL
     * @function: Hàm này trả về danh sách status của danh mục menu, sử dụng trong dropDownList
     */
    public static function getMenuStatus() {
        return [
            0 => NewsCategoryBase::MENU_INACTIVE,
            1 => NewsCategoryBase::MENU_ACTIVE
        ];
    }

    public static function getCategoryParent($currentId = ''){
        $query = self::find()
            ->select(['id', 'title','parent_id', 'order']);
        if(!empty($currentId)){
            $query->where(["!=", "id", $currentId]);
        }
        $result = $query->orderBy('order')
            ->orderBy('id')
            ->asArray()->all();
        return $result;
    }

    public static function getMenuStatusText($status) {
        if ($status == 1) {
            $textStatus = Menu::MENU_ACTIVE;
        } else {
            $textStatus = Menu::MENU_INACTIVE;
        }
        return $textStatus;
    }

	public static function getAllCategory()
    {
		$data = self::find()
			->where(['active' => NewsCategoryBase::MENU_ACTIVE])
			->orderBy('order')
			->orderBy('created_time')
			->asArray()->all();
		foreach ($data as $cate) {
			$category[$cate['id']] = $cate;
		}
        return $category;
    }

    public static function getCategory($id) {
        $cate = self::find()
            ->where(['active' => NewsCategoryBase::MENU_ACTIVE])
            ->andWhere(['id' => $id])
            ->asArray()
            ->one();
        if(!empty($cate)){
            return ['title' => $cate['title'], 'parent_id' => $cate['parent_id'], 'name' => $cate['title'], 'id' => $cate['id'], 'route' => $cate['route'], 'slug' => $cate['route']];
        }
        return [];
    }
    /**
     * @params: $parent_id ID của danh mục cha
     * @function: Tìm tên danh mục cha, khi biết id của danh mục cha (parent_id)
     */
    public static function getNameParent($parentId) {
        $result = NewsCategory::find()
            ->where('id = :parentId', [':parentId' => $parentId])
            ->asArray()
            ->one();
        if (!empty($result)) {
            return $result['title'];
        } else {
            return '';
        }
    }
    /**
    * @params: $url -> Đường dẫn của category
    * @function: Hàm này phân tích chuỗi url để lấy controller & action
    */
    public static function explodeUrl($url) {
        $explode = explode("/", $url);
        $i = 0;
        foreach($explode as $value) {
            if ($explode[$i] == '') {
                unset($explode[$i]);
            }
            $i++;
        }
        $arr = array_values($explode);
        if (array_key_exists(1, $arr))
            return $arr;
        else
            return false;
    }

    public static function getAllCate(){
        $data = [];
        $data[0] = 'Tất cả thể loại';
        $cates = self::find()
            ->select('id, title')
            ->where(['active' => 1])
            ->orderBy('order')
            ->asArray()
            ->all();
        foreach ($cates as $cate){
            $data[$cate['id']] = $cate['title'];
        }
        return $data;
    }

    public function getListPopup($colId, $params) {
        $query = self::find();
        if(!empty($params['title']))
            $this->title = $params['title'];
        $listNewsCategoryId = PermissionCategory::getListCategoryByAccountId($colId);
        $category= $query->andFilterWhere(['LIKE', 'title', $this->title])
            ->andFilterWhere(['NOT IN', 'id', array_column($listNewsCategoryId, 'category_id')])
            ->orderBy('order ASC, is_hot DESC')
            ->asArray()
            ->all();
        $dataProvider = new ArrayDataProvider([
            'key'=>'id',
            'allModels' => $category,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);
        return $dataProvider;
    }

    public function checkAdmin(){
        if (isset(Yii::$app->user->identity->username) && ((Yii::$app->user->identity->username === 'admin')) || Yii::$app->user->identity->admin_group_id == AdminGroup::GROUP_ADMIN)
            return true;
        return false;
    }

    public function getListCategoryByPermission(){
        $query = self::find()
            ->select('news_category.*')
            ->where(['active' => self::MENU_ACTIVE]);
        if(!$this->checkAdmin() && Yii::$app->user->identity->admin_group_id != AdminGroup::GROUP_TBT) {
            $query->innerJoin('permission_category', 'news_category.id = permission_category.category_id');
        }
        $result = $query->orderBy('is_hot DESC, order ASC')
            ->asArray()
            ->all();
        return $result;
    }
}
