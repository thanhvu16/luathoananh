<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 */
class UserDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_time'], 'string'],
            [['fullname', 'email'], 'string', 'max' => 255],
            [['note'], 'string', 'max' => 50000],
            [['phone'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 50],
			[['file'], 'file'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('cms', 'ID'),
            'fullname' => Yii::t('cms', 'Họ và tên'),
            'file' => Yii::t('cms', 'File'),
            'note' => Yii::t('cms', 'Ghi chú'),
            'email' => Yii::t('cms', 'Email'),
            'phone' => Yii::t('cms', 'Số điệu thoại'),
            'created_time' => Yii::t('cms', 'Ngày nhận yêu cầu'),
        ];
    }
}
