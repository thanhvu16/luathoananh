<?php

namespace common\models\db;

use Yii;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for collection "AdminLogMDB".
 *
 * @property \MongoId|string $_id
 * @property mixed $admin_id
 * @property mixed $admin_username
 * @property mixed $controller
 * @property mixed $action
 * @property mixed $object_id
 * @property mixed $object_name
 * @property mixed $params
 * @property mixed $log_time
 */
class AdminLogMDB extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['video', 'admin_log'];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'admin_id',
            'admin_username',
            'controller',
            'action',
            'object_id',
            'object_name',
            'params',
            'log_time',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_id', 'admin_username', 'controller', 'action', 'object_id', 'object_name', 'params', 'log_time'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'admin_id' => 'Admin ID',
            'admin_username' => Yii::t('cms', 'admin_username'),
            'controller' => 'Controller',
            'action' => 'Action',
            'object_id' => 'Object ID',
            'object_name' => Yii::t('cms', 'object_name'),
            'params' => 'Params',
            'log_time' => Yii::t('cms', 'log_time'),
        ];
    }
}