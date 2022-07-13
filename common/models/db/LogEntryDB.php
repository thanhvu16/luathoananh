<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "log_entry".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $object_id
 * @property integer $action_flag
 * @property string $action_time
 * @property string $model
 * @property string $object_repr
 * @property string $action_message
 */
class LogEntryDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_entry';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'object_id', 'action_flag'], 'integer'],
            [['action_time'], 'safe'],
            [['model', 'object_repr', 'action_message'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'object_id' => 'Object ID',
            'action_flag' => 'Action Flag',
            'action_time' => 'Action Time',
            'model' => 'Model',
            'object_repr' => 'Object Repr',
            'action_message' => 'Action Message',
        ];
    }
}
