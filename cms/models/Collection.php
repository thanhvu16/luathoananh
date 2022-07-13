<?php

namespace cms\models;

use common\behaviors\ChangedBehavior;
use common\behaviors\TimestampBehavior;
use common\models\CollectionBase;
use yii\behaviors\BlameableBehavior;


class Collection extends CollectionBase
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
}
