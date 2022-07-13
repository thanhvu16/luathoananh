<?php

namespace wap\widgets;

use yii\base\Widget;
use Yii;

class TopNavWidget extends Widget
{
    public $viewPath;

    public function init()
    {
        if(empty($this->viewPath))
            $this->viewPath = \Yii::$app->session->get('folder');

        parent::init();
    }

    public function run()
    {
        $this->renderContent();
    }

    protected function renderContent()
    {
        echo $this->render($this->viewPath . '/top_nav');
    }
}