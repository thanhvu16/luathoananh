<?php

namespace console\models;

use Yii;


class XpayRequest extends \common\models\XpayRequestBase{

    public static function getRequest($code, $limit, $offset){
        $data = self::find()
            ->where(['request_status' => 0, 'code_promotion' => $code])
            ->orderBy('id ASC')
            ->limit($limit)
            ->offset($offset)
            ->all();
        return $data;
    }
}