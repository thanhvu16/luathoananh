<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "magazines".
 *
 * @property integer $id
 * @property string $title
 * @property string $sapo
 * @property integer $status
 * @property string $public_time
 * @property string $image
 * @property string $created_time
 * @property string $updated_time
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $seo_keywords
 * @property string $seo_description
 * @property string $seo_title
 * @property string $designer
 * @property string $author
 * @property string $author_image
 * @property string $clip
 * @property string $image_cover_web
 * @property string $image_cover_wap
 * @property string $background
 * @property string $source
 * @property string $source_link
 * @property integer $is_hot
 * @property integer $deleted
 * @property string $rel_ids
 * @property string $link_cover
 * @property string $text_cover
 */
class MagazineDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'magazines';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['public_time', 'created_time', 'updated_time'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['title', 'image', 'seo_title', 'designer', 'author', 'author_image', 'clip', 'image_cover_web', 'image_cover_wap', 'background', 'source', 'source_link', 'rel_ids', 'link_cover', 'text_cover'], 'string', 'max' => 255],
            [['sapo', 'seo_keywords', 'seo_description'], 'string', 'max' => 500],
			[['content_cover'], 'string', 'max' => 1000],
            [['status', 'is_hot', 'deleted'], 'string', 'max' => 1]
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
            'sapo' => 'Sapo',
            'status' => 'Status',
            'public_time' => 'Public Time',
            'image' => 'Image',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'seo_keywords' => 'Seo Keywords',
            'seo_description' => 'Seo Description',
            'seo_title' => 'Seo Title',
            'designer' => 'Designer',
            'author' => 'Author',
            'author_image' => 'Author Image',
            'clip' => 'Clip',
            'image_cover_web' => 'Image Cover Web',
            'image_cover_wap' => 'Image Cover Wap',
            'background' => 'Background',
            'source' => 'Source',
            'source_link' => 'Source Link',
            'is_hot' => 'Is Hot',
            'deleted' => 'Deleted',
            'rel_ids' => 'Rel Ids',
            'link_cover' => 'Link Cover',
            'text_cover' => 'Text Cover',
            'content_cover' => 'Content Cover',
        ];
    }
}
