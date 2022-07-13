<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "news_category".
 *
 * @property integer $id
 * @property string $title
 * @property string $title_seo
 * @property string $description_seo
 * @property string $keyword
 * @property string $desc
 * @property string $page_intro
 * @property string $route
 * @property integer $active
 * @property integer $parent_id
 * @property integer $order
 * @property string $created_time
 * @property integer $created_by
 * @property string $updated_time
 * @property integer $updated_by
 * @property string $code
 * @property integer $is_hot
 * @property integer $league_id
 */
class NewsCategoryDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['id', 'parent_id', 'order', 'created_by', 'updated_by', 'league_id'], 'integer'],
            [['page_intro'], 'string'],
            [['created_time', 'updated_time'], 'safe'],
            [['title', 'title_seo', 'description_seo', 'keyword', 'desc'], 'string', 'max' => 255],
            [['route'], 'string', 'max' => 100],
            [['active'], 'string', 'max' => 3],
            [['code'], 'string', 'max' => 500],
            [['is_hot'], 'string', 'max' => 2]
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
            'description_seo' => 'Description Seo',
            'keyword' => 'Keyword',
            'desc' => 'Desc',
            'page_intro' => 'Page Intro',
            'route' => 'Route',
            'active' => 'Active',
            'parent_id' => 'Parent ID',
            'order' => 'Order',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'updated_time' => 'Updated Time',
            'updated_by' => 'Updated By',
            'code' => 'Code',
            'is_hot' => 'Hot',
            'league_id' => 'League ID'
        ];
    }
}
