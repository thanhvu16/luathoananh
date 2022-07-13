<?php

namespace wap\widgets;

use wap\models\News;
use yii\base\Widget;
use Yii;

class DoiNguWidget extends Widget
{

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $doingu = News::getListNewsByCateId(6, 4);
        return $this->render('doi-ngu', compact('doingu'));
    }
}