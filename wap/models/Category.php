<?php

namespace wap\models;

use yii\db\ActiveRecord;

class Category extends ActiveRecord
{
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return '{{categories}}';
    }

    public function getSubcategories()
    {
        return $this->hasMany(Subcategory::className(), ['category_id' => 'id']);
    }

    public function getTopPosts()
    {
        return $this->hasMany(Post::className(), ['category_id' => 'id'])->with(['subcategory'])->limit(10);
    }

    public function getHomePosts()
    {
        return $this->hasMany(Post::className(), ['category_id' => 'id'])->with(['subcategory'])->where(['is_show_home' => 1])->limit(10);
    }
}