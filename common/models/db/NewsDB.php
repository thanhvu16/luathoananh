<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $brief
 * @property string $content
 * @property string $content_amp
 * @property string $title_seo
 * @property string $description_seo
 * @property string $rel_ids
 * @property string $keyword
 * @property string $pseudonym
 * @property integer $status
 * @property string $created_time
 * @property string $updated_time
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $news_category_id
 * @property integer $deleted
 * @property string $time_active
 * @property integer $is_hot
 * @property integer $time_start_match
 * @property string $tags
 * @property string $image
 * @property integer $status_ping
 * @property integer $league_id
 * @property integer $match_id
 * @property string $menu_content
 */
class NewsDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'slug', 'content', 'news_category_id'], 'required'],
            [['content', 'menu_content', 'content_amp'], 'string'],
            [['created_time', 'updated_time', 'time_active'], 'safe'],
            [['status_ping', 'created_by', 'updated_by', 'news_category_id', 'deleted', 'status', 'is_hot', 'match_id', 'league_id', 'time_start_match'], 'integer'],
            [['title', 'slug', 'title_seo', 'description_seo', 'keyword', 'tags', 'image'], 'string', 'max' => 255],
            [['brief'], 'string', 'max' => 3000],
            [['rel_ids', 'pseudonym'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Tiêu đề',
            'slug' => 'Đường dẫn',
            'brief' => 'Mô tả bài viết',
            'content' => 'Nội dung',
            'content_amp' => 'Nội dung',
            'title_seo' => 'Tiêu đề Seo',
            'description_seo' => 'Mô tả Seo',
            'rel_ids' => 'Bài viết liên quan',
            'keyword' => 'Keyword',
            'pseudonym' => 'Chữ ký',
            'status' => 'Trạng thái',
            'created_time' => 'Ngày tạo',
            'updated_time' => 'Ngày cập nhật',
            'created_by' => 'Người tạo',
            'updated_by' => 'Người cập nhật',
            'news_category_id' => 'Danh mục bài viết',
            'deleted' => 'Deleted',
            'time_active' => 'Thời gian xuất bản',
            'is_hot' => 'Is Hot',
            'time_start_match' => 'Thời gian diễn ra trận đấu',
            'tags' => 'Tags',
            'image' => 'Image',
            'country_name' => 'Country Name',
            'status_ping' => 'Status Ping',
            'match_id' => 'ID Trận đấu',
            'league_id' => 'ID Giải đấu',
            'menu_content' => 'Menu Content'
        ];
    }
}
