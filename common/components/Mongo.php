<?php
/**
 * @Author: trinh.kethanh@gmail.com
 * @Date: 07/07/2015
 * @Function: Class xử lý phần mongo của hệ thống
 * @System: Video 2.0
 */

namespace common\components;

use Yii;
use yii\mongodb\Exception;

class Mongo
{
    /**
     * @param int $action (1: Gia hạn, 2: Truy thu))
     * @param int $status Trạng thái charging (0: Thành công, 1: Thất bại, ...)
     * @param null $msisdn Số điện thoại khách hàng
     * @param $price -> Giá của charging
     * @param $packageId -> Thuộc gói cước nào
     * Example: $paramsLog = [
        'action' => 2,
        'status' => 1,
        'msisdn' => '841689677808',
        'created_time' => new \MongoDate(strtotime(CFunction::formatDate())),
        'price' => 10000,
        'package_id' => 1
     ]
     */
    public static function insertChargingReport($action = 1, $status = 1, $msisdn = null, $price = 0, $packageId = 0)
    {
        $paramsLog = [
            'action' => $action,
            'status' => $status,
            'msisdn' => $msisdn,
            'created_time' => new \MongoDate(strtotime(CFunction::formatDate())),
            'price' => $price,
            'package_id' => $packageId
        ];

        $collection = Yii::$app->mongodb->getCollection('monitor_system_charging');
        try {
            $collection->insert($paramsLog);
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    /**
     * @param $userId
     * @param $ip
     * @param $packageId
     * @param bool $detector_response_time
     * @param bool $model
     * @param bool $ua
     * @param bool $method
     * @param bool $os
     * @param bool $os_version
     * @param bool $browser
     * @param bool $source
     * @param bool $referer
     * @param bool $page_id
     * Example: $paramsLog = [
        'user_id' => 'N/A',
        'ip' => '171.231.1.122',
        'package_id' => 1,
        'detector_response_time' => 18,
        'model' => 'desktop',
        'ua' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
        'method' => 'wap',
        'created_time' => new \MongoDate(strtotime(CFunction::formatDate())),
        'os' => '',
        'os_version' => '',
        'browser' => 'Mozilla',
        'source' => '',
        'referer' => '',
        'page_id' => 1
     ]
     */
    public static function insertSessionReport(
        $userId,
        $ip,
        $packageId,
        $detector_response_time = false,
        $model = false,
        $ua = false,
        $method = false,
        $os = false,
        $os_version = false,
        $browser = false,
        $source = false,
        $referer = false,
        $page_id = false
    )
    {
        $paramsLog = [
            'user_id' => $userId,
            'ip' => $ip,
            'package_id' => $packageId,
            'detector_response_time' => isset($detector_response_time) ? $detector_response_time : '',
            'model' => $model ? $model : '',
            'ua' => $ua ? $ua : '',
            'method' => $method ? $method : '',
            'created_time' => new \MongoDate(strtotime(CFunction::formatDate())),
            'os' => $os ? $os : '',
            'os_version' => $os_version ? $os_version : '',
            'browser' => $browser ? $browser : '',
            'source' => $source ? $source : '',
            'referer' => $referer ? $referer : '',
            'page_id' => $page_id ? $page_id : ''
        ];

        $collection = Yii::$app->mongodb->getCollection('session');
        try {
            $collection->insert($paramsLog);
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    /**
     * @param $requestId
     * @param $packageId
     * @param $userId
     * @param $action
     * @param $status
     * @param int $price
     * @param bool $content_id
     * @param bool $content_type_id
     * @param bool $part_id
     * @param bool $note
     * @param bool $page_id
     * @param bool $channel
     * @param bool $script_request_id
     * @param bool $user_package_status
     * @param bool $source
     * Example: $paramsLog = [
        'request_id' => 1426438982,
        'package_id' => 1,
        'user_id' => '841689677808',
        'action' => 1,
        'status' => 0,
        'content_id' => 88,
        'content_type_id' => 1,
        'part_id' => '',
        'note' => '',
        'price' => 5000,
        'created_time' => new \MongoDate(strtotime(CFunction::formatDate())),
        'page_id' => 1,
        'channel' => 'wap',
        'script_request_id' => 0,
        'user_package_status' => 0,
        'source' => 'google.com'
     ]
     */
    public static function insertRegisterCanncelReport(
        $requestId,
        $packageId,
        $userId,
        $action,
        $status,
        $price = 0,
        $content_id = false,
        $content_type_id= false,
        $part_id = false,
        $note = false,
        $page_id = false,
        $channel= false,
        $script_request_id = false,
        $user_package_status = false,
        $source = false
    )
    {
        $paramsLog = [
            'request_id' => $requestId,
            'package_id' => $packageId,
            'user_id' => $userId,
            'action' => $action,
            'status' => $status,
            'price' => $price,
            'content_id' => $content_id ? $content_id : '',
            'content_type_id' => $content_type_id ? $content_type_id : '',
            'part_id' => $part_id ? $part_id : '',
            'note' => $note ? $note : '',
            'created_time' => new \MongoDate(strtotime(CFunction::formatDate())),
            'page_id' => $page_id ? $page_id : '',
            'channel' => $channel ? $channel : '',
            'script_request_id' => $script_request_id ? $script_request_id : '',
            'user_package_status' => $user_package_status ? $user_package_status : '',
            'source' => $source ? $source : '',
        ];

        $collection = Yii::$app->mongodb->getCollection('monitor_register_cancel');
        try {
            $collection->insert($paramsLog);
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    /**
     * @param $userId
     * @param $content
     * @param $status
     * @param $type
     * @param bool $channel
     * @param bool $smsc'
     * Example: $paramsLog = [
        'user_id' => '841689677808',
        'content' => 'VOSPM',
        'created_time' => new \MongoDate(strtotime(CFunction::formatDate())),
        'status' => 1,
        'type' => 0,
        'request_id' => '2015030915175119',
        'channel' => 1,
        'referer' => 1,
        'smsc' => ''
     ]
     */
    public static function insertSmsReport($userId, $content, $status, $type, $channel = false, $smsc  = false)
    {
        $paramsLog = [
            'user_id' => $userId,
            'content' => $content,
            'created_time' => new \MongoDate(strtotime(CFunction::formatDate())),
            'status' => $status,
            'type' => $type,
            'channel' => $channel ? $channel : '',
            'smsc' => $smsc ? $smsc : '',
        ];
        $collection = Yii::$app->mongodb->getCollection('monitor_sms');
        try {
            $collection->insert($paramsLog);
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}