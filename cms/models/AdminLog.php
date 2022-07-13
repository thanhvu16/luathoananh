<?php

namespace cms\models;

use common\models\AdminLogMBase;
use Yii;

class AdminLog extends AdminLogMBase {
    public static function ActionLog($objectId = null, $objectName = null, $params = null) {
        $paramsLog = [
            'admin_id' => Yii::$app->user->id,
            'admin_username' => isset(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username : Yii::$app->user->isGuest,
            'controller' => Yii::$app->requestedAction->controller->id,
            'action' => Yii::$app->requestedAction->id,
            'object_id' => $objectId,
            'object_name' => $objectName,
            'params' => $params,
            'log_time' => date('Y-m-d H:i:s', time())
        ];
        //$collection = Yii::$app->mongodb->getCollection('admin_log');
        //$collection->insert($paramsLog);
    }
}