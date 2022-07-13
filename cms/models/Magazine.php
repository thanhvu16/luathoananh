<?php

namespace cms\models;

use Yii;


class Magazine extends \common\models\MagazineBase{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['public_time', 'created_time', 'updated_time'], 'safe'],
            [['created_by', 'updated_by', 'deleted', 'status', 'is_hot'], 'integer'],
            [['title', 'image', 'seo_title', 'designer', 'author', 'author_image', 'clip', 'image_cover_web', 'image_cover_wap', 'background', 'source', 'source_link', 'rel_ids', 'link_cover', 'text_cover'], 'string', 'max' => 255],
            [['sapo', 'seo_keywords', 'seo_description'], 'string', 'max' => 500],
			[['content_cover'], 'string', 'max' => 1000],
        ];
    }

}