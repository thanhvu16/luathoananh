<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "ads".
 *
 * @property integer $id
 * @property string $name
 * @property integer $order_no
 * @property string $url
 * @property string $image
 * @property string $created_time
 * @property string $updated_time
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $status
 */
class AdsDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ads';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['order_no', 'created_by', 'updated_by', 'status'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['url', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_no' => 'Vị trí',
            'image' => 'Ảnh thumb',
            'url' => 'Đường dẫn',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'status' => 'Trạng thái',
        ];
    }
}
