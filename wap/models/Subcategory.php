<?php

namespace wap\models;

use yii\db\ActiveRecord;

class Subcategory extends ActiveRecord
{
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName()
    {
        return '{{subcategories}}';
    }

    public function getCategory()
    {
        return $this->hasOne(Subcategory::className(), ['id' => 'category_id']);
    }
}