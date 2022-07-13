<?php

namespace cms\models;

use Yii;


class League extends \common\models\LeagueBase{

    static public function getListAll(){
        $datas = self::find()->all();
        $return  = [];
        if(!empty($datas)){
            foreach ($datas as $data){
                $return[$data->league_id] = !empty($data->custom_name)?$data->custom_name:$data->name;
            }
        }
        return $return;
    }

}