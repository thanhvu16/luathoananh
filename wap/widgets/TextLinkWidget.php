<?php

namespace wap\widgets;

use wap\models\NewsCategory;
use wap\models\TextLink;
use yii\base\Widget;
use Yii;

class TextLinkWidget extends Widget
{
    public $limit = 10;
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $textLink = TextLink::getList($this->limit);
        return $this->render('text-link', compact(
            'textLink'
        ));
    }
}