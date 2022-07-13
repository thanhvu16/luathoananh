<?php

namespace cms\models;

use common\behaviors\TimestampBehavior;
use Yii;
use common\models\EventGroupBase;
use common\behaviors\ChangedBehavior;
use yii\behaviors\BlameableBehavior;

class EventGroup extends EventGroupBase
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => ChangedBehavior::className(),
            ],
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_time', 'updated_time'],
                    self::EVENT_BEFORE_UPDATE => ['updated_time']
                ]
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }

    public static function getEventGroupNameById($id) {
        $result = self::find()
            ->where(['id' => $id])
            ->one();
        if (!empty($result))
            return $result->name;
        else
            return null;
    }
}