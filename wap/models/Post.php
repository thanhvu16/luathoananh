<?php

namespace wap\models;

use yii\db\ActiveRecord;

class Post extends ActiveRecord
{
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return '{{posts}}';
    }

    public function getCategories()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getSubcategory()
    {
        return $this->hasOne(Subcategory::className(), ['id' => 'subcategory_id']);
    }

    public static function getHotPosts()
    {
        return self::find()->with(['subcategory'])->orderBy('updated_at DESC')->limit(6)->all();
    }
}