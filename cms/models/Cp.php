<?php

namespace cms\models;

use Yii;
use common\models\CpBase;

class Cp extends CpBase
{
    /**
     * @param $name
     * @return bool
     */
    public static function checkCpNameExists($name)
    {
        $result = CpBase::find()->where(['name' => $name])->exists();
        if ($result)
            return true;
        else
            return false;
    }

    /**
     * @param $idGroup
     * @return mixed|string
     */
    public static function getCpNameByID($id)
    {
        $result = CpBase::find()
            ->select('name')
            ->where(['id' => $id])
            ->one();

        if (!empty($result)) {
            return $result->name;
        }
        return null;
    }
}