<?php

namespace common\models;
use yii\caching\DbDependency;
use common\components\CFunction;
use Yii;


class ActorBase extends \common\models\db\ActorDB{

    public static function getNameActor($id) {
        $dependency = new DbDependency(['sql' => 'SELECT MAX(updated_time) FROM actor']);
        $result = Yii::$app->cache->get('cache_actor_name'.$id);
        if ($result === false) {
            $result = ActorBase::find()->select('name')
                ->where('id = :id', [':id' => $id])
                ->asArray()
                ->one();
            Yii::$app->cache->set('cache_actor_name'.$id, $result, CFunction::getParams('cache_refresh'), $dependency);
        }
        if (!empty($result)) {
            return $result['name'];
        } else {
            return '';
        }
    }
}
