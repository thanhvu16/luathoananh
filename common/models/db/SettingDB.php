<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property string $group
 * @property integer $is_serializable
 * @property string $key
 * @property string $values
 */
class SettingDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group', 'key'], 'required'],
            [['values'], 'string'],
            [['group', 'key'], 'string', 'max' => 255],
            [['is_serializable'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'group' => 'Group',
            'is_serializable' => 'Is Serializable',
            'key' => 'Key',
            'values' => 'Values',
        ];
    }
}
