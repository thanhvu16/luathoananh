<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "magazine_contents".
 *
 * @property integer $id
 * @property integer $magazine_id
 * @property string $block_type
 * @property integer $sort_order
 * @property string $content
 * @property string $content_mobile
 * @property integer $updated_by
 * @property string $updated_time
 */
class MagazineContentDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'magazine_contents';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['magazine_id', 'block_type'], 'required'],
            [['magazine_id', 'updated_by'], 'integer'],
            [['content', 'content_mobile'], 'string'],
            [['updated_time'], 'safe'],
            [['block_type'], 'string', 'max' => 255],
            [['sort_order'], 'string', 'max' => 3]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'magazine_id' => 'Magazine ID',
            'block_type' => 'Block Type',
            'sort_order' => 'Sort Order',
            'content' => 'Content',
            'content_mobile' => 'Content Mobile',
            'updated_by' => 'Updated By',
            'updated_time' => 'Updated Time',
        ];
    }
}
