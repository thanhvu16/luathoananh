<?php

namespace cms\models;

use common\behaviors\ChangedBehavior;
use common\behaviors\TimestampBehavior;
use common\models\CollectionNewsBase;
use yii\behaviors\BlameableBehavior;
use Yii;


class CollectionNews extends CollectionNewsBase
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

    public static function addNews($collectionId, $newsId){
        $newsIds = explode(',',$newsId);

        $array = self::showOrderMaxByCollectionId($collectionId);
        $order_no = !empty($array['order']) ? $array['order'] : 0;
        $order_no++;
        foreach($newsIds as $id){
            $check=self::findOne(['collection_id'=>$collectionId,'news_id'=>$id]);
            if(empty($check)){
                $relation=new self();
                $relation->collection_id=$collectionId;
                $relation->news_id=$id;
                $relation->order=$order_no++;
                $relation->save();
            }
        }

        return true;
    }

    public static function showOrderMaxByCollectionId($id){
        $result = self::find()->select('order')->where(['collection_id' => $id])->orderBy('order DESC')->asArray()->one();
        return $result;
    }

    public static function deleteNews($collectionId, $newsId){
        Yii::$app->db->createCommand("
            DELETE FROM ".self::tableName()." 
            WHERE collection_id = $collectionId 
            AND news_id IN ($newsId)
        ")->execute();
    }

    public static function getListNewsByCollection($collectionId){
        $result = self::find()
            ->select('news_id')
            ->where(['collection_id' => $collectionId])
            ->asArray()->all();
        return $result;
    }
}
