<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "cp".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $status
 * @property string $email
 * @property string $code
 * @property string $priority
 * @property double $sharing_rate
 * @property string $created_time
 * @property string $updated_time
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $order
 */
class CpDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['status', 'code', 'priority', 'created_by', 'updated_by', 'order'], 'integer'],
            [['sharing_rate'], 'number'],
            [['created_time', 'updated_time'], 'safe'],
            [['name', 'email'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('cms', 'ID'),
            'name' => Yii::t('cms', 'Name'),
            'description' => Yii::t('cms', 'Description'),
            'status' => Yii::t('cms', 'Status'),
            'email' => Yii::t('cms', 'Email'),
            'code' => Yii::t('cms', 'Code'),
            'priority' => Yii::t('cms', 'Priority'),
            'sharing_rate' => Yii::t('cms', 'Sharing Rate'),
            'created_time' => Yii::t('cms', 'Created Time'),
            'updated_time' => Yii::t('cms', 'Updated Time'),
            'created_by' => Yii::t('cms', 'Created By'),
            'updated_by' => Yii::t('cms', 'Updated By'),
            'order' => Yii::t('cms', 'Order'),
        ];
    }
}
