<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "admin_action".
 *
 * @property integer $id
 * @property string $action
 * @property string $desc
 * @property string $updated_time
 * @property integer $admin_controller_id
 * @property integer $status
 */
class AdminActionDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_action';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['updated_time'], 'safe'],
            [['admin_controller_id', 'status'], 'integer'],
            [['action'], 'string', 'max' => 255],
            [['desc'], 'string', 'max' => 3000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'action' => 'Action',
            'desc' => 'Desc',
            'updated_time' => 'Updated Time',
            'admin_controller_id' => 'Admin Controller ID',
            'status' => 'Status',
        ];
    }
}
