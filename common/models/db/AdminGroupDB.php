<?php

namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "admin_group".
 *
 * @property integer $id
 * @property string $group_name
 * @property string $group_desc
 * @property integer $status
 * @property string $created_time
 * @property string $updated_time
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property AdminGroupPermission[] $adminGroupPermissions
 */
class AdminGroupDB extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['group_name'], 'required'],
            [['group_desc'], 'string'],
            [['status', 'created_by', 'updated_by'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['group_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_name' => Yii::t('cms', 'group_name'),
            'group_desc' => Yii::t('cms', 'group_desc'),
            'status' => Yii::t('cms', 'status'),
            'created_time' => Yii::t('cms', 'created_date'),
            'updated_time' => 'Updated Time',
            'created_by' => Yii::t('cms', 'created_by'),
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminGroupPermissions()
    {
        return $this->hasMany(AdminGroupPermissionDB::className(), ['admin_group_id' => 'id']);
    }
}