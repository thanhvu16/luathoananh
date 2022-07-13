<?php
namespace common\models\db;

use Yii;

/**
 * This is the model class for table "director".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $country_id
 * @property string $thumbnail
 * @property string $created_time
 * @property integer $created_by
 * @property string $updated_time
 * @property integer $updated_by
 *
 * @property Country $country
 */
class DirectorDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'director';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id', 'created_by', 'updated_by'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'required'],
            [['description'], 'string', 'max' => 3000],
            [['thumbnail'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'country_id' => 'Country ID',
            'thumbnail' => 'Thumbnail',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'updated_time' => 'Updated Time',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(CountryDB::className(), ['id' => 'country_id']);
    }
}