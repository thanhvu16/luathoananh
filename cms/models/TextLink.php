<?php

namespace cms\models;

use common\behaviors\ChangedBehavior;
use Yii;
use yii\behaviors\BlameableBehavior;


class TextLink extends \common\models\TextLinkBase{

    public function behaviors()
    {
        return [
            [
                'class' => ChangedBehavior::className(),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }
}
