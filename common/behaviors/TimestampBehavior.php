<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 8/4/2015
 * Time: 4:49 PM
 */

namespace common\behaviors;

use yii\db\Expression;

class TimestampBehavior extends \yii\behaviors\TimestampBehavior {

    /**
     * @inheritdoc
     */
    protected function getValue($event)
    {
        if ($this->value instanceof Expression) {
            return $this->value;
        } else {
            return $this->value !== null ? call_user_func($this->value, $event) : date('Y-m-d H:i:s');
        }
    }
}