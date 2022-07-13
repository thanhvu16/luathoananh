<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "permission_category".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $account_id
 * @property integer $group_id
 * @property integer $status
 * @property integer $is_deleted
 * @property string $created_time
 * @property string $updated_time
 * @property integer $created_by
 * @property integer $updated_by
 */
class PermissionCategoryDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'permission_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'account_id'], 'required'],
            [['category_id', 'account_id', 'group_id', 'created_by', 'updated_by'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['status', 'is_deleted'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'account_id' => 'Account ID',
            'group_id' => 'Group ID',
            'status' => 'Status',
            'is_deleted' => 'Is Deleted',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
