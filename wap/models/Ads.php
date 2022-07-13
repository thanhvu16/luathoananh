<?php

namespace wap\models;

use Yii;


class Ads extends \common\models\AdsBase
{
    public static function getAllAds($limit = 15)
    {
        $keyCache = 'ADS_getAllAds_' . $limit;
        $result = Yii::$app->cache->get($keyCache);
        if ($result === false) {
            $result = self::find()
                ->where(['status' => 1])
                ->limit($limit)
                ->asArray()
                ->all();
            Yii::$app->cache->set($keyCache, $result, 86400);
        }
        return $result;
    }
}
