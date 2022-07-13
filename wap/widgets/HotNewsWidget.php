<?php

namespace wap\widgets;

use wap\models\News;
use yii\base\Widget;
use Yii;

class HotNewsWidget extends Widget
{
    public $categories = [];
    public $isHot = 1;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $hotNews = News::getNewsHotIndex($this->categories,  $this->isHot, 24);
        return $this->render('hot-news', compact('hotNews'));
    }
}