<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "collection_news".
 *
 * @property integer $collection_id
 * @property integer $news_id
 * @property integer $order
 * @property string $created_time
 * @property string $updated_time
 * @property integer $created_by
 * @property integer $updated_by
 */
class CollectionNewsDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'collection_news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['collection_id', 'news_id'], 'required'],
            [['collection_id', 'news_id', 'order', 'created_by', 'updated_by'], 'integer'],
            [['created_time', 'updated_time'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'collection_id' => 'Collection ID',
            'news_id' => 'News ID',
            'order' => 'Order',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
