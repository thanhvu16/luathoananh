<?php

namespace common\models;
use yii\caching\DbDependency;
use common\components\CFunction;
use Yii;


class DirectorBase extends \common\models\db\DirectorDB{

    public static function getNameDirector($id) {
        $dependency = new DbDependency(['sql' => 'SELECT MAX(updated_time) FROM director']);
        $result = Yii::$app->cache->get('cache_director_name'.$id);
        if ($result === false) {
            $result = DirectorBase::find()->select('name')
                ->where('id = :id', [':id' => $id])
                ->asArray()
                ->one();
            Yii::$app->cache->set('cache_director_name'.$id, $result, CFunction::getParams('cache_refresh'), $dependency);
        }
        if (!empty($result)) {
            return $result['name'];
        } else {
            return '';
        }
    }

}
