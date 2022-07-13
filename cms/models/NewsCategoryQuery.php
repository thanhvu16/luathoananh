<?php

namespace cms\models;

use yii\db\ActiveQuery;
use cms\components\nestedsets\NestedSetsQueryBehavior;
use Yii;


class NewsCategoryQuery extends ActiveQuery
{
    public function behaviors()
    {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}