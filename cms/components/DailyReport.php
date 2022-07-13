<?php
/**
 * @Function: Lớp xử lý phần thống kê daily
 * @Author: trinh.kethanh@gmail.com
 * @Date: 30/06/2015
 * @System: Video 2.0
 */

namespace cms\components;

use yii\db\Query;

class DailyReport
{
    public static function getDailyReport($startDate, $endDate, $type)
    {
        $result = (new Query())
            ->select('*')
            ->from('daily_report')
            ->where('date >= :startDate', [':startDate' => $startDate])
            ->andWhere('date <= :endDate', [':endDate' => $endDate])
            ->andWhere('type = :type', [':type' => $type])
            ->all();

        return $result;
    }

    public static function get_report($from_date, $end_date, $type)
    {
        $arrData = array();
        $query = (new Query())
            ->from('daily_report')
            ->where('date >= :startTime AND date <= :endTime', array(':startTime' => $from_date, ':endTime' => $end_date))
            ->andWhere(array('type' => $type))
            ->all();
        foreach ($query as $item) {
            $arrData[$item['date']] = unserialize($item['data']);
        }
        return $arrData;
    }
}