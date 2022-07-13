<?php

namespace common\models;

use cms\models\Admin;
use cms\models\NewsCategory;
use Yii;


class PermissionCategoryBase extends \common\models\db\PermissionCategoryDB{

    public function getCategory()
    {
        return $this->hasOne(NewsCategory::className(), ['id' => 'category_id']);
    }

    public function getAccountAdmin()
    {
        return $this->hasOne(Admin::className(), ['id' => 'account_id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(Admin::className(), ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(Admin::className(), ['id' => 'updated_by']);
    }
}
