<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "text_link".
 *
 * @property integer $id
 * @property string $title
 * @property string $title_seo
 * @property string $link
 * @property integer $order
 * @property integer $status
 * @property integer $created_time
 * @property integer $updated_time
 * @property integer $created_by
 * @property integer $updated_by
 */
class TextLinkDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'text_link';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'link', 'order'], 'required'],
            [['order', 'created_time', 'updated_time', 'created_by', 'updated_by'], 'integer'],
            [['title', 'title_seo', 'link'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'title_seo' => 'Title Seo',
            'link' => 'Link',
            'order' => 'Order',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
