<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "log_news".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $news_id
 * @property integer $action_flag
 * @property string $action_time
 * @property string $model
 * @property string $object_repr
 * @property string $action_message
 * @property string $change_status
 */
class LogNewsDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'news_id', 'action_flag'], 'integer'],
            [['action_time'], 'safe'],
            [['model', 'object_repr', 'change_status'], 'string', 'max' => 255],
            [['action_message'], 'string']
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
            'news_id' => 'News ID',
            'action_flag' => 'Action Flag',
            'action_time' => 'Action Time',
            'model' => 'Model',
            'object_repr' => 'Object Repr',
            'action_message' => 'Action Message',
            'change_status' => 'Change Status',
        ];
    }
}
