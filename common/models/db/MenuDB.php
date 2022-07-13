<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property string $id
 * @property string $title_1
 * @property string $title_2
 * @property string $title_3
 * @property string $desc_1
 * @property string $desc_2
 * @property string $desc_3
 * @property string $route
 * @property integer $active
 * @property string $type
 * @property string $parent_id
 * @property string $order
 * @property string $icon
 * @property string $created_time
 * @property integer $created_by
 * @property string $updated_time
 * @property integer $updated_by
 * @property integer $content_id
 * @property string $content_type
 */
class MenuDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title_1'], 'required'],
            [['active', 'parent_id', 'order', 'created_by', 'updated_by', 'content_id'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['title_1', 'title_2', 'title_3', 'desc_1', 'desc_2', 'desc_3', 'type'], 'string', 'max' => 255],
            [['route', 'icon'], 'string', 'max' => 100],
            [['content_type'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('cms', 'ID'),
            'title_1' => Yii::t('cms', 'Title 1'),
            'title_2' => Yii::t('cms', 'Title 2'),
            'title_3' => Yii::t('cms', 'Title 3'),
            'desc_1' => Yii::t('cms', 'Desc 1'),
            'desc_2' => Yii::t('cms', 'Desc 2'),
            'desc_3' => Yii::t('cms', 'Desc 3'),
            'route' => Yii::t('cms', 'Route'),
            'active' => Yii::t('cms', 'Active'),
            'type' => Yii::t('cms', 'Type'),
            'parent_id' => Yii::t('cms', 'Parent ID'),
            'order' => Yii::t('cms', 'Order'),
            'icon' => Yii::t('cms', 'Icon'),
            'created_time' => Yii::t('cms', 'Created Time'),
            'created_by' => Yii::t('cms', 'Created By'),
            'updated_time' => Yii::t('cms', 'Updated Time'),
            'updated_by' => Yii::t('cms', 'Updated By'),
            'content_id' => Yii::t('cms', 'Content ID'),
            'content_type' => Yii::t('cms', 'Content Type'),
        ];
    }
}
