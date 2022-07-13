<?php

namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "admin".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $fullname
 * @property string $phonenumber
 * @property string $email
 * @property integer $status
 * @property string $created_time
 * @property string $updated_time
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $admin_group_id
 * @property integer $cp_id
 */
class AdminDB extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'admin_group_id','email'], 'required'],
            [['id', 'status', 'created_by', 'updated_by', 'admin_group_id', 'cp_id'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['username'], 'string', 'max' => 50],
            [['password', 'fullname', 'email'], 'string', 'max' => 255],
            [['phonenumber'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => Yii::t('cms', 'username'),
            'password' => Yii::t('cms', 'password'),
            'fullname' => Yii::t('cms', 'fullname'),
            'phonenumber' => Yii::t('cms', 'phonenumber'),
            'email' => 'Email',
            'status' => Yii::t('cms', 'status'),
            'created_time' => Yii::t('cms', 'created_time'),
            'updated_time' => 'Updated Time',
            'created_by' => Yii::t('cms', 'created_by'),
            'updated_by' => 'Updated By',
            'admin_group_id' => Yii::t('cms', 'mnu_admin_group'),
            'cp_id' => 'CP',
        ];
    }
}