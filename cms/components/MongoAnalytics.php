<?php
/**
 * @Function: Lớp xử lý phần thống kê bằng mongodb
 * @Author: trinh.kethanh@gmail.com
 * @Date: 09/03/2015
 * @System: Video 2.0
 */

namespace cms\components;

use Yii;
use common\components\CFunction;
use yii\helpers\ArrayHelper;

class MongoAnalytics
{
    /*
     * @params: $collection-> Collection cần thống kê
     * @params: $startUnix -> Thời gian bắt đầu thống kê (Định dạng: YY-mm-dd HH:ii:ss).
     * @params: $endUnix -> Thời gian kết thúc thống kê (Định dạng: YY-mm-dd HH:ii:ss).
     * @return: Hàm này trả về tổng số thuê bao nhận diện được và không nhận diện trong 1h, 1 ngày.
     */
    public static function getStatisticsMsisdn($collection, $startUnix, $endUnix)
    {
        // Collection instance.
        $collection = Yii::$app->mongodb->getCollection($collection);
        // Chuyển ngày về dạng string
        $start = strtotime(CFunction::formatDate($startUnix));
        $end = strtotime(CFunction::formatDate($endUnix));
        // Liệt kê những số thuê bao không nhận được và thuê bao nhận diện được theo từng giờ (hourly)
        $mongoStatsHourly = $collection->aggregate(
            [
                '$match' => [
                    'created_time' => [
                        '$gt' => new \MongoDate($start),
                        '$lt' => new \MongoDate($end)
                    ]
                ]
            ],
            [
                '$project' => [
                    'date' => [
                        'y' => ['$year' => '$created_time'],
                        'm' => ['$month' => '$created_time'],
                        'd' => ['$dayOfMonth' => '$created_time'],
                        'h' => ['$hour' => '$created_time']
                    ],
                    'user_id' => 1
                ]
            ],
            [
                '$group' => [
                    '_id' => [
                        'y' => '$date.y',
                        'm' => '$date.m',
                        'd' => '$date.d',
                        'h' => '$date.h'
                    ],
                    'fail' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$user_id', null]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'success' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$user_id', null]],
                                0,
                                1
                            ]
                        ]
                    ],
                    'count' => ['$sum' => 1]
                ]
            ],
            [
                '$sort' => ['_id' => 1]
            ]
        );
        // Liệt kê những số thuê bao không nhận được và thuê bao nhận diện được theo từng ngày (daily)
        $mongoStatsDaily = $collection->aggregate(
            [
                '$match' => [
                    'created_time' => [
                        '$gt' => new \MongoDate($start),
                        '$lt' => new \MongoDate($end)
                    ]
                ]
            ],
            [
                '$project' => [
                    'date' => [
                        'y' => ['$year' => '$created_time'],
                        'm' => ['$month' => '$created_time'],
                        'd' => ['$dayOfMonth' => '$created_time'],
                    ],
                    'user_id' => 1
                ]
            ],
            [
                '$group' => [
                    '_id' => [
                        'y' => '$date.y',
                        'm' => '$date.m',
                        'd' => '$date.d',
                    ],
                    'fail' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$user_id', null]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'success' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$user_id', null]],
                                0,
                                1
                            ]
                        ]
                    ],
                    'count' => ['$sum' => 1]
                ]
            ],
            [
                '$sort' => ['_id' => 1]
            ]
        );

        $outputHourly = [];
        // Xử lý mảng hourly
        $i = 0;
        foreach ($mongoStatsHourly as $key => $value) {
            $outputHourly[$i]['hour'] = $value['_id']['h'];
            $outputHourly[$i]['fail'] = $value['fail'];
            $outputHourly[$i]['success'] = $value['success'];
            $i++;
        }

        $outputDaily = [];
        // Xử lý mảng daily
        foreach ($mongoStatsDaily as $k => $v) {
            $outputDaily[$i]['hour'] = Yii::t('cms', 'general');
            $outputDaily[$i]['fail'] = '<b>'.$v['fail'].'/'.$v['count'].' = '.round(($v['fail']/$v['count'])*100, 2).'%</b>';
            $outputDaily[$i]['success'] = '<b>'.$v['success'].'/'.$v['count'].' = '.round(($v['success']/$v['count'])*100, 2).'%</b>';
        }

        // Mảng dữ liệu cần in ra để thống kê
        $output = $outputHourly + $outputDaily;

        return $output;
    }

    /*
     * @params: $collection -> Tên collection cần xử lý
     * @params: $startUnix -> Thời gian bắt đầu thống kê
     * @params: $endUnix -> Thời gian kết thúc thống kê
     * @return: Hàm này trả về danh sách ip không detect được số điện thoại
     */
    public static function getIpFail($collection, $startUnix, $endUnix)
    {
        $collection = Yii::$app->mongodb->getCollection($collection);
        // Chuyển ngày về dạng string
        $start = strtotime(CFunction::formatDate($startUnix));
        $end = strtotime(CFunction::formatDate($endUnix));
        // Liệt kê những ip không thể detect được số điện thoại
        $ipFail = $collection->aggregate(
            [
                '$match' => [
                    'created_time' => [
                        '$gt' => new \MongoDate($start),
                        '$lt' => new \MongoDate($end)
                    ],
                    'user_id' => null
                ]
            ],
            [
                '$group' => [
                    '_id' => '$ip'
                ]
            ],
            [
                '$sort' => ['_id' => 1]
            ]
        );
        if (is_array($ipFail) && sizeof($ipFail) > 0) {
            foreach ($ipFail as $key => $value) {
                $output[] = $value['_id'];
            }

            return $output;
        } else {
            return false;
        }
    }

    /*
     * @params: $collection-> Collection cần thống kê
     * @params: $startUnix -> Thời gian bắt đầu thống kê (Định dạng: YY-mm-dd HH:ii:ss).
     * @params: $endUnix -> Thời gian kết thúc thống kê (Định dạng: YY-mm-dd HH:ii:ss).
     * @return: Hàm này thống kế status msisdn, biểu diễn dạng biểu đồ
     */
    public static function getStatisticsMsisdnChart($collection, $startUnix, $endUnix)
    {
        // Collection instance.
        $collection = Yii::$app->mongodb->getCollection($collection);
        // Chuyển ngày về dạng string
        $start = strtotime(CFunction::formatDate($startUnix));
        $end = strtotime(CFunction::formatDate($endUnix));
        $mongoStatsChart = $collection->aggregate(
            [
                '$match' => [
                    'created_time' => [
                        '$gt' => new \MongoDate($start),
                        '$lt' => new \MongoDate($end)
                    ]
                ]
            ],
            [
                '$project' => [
                    'date' => [
                        'y' => ['$year' => '$created_time'],
                        'm' => ['$month' => '$created_time'],
                        'd' => ['$dayOfMonth' => '$created_time'],
                    ],
                    'user_id' => 1
                ]
            ],
            [
                '$group' => [
                    '_id' => [
                        'y' => '$date.y',
                        'm' => '$date.m',
                        'd' => '$date.d',
                    ],
                    'fail' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$user_id', null]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'success' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$user_id', null]],
                                0,
                                1
                            ]
                        ]
                    ],
                    'count' => ['$sum' => 1]
                ]
            ],
            [
                '$sort' => ['_id' => 1]
            ]
        );

        $i = 0;
        $outputChart = [];
        foreach ($mongoStatsChart as $k => $v) {
            $outputChart[$i]['period'] = $v['_id']['y'].'-'.CFunction::formatMonthDay($v['_id']['m']).'-'.CFunction::formatMonthDay($v['_id']['d']);
            $outputChart[$i]['fail'] = $v['fail'];
            $outputChart[$i]['success'] = $v['success'];
            $i++;
        }
        return $outputChart;
    }


    public static function getMysqlStatisticsCharging($startUnix, $endUnix, $package, $type){
        $start = date('Y-m-d H:i:s', $startUnix);
        $end = date('Y-m-d H:i:s', $endUnix);
        $startYmd = date('Y-m-d', $startUnix);

        if(empty($type)){
            $type = 1;
        }
        if ($type == 1){
            $action = 'subscribe';
        }
        if ($type == 2){
            $action = 'unsubscribe';
        }

        //Doanh thu
        $queryRevenue = "SELECT
            DATE_FORMAT(created_time, '%H') AS by_hour,
            SUM(price) as 'sum'
        FROM
            `transaction`
        WHERE
            action = '$action'
        AND created_time > '$start'
        AND created_time < '$end'";
        if (!empty($package)){
            $queryRevenue .= " AND package_id = $package";
        }
        $queryRevenue .= " GROUP BY by_hour";
        $revenueResult = Yii::$app->db->createCommand($queryRevenue)->queryAll();
        $revenueResult = ArrayHelper::map($revenueResult, 'by_hour', 'sum');

        //thanh cong
        $querySuccess = "SELECT
            DATE_FORMAT(created_time, '%H') AS by_hour,
            SUM(1) as 'sum'
        FROM
            `transaction`
        WHERE
            action = '$action'
        AND created_time > '$start'
        AND created_time < '$end'";
        if (!empty($package)){
            $querySuccess .= " AND package_id = $package";
        }
        $querySuccess .= " GROUP BY by_hour";
        $successResult = Yii::$app->db->createCommand($querySuccess)->queryAll();
        $successResult = ArrayHelper::map($successResult, 'by_hour', 'sum');

        //ko thanh cong
        $queryError = "SELECT
            DATE_FORMAT(created_time, '%H') AS by_hour,
            SUM(1) as sum
        FROM
            `transaction_error`
        WHERE
            action = '$action'
        AND created_time > '$start'
        AND created_time < '$end'";

        if (!empty($package)){
            $queryError .= " AND package_id = $package";
        }
        $queryError .= " GROUP BY by_hour";
        $errorResult = Yii::$app->db->createCommand($queryError)->queryAll();
        $errorResult = ArrayHelper::map($errorResult, 'by_hour', 'sum');

        //ko thanh cong do thieu tien
        $queryLowBalance = "SELECT
            DATE_FORMAT(created_time, '%H') AS by_hour,
            SUM(1) as sum
        FROM
            `transaction_error`
        WHERE
            action = '$action'
        AND created_time > '$start'
        AND error_code = '401'
        AND created_time < '$end'";

        if (!empty($package)){
            $queryLowBalance .= " AND package_id = $package";
        }
        $queryLowBalance .= " GROUP BY by_hour";
        $errorLowBalance = Yii::$app->db->createCommand($queryLowBalance)->queryAll();
        $errorLowBalance = ArrayHelper::map($errorLowBalance, 'by_hour', 'sum');

        // Xử lý mảng hourly
        $outputHourly = [];
        for ($i = 0; $i <= 23; $i ++){
            $niceHour = str_pad($i, 2, 0, STR_PAD_LEFT);
            $outputHourly[$i]['hour'] = $niceHour;
            $outputHourly[$i]['balance'] = (isset($errorLowBalance[$niceHour]))?$errorLowBalance[$niceHour]:0;
            $outputHourly[$i]['fail'] = (isset($errorResult[$niceHour]))?$errorResult[$niceHour]:0;
            $outputHourly[$i]['success'] = (isset($successResult[$niceHour]))?$successResult[$niceHour]:0;
            $outputHourly[$i]['revenue'] = (isset($revenueResult[$niceHour]))?$revenueResult[$niceHour]:0;
        }

        //Doanh thu
        $queryRevenueDay = "SELECT
            DATE_FORMAT(created_time, '%Y-%m-%d') AS date,
            SUM(price) as 'sum'
        FROM
            `transaction`
        WHERE
            action = '$action'
        AND created_time > '$start'
        AND created_time < '$end'";
        if (!empty($package)){
            $queryRevenueDay .= " AND package_id = $package";
        }
        $queryRevenueDay .= " GROUP BY date";
        $revenueResultDay = Yii::$app->db->createCommand($queryRevenueDay)->queryAll();
        $revenueResultDay = ArrayHelper::map($revenueResultDay, 'date', 'sum');

        //thanh cong
        $querySuccessDay = "SELECT
            DATE_FORMAT(created_time, '%Y-%m-%d') AS date,
            SUM(1) as 'sum'
        FROM
            `transaction`
        WHERE
            action = '$action'
        AND created_time > '$start'
        AND created_time < '$end'";
        if (!empty($package)){
            $querySuccessDay .= " AND package_id = $package";
        }
        $querySuccessDay .= " GROUP BY date";
        $successResultDay = Yii::$app->db->createCommand($querySuccessDay)->queryAll();
        $successResultDay = ArrayHelper::map($successResultDay, 'date', 'sum');


        //ko thanh cong
        $queryErrorDay = "SELECT
            DATE_FORMAT(created_time, '%Y-%m-%d') AS date,
            SUM(1) as sum
        FROM
            `transaction_error`
        WHERE
            action = '$action'
        AND created_time > '$start'
        AND created_time < '$end'";

        if (!empty($package)){
            $queryErrorDay .= " AND package_id = $package";
        }
        $queryErrorDay .= " GROUP BY date";
        $errorResultDay = Yii::$app->db->createCommand($queryErrorDay)->queryAll();
        $errorResultDay = ArrayHelper::map($errorResultDay, 'date', 'sum');

        //ko thanh cong do thieu tien
        $queryLowBalanceDay = "SELECT
            DATE_FORMAT(created_time, '%Y-%m-%d') AS date,
            SUM(1) as sum
        FROM
            `transaction_error`
        WHERE
            action = '$action'
        AND created_time > '$start'
        AND error_code = '401'
        AND created_time < '$end'";

        if (!empty($package)){
            $queryLowBalanceDay .= " AND package_id = $package";
        }
        $queryLowBalanceDay .= " GROUP BY date";
        $errorLowBalanceDay = Yii::$app->db->createCommand($queryLowBalanceDay)->queryAll();
        $errorLowBalanceDay = ArrayHelper::map($errorLowBalanceDay, 'date', 'sum');

        $outputDaily = [];
        // Xử lý mảng daily
        $balance = (isset($errorLowBalanceDay[$startYmd]))?$errorLowBalanceDay[$startYmd]:0;
        $fail = (isset($errorResultDay[$startYmd]))?$errorResultDay[$startYmd]:0;
        $success = (isset($successResultDay[$startYmd]))?$successResultDay[$startYmd]:0;
        $revenue = (isset($revenueResultDay[$startYmd]))?$revenueResultDay[$startYmd]:0;

        $total = $success + $fail;
        $outputDaily[$i]['hour'] = Yii::t('cms', 'general');

        $balancePercent = ($total != 0)?round(($balance/$total)*100, 2):'0';
        $outputDaily[$i]['balance'] = '<b>'.$balance.'/'.$total.' = '.$balancePercent.'%</b>';

        $failPercent = ($total != 0)?round(($fail/$total)*100, 2):'0';
        $outputDaily[$i]['fail'] = '<b>'.$fail.'/'.$total.' = '.$failPercent.'%</b>';

        $successPercent = ($total != 0)?round(($success/$total)*100, 2):'0';
        $outputDaily[$i]['success'] = '<b>'.$success.'/'.$total.' = '.$successPercent.'%</b>';

        $outputDaily[$i]['revenue'] = '<b>'.number_format($revenue, 0, '.', '.').'</b>';


        // Mảng dữ liệu cần in ra để thống kê
        $output = $outputHourly + $outputDaily;
        return $output;
    }
    /*
     * @params: $collection-> Collection cần thống kê
     * @params: $startUnix -> Thời gian bắt đầu thống kê (Định dạng: YY-mm-dd HH:ii:ss)
     * @params: $endUnix -> Thời gian kết thúc thống kê (Định dạng: YY-mm-dd HH:ii:ss)
     * @params: $package -> Gói cước charging
     * @params: $type -> Kiểu gia hạn hay truy thu
     * @return: Hàm này thống kê truy thu và gia hạn
     */
    public static function getStatisticsCharging($collection, $startUnix, $endUnix, $package, $type)
    {
        // Collection instance.
        $collection = Yii::$app->mongodb->getCollection($collection);
        // Chuyển ngày về dạng string
        $start = strtotime(CFunction::formatDate($startUnix));
        $end = strtotime(CFunction::formatDate($endUnix));
        // Liệt kê những số thuê bao không nhận được và thuê bao nhận diện được theo từng giờ (hourly)
        $mongoStatsHourly = $collection->aggregate(
            [
                '$match' => [
                    'created_time' => [
                        '$gt' => new \MongoDate($start),
                        '$lt' => new \MongoDate($end)
                    ],
                    'package_id' => $package != '' ? $package : ['$ne' => ''],
                    'action' => $type != '' ? $type : 1
                ]
            ],
            [
                '$project' => [
                    'date' => [
                        'y' => ['$year' => '$created_time'],
                        'm' => ['$month' => '$created_time'],
                        'd' => ['$dayOfMonth' => '$created_time'],
                        'h' => ['$hour' => '$created_time']
                    ],
                    'status' => 1,
                    'price' => 1
                ]
            ],
            [
                '$group' => [
                    '_id' => [
                        'y' => '$date.y',
                        'm' => '$date.m',
                        'd' => '$date.d',
                        'h' => '$date.h'
                    ],
                    'balance' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', 1]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'fail' => [
                        '$sum' => [
                            '$cond' => [
                                ['$gt' => ['$status', 1]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'success' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', 0]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'revenue' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', 0]],
                                '$price',
                                0
                            ]
                        ]
                    ],
                    'count' => ['$sum' => 1]
                ]
            ],
            [
                '$sort' => ['_id' => 1]
            ]
        );
        // Liệt kê những số thuê bao không nhận được và thuê bao nhận diện được theo từng ngày (daily)
        $mongoStatsDaily = $collection->aggregate(
            [
                '$match' => [
                    'created_time' => [
                        '$gt' => new \MongoDate($start),
                        '$lt' => new \MongoDate($end)
                    ],
                    'package_id' => $package != '' ? $package : ['$ne' => ''],
                    'action' => $type != '' ? $type : 1
                ]
            ],
            [
                '$project' => [
                    'date' => [
                        'y' => ['$year' => '$created_time'],
                        'm' => ['$month' => '$created_time'],
                        'd' => ['$dayOfMonth' => '$created_time'],
                    ],
                    'status' => 1,
                    'price' => 1
                ]
            ],
            [
                '$group' => [
                    '_id' => [
                        'y' => '$date.y',
                        'm' => '$date.m',
                        'd' => '$date.d',
                    ],
                    'balance' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', 1]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'fail' => [
                        '$sum' => [
                            '$cond' => [
                                ['$gt' => ['$status', 1]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'success' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', 0]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'revenue' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', 0]],
                                '$price',
                                0
                            ]
                        ]
                    ],
                    'count' => ['$sum' => 1]
                ]
            ],
            [
                '$sort' => ['_id' => 1]
            ]
        );
        $outputHourly = [];
        // Xử lý mảng hourly
        $i = 0;
        foreach ($mongoStatsHourly as $key => $value) {
            $outputHourly[$i]['hour'] = $value['_id']['h'];
            $outputHourly[$i]['balance'] = $value['balance'];
            $outputHourly[$i]['fail'] = $value['fail'];
            $outputHourly[$i]['success'] = $value['success'];
            $outputHourly[$i]['revenue'] = number_format($value['revenue'], 0, '.', '.');
            $i++;
        }

        $outputDaily = [];
        // Xử lý mảng daily
        foreach ($mongoStatsDaily as $k => $v) {
            $outputDaily[$i]['hour'] = Yii::t('cms', 'general');
            $outputDaily[$i]['balance'] = '<b>'.$v['balance'].'/'.$v['count'].' = '.round(($v['balance']/$v['count'])*100, 2).'%</b>';
            $outputDaily[$i]['fail'] = '<b>'.$v['fail'].'/'.$v['count'].' = '.round(($v['fail']/$v['count'])*100, 2).'%</b>';
            $outputDaily[$i]['success'] = '<b>'.$v['success'].'/'.$v['count'].' = '.round(($v['success']/$v['count'])*100, 2).'%</b>';
            $outputDaily[$i]['revenue'] = '<b>'.number_format($v['revenue'], 0, '.', '.').'</b>';
        }

        // Mảng dữ liệu cần in ra để thống kê
        $output = $outputHourly + $outputDaily;
        return $output;
    }

    /*
     *Thống kê gia hạn + truy thu
     *
     */
    public static function getStatisticsChargingAll($collection, $startUnix, $endUnix, $package)
    {
        // Collection instance.
        $collection = Yii::$app->mongodb->getCollection($collection);
        // Chuyển ngày về dạng string
        $start = strtotime(CFunction::formatDate($startUnix));
        $end = strtotime(CFunction::formatDate($endUnix));
        // Liệt kê những số thuê bao không nhận được và thuê bao nhận diện được theo từng giờ (hourly)
        $mongoStatsHourly = $collection->aggregate(
            [
                '$match' => [
                    'created_time' => [
                        '$gt' => new \MongoDate($start),
                        '$lt' => new \MongoDate($end)
                    ],
                    'package_id' => $package != '' ? $package : ['$ne' => ''],
                    //'action' => $type != '' ? $type : 1
                ]
            ],
            [
                '$project' => [
                    'date' => [
                        'y' => ['$year' => '$created_time'],
                        'm' => ['$month' => '$created_time'],
                        'd' => ['$dayOfMonth' => '$created_time'],
                        'h' => ['$hour' => '$created_time']
                    ],
                    'status' => 1,
                    'price' => 1
                ]
            ],
            [
                '$group' => [
                    '_id' => [
                        'y' => '$date.y',
                        'm' => '$date.m',
                        'd' => '$date.d',
                        'h' => '$date.h'
                    ],
                    'balance' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', 1]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'fail' => [
                        '$sum' => [
                            '$cond' => [
                                ['$gt' => ['$status', 1]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'success' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', 0]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'revenue' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', 0]],
                                '$price',
                                0
                            ]
                        ]
                    ],
                    'count' => ['$sum' => 1]
                ]
            ],
            [
                '$sort' => ['_id' => 1]
            ]
        );
        // Liệt kê những số thuê bao không nhận được và thuê bao nhận diện được theo từng ngày (daily)
        $mongoStatsDaily = $collection->aggregate(
            [
                '$match' => [
                    'created_time' => [
                        '$gt' => new \MongoDate($start),
                        '$lt' => new \MongoDate($end)
                    ],
                    'package_id' => $package != '' ? $package : ['$ne' => ''],
                    //'action' => $type != '' ? $type : 1
                ]
            ],
            [
                '$project' => [
                    'date' => [
                        'y' => ['$year' => '$created_time'],
                        'm' => ['$month' => '$created_time'],
                        'd' => ['$dayOfMonth' => '$created_time'],
                    ],
                    'status' => 1,
                    'price' => 1
                ]
            ],
            [
                '$group' => [
                    '_id' => [
                        'y' => '$date.y',
                        'm' => '$date.m',
                        'd' => '$date.d',
                    ],
                    'balance' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', 1]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'fail' => [
                        '$sum' => [
                            '$cond' => [
                                ['$gt' => ['$status', 1]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'success' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', 0]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'revenue' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', 0]],
                                '$price',
                                0
                            ]
                        ]
                    ],
                    'count' => ['$sum' => 1]
                ]
            ],
            [
                '$sort' => ['_id' => 1]
            ]
        );
        $outputHourly = [];
        // Xử lý mảng hourly
        $i = 0;
        foreach ($mongoStatsHourly as $key => $value) {
            $outputHourly[$i]['hour'] = $value['_id']['h'];
            $outputHourly[$i]['balance'] = $value['balance'];
            $outputHourly[$i]['fail'] = $value['fail'];
            $outputHourly[$i]['success'] = $value['success'];
            $outputHourly[$i]['revenue'] = number_format($value['revenue'], 0, '.', '.');
            $i++;
        }

        $outputDaily = [];
        // Xử lý mảng daily
        foreach ($mongoStatsDaily as $k => $v) {
            $outputDaily[$i]['hour'] = Yii::t('cms', 'general');
            $outputDaily[$i]['balance'] = '<b>'.$v['balance'].'/'.$v['count'].' = '.round(($v['balance']/$v['count'])*100, 2).'%</b>';
            $outputDaily[$i]['fail'] = '<b>'.$v['fail'].'/'.$v['count'].' = '.round(($v['fail']/$v['count'])*100, 2).'%</b>';
            $outputDaily[$i]['success'] = '<b>'.$v['success'].'/'.$v['count'].' = '.round(($v['success']/$v['count'])*100, 2).'%</b>';
            $outputDaily[$i]['revenue'] = '<b>'.number_format($v['revenue'], 0, '.', '.').'</b>';
        }

        // Mảng dữ liệu cần in ra để thống kê
        $output = $outputHourly + $outputDaily;
        return $output;
    }

    public static function getMysqlErrorCharging($startUnix, $endUnix, $package, $type){
        $start = date('Y-m-d H:i:s', $startUnix);
        $end = date('Y-m-d H:i:s', $endUnix);

        if(empty($type)){
            $type = 1;
        }
        if ($type == 1){
            $action = 'subscribe';
        }
        if ($type == 2){
            $action = 'unsubscribe';
        }
        //thanh cong
        $queryError = "SELECT
            DATE_FORMAT(created_time, '%Y-%m-%d') AS date,
            error_code,
            SUM(1) as total
        FROM
            transaction_error
        WHERE created_time >= '$start'
        AND created_time <= '$end'
        AND action = '$action'";
        if (!empty($package)){
            $queryError .= " AND package_id = '$package'";
        }
        $queryError .= " GROUP BY date, error_code";
        $result = Yii::$app->db->createCommand($queryError)->queryAll();
//        $errorLowBalanceDay = ArrayHelper::map($errorLowBalanceDay, 'date', 'sum');
        $i = 0;
        $data = [];
        if (!empty($result)){
            foreach ($result as $record){
                $data[$i]['date'] = $record['date'];
                $data[$i]['status'] = $record['error_code'];
                $data[$i]['msg'] = ($record['error_code'] == 401)?'Balance too low':'Other';
                $data[$i]['total'] = $record['total'];
                $i++;
            }
        }

        return $data;
    }
    /*
     * @params: $collection-> Collection cần thống kê
     * @params: $startUnix -> Thời gian bắt đầu thống kê (Định dạng: YY-mm-dd HH:ii:ss)
     * @params: $endUnix -> Thời gian kết thúc thống kê (Định dạng: YY-mm-dd HH:ii:ss)
     * @params: $package -> Gói cước charging
     * @params: $type -> Kiểu gia hạn hay truy thu
     * @return: Hàm này thống kê lỗi truy thu và gia hạn
     */
    public static function getErrorCharging($collection, $startUnix, $endUnix, $package, $type)
    {
        // Collection instance.
        $collection = Yii::$app->mongodb->getCollection($collection);
        // Chuyển ngày về dạng string
        $start = strtotime(CFunction::formatDate($startUnix));
        $end = strtotime(CFunction::formatDate($endUnix));
        // Liệt kê những số thuê bao không nhận được và thuê bao nhận diện được theo từng giờ (hourly)
        $mongoStatsError = $collection->aggregate(
            [
                '$match' => [
                    'created_time' => [
                        '$gt' => new \MongoDate($start),
                        '$lt' => new \MongoDate($end)
                    ],
                    'status' => ['$ne' => 0],
                    'package_id' => $package != '' ? $package : ['$ne' => ''],
                    'action' => $type != '' ? $type : 1
                ]
            ],
            [
                '$project' => [
                    'date' => [
                        'y' => ['$year' => '$created_time'],
                        'm' => ['$month' => '$created_time'],
                        'd' => ['$dayOfMonth' => '$created_time']
                    ],
                    'status' => 1
                ]
            ],
            [
                '$group' => [
                    '_id' => [
                        'y' => '$date.y',
                        'm' => '$date.m',
                        'd' => '$date.d',
                        'status' => '$status'
                    ],
                    'total' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', '$status']],
                                1,
                                0
                            ]
                        ]
                    ],
                ]
            ],
            [
                '$sort' => ['_id' => 1]
            ]
        );
        $outputError = [];
        $i = 0;
        // Xử lý mảng daily
        foreach ($mongoStatsError as $k => $v) {
            $outputError[$i]['status'] = $v['_id']['status'];
            $outputError[$i]['msg'] = Yii::$app->params['charging_error'][$v['_id']['status']];
            $outputError[$i]['total'] = $v['total'];
            $i++;
        }
        return $outputError;
    }
    /*
     * @params: $collection-> Collection cần thống kê
     * @params: $startUnix -> Thời gian bắt đầu thống kê (Định dạng: YY-mm-dd HH:ii:ss)
     * @params: $endUnix -> Thời gian kết thúc thống kê (Định dạng: YY-mm-dd HH:ii:ss)
     * @params: $package -> Gói cước charging
     * @params: $type -> Kiểu gia hạn hay truy thu
     * @return: Hàm này thống kê lỗi truy thu và gia hạn
     */
    public static function getErrorChargingAll($collection, $startUnix, $endUnix, $package)
    {
        // Collection instance.
        $collection = Yii::$app->mongodb->getCollection($collection);
        // Chuyển ngày về dạng string
        $start = strtotime(CFunction::formatDate($startUnix));
        $end = strtotime(CFunction::formatDate($endUnix));
        // Liệt kê những số thuê bao không nhận được và thuê bao nhận diện được theo từng giờ (hourly)
        $mongoStatsError = $collection->aggregate(
            [
                '$match' => [
                    'created_time' => [
                        '$gt' => new \MongoDate($start),
                        '$lt' => new \MongoDate($end)
                    ],
                    'status' => ['$ne' => 0],
                    'package_id' => $package != '' ? $package : ['$ne' => ''],
                ]
            ],
            [
                '$project' => [
                    'date' => [
                        'y' => ['$year' => '$created_time'],
                        'm' => ['$month' => '$created_time'],
                        'd' => ['$dayOfMonth' => '$created_time']
                    ],
                    'status' => 1
                ]
            ],
            [
                '$group' => [
                    '_id' => [
                        'y' => '$date.y',
                        'm' => '$date.m',
                        'd' => '$date.d',
                        'status' => '$status'
                    ],
                    'total' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', '$status']],
                                1,
                                0
                            ]
                        ]
                    ],
                ]
            ],
            [
                '$sort' => ['_id' => 1]
            ]
        );
        $outputError = [];
        $i = 0;
        // Xử lý mảng daily
        foreach ($mongoStatsError as $k => $v) {
            $outputError[$i]['status'] = $v['_id']['status'];
            $outputError[$i]['msg'] = Yii::$app->params['charging_error'][$v['_id']['status']];
            $outputError[$i]['total'] = $v['total'];
            $i++;
        }
        return $outputError;
    }

    public static function getMysqlStatisticsChargingChart($startUnix, $endUnix, $package, $type){
        $start = date('Y-m-d H:i:s', $startUnix);
        $end = date('Y-m-d H:i:s', $endUnix);

        if(empty($type)){
            $type = 1;
        }
        if ($type == 1){
            $action = 'subscribe';
        }
        if ($type == 2){
            $action = 'unsubscribe';
        }

        //thanh cong
        $querySuccess = "SELECT
            DATE_FORMAT(created_time, '%Y-%m-%d') AS date,
            SUM(1) as 'sum'
        FROM
            `transaction`
        WHERE
            action = '$action'
        AND created_time > '$start'
        AND created_time < '$end'";
        if (!empty($package)){
            $querySuccess .= " AND package_id = $package";
        }
        $querySuccess .= " GROUP BY date";
        $successResult = Yii::$app->db->createCommand($querySuccess)->queryAll();
        $successResult = ArrayHelper::map($successResult, 'date', 'sum');

        //ko thanh cong
        $queryError = "SELECT
            DATE_FORMAT(created_time, '%Y-%m-%d') AS date,
            SUM(1) as sum
        FROM
            `transaction_error`
        WHERE
            action = '$action'
        AND created_time > '$start'
        AND created_time < '$end'";

        if (!empty($package)){
            $queryError .= " AND package_id = $package";
        }
        $queryError .= " GROUP BY date";
        $errorResult = Yii::$app->db->createCommand($queryError)->queryAll();
        $errorResult = ArrayHelper::map($errorResult, 'date', 'sum');

        //ko thanh cong do thieu tien
        $queryLowBalance = "SELECT
            DATE_FORMAT(created_time, '%Y-%m-%d') AS date,
            SUM(1) as sum
        FROM
            `transaction_error`
        WHERE
            action = '$action'
        AND created_time > '$start'
        AND error_code = '401'
        AND created_time < '$end'";

        if (!empty($package)){
            $queryLowBalance .= " AND package_id = $package";
        }
        $queryLowBalance .= " GROUP BY date";
        $errorLowBalance = Yii::$app->db->createCommand($queryLowBalance)->queryAll();
        $errorLowBalance = ArrayHelper::map($errorLowBalance, 'date', 'sum');

        $temp = date('Y-m-d', $startUnix);
        $endYmd = date('Y-m-d', $endUnix);
        $getDone = false;
        $i = 0;
        $data = [];
        while (!$getDone){
            if ($temp > $endYmd){
                $getDone = true;
            }else{
                $data[$i]['period'] = $temp;
                $data[$i]['balance'] = (isset($errorLowBalance[$temp]))?$errorLowBalance[$temp]:0;
                $data[$i]['fail'] = (isset($errorResult[$temp]))?$errorResult[$temp]:0;
                $data[$i]['success'] = (isset($successResult[$temp]))?$successResult[$temp]:0;

                $i++;
                $temp = date('Y-m-d', strtotime($temp) + 86400);
            }
        }
        return $data;
    }
    /*
     * @params: $collection-> Collection cần thống kê
     * @params: $startUnix -> Thời gian bắt đầu thống kê (Định dạng: YY-mm-dd HH:ii:ss)
     * @params: $endUnix -> Thời gian kết thúc thống kê (Định dạng: YY-mm-dd HH:ii:ss)
     * @params: $package -> Gói cước charging
     * @params: $type -> Kiểu gia hạn hay truy thu
     * @return: Hàm này thống kê truy thu và gia hạn, biểu diễn bằng biểu đồ
     */
    public static function getStatisticsChargingChart($collection, $startUnix, $endUnix, $package, $type)
    {
        // Collection instance.
        $collection = Yii::$app->mongodb->getCollection($collection);
        // Chuyển ngày về dạng string
        $start = strtotime(CFunction::formatDate($startUnix));
        $end = strtotime(CFunction::formatDate($endUnix));
        // Liệt kê những số thuê bao không nhận được và thuê bao nhận diện được theo từng giờ (hourly)
        $mongoStatsChart = $collection->aggregate(
            [
                '$match' => [
                    'created_time' => [
                        '$gt' => new \MongoDate($start),
                        '$lt' => new \MongoDate($end)
                    ],
                    'package_id' => $package != '' ? $package : ['$ne' => ''],
                    'action' => $type != '' ? $type : 1
                ]
            ],
            [
                '$project' => [
                    'date' => [
                        'y' => ['$year' => '$created_time'],
                        'm' => ['$month' => '$created_time'],
                        'd' => ['$dayOfMonth' => '$created_time'],
                    ],
                    'status' => 1
                ]
            ],
            [
                '$group' => [
                    '_id' => [
                        'y' => '$date.y',
                        'm' => '$date.m',
                        'd' => '$date.d'
                    ],
                    'balance' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', 1]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'fail' => [
                        '$sum' => [
                            '$cond' => [
                                ['$gt' => ['$status', 1]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'success' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', 0]],
                                1,
                                0
                            ]
                        ]
                    ],
                ]
            ],
            [
                '$sort' => ['_id' => 1]
            ]
        );
        $outputChart = [];
        $i = 0;
        foreach ($mongoStatsChart as $k => $v) {
            $outputChart[$i]['period'] = $v['_id']['y'].'-'.CFunction::formatMonthDay($v['_id']['m']).'-'.CFunction::formatMonthDay($v['_id']['d']);
            $outputChart[$i]['balance'] = $v['balance'];
            $outputChart[$i]['fail'] = $v['fail'];
            $outputChart[$i]['success'] = $v['success'];
            $i++;
        }
        return $outputChart;
    }

    /*
     * @params: $collection-> Collection cần thống kê
     * @params: $startUnix -> Thời gian bắt đầu thống kê (Định dạng: YY-mm-dd HH:ii:ss)
     * @params: $endUnix -> Thời gian kết thúc thống kê (Định dạng: YY-mm-dd HH:ii:ss)
     * @params: $package -> Gói cước charging
     * @params: $type -> Kiểu gia hạn hay truy thu
     * @return: Hàm này thống kê truy thu và gia hạn, biểu diễn bằng biểu đồ
     */
    public static function getStatisticsChargingChartAll($collection, $startUnix, $endUnix, $package)
    {
        // Collection instance.
        $collection = Yii::$app->mongodb->getCollection($collection);
        // Chuyển ngày về dạng string
        $start = strtotime(CFunction::formatDate($startUnix));
        $end = strtotime(CFunction::formatDate($endUnix));
        // Liệt kê những số thuê bao không nhận được và thuê bao nhận diện được theo từng giờ (hourly)
        $mongoStatsChart = $collection->aggregate(
            [
                '$match' => [
                    'created_time' => [
                        '$gt' => new \MongoDate($start),
                        '$lt' => new \MongoDate($end)
                    ],
                    'package_id' => $package != '' ? $package : ['$ne' => ''],
                ]
            ],
            [
                '$project' => [
                    'date' => [
                        'y' => ['$year' => '$created_time'],
                        'm' => ['$month' => '$created_time'],
                        'd' => ['$dayOfMonth' => '$created_time'],
                    ],
                    'status' => 1
                ]
            ],
            [
                '$group' => [
                    '_id' => [
                        'y' => '$date.y',
                        'm' => '$date.m',
                        'd' => '$date.d'
                    ],
                    'balance' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', 1]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'fail' => [
                        '$sum' => [
                            '$cond' => [
                                ['$gt' => ['$status', 1]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'success' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', 0]],
                                1,
                                0
                            ]
                        ]
                    ],
                ]
            ],
            [
                '$sort' => ['_id' => 1]
            ]
        );
        $outputChart = [];
        $i = 0;
        foreach ($mongoStatsChart as $k => $v) {
            $outputChart[$i]['period'] = $v['_id']['y'].'-'.CFunction::formatMonthDay($v['_id']['m']).'-'.CFunction::formatMonthDay($v['_id']['d']);
            $outputChart[$i]['balance'] = $v['balance'];
            $outputChart[$i]['fail'] = $v['fail'];
            $outputChart[$i]['success'] = $v['success'];
            $i++;
        }
        return $outputChart;
    }
    /*
     * @params: $startUnix -> Thời gian bắt đầu thống kê (Định dạng: YY-mm-dd HH:ii:ss)
     * @params: $endUnix -> Thời gian kết thúc thống kê (Định dạng: YY-mm-dd HH:ii:ss)
     * @params: $pageId -> Thuộc chuyên trang nào
     * @params: $cpId -> CpID thống kê
     * @return: Hàm này thống kê theo chuyên mục
     */
    public static function getStatisticsCategory($startUnix, $endUnix, $pageId, $cpId)
    {
        // Collection instance.
        $collection = Yii::$app->mongodb->getCollection('category_stats_daily');
        // Chuyển ngày về dạng string
        $start = strtotime(CFunction::formatDate($startUnix));
        $end = strtotime(CFunction::formatDate($endUnix));
        // Liệt kê những số thuê bao không nhận được và thuê bao nhận diện được theo từng giờ (hourly)
        $mongoStatsCategory = $collection->aggregate(
            [
                '$match' => [
                    'date' => [
                        '$gte' => new \MongoDate($start),
                        '$lte' => new \MongoDate($end)
                    ],
                    'page_id' => $pageId != '' ? $pageId : ['$ne' => '']
                ]
            ],
            [
                '$project' => [
                    'category_id' => 1,
                    'data' => 1
                ]
            ],
            [
                '$group' => [
                    '_id' => '$category_id',
                    'totalViewFree' => ['$sum' => '$data.cp_'.$cpId.'.totalViewFree'],
                    'totalViewCharge' => ['$sum' => '$data.cp_'.$cpId.'.totalViewFree'],
                    'totalView' => ['$sum' => '$data.cp_'.$cpId.'.totalView'],
                    'totalTimeStreaming' => ['$sum' => '$data.cp_'.$cpId.'.totalTimeStreaming'],
                    'totalMbStreaming' => ['$sum' => '$data.cp_'.$cpId.'.totalMbStreaming'],
                    'totalDownload' => ['$sum' => '$data.cp_'.$cpId.'.totalDownload'],
                    'totalRevenue' => ['$sum' => '$data.cp_'.$cpId.'.totalRevenue']
                ]
            ],
            [
                '$sort' => ['_id' => 1]
            ]
        );
        return $mongoStatsCategory;
    }

    /*
     * @params: $collection-> Collection cần thống kê
     * @params: $startUnix -> Thời gian bắt đầu thống kê (Định dạng: YY-mm-dd HH:ii:ss)
     * @params: $endUnix -> Thời gian kết thúc thống kê (Định dạng: YY-mm-dd HH:ii:ss)
     * @params: $type -> Kiểu gia hạn hay truy thu
     * @return: Hàm này thống kê sms, biểu diễn bằng biểu đồ
     */
    public static function getStatisticsSmsChart($collection, $startUnix, $endUnix, $type)
    {
        // Collection instance.
        $collection = Yii::$app->mongodb->getCollection($collection);
        // Chuyển ngày về dạng string
        $start = strtotime(CFunction::formatDate($startUnix));
        $end = strtotime(CFunction::formatDate($endUnix));
        // Liệt kê những số thuê bao không nhận được và thuê bao nhận diện được theo từng giờ (hourly)
        $mongoStatsChart = $collection->aggregate(
            [
                '$match' => [
                    'created_time' => [
                        '$gt' => new \MongoDate($start),
                        '$lt' => new \MongoDate($end)
                    ],
                    'type' => $type
                ]
            ],
            [
                '$project' => [
                    'date' => [
                        'y' => ['$year' => '$created_time'],
                        'm' => ['$month' => '$created_time'],
                        'd' => ['$dayOfMonth' => '$created_time'],
                    ],
                    'status' => 1
                ]
            ],
            [
                '$group' => [
                    '_id' => [
                        'y' => '$date.y',
                        'm' => '$date.m',
                        'd' => '$date.d'
                    ],
                    'fail' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', 1]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'success' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', 0]],
                                1,
                                0
                            ]
                        ]
                    ],
                ]
            ]
        );
        $outputChart = [];
        $i = 0;
        foreach ($mongoStatsChart as $k => $v) {
            $outputChart[$i]['period'] = $v['_id']['y'].'-'.CFunction::formatMonthDay($v['_id']['m']).'-'.CFunction::formatMonthDay($v['_id']['d']);
            $outputChart[$i]['fail'] = $v['fail'];
            $outputChart[$i]['success'] = $v['success'];
            $i++;
        }
        return $outputChart;
    }

    /*
    * @params: $collection-> Collection cần thống kê
    * @params: $startUnix -> Thời gian bắt đầu thống kê (Định dạng: YY-mm-dd HH:ii:ss)
    * @params: $endUnix -> Thời gian kết thúc thống kê (Định dạng: YY-mm-dd HH:ii:ss)
    * @params: $type -> Kiểu gia hạn hay truy thu
    * @return: Hàm này thống kê sms
    */
    public static function getStatisticsSms($collection, $startUnix, $endUnix, $type)
    {
        // Collection instance.
        $collection = Yii::$app->mongodb->getCollection($collection);
        // Chuyển ngày về dạng string
        $start = strtotime(CFunction::formatDate($startUnix));
        $end = strtotime(CFunction::formatDate($endUnix));
        // Liệt kê những số thuê bao không nhận được và thuê bao nhận diện được theo từng giờ (hourly)
        $mongoStatsHourly = $collection->aggregate(
            [
                '$match' => [
                    'created_time' => [
                        '$gt' => new \MongoDate($start),
                        '$lt' => new \MongoDate($end)
                    ],
                    'type' => $type
                ]
            ],
            [
                '$project' => [
                    'date' => [
                        'y' => ['$year' => '$created_time'],
                        'm' => ['$month' => '$created_time'],
                        'd' => ['$dayOfMonth' => '$created_time'],
                        'h' => ['$hour' => '$created_time']
                    ],
                    'status' => 1
                ]
            ],
            [
                '$group' => [
                    '_id' => [
                        'y' => '$date.y',
                        'm' => '$date.m',
                        'd' => '$date.d',
                        'h' => '$date.h'
                    ],
                    'fail' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', 1]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'success' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', 0]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'count' => ['$sum' => 1]
                ]
            ],
            [
                '$sort' => ['_id' => 1]
            ]
        );
        // Liệt kê những số thuê bao không nhận được và thuê bao nhận diện được theo từng ngày (daily)
        $mongoStatsDaily = $collection->aggregate(
            [
                '$match' => [
                    'created_time' => [
                        '$gt' => new \MongoDate($start),
                        '$lt' => new \MongoDate($end)
                    ],
                    'type' => $type
                ]
            ],
            [
                '$project' => [
                    'date' => [
                        'y' => ['$year' => '$created_time'],
                        'm' => ['$month' => '$created_time'],
                        'd' => ['$dayOfMonth' => '$created_time'],
                    ],
                    'status' => 1
                ]
            ],
            [
                '$group' => [
                    '_id' => [
                        'y' => '$date.y',
                        'm' => '$date.m',
                        'd' => '$date.d',
                    ],
                    'fail' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', 1]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'success' => [
                        '$sum' => [
                            '$cond' => [
                                ['$eq' => ['$status', 0]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'count' => ['$sum' => 1]
                ]
            ],
            [
                '$sort' => ['_id' => 1]
            ]
        );

        $outputHourly = [];
        // Xử lý mảng hourly
        $i = 0;
        foreach ($mongoStatsHourly as $key => $value) {
            $outputHourly[$i]['hour'] = $value['_id']['h'];
            $outputHourly[$i]['fail'] = $value['fail'];
            $outputHourly[$i]['success'] = $value['success'];
            $i++;
        }

        $outputDaily = [];
        // Xử lý mảng daily
        foreach ($mongoStatsDaily as $k => $v) {
            $outputDaily[$i]['hour'] = Yii::t('cms', 'general');
            $outputDaily[$i]['fail'] = '<b>'.$v['fail'].'/'.$v['count'].' = '.round(($v['fail']/$v['count'])*100, 2).'%</b>';
            $outputDaily[$i]['success'] = '<b>'.$v['success'].'/'.$v['count'].' = '.round(($v['success']/$v['count'])*100, 2).'%</b>';
        }

        // Mảng dữ liệu cần in ra để thống kê
        $output = $outputHourly + $outputDaily;

        return $output;
    }
}