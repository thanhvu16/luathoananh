<?php

namespace wap\models;

use Yii;


class TextLink extends \common\models\TextLinkBase{
    public static function getList($limit = 10){
        $result = self::find()
            ->where(['status' => 1])
            ->limit($limit)
            ->orderBy('order ASC, updated_time DESC')
            ->all();
        return $result;
    }

}