<?php

namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "admin_permission".
 *
 * @property integer $id
 * @property string $controller
 * @property integer $admin_id
 * @property string $permission
 */
class AdminPermissionDB extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_permission';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_id'], 'integer'],
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
            'admin_id' => 'Admin ID',
            'permission' => 'Permission',
        ];
    }
}
