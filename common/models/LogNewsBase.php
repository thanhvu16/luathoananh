<?php

namespace common\models;

use common\behaviors\TimestampBehavior;
use Yii;


class LogNewsBase extends \common\models\db\LogNewsDB{
    const ACTION_INSERT = 1;
    const ACTION_UPDATE = 2;
    const ACTION_DELETE = 3;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['action_time'],
                ],
            ],
        ];
    }
}
