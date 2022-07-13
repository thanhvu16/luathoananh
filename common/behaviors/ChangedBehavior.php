<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 9/30/2015
 * Time: 10:16 AM
 */

namespace common\behaviors;

use yii\base\Behavior;
use yii\db\BaseActiveRecord;
use common\models\LogEntryBase;

class ChangedBehavior  extends Behavior{

    /**
     * @return array
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_AFTER_INSERT => 'logInsertEntry',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'logUpdateEntry',
            BaseActiveRecord::EVENT_AFTER_DELETE => 'logDeleteEntry',
        ];
    }

    /**
     * @param $event
     */
    public function logInsertEntry($event){
        /* @var $owner BaseActiveRecord */
        $owner = $this->owner;
        try {
            $objectRef = $owner->title ?? $owner->name ?? get_class($owner);
        }catch (\Exception $e){
            $objectRef = get_class($owner);
        }

        try{

            $logEntry = new LogEntryBase();
            $logEntry->user_id = \Yii::$app->user->id;
            $logEntry->model = get_class($owner);
            $logEntry->object_id = $owner->id;
            $logEntry->object_repr = $objectRef;
            $logEntry->action_flag = LogEntryBase::ACTION_INSERT;
            $logEntry->action_message = sprintf("Added '%s' ", $objectRef);
            $logEntry->save(false);
        }catch (\Exception $e){

        }
    }

    /**
     * @param $event
     */
    public function logUpdateEntry($event){

        $changedValues = [];

        /* @var $owner BaseActiveRecord */
        $owner = $this->owner;
        try {
            $objectRef = (string)$owner;
        }catch (\Exception $e){
            $objectRef = get_class($owner);
        }
        $attributes = (array) $owner->attributes;
        foreach ($event->changedAttributes as $attribute=>$value) {
            if ($event->changedAttributes[$attribute] != $attributes[$attribute]) {
                $changedValues[] = $attribute.':'.$owner->$attribute;
            }
        }
        try {
            $logEntry = new LogEntryBase();
            $logEntry->user_id = \Yii::$app->user->id;
            $logEntry->model = get_class($owner);
            $logEntry->object_id = $this->owner->id;
            $logEntry->object_repr = $objectRef;
            $logEntry->action_flag = LogEntryBase::ACTION_UPDATE;
            $logEntry->action_message = sprintf("Changed '%s'", implode(', ', $changedValues), $objectRef);
            $logEntry->save(false);
        }catch (\Exception $e){

        }

    }

    /**
     * @param $event
     */
    public function logDeleteEntry($event){

        /* @var $owner BaseActiveRecord */
        $owner = $this->owner;
        try {
            $objectRef = (string)$owner;
        }catch (\Exception $e){
            $objectRef = get_class($owner);
        }

        try{

            $logEntry = new LogEntryBase();
            $logEntry->user_id = \Yii::$app->user->id;
            $logEntry->model = get_class($owner);
            $logEntry->object_id = $owner->id;
            $logEntry->object_repr = $objectRef;
            $logEntry->action_flag = LogEntryBase::ACTION_DELETE;
            $logEntry->action_message = sprintf("Deleted '%s'", $objectRef);
            $logEntry->save(false);
        }catch (\Exception $e){

        }

    }
}