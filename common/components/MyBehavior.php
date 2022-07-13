<?php

namespace common\components;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class MyBehavior extends Behavior
{
    public $cacheFile;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    public function afterInsert()
    {
        file_put_contents(Yii::getAlias('@runtime/cache/' . $this->cacheFile), time());
    }

    public function afterUpdate()
    {
        file_put_contents(Yii::getAlias('@runtime/cache/' . $this->cacheFile), time());
    }

    public function afterDelete()
    {
        file_put_contents(Yii::getAlias('@runtime/cache/' . $this->cacheFile), time());
    }

    public function beforeInsert()
    {
        if (!is_file(Yii::getAlias('@runtime/cache/' . $this->cacheFile)))
            fopen(Yii::getAlias('@runtime/cache/' . $this->cacheFile), 'w');
    }

    public function beforeUpdate()
    {
        if (!is_file(Yii::getAlias('@runtime/cache/' . $this->cacheFile)))
            fopen(Yii::getAlias('@runtime/cache/' . $this->cacheFile), 'w');
    }

    public function beforeDelete()
    {
        if (!is_file(Yii::getAlias('@runtime/cache/' . $this->cacheFile)))
            fopen(Yii::getAlias('@runtime/cache/' . $this->cacheFile), 'w');
    }
}