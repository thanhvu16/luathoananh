<?php

namespace common\behaviors;

use cms\models\LogNews;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;
use common\models\LogEntryBase;

class LogNewsBehavior  extends Behavior{

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
        $objectRef = $owner->title;
        try{

            $logEntry = new LogNews();
            $logEntry->user_id = \Yii::$app->user->id;
            $logEntry->model = get_class($owner);
            $logEntry->news_id = $owner->id;
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
            $objectRef = $owner->title;
        }catch (\Exception $e){
            $objectRef = get_class($owner);
        }
        $attributes = (array) $owner->attributes;
        foreach ($event->changedAttributes as $attribute=>$value) {
            if ($event->changedAttributes[$attribute] != $attributes[$attribute]) {
                $changedValues[$attribute] = [
                    'old' => $event->changedAttributes[$attribute],
                    'news' => $owner->$attribute
                ];
            }
        }
        try {
            $logEntry = new LogNews();
            $logEntry->user_id = \Yii::$app->user->id;
            $logEntry->model = get_class($owner);
            $logEntry->news_id = $this->owner->id;
            $logEntry->object_repr = $objectRef;
            $logEntry->action_flag = LogEntryBase::ACTION_UPDATE;
            $logEntry->action_message = json_encode($changedValues);
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
            $objectRef = $owner->title;
        }catch (\Exception $e){
            $objectRef = get_class($owner);
        }

        try{

            $logEntry = new LogNews();
            $logEntry->user_id = \Yii::$app->user->id;
            $logEntry->model = get_class($owner);
            $logEntry->news_id = $owner->id;
            $logEntry->object_repr = $objectRef;
            $logEntry->action_flag = LogEntryBase::ACTION_DELETE;
            $logEntry->action_message = sprintf("Deleted '%s'", $objectRef);
            $logEntry->save(false);
        }catch (\Exception $e){

        }

    }
}
