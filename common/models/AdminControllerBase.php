<?php

namespace common\models;

use common\models\db\AdminControllerDB;
use Yii;

class AdminControllerBase extends AdminControllerDB {
    public function getCreatedBy()
    {
        return $this->hasOne(AdminBase::className(), ['created_by' => 'id']);
    }

    public function getAction()
    {
        return $this->hasMany(AdminActionBase::className(), ['admin_controller_id' => 'id']);
    }

    public function getActionActive()
    {
        return $this->hasMany(AdminActionBase::className(),['admin_controller_id' => 'id'])->onCondition(['status'=>1]);
    }

}
