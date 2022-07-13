<?php

namespace common\components;

use yii\web\AssetBundle;
use Yii;

class ApplicationAsset extends AssetBundle
{
    public function init(){
        if(!$this->sourcePath)
            $this->sourcePath = Yii::$app->view->theme->basePath;

        parent::init();
    }
}