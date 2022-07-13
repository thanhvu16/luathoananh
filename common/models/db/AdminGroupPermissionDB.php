<?php

namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "admin_group_permission".
 *
 * @property integer $id
 * @property string $controller
 * @property integer $admin_group_id
 * @property string $permission
 *
 * @property AdminGroup $adminGroup
 */
class AdminGroupPermissionDB extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_group_permission';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_group_id'], 'integer'],
            [['permission'], 'string'],
            [['controller'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'controller' => 'Controller',
            'admin_group_id' => 'Admin Group ID',
            'permission' => 'Permission',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminGroup()
    {
        return $this->hasOne(AdminGroupDB::className(), ['id' => 'admin_group_id']);
    }
}