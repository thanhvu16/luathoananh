<?php
/**
 * @Function: Lớp xử lý phần thống kê doanh thu.
 * @Author: trinh.kethanh@gmail.com
 * @Date: 23/03/2015
 * @System: Video 2.0
 */

namespace console\models;

use common\components\Utility;
use Yii;
use yii\db\Query;

use common\models\PromotionPointBase;
use common\models\PromotionTotalPointBase;
use common\models\PromotionEventsBase;
use common\models\PromotionWeekPointBase;
use common\models\PromotionWeekDateBase;
use common\models\PromotionWinnersWeekBase;
use common\models\PromotionWinnersFinalBase;


class Promotion
{

    /*
     * Get point winner week
     * */
    public function setPoint($week = null,$manual = null)
    {

        /*if(!$week) return;
        if($date){
            $date_day = $date;
        }else{
            $date_day = date('Y-m-d H:i:s');
        }
        $week_number = PromotionWeekDateBase::getWeekDate($date_day);
        if (!$week_number || ( $week_number && $week_number->week_number ==1))
            return;
        $week_number = $week_number->week_number -1;*/
        $week_number = $week;
        $rank = '';
        $data_set =  array();
        //giai chung cuoc
        if($week_number == FLG_WEEK_FINAL && !$manual){
            //get point toal
            $data = PromotionTotalPointBase::getTotalPoint();
            foreach ($data as $k=>$v){
                $data_set[$k]=$v;
                if($v['status'] == 1){
                    $data_set[$k]['point'] = $v['total_point'];
                }else{
                    $data_set[$k]['point']= $v['buy_content_point'];
                }
            }
            $data_set = self::array_orderby($data_set, 'point', SORT_DESC, 'created_time', SORT_ASC);
            $ii=0;
            foreach ($data_set as $k=>$v){
                if($ii==100){break;}
                $model = array();
                $model = new PromotionWinnersFinalBase();
                $model->msisdn = $v['msisdn'];
                $model->status =  $v['status'];
                $model->point = $v['point'];
                $model->created_time = $v['created_time'];
                $model->rank = $k+1;
                $model->save();
                $ii++;
            }

        }
        //giai tuan
        else if($manual || PromotionEventsBase::checkEventActive()){
            //check in week 1 return;
            $date_day = date('Y-m-d');
            if(!$manual){
                $check = PromotionWeekDateBase::getWeekDate($date_day);
                if (!$check || ( $check && $check->week_number == 1))
                    return;
                $week_number =  $check->week_number -1;
            }else{
                $week_number = $manual;
            }

            if($week_number > 1){
                $rank = PromotionWinnersWeekBase::getTop($week_number-1);
            }
            //get point week
            $data = PromotionWeekPointBase::getTotalPointAll($week_number);
            foreach ($data as $k=>$v){
                $data_set[$k]=$v;
                if($v['status'] == 1){
                    $data_set[$k]['point'] = $v['total_point'];
                }else{
                    $data_set[$k]['point']= $v['buy_content_point'];
                }
            }
            $data_set = self::array_orderby($data_set, 'point', SORT_DESC, 'created_time', SORT_ASC);
            $i=1;
            $flg = '';
            foreach ($data_set as $k=>$v){
                if($i==WINNERS_WEEK+10){break;}
                $model = array();
                $model = new PromotionWinnersWeekBase();
                $model->msisdn = $v['msisdn'];
                $model->status =  $v['status'];
                $model->point = $v['point'];
                $model->week_number =$v['week_number'];
                $model->created_time = $v['created_time'];;

                if( $i == 1 && $rank && $v['point'] == $rank->msisdn)
                {
                    $model->rank = 2;
                    $flg = $v['point'];
                }else if($i == 2 && $flg){
                    $model->rank = 1;
                }else{
                    $model->rank = $i;
                }
                $i++;
                $model->save();
            }  
        }
    }

    public  function  getUserNotice(){
        if(!PromotionEventsBase::checkEventActive()) return false;
        $data = PromotionTotalPointBase::getForNotice();
        return $data;
    }
    private function array_orderby()
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }
}