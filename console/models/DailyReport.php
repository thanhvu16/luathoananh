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
use cms\models\Cp;
use cms\models\Page;
use cms\models\Package;
use cms\models\AdsLink;
use yii\db\Query;

class DailyReport
{
    /**
     * @throws \yii\db\Exception
     * Cronjob thống kê doanh thu (type = 1)
     */
    public function Revenue($date = null)
    {
        $streaming = WATCH;
        $download = DOWNLOAD;
        $subscribe = SUBSCRIBE;
        $unsubscribe = UNSUBSCRIBE;
        $monfee = MONFEE;
        $channelWap = CHANNEL_WAP;
        $channelApp = CHANNEL_APP;
        $channelSms = CHANNEL_SMS;
        $channelCron = CHANNEL_CRON;
        $channelSys = CHANNEL_SYSTEM;

        $data = null;

        if (!$date || !isset($date)) {
            $date = date('Y-m-d');
        } else {
            $date = date('Y-m-d', strtotime($date));
        }

        $startDate = date('Y-m-d', strtotime($date) - 86400) . " 00:00:00";
        $endDate = date('Y-m-d', strtotime($date) - 86400) . " 23:59:59";


        // Tổng số thuê bao đăng ký
        $so_thue_bao_dang_ky_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :subscribe AND created_time >= :startDate AND created_time <= :endDate';
        $so_thue_bao_dang_ky = Yii::$app->db->createCommand($so_thue_bao_dang_ky_sql)
            ->bindParam(':subscribe', $subscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng số lượt đăng ký
        $so_luot_dang_ky_sql = 'SELECT COUNT(user_id) FROM transaction WHERE action = :subscribe AND created_time >= :startDate AND created_time <= :endDate';
        $so_luot_dang_ky = Yii::$app->db->createCommand($so_luot_dang_ky_sql)
            ->bindParam(':subscribe', $subscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng số thuê bao đăng ký qua các kênh khác
        $so_luot_dang_ky_khac_sql = 'SELECT COUNT(user_id) AS cnt FROM transaction WHERE action = :subscribe AND channel NOT IN (:channelCron,:channelSys,:channelApp,:channelSms,:channelWap) AND created_time >= :startDate AND created_time <= :endDate ';
        $so_luot_dang_ky_khac = Yii::$app->db->createCommand($so_luot_dang_ky_khac_sql)
            ->bindParam(':subscribe', $subscribe)
            ->bindParam(':channelCron', $channelCron)
            ->bindParam(':channelSys', $channelSys)
            ->bindParam(':channelApp', $channelApp)
            ->bindParam(':channelSms', $channelSms)
            ->bindParam(':channelWap', $channelWap)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryAll();


        // Tổng số thuê bao đăng ký qua các kênh
        $dang_ky_sql = 'SELECT channel, COUNT(DISTINCT user_id) AS cnt FROM transaction WHERE action = :subscribe AND created_time >= :startDate AND created_time <= :endDate GROUP by channel';
        $dang_ky_channel = Yii::$app->db->createCommand($dang_ky_sql)
            ->bindParam(':subscribe', $subscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryAll();

        // Tổng số thuê bao đăng ký qua các kênh khác $dang_ky_channel_khac
        $so_thue_bao_dang_ky_khac_sql = 'SELECT COUNT(DISTINCT user_id) AS cnt FROM transaction WHERE action = :subscribe AND channel NOT IN (:channelCron,:channelSys,:channelApp,:channelSms,:channelWap) AND created_time >= :startDate AND created_time <= :endDate';
        $so_thue_bao_dang_ky_khac = Yii::$app->db->createCommand($so_thue_bao_dang_ky_khac_sql)
            ->bindParam(':subscribe', $subscribe)
            ->bindParam(':channelCron', $channelCron)
            ->bindParam(':channelSys', $channelSys)
            ->bindParam(':channelApp', $channelApp)
            ->bindParam(':channelSms', $channelSms)
            ->bindParam(':channelWap', $channelWap)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryAll();

        // Số lượt thuê bao hủyc (so thue bao huy )
        $so_luot_thue_bao_huy_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :unsubscribe AND  created_time >= :startDate AND created_time <= :endDate';
        $so_luot_thue_bao_huy = Yii::$app->db->createCommand($so_luot_thue_bao_huy_sql)
            ->bindParam(':unsubscribe', $unsubscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();


        // Số lượt hủy
        $so_luot_huy_sql = 'SELECT COUNT(user_id) FROM transaction WHERE action = :unsubscribe AND created_time >= :startDate AND created_time <= :endDate';
        $so_luot_huy = Yii::$app->db->createCommand($so_luot_huy_sql)
            ->bindParam(':unsubscribe', $unsubscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();


        // Tổng thuê bao hủy qua các kênh
        $tong_huy_sql = 'SELECT channel, COUNT(DISTINCT user_id) AS cnt FROM transaction WHERE action = :unsubscribe AND created_time >= :startDate AND created_time <= :endDate GROUP BY channel';
        $tong_huy = Yii::$app->db->createCommand($tong_huy_sql)
            ->bindParam(':unsubscribe', $unsubscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryAll();


        // Tổng thuê bao hủy qua các kênh khac
        $tong_huy_khac_sql = 'SELECT COUNT(user_id) FROM transaction WHERE action = :unsubscribe AND channel NOT IN (:channelCron,:channelSys,:channelApp,:channelSms,:channelWap) AND created_time >= :startDate AND created_time <= :endDate';
        $tong_huy_khac = Yii::$app->db->createCommand($tong_huy_khac_sql)
            ->bindParam(':unsubscribe', $unsubscribe)
            ->bindParam(':channelCron', $channelCron)
            ->bindParam(':channelSys', $channelSys)
            ->bindParam(':channelApp', $channelApp)
            ->bindParam(':channelSms', $channelSms)
            ->bindParam(':channelWap', $channelWap)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Số thuê bao gia hạn
        $so_thue_bao_gia_han_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :monfee AND created_time >= :startDate AND created_time <= :endDate';
        $so_thue_bao_gia_han = Yii::$app->db->createCommand($so_thue_bao_gia_han_sql)
            ->bindParam(':monfee', $monfee)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng lượt xem mất phí
        $tong_luot_xem_mat_phi_sql = 'SELECT COUNT(id) FROM transaction WHERE action = :streaming AND price > 0 AND created_time >= :startDate AND created_time <= :endDate';
        $tong_luot_xem_mat_phi = Yii::$app->db->createCommand($tong_luot_xem_mat_phi_sql)
            ->bindParam(':streaming', $streaming)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng lượt tải mất phí
        $tong_luot_tai_mat_phi_sql = 'SELECT COUNT(id) FROM transaction WHERE action = :download AND price > 0 AND created_time >= :startDate AND created_time <= :endDate';
        $tong_luot_tai_mat_phi = Yii::$app->db->createCommand($tong_luot_tai_mat_phi_sql)
            ->bindParam(':download', $download)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng doanh thu
        $tong_doanh_thu_sql = 'SELECT COALESCE(SUM(price), 0) as tong_doanh_thu FROM transaction WHERE created_time >= :startDate AND created_time <= :endDate';
        $tong_doanh_thu = Yii::$app->db->createCommand($tong_doanh_thu_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Doanh thu theo action
        $doanh_thu_sql = 'SELECT action ,COALESCE(SUM(price), 0) AS sum FROM transaction WHERE created_time >= :startDate AND created_time <= :endDate GROUP BY action';
        $doanh_thu_action = Yii::$app->db->createCommand($doanh_thu_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryAll();

        foreach ($doanh_thu_action as $item) {
            $doanh_thu[$item['action']] = ($item['sum']) ? $item['sum'] : 0;
        }

        foreach ($dang_ky_channel as $item) {
            $dang_ky[$item['channel']] = ($item['cnt']) ? $item['cnt'] : 0;
        }


        foreach ($tong_huy as $item) {
            $huy[$item['channel']] = ($item['cnt']) ? $item['cnt'] : 0;
        }

        $data['page_all'] = [
            'so_thue_bao_dang_ky' => $so_thue_bao_dang_ky,
            'so_thue_bao_dang_ky_khac' => $so_thue_bao_dang_ky_khac,
            'so_luot_dang_ky' => $so_luot_dang_ky,
            'dang_ky_qua_wap' => isset($dang_ky[$channelWap]) ? $dang_ky[$channelWap] : 0,
            'dang_ky_qua_app' => isset($dang_ky[$channelApp]) ? $dang_ky[$channelApp] : 0,
            'dang_ky_qua_sms' => isset($dang_ky[$channelSms]) ? $dang_ky[$channelSms] : 0,
            'dang_ky_qua_sys' => (isset($dang_ky[$channelSys]) &&  isset($dang_ky[$channelCron])) ? ($dang_ky[$channelSys] + $dang_ky[$channelCron]) : 0,
            'dang_ky_qua_khac' => ($so_luot_dang_ky_khac) ? $so_luot_dang_ky_khac : 0,
            'doanh_thu_dang_ky' => isset($doanh_thu[$subscribe])?$doanh_thu[$subscribe]:0,
            'so_thue_bao_bi_huy' => (isset($huy[$channelCron])?$huy[$channelCron]:0) + (isset($huy[$channelSys])?$huy[$channelSys]:0),
            'so_luot_thue_bao_huy' => $so_luot_thue_bao_huy,
            'so_luot_huy' => $so_luot_huy,//CLIENT//CSKH//SMS//SYSTEM//WAP
            'huy_qua_sms' => isset($huy[$channelSms]) ? $huy[$channelSms] : 0,
            'huy_qua_wap' => isset($huy[$channelWap]) ? $huy[$channelWap] : 0,
            'huy_qua_app' => isset($huy[$channelApp]) ? $huy[$channelApp] : 0,
            'huy_qua_sys' => (isset($huy[$channelSys])?$huy[$channelSys]:0) + (isset($huy[$channelCron])?$huy[$channelCron]:0),
            'huy_qua_khac' => isset($tong_huy_khac) ? $tong_huy_khac : 0,
            'so_thue_bao_gia_han' => $so_thue_bao_gia_han,
            'doanh_thu_gia_han' => isset($doanh_thu[$monfee])?$doanh_thu[$monfee]:0,
            'tong_luot_xem_mat_phi' => $tong_luot_xem_mat_phi,
            'tong_luot_tai_mat_phi' => $tong_luot_tai_mat_phi,
            'doanh_thu_noi_dung' => isset($doanh_thu[$streaming])?$doanh_thu[$streaming]:0,
            'tong_doanh_thu' => $tong_doanh_thu
        ];


        //aaaaaa
        // Tổng số thuê bao theo các action
        $page_so_thue_sql = 'SELECT page_id, action, COUNT(DISTINCT user_id) AS cnt FROM transaction WHERE  created_time >= :startDate AND created_time <= :endDate GROUP BY page_id, action';
        $page_so_thue_bao = Yii::$app->db->createCommand($page_so_thue_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryAll();
        foreach ($page_so_thue_bao as $item) {
            $data['page_' . $item['page_id']]['so_thue_bao_' . $item['action']] = ($item['cnt']) ? $item['cnt'] : 0;

        }

        // Tổng số lượt đăng ký
        $so_luot_dang_ky_sql = 'SELECT page_id, COUNT(user_id) as cnt FROM transaction WHERE action = :subscribe AND created_time >= :startDate AND created_time <= :endDate GROUP BY page_id';
        $so_luot_dang_ky = Yii::$app->db->createCommand($so_luot_dang_ky_sql)
            ->bindParam(':subscribe', $subscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryAll();
        foreach ($so_luot_dang_ky as $item) {
            $data['page_' . $item['page_id']]['so_luot_dang_ky'] = ($item['cnt']) ? $item['cnt'] : 0;
        }

        //aaaaaa
        // Tổng số đăng ký qua các kênh
        $page_dang_ky_channel_sql = 'SELECT page_id, channel, COUNT(DISTINCT user_id) as cnt FROM transaction WHERE action = :subscribe AND created_time >= :startDate AND created_time <= :endDate GROUP BY  page_id, channel';
        $page_dang_ky = Yii::$app->db->createCommand($page_dang_ky_channel_sql)
            ->bindParam(':subscribe', $subscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryAll();
        foreach ($page_dang_ky as $item) {
            $data['page_' . $item['page_id']]['dang_ky_qua_' . $item['channel']] = ($item['cnt']) ? $item['cnt'] : 0;
        }

        //aaaaaa
        // Doanh thu theo các action
        $page_doanh_thu_sql = 'SELECT page_id, action, COALESCE(SUM(price), 0) AS sum FROM transaction WHERE created_time >= :startDate AND created_time <= :endDate GROUP BY page_id, action';
        $page_doanh_thu = Yii::$app->db->createCommand($page_doanh_thu_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryAll();
        foreach ($page_doanh_thu as $item) {
            $data['page_' . $item['page_id']]['doanh_thu_' . $item['action']] = !empty($item['sum']) ? $item['sum'] : 0;
        }


        // Số lượt thuê bao hủy =
        $so_luot_thue_bao_huy_sql = 'SELECT page_id, COUNT(DISTINCT user_id) AS cnt FROM transaction WHERE action = :unsubscribe AND created_time >= :startDate AND created_time <= :endDate GROUP BY page_id';
        $so_luot_thue_bao_huy = Yii::$app->db->createCommand($so_luot_thue_bao_huy_sql)
            ->bindParam(':unsubscribe', $unsubscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryAll();

        foreach ($so_luot_thue_bao_huy as $item) {
            $data['page_' . $item['page_id']]['so_luot_thue_bao_huy'] = ($item['cnt']) ? $item['cnt'] : 0;
        }

        // Số lượt hủy
        $so_luot_huy_sql = 'SELECT page_id, COUNT(user_id) AS cnt FROM transaction WHERE action = :unsubscribe AND created_time >= :startDate AND created_time <= :endDate GROUP BY page_id';
        $so_luot_huy = Yii::$app->db->createCommand($so_luot_huy_sql)
            ->bindParam(':unsubscribe', $unsubscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryAll();
        foreach ($so_luot_huy as $item) {
            $data['page_' . $item['page_id']]['so_luot_huy'] = ($item['cnt']) ? $item['cnt'] : 0;
        }

        //aaaaaa
        // Hủy qua các kênh
        $page_huy_qua_channel_sql = 'SELECT page_id, channel, COUNT(DISTINCT user_id) as cnt FROM transaction WHERE action = :unsubscribe AND created_time >= :startDate AND created_time <= :endDate GROUP BY page_id, channel';
        $page_huy_qua_channel = Yii::$app->db->createCommand($page_huy_qua_channel_sql)
            ->bindParam(':unsubscribe', $unsubscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryAll();
        foreach ($page_huy_qua_channel as $item) {
            $data['page_' . $item['page_id']]['huy_qua_' . $item['channel']] = ($item['cnt']) ? $item['cnt'] : 0;
        }


        // Tổng lượt xem mất phí
        $tong_luot_xem_mat_phi_sql = 'SELECT page_id, COUNT(id) as cnt FROM transaction WHERE action = :streaming AND price > 0 AND created_time >= :startDate AND created_time <= :endDate GROUP BY page_id';
        $tong_luot_xem_mat_phi = Yii::$app->db->createCommand($tong_luot_xem_mat_phi_sql)
            ->bindParam(':streaming', $streaming)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryAll();
        foreach ($tong_luot_xem_mat_phi as $item) {
            $data['page_' . $item['page_id']]['tong_luot_xem_mat_phi'] = ($item['cnt']) ? $item['cnt'] : 0;
        }

        // Tổng lượt tải mất phí
        $tong_luot_tai_mat_phi_sql = 'SELECT page_id, COUNT(id) AS  cnt FROM transaction WHERE action = :download AND price > 0 AND created_time >= :startDate AND created_time <= :endDate GROUP BY page_id';
        $tong_luot_tai_mat_phi = Yii::$app->db->createCommand($tong_luot_tai_mat_phi_sql)
            ->bindParam(':download', $download)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryAll();
        foreach ($tong_luot_tai_mat_phi as $item) {
            $data['page_' . $item['page_id']]['tong_luot_tai_mat_phi'] = ($item['cnt']) ? $item['cnt'] : 0;
        }

        // Tổng doanh thu
        $tong_doanh_thu_sql = 'SELECT page_id, COALESCE(SUM(price), 0) as tong_doanh_thu FROM transaction WHERE created_time >= :startDate AND created_time <= :endDate GROUP BY page_id';
        $tong_doanh_thu = Yii::$app->db->createCommand($tong_doanh_thu_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryAll();
        foreach ($tong_doanh_thu as $item) {
            $data['page_' . $item['page_id']]['tong_doanh_thu'] = $item['tong_doanh_thu'];
        }

        $data = serialize($data);
        $sql = 'INSERT INTO daily_report SET date = :startDate, type = 1, data = :data ON DUPLICATE KEY UPDATE data = :data';

        Yii::$app->db->createCommand($sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':data', $data)
            ->execute();

    }

    /**
     * @throws \yii\db\Exception
     * Thống kê gói cước (type = 2)
     */
    public function Package($date = null)
    {
        $subscribe = SUBSCRIBE;
        $unsubscribe = UNSUBSCRIBE;
        $monfee = MONFEE;
        $retryExtend = ARREARS;
        $channelWap = CHANNEL_WAP;
        $channelApp = CHANNEL_APP;
        $channelSms = CHANNEL_SMS;
        $channelCron = CHANNEL_CRON;
        $channelSys = CHANNEL_SYSTEM;
        $status = 2;

        if(!empty($date)){
            $startDate = $date;
            $endDate = $startDate . ' 23:59:59';
        }else{
            $startDate = date('Y-m-d', strtotime('-1 day'));
            $endDate = $startDate . ' 23:59:59';
        }

        // Tổng số thuê bao active
        $tong_so_thue_bao_active_sql = 'SELECT COUNT(user_id) FROM user_package WHERE status <> :status';
        $tong_so_thue_bao_active = Yii::$app->db->createCommand($tong_so_thue_bao_active_sql)
            ->bindParam(':status', $status)
            ->queryScalar();

        // Tổng số thuê bao cần gia hạn lần 1
        $tong_so_thue_bao_can_gia_han_lan_1_sql = 'SELECT COUNT(DISTINCT user_id) FROM user_package WHERE status <> :status AND extend_fail_count = 0 AND expired_time <= :endDate';
        $tong_so_thue_bao_can_gia_han_lan_1 = Yii::$app->db->createCommand($tong_so_thue_bao_can_gia_han_lan_1_sql)
            ->bindParam(':status', $status)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng số thuê bao cần truy thu
        $tong_so_thue_bao_can_truy_thu_sql = 'SELECT COUNT(DISTINCT user_id) FROM user_package WHERE status <> :status AND extend_fail_count > 0 AND expired_time <= :endDate';
        $tong_so_thue_bao_can_truy_thu = Yii::$app->db->createCommand($tong_so_thue_bao_can_truy_thu_sql)
            ->bindParam(':status', $status)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng số thuê bao gia hạn thành công
        $tong_so_thue_bao_gia_han_thanh_cong_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE (action = :monfee OR action =:action ) AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_thue_bao_gia_han_thanh_cong = Yii::$app->db->createCommand($tong_so_thue_bao_gia_han_thanh_cong_sql)
            ->bindParam(':monfee', $monfee)
            ->bindParam(':action', $retryExtend)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng số thuê bao gia hạn 1 lần thành công
        $tong_so_thue_bao_gia_han_1_lan_thanh_cong_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :action AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_thue_bao_gia_han_1_lan_thanh_cong = Yii::$app->db->createCommand($tong_so_thue_bao_gia_han_1_lan_thanh_cong_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->bindParam(':action', $monfee)
            ->queryScalar();

        // Tổng số thuê bao truy thu thành công
        $tong_so_thue_bao_truy_thu_thanh_cong_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :action AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_thue_bao_truy_thu_thanh_cong = Yii::$app->db->createCommand($tong_so_thue_bao_truy_thu_thanh_cong_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->bindParam(':action', $retryExtend)
            ->queryScalar();

        // Tổng số thuê bao đăng ký
        $tong_so_thue_bao_dang_ky_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :subscribe AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_thue_bao_dang_ky = Yii::$app->db->createCommand($tong_so_thue_bao_dang_ky_sql)
            ->bindParam(':subscribe', $subscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng số thuê bao đăng ký qua sms
        $tong_so_thue_bao_dang_ky_qua_sms_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :subscribe AND channel = :channelSms AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_thue_bao_dang_ky_qua_sms = Yii::$app->db->createCommand($tong_so_thue_bao_dang_ky_qua_sms_sql)
            ->bindParam(':subscribe', $subscribe)
            ->bindParam(':channelSms', $channelSms)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng số thuê bao đăng ký qua wap
        $tong_so_thue_bao_dang_ky_qua_wap_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :subscribe AND channel = :channelWap AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_thue_bao_dang_ky_qua_wap = Yii::$app->db->createCommand($tong_so_thue_bao_dang_ky_qua_wap_sql)
            ->bindParam(':subscribe', $subscribe)
            ->bindParam(':channelWap', $channelWap)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng số thuê bao đăng ký qua app
        $tong_so_thue_bao_dang_ky_qua_app_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :subscribe AND channel = :channelApp AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_thue_bao_dang_ky_qua_app = Yii::$app->db->createCommand($tong_so_thue_bao_dang_ky_qua_app_sql)
            ->bindParam(':subscribe', $subscribe)
            ->bindParam(':channelApp', $channelApp)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng số thuê bao hủy
        $tong_so_thue_bao_huy_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :unsubscribe AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_thue_bao_huy = Yii::$app->db->createCommand($tong_so_thue_bao_huy_sql)
            ->bindParam(':unsubscribe', $unsubscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng số thuê bao tự hủy
        $tong_so_thue_bao_tu_huy_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :unsubscribe AND channel NOT IN (:channelCron,:channelSys) AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_thue_bao_tu_huy = Yii::$app->db->createCommand($tong_so_thue_bao_tu_huy_sql)
            ->bindParam(':unsubscribe', $unsubscribe)
            ->bindParam(':channelCron', $channelCron)
            ->bindParam(':channelSys', $channelSys)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng số thuê bao tự hủy qua sms
        $tong_so_thue_bao_tu_huy_qua_sms_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :unsubscribe AND channel = :channelSms AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_thue_bao_tu_huy_qua_sms = Yii::$app->db->createCommand($tong_so_thue_bao_tu_huy_qua_sms_sql)
            ->bindParam(':unsubscribe', $unsubscribe)
            ->bindParam(':channelSms', $channelSms)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng số thuê bao tự hủy qua wap
        $tong_so_thue_bao_tu_huy_qua_wap_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :unsubscribe AND channel = :channelWap AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_thue_bao_tu_huy_qua_wap = Yii::$app->db->createCommand($tong_so_thue_bao_tu_huy_qua_wap_sql)
            ->bindParam(':unsubscribe', $unsubscribe)
            ->bindParam(':channelWap', $channelWap)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng số thuê bao tự hủy qua app
        $tong_so_thue_bao_tu_huy_qua_app_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :unsubscribe AND channel = :channelApp AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_thue_bao_tu_huy_qua_app = Yii::$app->db->createCommand($tong_so_thue_bao_tu_huy_qua_app_sql)
            ->bindParam(':unsubscribe', $unsubscribe)
            ->bindParam(':channelApp', $channelApp)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng số thuê bao bị hủy tu channel khac
        $tong_so_thue_bao_bi_huy_channel_khac_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :unsubscribe AND channel NOT IN (:channelCron,:channelSys,:channelWap,:channelSms,:channelApp) AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_thue_bao_bi_huy_channel_khac = Yii::$app->db->createCommand($tong_so_thue_bao_bi_huy_channel_khac_sql)
            ->bindParam(':unsubscribe', $unsubscribe)
            ->bindParam(':channelCron', $channelCron)
            ->bindParam(':channelSys', $channelSys)
            ->bindParam(':channelWap', $channelWap)
            ->bindParam(':channelSms', $channelSms)
            ->bindParam(':channelApp', $channelApp)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng số thuê bao bị hủy
        $tong_so_thue_bao_bi_huy_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :unsubscribe AND channel IN (:channelCron,:channelSys) AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_thue_bao_bi_huy = Yii::$app->db->createCommand($tong_so_thue_bao_bi_huy_sql)
            ->bindParam(':unsubscribe', $unsubscribe)
            ->bindParam(':channelCron', $channelCron)
            ->bindParam(':channelSys', $channelSys)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();


        // Doanh thu đăng ký gói
        $doanh_thu_dang_ky_goi_sql = 'SELECT COALESCE(SUM(price), 0) FROM transaction WHERE action = :subscribe AND created_time >= :startDate AND created_time <= :endDate';
        $doanh_thu_dang_ky_goi = Yii::$app->db->createCommand($doanh_thu_dang_ky_goi_sql)
            ->bindParam(':subscribe', $subscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Doanh thu gia hạn gói
        $doanh_thu_gia_han_goi_sql = 'SELECT COALESCE(SUM(price), 0) FROM transaction WHERE action = :monfee AND created_time >= :startDate AND created_time <= :endDate';
        $doanh_thu_gia_han_goi = Yii::$app->db->createCommand($doanh_thu_gia_han_goi_sql)
            ->bindParam(':monfee', $monfee)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Doanh thu truy thu gói
        $doanh_thu_truy_thu_goi_sql = 'SELECT COALESCE(SUM(price), 0) FROM transaction WHERE action = :retryExtend AND created_time >= :startDate AND created_time <= :endDate';
        $doanh_thu_truy_thu_goi = Yii::$app->db->createCommand($doanh_thu_truy_thu_goi_sql)
            ->bindParam(':retryExtend', $retryExtend)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng doanh thu gói
        $tong_doanh_thu_goi_sql = 'SELECT COALESCE(SUM(price), 0) FROM transaction WHERE package_id > 0 AND created_time >= :startDate AND created_time <= :endDate';
        $tong_doanh_thu_goi = Yii::$app->db->createCommand($tong_doanh_thu_goi_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng Số thuê bao active theo gói
        $tong_stb_active_package = 'SELECT count(DISTINCT user_id) as sum,package_id FROM `user_package` WHERE status <> :status GROUP BY package_id';
        $tong_STBC = Yii::$app->db->createCommand($tong_stb_active_package)
            ->bindParam(':status', $status)
            ->queryAll();
        $tong_so_thue_bao_active_id1
            = $tong_so_thue_bao_active_v1
            = $tong_so_thue_bao_active_v7
            = $tong_so_thue_bao_active_v30 = 0;
        foreach ($tong_STBC as $v) {
            if (isset($v['package_id']) && $v['package_id'] == 1) {
                $tong_so_thue_bao_active_vclip = $v['sum'];
            } else if (isset($v['package_id']) && $v['package_id'] == 3) {
                $tong_so_thue_bao_active_v1 = $v['sum'];
            } else if (isset($v['package_id']) && $v['package_id'] == 13) {
                $tong_so_thue_bao_active_v7 = $v['sum'];
            } else if (isset($v['package_id']) && $v['package_id'] == 15) {
                $tong_so_thue_bao_active_v30 = $v['sum'];
            }
        }


        $data['package_all'] = [
            'tong_so_thue_bao_active' => $tong_so_thue_bao_active,
            //update 19-10-2016
            'tong_so_thue_bao_active_id1' => $tong_so_thue_bao_active_vclip,
            'tong_so_thue_bao_active_id3' => $tong_so_thue_bao_active_v1,
            'tong_so_thue_bao_active_id13' => $tong_so_thue_bao_active_v7,
            'tong_so_thue_bao_active_id15' => $tong_so_thue_bao_active_v30,

            'tong_so_thue_bao_can_gia_han_lan_1' => $tong_so_thue_bao_can_gia_han_lan_1,
            'tong_so_thue_bao_can_truy_thu' => $tong_so_thue_bao_can_truy_thu,
            'tong_so_thue_bao_gia_han_thanh_cong' => $tong_so_thue_bao_gia_han_thanh_cong,
            'tong_so_thue_bao_gia_han_1_lan_thanh_cong' => $tong_so_thue_bao_gia_han_1_lan_thanh_cong,
            'tong_so_thue_bao_truy_thu_thanh_cong' => $tong_so_thue_bao_truy_thu_thanh_cong,
            'tong_so_thue_bao_dang_ky' => $tong_so_thue_bao_dang_ky,
            'tong_so_thue_bao_dang_ky_qua_sms' => $tong_so_thue_bao_dang_ky_qua_sms,
            'tong_so_thue_bao_dang_ky_qua_wap' => $tong_so_thue_bao_dang_ky_qua_wap,
            'tong_so_thue_bao_dang_ky_qua_app' => $tong_so_thue_bao_dang_ky_qua_app,
            'tong_so_thue_bao_huy' => $tong_so_thue_bao_huy,
            'tong_so_thue_bao_tu_huy' => $tong_so_thue_bao_tu_huy,
            'tong_so_thue_bao_tu_huy_qua_sms' => $tong_so_thue_bao_tu_huy_qua_sms,
            'tong_so_thue_bao_tu_huy_qua_wap' => $tong_so_thue_bao_tu_huy_qua_wap,
            'tong_so_thue_bao_tu_huy_qua_app' => $tong_so_thue_bao_tu_huy_qua_app,
            'tong_so_thue_bao_bi_huy_channel_khac' => $tong_so_thue_bao_bi_huy_channel_khac,
            'tong_so_thue_bao_bi_huy' => $tong_so_thue_bao_bi_huy,
            'doanh_thu_dang_ky_goi' => $doanh_thu_dang_ky_goi,
            'doanh_thu_gia_han_goi' => $doanh_thu_gia_han_goi,
            'doanh_thu_truy_thu_goi' => $doanh_thu_truy_thu_goi,
            'tong_doanh_thu_goi' => $tong_doanh_thu_goi
        ];

        $listPackage = Package::find()->all();
        if (!empty($listPackage)) {
            foreach ($listPackage as $package) {
                $packageId = $package->id;

                // Tổng số thuê bao active
                $tong_so_thue_bao_active_sql = 'SELECT COUNT(DISTINCT user_id) FROM user_package WHERE status <> :status AND package_id = :packageId';
                $tong_so_thue_bao_active = Yii::$app->db->createCommand($tong_so_thue_bao_active_sql)
                    ->bindParam(':status', $status)
                    ->bindParam(':packageId', $packageId)
                    ->queryScalar();

                // Tổng số thuê bao cần gia hạn lần 1
                $tong_so_thue_bao_can_gia_han_lan_1_sql = 'SELECT COUNT(DISTINCT user_id) FROM user_package WHERE status <> :status AND extend_fail_count = 0 AND expired_time <= :endDate AND package_id = :packageId';
                $tong_so_thue_bao_can_gia_han_lan_1 = Yii::$app->db->createCommand($tong_so_thue_bao_can_gia_han_lan_1_sql)
                    ->bindParam(':status', $status)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':packageId', $packageId)
                    ->queryScalar();

                // Tổng số thuê bao cần truy thu
                $tong_so_thue_bao_can_truy_thu_sql = 'SELECT COUNT(DISTINCT user_id) FROM user_package WHERE status <> :status AND extend_fail_count > 0 AND expired_time <= :endDate AND package_id = :packageId';
                $tong_so_thue_bao_can_truy_thu = Yii::$app->db->createCommand($tong_so_thue_bao_can_truy_thu_sql)
                    ->bindParam(':status', $status)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':packageId', $packageId)
                    ->queryScalar();

                // Tổng số thuê bao gia hạn thành công
                $tong_so_thue_bao_gia_han_thanh_cong_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE (action = :monfee OR action = :retryExtend) AND created_time >= :startDate AND created_time <= :endDate AND package_id = :packageId';
                $tong_so_thue_bao_gia_han_thanh_cong = Yii::$app->db->createCommand($tong_so_thue_bao_gia_han_thanh_cong_sql)
                    ->bindParam(':monfee', $monfee)
                    ->bindParam(':retryExtend', $retryExtend)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':packageId', $packageId)
                    ->queryScalar();

                // Tổng số thuê bao gia hạn 1 lần thành công
                $tong_so_thue_bao_gia_han_1_lan_thanh_cong_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :monfee AND created_time >= :startDate AND created_time <= :endDate AND package_id = :packageId';
                $tong_so_thue_bao_gia_han_1_lan_thanh_cong = Yii::$app->db->createCommand($tong_so_thue_bao_gia_han_1_lan_thanh_cong_sql)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':monfee', $monfee)
                    ->bindParam(':packageId', $packageId)
                    ->queryScalar();

                // Tổng số thuê bao truy thu thành công
                $tong_so_thue_bao_truy_thu_thanh_cong_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :action AND created_time >= :startDate AND created_time <= :endDate AND package_id = :packageId';
                $tong_so_thue_bao_truy_thu_thanh_cong = Yii::$app->db->createCommand($tong_so_thue_bao_truy_thu_thanh_cong_sql)
                    ->bindParam(':action', $retryExtend)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':packageId', $packageId)
                    ->queryScalar();

                // Tổng số thuê bao đăng ký
                $tong_so_thue_bao_dang_ky_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :subscribe AND created_time >= :startDate AND created_time <= :endDate AND package_id = :packageId';
                $tong_so_thue_bao_dang_ky = Yii::$app->db->createCommand($tong_so_thue_bao_dang_ky_sql)
                    ->bindParam(':subscribe', $subscribe)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':packageId', $packageId)
                    ->queryScalar();

                // Tổng số thuê bao đăng ký qua sms
                $tong_so_thue_bao_dang_ky_qua_sms_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :subscribe AND channel = :channelSms AND created_time >= :startDate AND created_time <= :endDate AND package_id = :packageId';
                $tong_so_thue_bao_dang_ky_qua_sms = Yii::$app->db->createCommand($tong_so_thue_bao_dang_ky_qua_sms_sql)
                    ->bindParam(':subscribe', $subscribe)
                    ->bindParam(':channelSms', $channelSms)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':packageId', $packageId)
                    ->queryScalar();

                // Tổng số thuê bao đăng ký qua wap
                $tong_so_thue_bao_dang_ky_qua_wap_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :subscribe AND channel = :channelWap AND created_time >= :startDate AND created_time <= :endDate AND package_id = :packageId';
                $tong_so_thue_bao_dang_ky_qua_wap = Yii::$app->db->createCommand($tong_so_thue_bao_dang_ky_qua_wap_sql)
                    ->bindParam(':subscribe', $subscribe)
                    ->bindParam(':channelWap', $channelWap)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':packageId', $packageId)
                    ->queryScalar();

                // Tổng số thuê bao đăng ký qua app
                $tong_so_thue_bao_dang_ky_qua_app_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :subscribe AND channel = :channelApp AND created_time >= :startDate AND created_time <= :endDate AND package_id = :packageId';
                $tong_so_thue_bao_dang_ky_qua_app = Yii::$app->db->createCommand($tong_so_thue_bao_dang_ky_qua_app_sql)
                    ->bindParam(':subscribe', $subscribe)
                    ->bindParam(':channelApp', $channelApp)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':packageId', $packageId)
                    ->queryScalar();

                // Tổng số thuê bao hủy
                $tong_so_thue_bao_huy_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :unsubscribe AND created_time >= :startDate AND created_time <= :endDate AND package_id = :packageId';
                $tong_so_thue_bao_huy = Yii::$app->db->createCommand($tong_so_thue_bao_huy_sql)
                    ->bindParam(':unsubscribe', $unsubscribe)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':packageId', $packageId)
                    ->queryScalar();

                // Tổng số thuê bao tự hủy
                $tong_so_thue_bao_tu_huy_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :unsubscribe AND channel <> :channelCron AND created_time >= :startDate AND created_time <= :endDate AND package_id = :packageId';
                $tong_so_thue_bao_tu_huy = Yii::$app->db->createCommand($tong_so_thue_bao_tu_huy_sql)
                    ->bindParam(':unsubscribe', $unsubscribe)
                    ->bindParam(':channelCron', $channelCron)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':packageId', $packageId)
                    ->queryScalar();

                // Tổng số thuê bao tự hủy qua sms
                $tong_so_thue_bao_tu_huy_qua_sms_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :unsubscribe AND channel = :channelSms AND created_time >= :startDate AND created_time <= :endDate AND package_id = :packageId';
                $tong_so_thue_bao_tu_huy_qua_sms = Yii::$app->db->createCommand($tong_so_thue_bao_tu_huy_qua_sms_sql)
                    ->bindParam(':unsubscribe', $unsubscribe)
                    ->bindParam(':channelSms', $channelSms)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':packageId', $packageId)
                    ->queryScalar();

                // Tổng số thuê bao tự hủy qua wap
                $tong_so_thue_bao_tu_huy_qua_wap_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :unsubscribe AND channel = :channelWap AND created_time >= :startDate AND created_time <= :endDate AND package_id = :packageId';
                $tong_so_thue_bao_tu_huy_qua_wap = Yii::$app->db->createCommand($tong_so_thue_bao_tu_huy_qua_wap_sql)
                    ->bindParam(':unsubscribe', $unsubscribe)
                    ->bindParam(':channelWap', $channelWap)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':packageId', $packageId)
                    ->queryScalar();

                // Tổng số thuê bao tự hủy qua app
                $tong_so_thue_bao_tu_huy_qua_app_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :unsubscribe AND channel = :channelApp AND created_time >= :startDate AND created_time <= :endDate AND package_id = :packageId';
                $tong_so_thue_bao_tu_huy_qua_app = Yii::$app->db->createCommand($tong_so_thue_bao_tu_huy_qua_app_sql)
                    ->bindParam(':unsubscribe', $unsubscribe)
                    ->bindParam(':channelApp', $channelApp)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':packageId', $packageId)
                    ->queryScalar();

                // Tổng số thuê bao bị hủy tu channel khac
                $tong_so_thue_bao_bi_huy_channel_khac_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :unsubscribe AND channel NOT IN (:channelCron,:channelSys,:channelWap,:channelSms,:channelApp) AND created_time >= :startDate AND created_time <= :endDate AND package_id = :packageId';
                $tong_so_thue_bao_bi_huy_channel_khac = Yii::$app->db->createCommand($tong_so_thue_bao_bi_huy_channel_khac_sql)
                    ->bindParam(':unsubscribe', $unsubscribe)
                    ->bindParam(':channelCron', $channelCron)
                    ->bindParam(':channelSys', $channelSys)
                    ->bindParam(':channelWap', $channelWap)
                    ->bindParam(':channelSms', $channelSms)
                    ->bindParam(':channelApp', $channelApp)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':packageId', $packageId)
                    ->queryScalar();

                // Tổng số thuê bao bị hủy
                $tong_so_thue_bao_bi_huy_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :unsubscribe AND channel = :channelCron AND created_time >= :startDate AND created_time <= :endDate AND package_id = :packageId';
                $tong_so_thue_bao_bi_huy = Yii::$app->db->createCommand($tong_so_thue_bao_bi_huy_sql)
                    ->bindParam(':unsubscribe', $unsubscribe)
                    ->bindParam(':channelCron', $channelCron)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':packageId', $packageId)
                    ->queryScalar();

                // Doanh thu đăng ký gói
                $doanh_thu_dang_ky_goi_sql = 'SELECT COALESCE(SUM(price), 0) FROM transaction WHERE action = :subscribe AND created_time >= :startDate AND created_time <= :endDate AND package_id = :packageId';
                $doanh_thu_dang_ky_goi = Yii::$app->db->createCommand($doanh_thu_dang_ky_goi_sql)
                    ->bindParam(':subscribe', $subscribe)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':packageId', $packageId)
                    ->queryScalar();

                // Doanh thu gia hạn gói
                $doanh_thu_gia_han_goi_sql = 'SELECT COALESCE(SUM(price), 0) FROM transaction WHERE action = :monfee AND created_time >= :startDate AND created_time <= :endDate AND package_id = :packageId';
                $doanh_thu_gia_han_goi = Yii::$app->db->createCommand($doanh_thu_gia_han_goi_sql)
                    ->bindParam(':monfee', $monfee)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':packageId', $packageId)
                    ->queryScalar();

                // Doanh thu truy thu gói
                $doanh_thu_truy_thu_goi_sql = 'SELECT COALESCE(SUM(price), 0) FROM transaction WHERE action = :retryExtend AND created_time >= :startDate AND created_time <= :endDate AND package_id = :packageId';
                $doanh_thu_truy_thu_goi = Yii::$app->db->createCommand($doanh_thu_truy_thu_goi_sql)
                    ->bindParam(':retryExtend', $retryExtend)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':packageId', $packageId)
                    ->queryScalar();

                // Tổng doanh thu gói
                $tong_doanh_thu_goi_sql = 'SELECT COALESCE(SUM(price), 0) FROM transaction WHERE created_time >= :startDate AND created_time <= :endDate AND package_id = :packageId';
                $tong_doanh_thu_goi = Yii::$app->db->createCommand($tong_doanh_thu_goi_sql)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':packageId', $packageId)
                    ->queryScalar();

                $data['package_' . $packageId] = [
                    'tong_so_thue_bao_active' => $tong_so_thue_bao_active,
                    'tong_so_thue_bao_can_gia_han_lan_1' => $tong_so_thue_bao_can_gia_han_lan_1,
                    'tong_so_thue_bao_can_truy_thu' => $tong_so_thue_bao_can_truy_thu,
                    'tong_so_thue_bao_gia_han_thanh_cong' => $tong_so_thue_bao_gia_han_thanh_cong,
                    'tong_so_thue_bao_gia_han_1_lan_thanh_cong' => $tong_so_thue_bao_gia_han_1_lan_thanh_cong,
                    'tong_so_thue_bao_truy_thu_thanh_cong' => $tong_so_thue_bao_truy_thu_thanh_cong,
                    'tong_so_thue_bao_dang_ky' => $tong_so_thue_bao_dang_ky,
                    'tong_so_thue_bao_dang_ky_qua_sms' => $tong_so_thue_bao_dang_ky_qua_sms,
                    'tong_so_thue_bao_dang_ky_qua_wap' => $tong_so_thue_bao_dang_ky_qua_wap,
                    'tong_so_thue_bao_dang_ky_qua_app' => $tong_so_thue_bao_dang_ky_qua_app,
                    'tong_so_thue_bao_huy' => $tong_so_thue_bao_huy,
                    'tong_so_thue_bao_tu_huy' => $tong_so_thue_bao_tu_huy,
                    'tong_so_thue_bao_tu_huy_qua_sms' => $tong_so_thue_bao_tu_huy_qua_sms,
                    'tong_so_thue_bao_tu_huy_qua_wap' => $tong_so_thue_bao_tu_huy_qua_wap,
                    'tong_so_thue_bao_tu_huy_qua_app' => $tong_so_thue_bao_tu_huy_qua_app,
                    'tong_so_thue_bao_bi_huy_channel_khac' => $tong_so_thue_bao_bi_huy_channel_khac,
                    'tong_so_thue_bao_bi_huy' => $tong_so_thue_bao_bi_huy,
                    'doanh_thu_dang_ky_goi' => $doanh_thu_dang_ky_goi,
                    'doanh_thu_gia_han_goi' => $doanh_thu_gia_han_goi,
                    'doanh_thu_truy_thu_goi' => $doanh_thu_truy_thu_goi,
                    'tong_doanh_thu_goi' => $tong_doanh_thu_goi
                ];
            }
            $data = serialize($data);
            $sql = 'INSERT INTO daily_report SET date = :startDate, type = 2, data = :data ON DUPLICATE KEY UPDATE data = :data';

            Yii::$app->db->createCommand($sql)
                ->bindParam(':startDate', $startDate)
                ->bindParam(':data', $data)
                ->execute();
        }
    }

    /**
     * @throws \yii\db\Exception
     * Thống kê khung giờ (type = 3)
     */
    public function Hour()
    {
        $streaming = WATCH;
        $subscribe = SUBSCRIBE;

        $startDate = date('Y-m-d', strtotime('-1 day'));
        $endDate = $startDate . ' 23:59:59';
        $data = [];

        // Tổng số lượt truy cập
        $luot_truy_cap = [];
        $luot_truy_cap_sql = 'SELECT HOUR(created_time) as hour, COUNT(id) as luot_truy_cap FROM session WHERE created_time BETWEEN :startDate AND :endDate GROUP BY HOUR(created_time)';
        $luot_truy_cap_query = Yii::$app->db->createCommand($luot_truy_cap_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryAll();
        if (!empty($luot_truy_cap_query)) {
            foreach ($luot_truy_cap_query as $key => $value) {
                $data['method_1'][$value['hour']] = $value['luot_truy_cap'];
            }
        }


        // Tổng số lượt streaming
        $luot_streaming = [];
        $luot_streaming_sql = '
         (
             SELECT
                HOUR(created_time) as hour,
                COUNT(user_id) as luot_streaming
             FROM
                transaction
             WHERE
                created_time BETWEEN :startDate AND :endDate AND action = :streaming
             GROUP BY
                HOUR(created_time)
             ORDER BY
                HOUR(created_time) ASC
         )
         UNION
         (
             SELECT
                HOUR(created_time) as hour,
                COUNT(user_id) as luot_streaming
             FROM
                user_content
             WHERE
                created_time BETWEEN :startDate AND :endDate AND action = :streaming
             GROUP BY
                HOUR(created_time)
             ORDER BY
                HOUR(created_time) ASC
         )
         ';
        $luot_streaming_query = Yii::$app->db->createCommand($luot_streaming_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->bindParam(':streaming', $streaming)
            ->queryAll();
        if (!empty($luot_streaming_query)) {
            foreach ($luot_streaming_query as $key => $value) {
                $data['method_2'][$value['hour']] = $value['luot_streaming'];
            }
        }

        // Tổng số lượt đăng ký
        $luot_dang_ky = [];
        $luot_dang_ky_sql = 'SELECT HOUR(created_time) as hour, COUNT(user_id) as luot_dang_ky FROM transaction WHERE created_time BETWEEN :startDate AND :endDate AND action = :subscribe GROUP BY HOUR(created_time) ORDER BY HOUR(created_time) ASC';
        $luot_dang_ky_query = Yii::$app->db->createCommand($luot_dang_ky_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->bindParam(':subscribe', $subscribe)
            ->queryAll();
        if (!empty($luot_dang_ky_query)) {
            foreach ($luot_dang_ky_query as $key => $value) {
                $luot_dang_ky[$value['hour']] = $value;
                $data['method_3'][$value['hour']] = $value['luot_dang_ky'];
            }
        }

        $data = serialize($data);
        $sql = 'INSERT INTO daily_report SET date = :startDate, type = 3, data = :data ON DUPLICATE KEY UPDATE data = :data';

        Yii::$app->db->createCommand($sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':data', $data)
            ->execute();
    }

    /**
     * @throws \yii\db\Exception
     * Thống kê đối soát (type = 4)
     */
    public function CrossCheck($date = null)
    {
        $streaming = WATCH;
        $download = DOWNLOAD;
        $subscribe = SUBSCRIBE;

        if(empty($date)){
            $startDate = date('Y-m-d', strtotime('-1 day'));
            $endDate = $startDate . ' 23:59:59';
            $dateNow = date('Y-m-d', time());
        }else{
            $time = strtotime($date);
            $startDate = date('Y-m-d', $time);
            $endDate = $startDate . ' 23:59:59';
            $dateNow = date('Y-m-d', $time+86400);
        }

        $firstMonthNow = date('Y-m-01', time());
        /*-------------------------------------Thống kê tổng cp của toàn dịch vụ---------------------------*/
        // Tổng lượt xem/tải các nội dung của đơn vị CC từ thuê bao đăng ký
        $tong_luot_xem_tai_sql = 'SELECT COUNT(user_id) 
            FROM user_content 
            WHERE (action = :streaming OR action = :download) 
            AND package_id > 0 
            AND created_time >= :startDate 
            AND created_time <= :endDate
        ';
        $tong_luot_xem_tai = Yii::$app->db->createCommand($tong_luot_xem_tai_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->bindParam(':streaming', $streaming)
            ->bindParam(':download', $download)
            ->queryScalar();

        // Tổng lượt xem/tải từ thuê bao đăng ký của toàn dịch vụ
        $tong_luot_xem_tai_toan_dich_vu_sql = 'SELECT COUNT(user_id) 
          FROM user_content 
          WHERE (action = :streaming OR action = :download) 
          AND package_id > 0 
          AND created_time >= :startDate 
          AND created_time <= :endDate';
        $tong_luot_xem_tai_toan_dich_vu = Yii::$app->db->createCommand($tong_luot_xem_tai_toan_dich_vu_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->bindParam(':streaming', $streaming)
            ->bindParam(':download', $download)
            ->queryScalar();

        // Tổng doanh thu dịch vụ từ thuê bao đăng ký
        $tong_doanh_thu_dich_vu_sql = 'SELECT COALESCE(SUM(price), 0) 
          FROM transaction 
          WHERE action = :subscribe 
          AND  price > 0 
          AND package_id > 0 
          AND created_time >= :startDate 
          AND created_time <= :endDate';
        $tong_doanh_thu_dich_vu = Yii::$app->db->createCommand($tong_doanh_thu_dich_vu_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->bindParam(':subscribe', $subscribe)
            ->queryScalar();

        // Doanh thu
        $doanh_thu_sql = 'SELECT COALESCE(SUM(price), 0) 
            FROM transaction 
            WHERE price > 0 
            AND created_time >= :startDate 
            AND created_time <= :endDate';
        $doanh_thu = Yii::$app->db->createCommand($doanh_thu_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Doanh thu lũy kế tháng
        $doanh_thu_luy_ke_thang_sql = '
          SELECT COALESCE(SUM(price), 0) 
          FROM transaction WHERE price > 0 
          AND created_time >= :firstMonthNow 
          AND created_time <= :dateNow';
        $doanh_thu_luy_ke_thang = Yii::$app->db->createCommand($doanh_thu_luy_ke_thang_sql)
            ->bindParam(':firstMonthNow', $firstMonthNow)
            ->bindParam(':dateNow', $dateNow)
            ->queryScalar();

        $data['cp_all'] = [
            'tong_luot_xem_tai' => $tong_luot_xem_tai,
            'tong_luot_xem_tai_toan_dich_vu' => $tong_luot_xem_tai_toan_dich_vu,
            'tong_doanh_thu_dich_vu' => $tong_doanh_thu_dich_vu,
            'doanh_thu' => $doanh_thu,
            'doanh_thu_luy_ke_thang' => $doanh_thu_luy_ke_thang
        ];
        /*-------------------------------------Thống kê theo từng cp---------------------------*/
        $listCp = Cp::find()->all();
        if (!empty($listCp)) {
            foreach ($listCp as $cp) {
                $cpId = $cp->id;

                // Tổng lượt xem/tải các nội dung của đơn vị CC từ thuê bao đăng ký
                $tong_luot_xem_tai_sql = 'SELECT COUNT(user_id) FROM user_content WHERE (action = :streaming OR action = :download) AND package_id > 0 AND created_time >= :startDate AND created_time <= :endDate AND cp_id = :cpId';
                $tong_luot_xem_tai = Yii::$app->db->createCommand($tong_luot_xem_tai_sql)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':streaming', $streaming)
                    ->bindParam(':download', $download)
                    ->bindParam(':cpId', $cpId)
                    ->queryScalar();

                // Tổng lượt xem/tải từ thuê bao đăng ký của toàn dịch vụ
                $tong_luot_xem_tai_toan_dich_vu_sql = 'SELECT COUNT(user_id) FROM user_content WHERE (action = :streaming OR action = :download) AND package_id > 0 AND created_time >= :startDate AND created_time <= :endDate';
                $tong_luot_xem_tai_toan_dich_vu = Yii::$app->db->createCommand($tong_luot_xem_tai_toan_dich_vu_sql)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':streaming', $streaming)
                    ->bindParam(':download', $download)
                    ->queryScalar();

                // Tổng doanh thu dịch vụ từ thuê bao đăng ký
                $tong_doanh_thu_dich_vu_sql = 'SELECT COALESCE(SUM(price), 0) FROM transaction WHERE action = :subscribe AND  price > 0 AND package_id > 0 AND created_time >= :startDate AND created_time <= :endDate AND cp_id = :cpId';
                $tong_doanh_thu_dich_vu = Yii::$app->db->createCommand($tong_doanh_thu_dich_vu_sql)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':subscribe', $subscribe)
                    ->bindParam(':cpId', $cpId)
                    ->queryScalar();

                // Tỷ lệ chia sẻ
                $ty_le_chia_se_sql = 'SELECT sharing_rate FROM cp WHERE id = :cpId';
                $ty_le_chia_se = Yii::$app->db->createCommand($ty_le_chia_se_sql)
                    ->bindParam(':cpId', $cpId)
                    ->queryOne();
                $ty_le_chia_se = $ty_le_chia_se['sharing_rate'];

                // Doanh thu
                $doanh_thu_sql = 'SELECT COALESCE(SUM(price), 0) FROM transaction WHERE price > 0 AND created_time >= :startDate AND created_time <= :endDate AND cp_id = :cpId';
                $doanh_thu = Yii::$app->db->createCommand($doanh_thu_sql)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':cpId', $cpId)
                    ->queryScalar();

                // Doanh thu lũy kế tháng
                $doanh_thu_luy_ke_thang_sql = 'SELECT COALESCE(SUM(price), 0) FROM transaction WHERE price > 0 AND created_time >= :firstMonthNow AND created_time <= :dateNow AND cp_id = :cpId';
                $doanh_thu_luy_ke_thang = Yii::$app->db->createCommand($doanh_thu_luy_ke_thang_sql)
                    ->bindParam(':firstMonthNow', $firstMonthNow)
                    ->bindParam(':dateNow', $dateNow)
                    ->bindParam(':cpId', $cpId)
                    ->queryScalar();

                $data['cp_' . $cpId] = [
                    'tong_luot_xem_tai' => $tong_luot_xem_tai,
                    'tong_luot_xem_tai_toan_dich_vu' => $tong_luot_xem_tai_toan_dich_vu,
                    'tong_doanh_thu_dich_vu' => $tong_doanh_thu_dich_vu,
                    'ty_le_chia_se' => $ty_le_chia_se,
                    'doanh_thu' => $doanh_thu,
                    'doanh_thu_luy_ke_thang' => $doanh_thu_luy_ke_thang
                ];
            }
        }
        $data = serialize($data);
        $sql = 'INSERT INTO daily_report SET date = :startDate, type = 4, data = :data ON DUPLICATE KEY UPDATE data = :data';

        Yii::$app->db->createCommand($sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':data', $data)
            ->execute();
    }

    /**
     * @param int $type
     * @param string $date ngay thong ke su dung cho thong ke ngay
     *
     * @throws \yii\db\Exception
     * thong ke su dung the ngay (type = 5), thong ke su dung thang type = 6
     */
    public function UserDayMonth($type = 1, $date=null)
    {
        $streaming = WATCH;
        $download = DOWNLOAD;
        $monfee = MONFEE;
        $retry_extend = ARREARS;
        $channelWap = CHANNEL_WAP;
        $channelApp = CHANNEL_APP;
        $status = 1; // Những thuê bao đã đăng ký dịch vụ

        if ($type == 1) {
            if(empty($date)){
                $startDate = date('Y-m-d', strtotime('-1 day'));
                $endDate = $startDate . ' 23:59:59';
            }else{
                $startDate = $date;
                $endDate = $startDate . ' 23:59:59';
            }

        } else if ($type == 2) {
            $startDate = date('Y-m-01', time());
            $endDate = date('Y-m-d', time());
        } else {
            $startDate = date('Y-m-d', strtotime('-1 day'));
            $endDate = $startDate . ' 23:59:59';
        }

        /*-------------------Thống kê trên toàn channel----------------------*/

        /*-------------------BEGIN Tạo bảng tạm----------------------*/
        // Tạo bảng tạm tmp_session.
        //Yii::$app->db->createCommand('CREATE TABLE IF NOT EXISTS tmp_session (user_id VARCHAR(15) NOT NULL,method varchar(10) DEFAULT NULL,package_id tinyint(4) DEFAULT 0,user_package_status tinyint(3) DEFAULT 0,INDEX (user_id),INDEX (method),INDEX (package_id),INDEX (user_package_status))')->execute();

        // Tạo bảng tạm tmp_user_package. Những thuê bao đã đăng ký dịch vụ status = 1
        //Yii::$app->db->createCommand('CREATE TABLE IF NOT EXISTS tmp_user_package (user_id VARCHAR(15) NOT NULL, INDEX (user_id))')->execute();

        // Tạo bảng tạm tmp_use_content.
        //Yii::$app->db->createCommand('CREATE TABLE IF NOT EXISTS tmp_user_content (user_id VARCHAR(15) NOT NULL,price int(11) DEFAULT 0,action varchar(10) DEFAULT NULL,channel varchar(10) DEFAULT NULL,package_id tinyint(4) DEFAULT 0,user_package_status tinyint(3) DEFAULT 0, INDEX (user_id),INDEX (price),INDEX (action),INDEX (channel),INDEX (package_id),INDEX (user_package_status))')->execute();

        // Tạo bảng tạm tmp_transaction.
        //Yii::$app->db->createCommand('CREATE TABLE IF NOT EXISTS tmp_transaction (user_id VARCHAR(15) NOT NULL,price int(11) DEFAULT 0,action varchar(10) DEFAULT NULL,channel varchar(10) DEFAULT NULL,package_id tinyint(4) DEFAULT 0,user_package_status tinyint(3) DEFAULT 0, INDEX (user_id),INDEX (price),INDEX (action),INDEX (channel),INDEX (package_id),INDEX (user_package_status))')->execute();

        // Tạo bảng tạm tmp_use_user_content. Những thuê bao sử dụng nội dung miễn phí
        //Yii::$app->db->createCommand('CREATE TABLE IF NOT EXISTS tmp_use_user_content (user_id VARCHAR(15) NOT NULL, INDEX (user_id))')->execute();

        // Tạo bảng tạm tmp_use_user_transaction. Những thuê bao sử dụng nội dung mất phí
        //Yii::$app->db->createCommand('CREATE TABLE IF NOT EXISTS tmp_use_user_transaction (user_id VARCHAR(15) NOT NULL, INDEX (user_id))')->execute();

        // Tạo bảng tạm tmp_not_use_content. Những thuê bao không sử dụng nội dung
        //Yii::$app->db->createCommand('CREATE TABLE IF NOT EXISTS tmp_not_use_content (user_id VARCHAR(15) NOT NULL, INDEX (user_id))')->execute();

        // Tạo bảng tạm tmp_monfee. Những thuê bao gia hạn được
        //Yii::$app->db->createCommand('CREATE TABLE IF NOT EXISTS tmp_monfee (user_id VARCHAR(15) NOT NULL,price int(11) DEFAULT 0,action varchar(10) DEFAULT NULL,channel varchar(10) DEFAULT NULL, INDEX (user_id),INDEX (price),INDEX (channel), INDEX (action))')->execute();

        // Thực hiện truncate bảng tmp_session & tmp_user_package khi chạy cronjob
        Yii::$app->db->createCommand("TRUNCATE TABLE tmp_session")->execute();
        Yii::$app->db->createCommand("TRUNCATE TABLE tmp_user_package")->execute();
        Yii::$app->db->createCommand("TRUNCATE TABLE tmp_user_content")->execute();
        Yii::$app->db->createCommand("TRUNCATE TABLE tmp_transaction")->execute();
        //Yii::$app->db->createCommand("TRUNCATE TABLE tmp_use_user_content")->execute();
        //Yii::$app->db->createCommand("TRUNCATE TABLE tmp_use_user_transaction")->execute();
        //Yii::$app->db->createCommand("TRUNCATE TABLE tmp_not_use_content")->execute();
        Yii::$app->db->createCommand("TRUNCATE TABLE tmp_monfee")->execute();

        // Insert dữ liệu vào bảng tmp_session.
        Yii::$app->db->createCommand('INSERT INTO tmp_session (SELECT user_id,method,package_id,user_package_status FROM session WHERE created_time >= :startDate AND created_time <= :endDate)')
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->execute();

        // Insert dữ liệu vào bảng tmp_user_package. Những thuê bao đã đăng ký dịch vụ
        Yii::$app->db->createCommand('INSERT INTO tmp_user_package (user_id) (SELECT DISTINCT user_id FROM user_package WHERE status = :status)')
            ->bindParam(':status', $status)
            ->execute();

        // Insert dữ liệu vào bảng tmp_use_content.
        Yii::$app->db->createCommand('INSERT INTO tmp_user_content (SELECT user_id,price,action,channel,package_id,user_package_status FROM user_content WHERE created_time >= :startDate AND created_time <= :endDate)')
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->execute();

        // Insert dữ liệu vào bảng tmp_transaction.
        Yii::$app->db->createCommand('INSERT INTO tmp_transaction (SELECT user_id,price,action,channel,package_id,user_package_status FROM transaction WHERE created_time >= :startDate AND created_time <= :endDate)')
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->execute();

        // Insert dữ liệu vào bảng tmp_use_user_content. Những thuê bao sử dụng nội dung miễn phí price=0
        //Yii::$app->db->createCommand('INSERT INTO tmp_use_user_content (user_id) (SELECT DISTINCT(user_id) FROM user_content WHERE (action = :streaming OR action = :download) AND price=0 AND user_id <> 0 AND created_time >= :startDate AND created_time <= :endDate)')
        //    ->bindParam(':streaming', $streaming)
        //    ->bindParam(':download', $download)
        //    ->bindParam(':startDate', $startDate)
        //    ->bindParam(':endDate', $endDate)
        //    ->execute();

        // Insert dữ liệu vào bảng tmp_use_user_transaction. Những thuê bao sử dụng nội dung mất phí
        //Yii::$app->db->createCommand('INSERT INTO tmp_use_user_transaction (user_id) (SELECT DISTINCT user_id FROM user_content WHERE (action = :streaming OR action = :download) AND created_time >= :startDate AND created_time <= :endDate)')
        //    ->bindParam(':streaming', $streaming)
        //    ->bindParam(':download', $download)
        //    ->bindParam(':startDate', $startDate)
        //    ->bindParam(':endDate', $endDate)
        //    ->execute();

        // Insert dữ liệu vào bảng tmp_not_use_content. Những thuê bao không sử dụng nội dung
        //Yii::$app->db->createCommand('INSERT INTO tmp_not_use_content (user_id) (SELECT DISTINCT user_id FROM transaction WHERE (action <> :streaming AND action <> :download) AND created_time >= :startDate AND created_time <= :endDate)')
        //    ->bindParam(':streaming', $streaming)
        //    ->bindParam(':download', $download)
        //    ->bindParam(':startDate', $startDate)
        //    ->bindParam(':endDate', $endDate)
        //    ->execute();

        // Insert dữ liệu vào bảng tmp_monfee. Trừ được cước, chỉ tính gia hạn
        Yii::$app->db->createCommand('INSERT INTO tmp_monfee (SELECT user_id,price,action,channel FROM transaction WHERE (action = :monfee OR ACTION = :retryExtend) AND created_time >= :startDate AND created_time <= :endDate)')
            ->bindParam(':monfee', $monfee)
            ->bindParam(':retryExtend', $retry_extend)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->execute();
        echo 'insert success';
        /*-------------------END Tạo bảng tạm----------------------*/


        // Tổng số thuê bao trừ được cước trên dịch vụ
        //$tong_so_thue_bao_tru_duoc_cuoc_sql = 'SELECT COUNT(DISTINCT user_id) FROM tmp_transaction WHERE action = :monfee OR ACTION = :retryExtend';
        //$tong_so_thue_bao_tru_duoc_cuoc = Yii::$app->db->createCommand($tong_so_thue_bao_tru_duoc_cuoc_sql)
        //    ->bindParam(':monfee', $monfee)
        //    ->bindParam(':retryExtend',$retry_extend)
        //    ->queryScalar();
        $tong_so_thue_bao_tru_duoc_cuoc_sql = 'SELECT COUNT(DISTINCT user_id) FROM tmp_monfee ';
        $tong_so_thue_bao_tru_duoc_cuoc = Yii::$app->db->createCommand($tong_so_thue_bao_tru_duoc_cuoc_sql)->queryScalar();

        // Tổng số thuê bao có thực hiện xem/tải
        $tong_so_thue_bao_co_thuc_hien_xem_tai_sql = 'SELECT COUNT(DISTINCT user_id) FROM tmp_user_content';
        $tong_so_thue_bao_co_thuc_hien_xem_tai = Yii::$app->db->createCommand($tong_so_thue_bao_co_thuc_hien_xem_tai_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tỷ lệ người dùng thường xuyên
        $ty_le_nguoi_dung_thuong_xuyen = 0;

        // Số thuê bao trừ được cước có thực hiện xem tải
        $so_thue_bao_tru_duoc_cuoc_co_thuc_hien_xem_tai_sql = 'SELECT  COUNT(DISTINCT(M.user_id)) FROM tmp_monfee AS M
                                                                LEFT JOIN tmp_user_content AS C ON M.user_id = C.user_id
                                                                WHERE C.user_id IS NOT NULL';
        $so_thue_bao_tru_duoc_cuoc_co_thuc_hien_xem_tai = Yii::$app->db->createCommand($so_thue_bao_tru_duoc_cuoc_co_thuc_hien_xem_tai_sql)
            ->queryScalar();

        // Số thuê bao trong chu kỳ khuyến mại có thực hiện xem/tải
        $so_thue_bao_trong_chu_ky_khuyen_mai_co_thuc_hien_xem_tai = 0;

        // Số thuê bao chưa đăng ký xem/tải mất phí
        $so_thue_bao_chua_dang_ky_xem_tai_mat_phi_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE price>0 AND user_package_status!=1';
        $so_thue_bao_chua_dang_ky_xem_tai_mat_phi = Yii::$app->db->createCommand($so_thue_bao_chua_dang_ky_xem_tai_mat_phi_sql)
            ->queryScalar();

        // Số thuê bao chưa đăng ký xem/tải miễn phí
        $so_thue_bao_chua_dang_ky_xem_tai_mien_phi_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE price>0 AND user_package_status!=1';
        $so_thue_bao_chua_dang_ky_xem_tai_mien_phi = Yii::$app->db->createCommand($so_thue_bao_chua_dang_ky_xem_tai_mien_phi_sql)
            ->queryScalar();

        // Tổng lượt xem tải trên dịch vụ
        $tong_luot_xem_tai_tren_dich_vu_sql = 'SELECT COUNT(*) FROM tmp_user_content';
        $tong_luot_xem_tai_tren_dich_vu = Yii::$app->db->createCommand($tong_luot_xem_tai_tren_dich_vu_sql)
            ->queryScalar();

        // Tổng lượt xem  trên dịch vụ
        $tong_luot_xem_tren_dich_vu_sql = 'SELECT COUNT(*) FROM tmp_user_content WHERE action = :streaming';
        $tong_luot_xem_tren_dich_vu = Yii::$app->db->createCommand($tong_luot_xem_tren_dich_vu_sql)
            ->bindParam(':streaming', $streaming)
            ->queryScalar();

        // Trung bình số lượt xem video trên 1 lần truy cập dịch vụ
        $trung_binh_so_luot_xem_video_tren_1_lan_truy_cap_dich_vu = 0;

        // Tổng số lượt truy cập
        $tong_so_luot_truy_cap_sql = 'SELECT COUNT(*) FROM tmp_session';
        $tong_so_luot_truy_cap = Yii::$app->db->createCommand($tong_so_luot_truy_cap_sql)
            ->queryScalar();

        // Tổng số thuê bao truy cập
        $tong_so_thue_bao_truy_cap_sql = 'SELECT COUNT(DISTINCT user_id) FROM tmp_session';
        $tong_so_thue_bao_truy_cap = Yii::$app->db->createCommand($tong_so_thue_bao_truy_cap_sql)
            ->queryScalar();

        // Thuê bao đăng ký truy cập
        $tb_dang_ky_truy_cap_sql = 'SELECT COUNT(DISTINCT user_id) FROM tmp_session WHERE user_package_status = 1';
        $tb_dang_ky_truy_cap = Yii::$app->db->createCommand($tb_dang_ky_truy_cap_sql)
            ->queryScalar();

        // Trung bình số lượt xem video trên 1 lần truy cập dịch vụ
        $trung_binh_so_luot_xem_video_tren_1_lan_truy_cap_dich_vu = round($tong_luot_xem_tren_dich_vu / $tong_so_luot_truy_cap, 5);

        /*----------- Start >>>>>>>>> Số thuê bao truy cập là thuê bao dịch vụ--------------*/

        //echo 'Tao bang tam';


        // Số thuê bao truy cập là thuê bao dịch vụ. Có sử dụng nội dung
        //$so_thue_bao_truy_cap_la_thue_bao_dich_vu_co_su_dung_noi_dung_sql = 'SELECT COUNT(t1.user_id) FROM tmp_user_package t1 INNER JOIN tmp_session t2 ON t1.user_id = t2.user_id INNER JOIN (SELECT user_id FROM tmp_use_content UNION SELECT user_id FROM tmp_use_user_transaction) t3 ON t1.user_id = t3.user_id';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_co_su_dung_noi_dung_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE user_package_status=1 ';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_co_su_dung_noi_dung = Yii::$app->db->createCommand($so_thue_bao_truy_cap_la_thue_bao_dich_vu_co_su_dung_noi_dung_sql)->queryScalar();

        // Số thuê bao truy cập là thuê bao dịch vụ. Sử dụng nội dung miễn phí
        //$so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi_sql = 'SELECT COUNT(t1.user_id) FROM tmp_user_package t1 INNER JOIN tmp_session t2 ON t1.user_id = t2.user_id INNER JOIN tmp_use_user_content t3 ON t1.user_id = t3.user_id';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE user_package_status=1 AND price = 0 ';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi = Yii::$app->db->createCommand($so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi_sql)->queryScalar();

        // Số thuê bao truy cập là thuê bao dịch vụ. Sử dụng nội dung mất phí
        //$so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi_sql = 'SELECT COUNT(*) FROM tmp_user_content WHERE user_package_status=1 AND price > 0';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE user_package_status=1 AND price>0 ';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi = Yii::$app->db->createCommand($so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi_sql)->queryScalar();

        // Số thuê bao truy cập là thuê bao dịch vụ. Không sử dụng nội dung
        //$so_thue_bao_truy_cap_la_thue_bao_dich_vu_khong_su_dung_noi_dung_sql = 'SELECT COUNT(t1.user_id) FROM tmp_user_package t1 INNER JOIN tmp_session t2 ON t1.user_id = t2.user_id INNER JOIN tmp_not_use_content t3 ON t1.user_id = t3.user_id';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_khong_su_dung_noi_dung_sql = 'SELECT COUNT(DISTINCT(S.user_id)) FROM tmp_session AS S LEFT JOIN tmp_user_content AS C ON S.user_id = C.user_id WHERE S.user_package_status=1 AND C.user_id IS NULL';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_khong_su_dung_noi_dung = Yii::$app->db->createCommand($so_thue_bao_truy_cap_la_thue_bao_dich_vu_khong_su_dung_noi_dung_sql)->queryScalar();

        // Số thuê bao truy cập không là thuê bao dịch vụ. Có sử dụng nội dung
        //$so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_co_su_dung_noi_dung_sql = 'SELECT COUNT(t1.user_id) FROM tmp_session t1 INNER JOIN tmp_user_package t2 ON t1.user_id <> t2.user_id INNER JOIN tmp_use_content t3 ON t1.user_id = t3.user_id';
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_co_su_dung_noi_dung_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE user_package_status!=1 AND user_id IS NOT NULL';
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_co_su_dung_noi_dung = Yii::$app->db->createCommand($so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_co_su_dung_noi_dung_sql)->queryScalar();

        // Số thuê bao truy cập không là thuê bao dịch vụ. Sử dụng nội dung miễn phí
        //$so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi_sql = 'SELECT COUNT(t1.user_id) FROM tmp_session t1 INNER JOIN tmp_user_package t2 ON t1.user_id <> t2.user_id INNER JOIN tmp_use_user_content t3 ON t1.user_id = t3.user_id';
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE user_package_status!=1 AND price=0 AND user_id IS NOT NULL';
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi = Yii::$app->db->createCommand($so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi_sql)->queryScalar();

        // Số thuê bao truy cập không là thuê bao dịch vụ. Sử dụng nội dung mất phí
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE user_package_status!=1 AND price>0 AND user_id IS NOT NULL';
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi = Yii::$app->db->createCommand($so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi_sql)->queryScalar();

        // Số thuê bao truy cập không là thuê bao dịch vụ. Không sử dụng nội dung
        //$so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_khong_su_dung_noi_dung_sql = 'SELECT COUNT(t1.user_id) FROM tmp_session t1 INNER JOIN tmp_user_package t2 ON t1.user_id <> t2.user_id INNER JOIN tmp_not_use_content t3 ON t1.user_id = t3.user_id';
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_khong_su_dung_noi_dung_sql = 'SELECT COUNT(DISTINCT(S.user_id)) FROM tmp_session AS S
                                                                                        LEFT JOIN tmp_user_content AS C ON S.user_id = C.user_id
                                                                                        WHERE S.user_package_status!=1 AND (C.user_id IS NULL)';
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_khong_su_dung_noi_dung = Yii::$app->db->createCommand($so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_khong_su_dung_noi_dung_sql)->queryScalar();


        // Nhóm thuê bao trừ được cước không truy cập
        //$nhom_thue_bao_tru_duoc_cuoc_khong_truy_cap_sql = 'SELECT COUNT(t1.user_id) FROM tmp_monfee t1 INNER JOIN tmp_session t2 ON t1.user_id <> t2.user_id';
        $nhom_thue_bao_tru_duoc_cuoc_khong_truy_cap_sql = 'SELECT COUNT(DISTINCT(M.user_id)) FROM tmp_monfee AS M LEFT JOIN tmp_session AS S ON S.user_id = M.user_id WHERE S.user_id IS NULL';
        $nhom_thue_bao_tru_duoc_cuoc_khong_truy_cap = Yii::$app->db->createCommand($nhom_thue_bao_tru_duoc_cuoc_khong_truy_cap_sql)->queryScalar();

        // Nhóm thuê bao trừ được cước có sử dụng nội dung
        //$nhom_thue_bao_tru_duoc_cuoc_co_su_dung_noi_dung_sql = 'SELECT COUNT(DISTINCT user_id) FROM user_content WHERE (action = :streaming OR action = :download) AND created_time >= :startDate AND created_time <= :endDate';
        $nhom_thue_bao_tru_duoc_cuoc_co_su_dung_noi_dung_sql = 'SELECT COUNT(DISTINCT(M.user_id)) FROM tmp_monfee AS M LEFT JOIN tmp_user_content AS C ON C.user_id = M.user_id WHERE  C.user_id IS NOT NULL';
        $nhom_thue_bao_tru_duoc_cuoc_co_su_dung_noi_dung = Yii::$app->db->createCommand($nhom_thue_bao_tru_duoc_cuoc_co_su_dung_noi_dung_sql)->queryScalar();


        // Nhóm thuê bao trừ được cước không sử dụng nội dung
        //$nhom_thue_bao_tru_duoc_cuoc_khong_su_dung_noi_dung_sql = 'SELECT  COUNT(DISTINCT user_id) FROM   transaction t1 NATURAL LEFT JOIN user_content t2 WHERE t1.action = :monfee AND t1.created_time >= :startDate AND t1.created_time <= :endDate';
        $nhom_thue_bao_tru_duoc_cuoc_khong_su_dung_noi_dung_sql = 'SELECT COUNT(DISTINCT(M.user_id)) FROM tmp_monfee AS M LEFT JOIN tmp_user_content AS C ON C.user_id = M.user_id
                                                                    LEFT JOIN tmp_session AS S ON S.user_id = M.user_id WHERE  C.user_id IS  NULL AND S.user_id IS NOT NULL';
        $nhom_thue_bao_tru_duoc_cuoc_khong_su_dung_noi_dung = Yii::$app->db->createCommand($nhom_thue_bao_tru_duoc_cuoc_khong_su_dung_noi_dung_sql)->queryScalar();


        $data['channel_all'] = [
            'tong_so_thue_bao_tru_duoc_cuoc' => $tong_so_thue_bao_tru_duoc_cuoc,
            'tong_so_thue_bao_co_thuc_hien_xem_tai' => $tong_so_thue_bao_co_thuc_hien_xem_tai,
            'ty_le_nguoi_dung_thuong_xuyen' => $ty_le_nguoi_dung_thuong_xuyen,
            'so_thue_bao_tru_duoc_cuoc_co_thuc_hien_xem_tai' => $so_thue_bao_tru_duoc_cuoc_co_thuc_hien_xem_tai,
            'so_thue_bao_trong_chu_ky_khuyen_mai_co_thuc_hien_xem_tai' => $so_thue_bao_trong_chu_ky_khuyen_mai_co_thuc_hien_xem_tai,
            'so_thue_bao_chua_dang_ky_xem_tai_mat_phi' => $so_thue_bao_chua_dang_ky_xem_tai_mat_phi,
            'so_thue_bao_chua_dang_ky_xem_tai_mien_phi' => $so_thue_bao_chua_dang_ky_xem_tai_mien_phi,
            'tong_luot_xem_tai_tren_dich_vu' => $tong_luot_xem_tai_tren_dich_vu,
            'tong_luot_xem_tren_dich_vu' => $tong_luot_xem_tren_dich_vu,
            'trung_binh_so_luot_xem_video_tren_1_lan_truy_cap_dich_vu' => $trung_binh_so_luot_xem_video_tren_1_lan_truy_cap_dich_vu,
            'tong_so_luot_truy_cap' => $tong_so_luot_truy_cap,
            'tong_so_thue_bao_truy_cap' => $tong_so_thue_bao_truy_cap,
            'so_thue_bao_truy_cap_la_thue_bao_dich_vu_co_su_dung_noi_dung' => $so_thue_bao_truy_cap_la_thue_bao_dich_vu_co_su_dung_noi_dung,
            'so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi' => $so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi,
            'so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi' => $so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi,
            'so_thue_bao_truy_cap_la_thue_bao_dich_vu_khong_su_dung_noi_dung' => $so_thue_bao_truy_cap_la_thue_bao_dich_vu_khong_su_dung_noi_dung,
            'so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_co_su_dung_noi_dung' => $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_co_su_dung_noi_dung,
            'so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi' => $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi,
            'so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi' => $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi,
            'so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_khong_su_dung_noi_dung' => $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_khong_su_dung_noi_dung,
            'nhom_thue_bao_tru_duoc_cuoc_khong_truy_cap' => $nhom_thue_bao_tru_duoc_cuoc_khong_truy_cap,
            'nhom_thue_bao_tru_duoc_cuoc_co_su_dung_noi_dung' => $nhom_thue_bao_tru_duoc_cuoc_co_su_dung_noi_dung,
            'nhom_thue_bao_tru_duoc_cuoc_khong_su_dung_noi_dung' => $nhom_thue_bao_tru_duoc_cuoc_khong_su_dung_noi_dung,
            'tb_dang_ky_truy_cap' => $tb_dang_ky_truy_cap,
        ];


        /*---------------------------------Thống kê trên wap----------------------------------*/

        // Tổng số thuê bao trừ được cước trên dịch vụ
        //$tong_so_thue_bao_tru_duoc_cuoc_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :monfee AND channel = :channelWap AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_thue_bao_tru_duoc_cuoc_sql = 'SELECT COUNT(DISTINCT user_id) FROM tmp_monfee  ';
        $tong_so_thue_bao_tru_duoc_cuoc = Yii::$app->db->createCommand($tong_so_thue_bao_tru_duoc_cuoc_sql)
            ->queryScalar();

        // Tổng số thuê bao có thực hiện xem/tải
        //$tong_so_thue_bao_co_thuc_hien_xem_tai_sql = 'SELECT (SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :streaming  AND action = :download AND channel = :channelWap AND created_time >= :startDate AND created_time <= :endDate) + (SELECT COUNT(DISTINCT user_id) FROM user_content WHERE (action = :streaming OR action = :download) AND created_time >= :startDate AND created_time <= :endDate)';
        $tong_so_thue_bao_co_thuc_hien_xem_tai_sql = 'SELECT COUNT(DISTINCT user_id) FROM tmp_user_content WHERE channel = :channelWap';
        $tong_so_thue_bao_co_thuc_hien_xem_tai = Yii::$app->db->createCommand($tong_so_thue_bao_co_thuc_hien_xem_tai_sql)
            ->bindParam(':channelWap', $channelWap)->queryScalar();

        // Tỷ lệ người dùng thường xuyên
        $ty_le_nguoi_dung_thuong_xuyen = 0;

        // Số thuê bao trừ được cước có thực hiện xem tải
        //$so_thue_bao_tru_duoc_cuoc_co_thuc_hien_xem_tai_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE (action = :monfee OR action = :streaming OR action = :download) AND channel = :channelWap AND created_time >= :startDate AND created_time <= :endDate';
        $so_thue_bao_tru_duoc_cuoc_co_thuc_hien_xem_tai_sql = 'SELECT  COUNT(DISTINCT(M.user_id)) FROM tmp_monfee AS M
                                                                LEFT JOIN tmp_user_content AS C ON M.user_id = C.user_id
                                                                WHERE C.channel = :channelWap AND C.user_id IS NOT NULL';
        $so_thue_bao_tru_duoc_cuoc_co_thuc_hien_xem_tai = Yii::$app->db->createCommand($so_thue_bao_tru_duoc_cuoc_co_thuc_hien_xem_tai_sql)
            ->bindParam(':channelWap', $channelWap)->queryScalar();

        // Số thuê bao trong chu kỳ khuyến mại có thực hiện xem/tải
        $so_thue_bao_trong_chu_ky_khuyen_mai_co_thuc_hien_xem_tai = 0;

        // Số thuê bao chưa đăng ký xem/tải mất phí
        //$so_thue_bao_chua_dang_ky_xem_tai_mat_phi_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE (package_id = 0 OR package_id is null) AND price > 0 AND (action = :streaming OR action = :download) AND channel = :channelWap AND created_time >= :startDate AND created_time <= :endDate';
        $so_thue_bao_chua_dang_ky_xem_tai_mat_phi_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE channel = :channelWap AND price>0 AND user_package_status!=1';
        $so_thue_bao_chua_dang_ky_xem_tai_mat_phi = Yii::$app->db->createCommand($so_thue_bao_chua_dang_ky_xem_tai_mat_phi_sql)
            ->bindParam(':channelWap', $channelWap)->queryScalar();

        // Số thuê bao chưa đăng ký xem/tải miễn phí
        //$so_thue_bao_chua_dang_ky_xem_tai_mien_phi_sql = 'SELECT COUNT(DISTINCT user_id) FROM user_content WHERE (package_id = 0 OR package_id is null) AND price = 0 AND (action = :streaming OR action = :download) AND channel = :channelWap AND created_time >= :startDate AND created_time <= :endDate';
        $so_thue_bao_chua_dang_ky_xem_tai_mien_phi_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE channel = :channelWap AND price>0 AND user_package_status!=1';
        $so_thue_bao_chua_dang_ky_xem_tai_mien_phi = Yii::$app->db->createCommand($so_thue_bao_chua_dang_ky_xem_tai_mien_phi_sql)
            ->bindParam(':channelWap', $channelWap)->queryScalar();

        // Tổng lượt xem tải trên dịch vụ
        //$tong_luot_xem_tai_tren_dich_vu_sql = 'SELECT (SELECT COUNT(user_id) FROM transaction WHERE (action = :streaming OR action = :download) AND channel = :channelWap AND created_time >= :startDate AND created_time <= :endDate) + (SELECT COUNT(user_id) FROM user_content WHERE (action = :streaming OR action = :download) AND created_time >= :startDate AND created_time <= :endDate)';
        $tong_luot_xem_tai_tren_dich_vu_sql = 'SELECT COUNT(*) FROM tmp_user_content WHERE channel = :channelWap';
        $tong_luot_xem_tai_tren_dich_vu = Yii::$app->db->createCommand($tong_luot_xem_tai_tren_dich_vu_sql)
            ->bindParam(':channelWap', $channelWap)->queryScalar();

        // Tổng lượt xem  trên dịch vụ
        $tong_luot_xem_tren_dich_vu_sql = 'SELECT COUNT(*) FROM tmp_user_content WHERE action = :streaming AND channel = :channelWap';
        $tong_luot_xem_tren_dich_vu = Yii::$app->db->createCommand($tong_luot_xem_tren_dich_vu_sql)
            ->bindParam(':channelWap', $channelWap)->bindParam(':streaming', $streaming)->queryScalar();

        // Trung bình số lượt xem video trên 1 lần truy cập dịch vụ
        $trung_binh_so_luot_xem_video_tren_1_lan_truy_cap_dich_vu = 0;

        // Tổng số lượt truy cập
        //$tong_so_luot_truy_cap_sql = 'SELECT COUNT(id) FROM session WHERE method = :channelWap AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_luot_truy_cap_sql = 'SELECT COUNT(*) FROM tmp_session WHERE method = :channelWap';
        $tong_so_luot_truy_cap = Yii::$app->db->createCommand($tong_so_luot_truy_cap_sql)
            ->bindParam(':channelWap', $channelWap)->queryScalar();

        // Tổng số thuê bao truy cập
        //$tong_so_thue_bao_truy_cap_sql = 'SELECT COUNT(DISTINCT user_id) FROM session WHERE method = :channelWap AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_thue_bao_truy_cap_sql = 'SELECT COUNT(DISTINCT user_id) FROM tmp_session WHERE method = :channelWap';
        $tong_so_thue_bao_truy_cap = Yii::$app->db->createCommand($tong_so_thue_bao_truy_cap_sql)
            ->bindParam(':channelWap', $channelWap)->queryScalar();

        // Thuê bao đăng ký truy cập
        $tb_dang_ky_truy_cap_sql = 'SELECT COUNT(DISTINCT user_id) FROM tmp_session WHERE user_package_status = 1 AND method = :channelWap';
        $tb_dang_ky_truy_cap = Yii::$app->db->createCommand($tb_dang_ky_truy_cap_sql)
            ->bindParam(':channelWap', $channelWap)
            ->queryScalar();

        // Trung bình số lượt xem video trên 1 lần truy cập dịch vụ
        $trung_binh_so_luot_xem_video_tren_1_lan_truy_cap_dich_vu = round($tong_luot_xem_tren_dich_vu / $tong_so_luot_truy_cap, 5);

        // Số thuê bao truy cập là thuê bao dịch vụ. Có sử dụng nội dung
        //$so_thue_bao_truy_cap_la_thue_bao_dich_vu_co_su_dung_noi_dung_sql = 'SELECT COUNT(t1.user_id) FROM tmp_user_package t1 LEFT JOIN tmp_session t2 ON t1.user_id = t2.user_id LEFT JOIN tmp_use_content t3 ON t1.user_id = t3.user_id';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_co_su_dung_noi_dung_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE user_package_status=1 AND channel = :channelWap';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_co_su_dung_noi_dung = Yii::$app->db->createCommand($so_thue_bao_truy_cap_la_thue_bao_dich_vu_co_su_dung_noi_dung_sql)
            ->bindParam(':channelWap', $channelWap)->queryScalar();


        // Số thuê bao truy cập là thuê bao dịch vụ. Sử dụng nội dung miễn phí
        //$so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi_sql = 'SELECT COUNT(t1.user_id) FROM tmp_user_package t1 LEFT JOIN tmp_session t2 ON t1.user_id = t2.user_id LEFT JOIN tmp_use_user_content t3 ON t1.user_id = t3.user_id';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE user_package_status=1 AND price = 0 AND channel = :channelWap ';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi = Yii::$app->db->createCommand($so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi_sql)
            ->bindParam(':channelWap', $channelWap)->queryScalar();

        // Số thuê bao truy cập là thuê bao dịch vụ. Sử dụng nội dung mất phí
        //$so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi_sql = 'SELECT COUNT(t1.user_id) FROM tmp_user_package t1 LEFT JOIN tmp_session t2 ON t1.user_id = t2.user_id LEFT JOIN tmp_use_user_transaction t3 ON t1.user_id = t3.user_id';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE user_package_status=1 AND price>0 AND channel = :channelWap ';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi = Yii::$app->db->createCommand($so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi_sql)
            ->bindParam(':channelWap', $channelWap)->queryScalar();

        // Số thuê bao truy cập là thuê bao dịch vụ. Không sử dụng nội dung
        //$so_thue_bao_truy_cap_la_thue_bao_dich_vu_khong_su_dung_noi_dung_sql = 'SELECT COUNT(t1.user_id) FROM tmp_user_package t1 LEFT JOIN tmp_session t2 ON t1.user_id = t2.user_id LEFT JOIN tmp_not_use_content t3 ON t1.user_id = t3.user_id';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_khong_su_dung_noi_dung_sql = 'SELECT COUNT(DISTINCT(S.user_id)) FROM tmp_session AS S LEFT JOIN tmp_user_content AS C ON S.user_id = C.user_id WHERE S.user_package_status=1 AND C.user_id IS NULL AND S.method = :channelWap  AND C.channel = :channelWap ';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_khong_su_dung_noi_dung = Yii::$app->db->createCommand($so_thue_bao_truy_cap_la_thue_bao_dich_vu_khong_su_dung_noi_dung_sql)
            ->bindParam(':channelWap', $channelWap)->queryScalar();

        // Số thuê bao truy cập không là thuê bao dịch vụ. Có sử dụng nội dung
        //$so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_co_su_dung_noi_dung_sql = 'SELECT COUNT(t1.user_id) FROM tmp_session t1 LEFT JOIN tmp_user_package t2 ON t1.user_id <> t2.user_id LEFT JOIN tmp_use_content t3 ON t1.user_id = t3.user_id';
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_co_su_dung_noi_dung_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE user_package_status!=1 AND price=0 AND user_id IS NOT NULL AND channel = :channelWap';
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_co_su_dung_noi_dung = Yii::$app->db->createCommand($so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_co_su_dung_noi_dung_sql)
            ->bindParam(':channelWap', $channelWap)->queryScalar();

        // Số thuê bao truy cập không là thuê bao dịch vụ. Sử dụng nội dung miễn phí
        //$so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi_sql = 'SELECT COUNT(t1.user_id) FROM tmp_session t1 LEFT JOIN tmp_user_package t2 ON t1.user_id <> t2.user_id LEFT JOIN tmp_use_user_content t3 ON t1.user_id = t3.user_id';
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE user_package_status!=1 AND price=0 AND user_id IS NOT NULL  AND channel = :channelWap ';
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi = Yii::$app->db->createCommand($so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi_sql)
            ->bindParam(':channelWap', $channelWap)->queryScalar();

        // Số thuê bao truy cập không là thuê bao dịch vụ. Sử dụng nội dung mất phí
        //$so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi_sql = 'SELECT COUNT(t1.user_id) FROM tmp_session t1 LEFT JOIN tmp_user_package t2 ON t1.user_id <> t2.user_id LEFT JOIN tmp_use_user_transaction t3 ON t1.user_id = t3.user_id';
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE user_package_status!=1 AND price>0 AND user_id IS NOT NULL  AND channel = :channelWap';
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi = Yii::$app->db->createCommand($so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi_sql)
            ->bindParam(':channelWap', $channelWap)->queryScalar();

        // Số thuê bao truy cập không là thuê bao dịch vụ. Không sử dụng nội dung
        //$so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_khong_su_dung_noi_dung_sql = 'SELECT COUNT(t1.user_id) FROM tmp_session t1 LEFT JOIN tmp_user_package t2 ON t1.user_id <> t2.user_id LEFT JOIN tmp_not_use_content t3 ON t1.user_id = t3.user_id';
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_khong_su_dung_noi_dung_sql = 'SELECT COUNT(DISTINCT(S.user_id)) FROM tmp_session AS S
                                                                                        LEFT JOIN tmp_user_content AS C ON S.user_id = C.user_id
                                                                                        WHERE S.user_package_status!=1 AND (C.user_id IS NULL) AND S.method = :channelWap  AND C.channel = :channelWap ';
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_khong_su_dung_noi_dung = Yii::$app->db->createCommand($so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_khong_su_dung_noi_dung_sql)
            ->bindParam(':channelWap', $channelWap)->queryScalar();


        // Nhóm thuê bao trừ được cước không truy cập
        //$nhom_thue_bao_tru_duoc_cuoc_khong_truy_cap_sql = 'SELECT COUNT(t1.user_id) FROM tmp_monfee t1 LEFT JOIN tmp_session t2 ON t1.user_id <> t2.user_id';
        $nhom_thue_bao_tru_duoc_cuoc_khong_truy_cap_sql = 'SELECT COUNT(DISTINCT(M.user_id)) FROM tmp_monfee AS M LEFT JOIN tmp_session AS S ON S.user_id = M.user_id WHERE S.user_id IS NULL AND S.method = :channelWap';
        $nhom_thue_bao_tru_duoc_cuoc_khong_truy_cap = Yii::$app->db->createCommand($nhom_thue_bao_tru_duoc_cuoc_khong_truy_cap_sql)
            ->bindParam(':channelWap', $channelWap)->queryScalar();

        // Nhóm thuê bao trừ được cước có sử dụng nội dung
        //$nhom_thue_bao_tru_duoc_cuoc_co_su_dung_noi_dung_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE (action = :monfee OR action = :streaming OR action = :download) AND channel = :channelWap AND created_time >= :startDate AND created_time <= :endDate';
        $nhom_thue_bao_tru_duoc_cuoc_co_su_dung_noi_dung_sql = 'SELECT COUNT(DISTINCT(M.user_id)) FROM tmp_monfee AS M LEFT JOIN tmp_user_content AS C ON C.user_id = M.user_id WHERE  C.user_id IS NOT NULL AND C.channel = :channelWap ';
        $nhom_thue_bao_tru_duoc_cuoc_co_su_dung_noi_dung = Yii::$app->db->createCommand($nhom_thue_bao_tru_duoc_cuoc_co_su_dung_noi_dung_sql)
            ->bindParam(':channelWap', $channelWap)->queryScalar();

        // Nhóm thuê bao trừ được cước không sử dụng nội dung
        //$nhom_thue_bao_tru_duoc_cuoc_khong_su_dung_noi_dung_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE (action = :monfee OR (action <> :streaming AND action <> :download)) AND channel = :channelWap AND created_time >= :startDate AND created_time <= :endDate';
        $nhom_thue_bao_tru_duoc_cuoc_khong_su_dung_noi_dung_sql = 'SELECT COUNT(DISTINCT(M.user_id)) FROM tmp_monfee AS M LEFT JOIN tmp_user_content AS C ON C.user_id = M.user_id
                                                                    LEFT JOIN tmp_session AS S ON S.user_id = M.user_id WHERE  C.user_id IS  NULL AND S.user_id IS NOT NULL AND C.channel = :channelWap';
        $nhom_thue_bao_tru_duoc_cuoc_khong_su_dung_noi_dung = Yii::$app->db->createCommand($nhom_thue_bao_tru_duoc_cuoc_khong_su_dung_noi_dung_sql)
            ->bindParam(':channelWap', $channelWap)->queryScalar();

        $data['channel_1'] = [
            'tong_so_thue_bao_tru_duoc_cuoc' => $tong_so_thue_bao_tru_duoc_cuoc,
            'tong_so_thue_bao_co_thuc_hien_xem_tai' => $tong_so_thue_bao_co_thuc_hien_xem_tai,
            'ty_le_nguoi_dung_thuong_xuyen' => $ty_le_nguoi_dung_thuong_xuyen,
            'so_thue_bao_tru_duoc_cuoc_co_thuc_hien_xem_tai' => $so_thue_bao_tru_duoc_cuoc_co_thuc_hien_xem_tai,
            'so_thue_bao_trong_chu_ky_khuyen_mai_co_thuc_hien_xem_tai' => $so_thue_bao_trong_chu_ky_khuyen_mai_co_thuc_hien_xem_tai,
            'so_thue_bao_chua_dang_ky_xem_tai_mat_phi' => $so_thue_bao_chua_dang_ky_xem_tai_mat_phi,
            'so_thue_bao_chua_dang_ky_xem_tai_mien_phi' => $so_thue_bao_chua_dang_ky_xem_tai_mien_phi,
            'tong_luot_xem_tai_tren_dich_vu' => $tong_luot_xem_tai_tren_dich_vu,
            'tong_luot_xem_tren_dich_vu' => $tong_luot_xem_tren_dich_vu,
            'trung_binh_so_luot_xem_video_tren_1_lan_truy_cap_dich_vu' => $trung_binh_so_luot_xem_video_tren_1_lan_truy_cap_dich_vu,
            'tong_so_luot_truy_cap' => $tong_so_luot_truy_cap,
            'tong_so_thue_bao_truy_cap' => $tong_so_thue_bao_truy_cap,
            'so_thue_bao_truy_cap_la_thue_bao_dich_vu_co_su_dung_noi_dung' => $so_thue_bao_truy_cap_la_thue_bao_dich_vu_co_su_dung_noi_dung,
            'so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi' => $so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi,
            'so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi' => $so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi,
            'so_thue_bao_truy_cap_la_thue_bao_dich_vu_khong_su_dung_noi_dung' => $so_thue_bao_truy_cap_la_thue_bao_dich_vu_khong_su_dung_noi_dung,
            'so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_co_su_dung_noi_dung' => $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_co_su_dung_noi_dung,
            'so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi' => $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi,
            'so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi' => $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi,
            'so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_khong_su_dung_noi_dung' => $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_khong_su_dung_noi_dung,
            'nhom_thue_bao_tru_duoc_cuoc_khong_truy_cap' => $nhom_thue_bao_tru_duoc_cuoc_khong_truy_cap,
            'nhom_thue_bao_tru_duoc_cuoc_co_su_dung_noi_dung' => $nhom_thue_bao_tru_duoc_cuoc_co_su_dung_noi_dung,
            'nhom_thue_bao_tru_duoc_cuoc_khong_su_dung_noi_dung' => $nhom_thue_bao_tru_duoc_cuoc_khong_su_dung_noi_dung,
            'tb_dang_ky_truy_cap' => $tb_dang_ky_truy_cap,
        ];


        /*---------------------------------Thống kê trên app----------------------------------*/

        // Tổng số thuê bao trừ được cước trên dịch vụ
        //$tong_so_thue_bao_tru_duoc_cuoc_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :monfee AND channel = :channelApp AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_thue_bao_tru_duoc_cuoc_sql = 'SELECT COUNT(DISTINCT user_id) FROM tmp_monfee  ';
        $tong_so_thue_bao_tru_duoc_cuoc = Yii::$app->db->createCommand($tong_so_thue_bao_tru_duoc_cuoc_sql)
            ->queryScalar();

        // Tổng số thuê bao có thực hiện xem/tải
        //$tong_so_thue_bao_co_thuc_hien_xem_tai_sql = 'SELECT (SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :streaming  AND action = :download AND channel = :channelApp AND created_time >= :startDate AND created_time <= :endDate) + (SELECT COUNT(DISTINCT user_id) FROM user_content WHERE (action = :streaming OR action = :download) AND created_time >= :startDate AND created_time <= :endDate)';
        $tong_so_thue_bao_co_thuc_hien_xem_tai_sql = 'SELECT COUNT(DISTINCT user_id) FROM tmp_user_content WHERE channel = :channelApp';
        $tong_so_thue_bao_co_thuc_hien_xem_tai = Yii::$app->db->createCommand($tong_so_thue_bao_co_thuc_hien_xem_tai_sql)
            ->bindParam(':channelApp', $channelApp)->queryScalar();

        // Tỷ lệ người dùng thường xuyên
        $ty_le_nguoi_dung_thuong_xuyen = 0;

        // Số thuê bao trừ được cước có thực hiện xem tải
        //$so_thue_bao_tru_duoc_cuoc_co_thuc_hien_xem_tai_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE (action = :monfee OR action = :streaming OR action = :download) AND channel = :channelApp AND created_time >= :startDate AND created_time <= :endDate';
        $so_thue_bao_tru_duoc_cuoc_co_thuc_hien_xem_tai_sql = 'SELECT  COUNT(DISTINCT(M.user_id)) FROM tmp_monfee AS M
                                                                LEFT JOIN tmp_user_content AS C ON M.user_id = C.user_id
                                                                WHERE C.channel = :channelApp AND C.user_id IS NOT NULL';
        $so_thue_bao_tru_duoc_cuoc_co_thuc_hien_xem_tai = Yii::$app->db->createCommand($so_thue_bao_tru_duoc_cuoc_co_thuc_hien_xem_tai_sql)
            ->bindParam(':channelApp', $channelApp)->queryScalar();

        // Số thuê bao trong chu kỳ khuyến mại có thực hiện xem/tải
        $so_thue_bao_trong_chu_ky_khuyen_mai_co_thuc_hien_xem_tai = 0;

        // Số thuê bao chưa đăng ký xem/tải mất phí
        //$so_thue_bao_chua_dang_ky_xem_tai_mat_phi_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE (package_id = 0 OR package_id is null) AND price > 0 AND (action = :streaming OR action = :download) AND channel = :channelApp AND created_time >= :startDate AND created_time <= :endDate';
        $so_thue_bao_chua_dang_ky_xem_tai_mat_phi_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE channel = :channelApp AND price>0 AND user_package_status!=1';
        $so_thue_bao_chua_dang_ky_xem_tai_mat_phi = Yii::$app->db->createCommand($so_thue_bao_chua_dang_ky_xem_tai_mat_phi_sql)
            ->bindParam(':channelApp', $channelApp)->queryScalar();

        // Số thuê bao chưa đăng ký xem/tải miễn phí
        //$so_thue_bao_chua_dang_ky_xem_tai_mien_phi_sql = 'SELECT COUNT(DISTINCT user_id) FROM user_content WHERE (package_id = 0 OR package_id is null) AND price = 0 AND (action = :streaming OR action = :download) AND channel = :channelApp AND created_time >= :startDate AND created_time <= :endDate';
        $so_thue_bao_chua_dang_ky_xem_tai_mien_phi_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE channel = :channelApp AND price>0 AND user_package_status!=1';
        $so_thue_bao_chua_dang_ky_xem_tai_mien_phi = Yii::$app->db->createCommand($so_thue_bao_chua_dang_ky_xem_tai_mien_phi_sql)
            ->bindParam(':channelApp', $channelApp)->queryScalar();

        // Tổng lượt xem tải trên dịch vụ
        //$tong_luot_xem_tai_tren_dich_vu_sql = 'SELECT (SELECT COUNT(user_id) FROM transaction WHERE (action = :streaming OR action = :download) AND channel = :channelApp AND created_time >= :startDate AND created_time <= :endDate) + (SELECT COUNT(user_id) FROM user_content WHERE (action = :streaming OR action = :download) AND created_time >= :startDate AND created_time <= :endDate)';
        $tong_luot_xem_tai_tren_dich_vu_sql = 'SELECT COUNT(*) FROM tmp_user_content WHERE channel = :channelApp';
        $tong_luot_xem_tai_tren_dich_vu = Yii::$app->db->createCommand($tong_luot_xem_tai_tren_dich_vu_sql)
            ->bindParam(':channelApp', $channelApp)->queryScalar();

        // Tổng lượt xem  trên dịch vụ
        $tong_luot_xem_tren_dich_vu_sql = 'SELECT COUNT(*) FROM tmp_user_content WHERE action = :streaming AND channel = :channelApp';
        $tong_luot_xem_tren_dich_vu = Yii::$app->db->createCommand($tong_luot_xem_tren_dich_vu_sql)
            ->bindParam(':channelApp', $channelApp)->bindParam(':streaming', $streaming)->queryScalar();

        // Trung bình số lượt xem video trên 1 lần truy cập dịch vụ
        $trung_binh_so_luot_xem_video_tren_1_lan_truy_cap_dich_vu = 0;

        // Tổng số lượt truy cập
        //$tong_so_luot_truy_cap_sql = 'SELECT COUNT(id) FROM session WHERE method = :channelApp AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_luot_truy_cap_sql = 'SELECT COUNT(*) FROM tmp_session WHERE method = :channelApp';
        $tong_so_luot_truy_cap = Yii::$app->db->createCommand($tong_so_luot_truy_cap_sql)
            ->bindParam(':channelApp', $channelApp)->queryScalar();

        // Tổng số thuê bao truy cập
        //$tong_so_thue_bao_truy_cap_sql = 'SELECT COUNT(DISTINCT user_id) FROM session WHERE method = :channelApp AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_thue_bao_truy_cap_sql = 'SELECT COUNT(DISTINCT user_id) FROM tmp_session WHERE method = :channelApp';
        $tong_so_thue_bao_truy_cap = Yii::$app->db->createCommand($tong_so_thue_bao_truy_cap_sql)
            ->bindParam(':channelApp', $channelApp)->queryScalar();

        // Thuê bao đăng ký truy cập
        $tb_dang_ky_truy_cap_sql = 'SELECT COUNT(DISTINCT user_id) FROM tmp_session WHERE user_package_status = 1 AND method = :channelApp';
        $tb_dang_ky_truy_cap = Yii::$app->db->createCommand($tb_dang_ky_truy_cap_sql)
            ->bindParam(':channelApp', $channelApp)
            ->queryScalar();

        // Trung bình số lượt xem video trên 1 lần truy cập dịch vụ
        $trung_binh_so_luot_xem_video_tren_1_lan_truy_cap_dich_vu = ($tong_so_luot_truy_cap != 0)?round($tong_luot_xem_tren_dich_vu / $tong_so_luot_truy_cap, 5):0 ;

        // Số thuê bao truy cập là thuê bao dịch vụ. Có sử dụng nội dung
        //$so_thue_bao_truy_cap_la_thue_bao_dich_vu_co_su_dung_noi_dung_sql = 'SELECT COUNT(t1.user_id) FROM tmp_user_package t1 LEFT JOIN tmp_session t2 ON t1.user_id = t2.user_id LEFT JOIN tmp_use_content t3 ON t1.user_id = t3.user_id';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_co_su_dung_noi_dung_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE user_package_status=1 AND channel = :channelApp';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_co_su_dung_noi_dung = Yii::$app->db->createCommand($so_thue_bao_truy_cap_la_thue_bao_dich_vu_co_su_dung_noi_dung_sql)
            ->bindParam(':channelApp', $channelApp)->queryScalar();


        // Số thuê bao truy cập là thuê bao dịch vụ. Sử dụng nội dung miễn phí
        //$so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi_sql = 'SELECT COUNT(t1.user_id) FROM tmp_user_package t1 LEFT JOIN tmp_session t2 ON t1.user_id = t2.user_id LEFT JOIN tmp_use_user_content t3 ON t1.user_id = t3.user_id';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE user_package_status=1 AND price = 0 AND channel = :channelApp ';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi = Yii::$app->db->createCommand($so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi_sql)
            ->bindParam(':channelApp', $channelApp)->queryScalar();

        // Số thuê bao truy cập là thuê bao dịch vụ. Sử dụng nội dung mất phí
        //$so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi_sql = 'SELECT COUNT(t1.user_id) FROM tmp_user_package t1 LEFT JOIN tmp_session t2 ON t1.user_id = t2.user_id LEFT JOIN tmp_use_user_transaction t3 ON t1.user_id = t3.user_id';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE user_package_status=1 AND price>0 AND channel = :channelApp ';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi = Yii::$app->db->createCommand($so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi_sql)
            ->bindParam(':channelApp', $channelApp)->queryScalar();

        // Số thuê bao truy cập là thuê bao dịch vụ. Không sử dụng nội dung
        //$so_thue_bao_truy_cap_la_thue_bao_dich_vu_khong_su_dung_noi_dung_sql = 'SELECT COUNT(t1.user_id) FROM tmp_user_package t1 LEFT JOIN tmp_session t2 ON t1.user_id = t2.user_id LEFT JOIN tmp_not_use_content t3 ON t1.user_id = t3.user_id';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_khong_su_dung_noi_dung_sql = 'SELECT COUNT(DISTINCT(S.user_id)) FROM tmp_session AS S LEFT JOIN tmp_user_content AS C ON S.user_id = C.user_id WHERE S.user_package_status=1 AND C.user_id IS NULL AND S.method = :channelApp  AND C.channel = :channelApp ';
        $so_thue_bao_truy_cap_la_thue_bao_dich_vu_khong_su_dung_noi_dung = Yii::$app->db->createCommand($so_thue_bao_truy_cap_la_thue_bao_dich_vu_khong_su_dung_noi_dung_sql)
            ->bindParam(':channelApp', $channelApp)->queryScalar();

        // Số thuê bao truy cập không là thuê bao dịch vụ. Có sử dụng nội dung
        //$so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_co_su_dung_noi_dung_sql = 'SELECT COUNT(t1.user_id) FROM tmp_session t1 LEFT JOIN tmp_user_package t2 ON t1.user_id <> t2.user_id LEFT JOIN tmp_use_content t3 ON t1.user_id = t3.user_id';
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_co_su_dung_noi_dung_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE user_package_status!=1 AND price=0 AND user_id IS NOT NULL  AND channel = :channelApp';
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_co_su_dung_noi_dung = Yii::$app->db->createCommand($so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_co_su_dung_noi_dung_sql)
            ->bindParam(':channelApp', $channelApp)->queryScalar();

        // Số thuê bao truy cập không là thuê bao dịch vụ. Sử dụng nội dung miễn phí
        //$so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi_sql = 'SELECT COUNT(t1.user_id) FROM tmp_session t1 LEFT JOIN tmp_user_package t2 ON t1.user_id <> t2.user_id LEFT JOIN tmp_use_user_content t3 ON t1.user_id = t3.user_id';
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE user_package_status!=1 AND price=0 AND user_id IS NOT NULL  AND channel = :channelApp';
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi = Yii::$app->db->createCommand($so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi_sql)
            ->bindParam(':channelApp', $channelApp)->queryScalar();

        // Số thuê bao truy cập không là thuê bao dịch vụ. Sử dụng nội dung mất phí
        //$so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi_sql = 'SELECT COUNT(t1.user_id) FROM tmp_session t1 LEFT JOIN tmp_user_package t2 ON t1.user_id <> t2.user_id LEFT JOIN tmp_use_user_transaction t3 ON t1.user_id = t3.user_id';
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi_sql = 'SELECT COUNT(DISTINCT(user_id)) FROM tmp_user_content WHERE user_package_status!=1 AND price>0 AND user_id IS NOT NULL  AND channel = :channelApp';
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi = Yii::$app->db->createCommand($so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi_sql)
            ->bindParam(':channelApp', $channelApp)->queryScalar();

        // Số thuê bao truy cập không là thuê bao dịch vụ. Không sử dụng nội dung
        //$so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_khong_su_dung_noi_dung_sql = 'SELECT COUNT(t1.user_id) FROM tmp_session t1 LEFT JOIN tmp_user_package t2 ON t1.user_id <> t2.user_id LEFT JOIN tmp_not_use_content t3 ON t1.user_id = t3.user_id';
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_khong_su_dung_noi_dung_sql = 'SELECT COUNT(DISTINCT(S.user_id)) FROM tmp_session AS S
                                                                                        LEFT JOIN tmp_user_content AS C ON S.user_id = C.user_id
                                                                                        WHERE S.user_package_status!=1 AND (C.user_id IS NULL) AND S.method = :channelApp  AND C.channel = :channelApp ';
        $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_khong_su_dung_noi_dung = Yii::$app->db->createCommand($so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_khong_su_dung_noi_dung_sql)
            ->bindParam(':channelApp', $channelApp)->queryScalar();


        // Nhóm thuê bao trừ được cước không truy cập
        //$nhom_thue_bao_tru_duoc_cuoc_khong_truy_cap_sql = 'SELECT COUNT(t1.user_id) FROM tmp_monfee t1 LEFT JOIN tmp_session t2 ON t1.user_id <> t2.user_id';
        $nhom_thue_bao_tru_duoc_cuoc_khong_truy_cap_sql = 'SELECT COUNT(DISTINCT(M.user_id)) FROM tmp_monfee AS M LEFT JOIN tmp_session AS S ON S.user_id = M.user_id WHERE S.user_id IS NULL AND S.method = :channelApp';
        $nhom_thue_bao_tru_duoc_cuoc_khong_truy_cap = Yii::$app->db->createCommand($nhom_thue_bao_tru_duoc_cuoc_khong_truy_cap_sql)
            ->bindParam(':channelApp', $channelApp)->queryScalar();

        // Nhóm thuê bao trừ được cước có sử dụng nội dung
        //$nhom_thue_bao_tru_duoc_cuoc_co_su_dung_noi_dung_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE (action = :monfee OR action = :streaming OR action = :download) AND channel = :channelApp AND created_time >= :startDate AND created_time <= :endDate';
        $nhom_thue_bao_tru_duoc_cuoc_co_su_dung_noi_dung_sql = 'SELECT COUNT(DISTINCT(M.user_id)) FROM tmp_monfee AS M LEFT JOIN tmp_user_content AS C ON C.user_id = M.user_id WHERE  C.user_id IS NOT NULL AND C.channel = :channelApp ';
        $nhom_thue_bao_tru_duoc_cuoc_co_su_dung_noi_dung = Yii::$app->db->createCommand($nhom_thue_bao_tru_duoc_cuoc_co_su_dung_noi_dung_sql)
            ->bindParam(':channelApp', $channelApp)->queryScalar();

        // Nhóm thuê bao trừ được cước không sử dụng nội dung
        //$nhom_thue_bao_tru_duoc_cuoc_khong_su_dung_noi_dung_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE (action = :monfee OR (action <> :streaming AND action <> :download)) AND channel = :channelApp AND created_time >= :startDate AND created_time <= :endDate';
        $nhom_thue_bao_tru_duoc_cuoc_khong_su_dung_noi_dung_sql = 'SELECT COUNT(DISTINCT(M.user_id)) FROM tmp_monfee AS M LEFT JOIN tmp_user_content AS C ON C.user_id = M.user_id
                                                                    LEFT JOIN tmp_session AS S ON S.user_id = M.user_id WHERE  C.user_id IS  NULL AND S.user_id IS NOT NULL AND C.channel = :channelApp';
        $nhom_thue_bao_tru_duoc_cuoc_khong_su_dung_noi_dung = Yii::$app->db->createCommand($nhom_thue_bao_tru_duoc_cuoc_khong_su_dung_noi_dung_sql)
            ->bindParam(':channelApp', $channelApp)->queryScalar();


        $data['channel_2'] = [
            'tong_so_thue_bao_tru_duoc_cuoc' => $tong_so_thue_bao_tru_duoc_cuoc,
            'tong_so_thue_bao_co_thuc_hien_xem_tai' => $tong_so_thue_bao_co_thuc_hien_xem_tai,
            'ty_le_nguoi_dung_thuong_xuyen' => $ty_le_nguoi_dung_thuong_xuyen,
            'so_thue_bao_tru_duoc_cuoc_co_thuc_hien_xem_tai' => $so_thue_bao_tru_duoc_cuoc_co_thuc_hien_xem_tai,
            'so_thue_bao_trong_chu_ky_khuyen_mai_co_thuc_hien_xem_tai' => $so_thue_bao_trong_chu_ky_khuyen_mai_co_thuc_hien_xem_tai,
            'so_thue_bao_chua_dang_ky_xem_tai_mat_phi' => $so_thue_bao_chua_dang_ky_xem_tai_mat_phi,
            'so_thue_bao_chua_dang_ky_xem_tai_mien_phi' => $so_thue_bao_chua_dang_ky_xem_tai_mien_phi,
            'tong_luot_xem_tai_tren_dich_vu' => $tong_luot_xem_tai_tren_dich_vu,
            'tong_luot_xem_tren_dich_vu' => $tong_luot_xem_tren_dich_vu,
            'trung_binh_so_luot_xem_video_tren_1_lan_truy_cap_dich_vu' => $trung_binh_so_luot_xem_video_tren_1_lan_truy_cap_dich_vu,
            'tong_so_luot_truy_cap' => $tong_so_luot_truy_cap,
            'tong_so_thue_bao_truy_cap' => $tong_so_thue_bao_truy_cap,
            'so_thue_bao_truy_cap_la_thue_bao_dich_vu_co_su_dung_noi_dung' => $so_thue_bao_truy_cap_la_thue_bao_dich_vu_co_su_dung_noi_dung,
            'so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi' => $so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi,
            'so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi' => $so_thue_bao_truy_cap_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi,
            'so_thue_bao_truy_cap_la_thue_bao_dich_vu_khong_su_dung_noi_dung' => $so_thue_bao_truy_cap_la_thue_bao_dich_vu_khong_su_dung_noi_dung,
            'so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_co_su_dung_noi_dung' => $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_co_su_dung_noi_dung,
            'so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi' => $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mien_phi,
            'so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi' => $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_su_dung_noi_dung_mat_phi,
            'so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_khong_su_dung_noi_dung' => $so_thue_bao_truy_cap_khong_la_thue_bao_dich_vu_khong_su_dung_noi_dung,
            'nhom_thue_bao_tru_duoc_cuoc_khong_truy_cap' => $nhom_thue_bao_tru_duoc_cuoc_khong_truy_cap,
            'nhom_thue_bao_tru_duoc_cuoc_co_su_dung_noi_dung' => $nhom_thue_bao_tru_duoc_cuoc_co_su_dung_noi_dung,
            'nhom_thue_bao_tru_duoc_cuoc_khong_su_dung_noi_dung' => $nhom_thue_bao_tru_duoc_cuoc_khong_su_dung_noi_dung,
            'tb_dang_ky_truy_cap' => $tb_dang_ky_truy_cap,
        ];

        $data = serialize($data);
        if ($type == 1) {
            $sql = 'INSERT INTO daily_report SET date = :startDate, type = 5, data = :data ON DUPLICATE KEY UPDATE data = :data';
        } else if ($type == 2) {
            $sql = 'INSERT INTO daily_report SET date = :startDate, type = 6, data = :data ON DUPLICATE KEY UPDATE data = :data';
        } else {
            $sql = 'INSERT INTO daily_report SET date = :startDate, type = 5, data = :data ON DUPLICATE KEY UPDATE data = :data';
        }

        Yii::$app->db->createCommand($sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':data', $data)
            ->execute();
    }

    /**
     * @throws \yii\db\Exception
     * Thống kê quảng cáo (type = 7)
     */
    public function Adv($date = null)
    {
        $streaming = WATCH;
        $download = DOWNLOAD;
        $subscribe = SUBSCRIBE;
        $unsubscribe = UNSUBSCRIBE;
        $monfee = MONFEE;
        $channelCron = CHANNEL_CRON;
        $channelSystem = CHANNEL_SYSTEM;
        $channelSms = CHANNEL_SMS;
        $channelWap = CHANNEL_WAP;

        $data = array();

        if(!empty($date)){
            $startDate = $date;
            $endDate = $startDate . ' 23:59:59';
        }else {
            $startDate = date('Y-m-d', strtotime('-1 day'));
            $endDate = $startDate . ' 23:59:59';
        }

        $listChannel = AdsLink::find()->where(['status' => 1])->all();
        if (!empty($listChannel)) {
            foreach ($listChannel as $channel) {
                $channelTitle = $channel->source;

                //Tong hop cac thue bao dk qua nguon quang cao
                $sql = "INSERT INTO tmp_user_admob(msisdn,source_ads) SELECT distinct(user_id), source FROM transaction
                WHERE action = :subscribe AND created_time >= :startDate AND created_time <= :endDate
                AND source = :channelTitle
                ON DUPLICATE KEY UPDATE tmp_user_admob.source_ads = tmp_user_admob.source_ads";
                Yii::$app->db->createCommand($sql)
                    ->bindParam(':subscribe', $subscribe)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->execute();

                // Tổng số lượt click
                $tong_so_luot_click_sql = 'SELECT COUNT(id) FROM session WHERE source = :channelTitle AND created_time >= :startDate AND created_time <= :endDate';
                $tong_so_luot_click = Yii::$app->db->createCommand($tong_so_luot_click_sql)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->queryScalar();

                // Tổng số lượt click không trùng ip
                $tong_so_luot_click_khong_trung_ip_sql = 'SELECT COUNT(DISTINCT ip) FROM session WHERE source = :channelTitle AND created_time >= :startDate AND created_time <= :endDate';
                $tong_so_luot_click_khong_trung_ip = Yii::$app->db->createCommand($tong_so_luot_click_khong_trung_ip_sql)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->queryScalar();

                // Số lượt click nhận diện được
                $so_luot_click_nhan_dien_duoc_sql = 'SELECT COUNT(user_id) FROM session WHERE source = :channelTitle AND user_id IS NOT NULL AND created_time >= :startDate AND created_time <= :endDate';
                $so_luot_click_nhan_dien_duoc = Yii::$app->db->createCommand($so_luot_click_nhan_dien_duoc_sql)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->queryScalar();

                // Số lươt click nhận diện được không trùng ip
                $so_luot_click_nhan_dien_duoc_khong_trung_ip_sql = 'SELECT COUNT(DISTINCT ip) FROM session WHERE source = :channelTitle AND user_id IS NOT NULL AND created_time >= :startDate AND created_time <= :endDate';
                $so_luot_click_nhan_dien_duoc_khong_trung_ip = Yii::$app->db->createCommand($so_luot_click_nhan_dien_duoc_khong_trung_ip_sql)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->queryScalar();

                // Tổng số lượt đăng ký
                $tong_so_luot_dang_ky_sql = 'SELECT (SELECT COUNT(user_id) 
                FROM transaction 
                WHERE source = :channelTitle 
                AND action = :subscribe 
                AND created_time >= :startDate 
                AND created_time <= :endDate) + (SELECT COUNT(user_id) 
                FROM user_content 
                WHERE source = :channelTitle 
                AND action = :subscribe 
                AND created_time >= :startDate 
                AND created_time <= :endDate)';
                $tong_so_luot_dang_ky = Yii::$app->db->createCommand($tong_so_luot_dang_ky_sql)
                    ->bindParam(':subscribe', $subscribe)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->queryScalar();

                // Tổng số lượt đăng ký miễn phí
                $tong_so_luot_dang_ky_mien_phi_sql = 'SELECT COUNT(user_id) FROM user_content WHERE source = :channelTitle AND action = :subscribe AND price = 0 AND created_time >= :startDate AND created_time <= :endDate';
                $tong_so_luot_dang_ky_mien_phi = Yii::$app->db->createCommand($tong_so_luot_dang_ky_mien_phi_sql)
                    ->bindParam(':subscribe', $subscribe)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->queryScalar();

                // Số lượt hủy
                $so_luot_huy_sql = 'SELECT COUNT(t.user_id) 
                FROM transaction t
                INNER JOIN user_package up ON up.user_id = t.user_id
                WHERE up.source = :channelTitle 
                AND t.action = :unsubscribe 
                AND t.created_time >= :startDate 
                AND t.created_time <= :endDate';
                $so_luot_huy = Yii::$app->db->createCommand($so_luot_huy_sql)
                    ->bindParam(':unsubscribe', $unsubscribe)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->queryScalar();

                // Số lượt tự hủy
                $so_luot_tu_huy_sql = 'SELECT COUNT(user_id) 
                FROM transaction t
                INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id
                WHERE ut.source_ads = :channelTitle 
                AND t.action = :unsubscribe AND channel <> :channelCron 
                AND t.created_time >= :startDate 
                AND t.created_time <= :endDate';
                $so_luot_tu_huy = Yii::$app->db->createCommand($so_luot_tu_huy_sql)
                    ->bindParam(':unsubscribe', $unsubscribe)
                    ->bindParam(':channelCron', $channelCron)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->queryScalar();

                // Số lượt gia hạn
                $so_luot_gia_han_sql = 'SELECT COUNT(user_id) 
                FROM transaction t
                INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id 
                WHERE ut.source_ads = :channelTitle 
                AND t.action = :monfee 
                AND t.created_time >= :startDate 
                AND t.created_time <= :endDate';
                $so_luot_gia_han = Yii::$app->db->createCommand($so_luot_gia_han_sql)
                    ->bindParam(':monfee', $monfee)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->queryScalar();

                // Số lượt xem tải
                $so_luot_xem_tai_sql = 'SELECT (SELECT COUNT(user_id) 
                FROM transaction t
                INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id
                WHERE ut.source_ads = :channelTitle 
                AND (t.action = :streaming OR t.action = :download) 
                AND t.created_time >= :startDate 
                AND t.created_time <= :endDate) + (SELECT COUNT(user_id) FROM user_content WHERE source = :channelTitle 
                AND (action = :streaming OR action = :download) 
                AND created_time >= :startDate 
                AND created_time <= :endDate)';
                $so_luot_xem_tai = Yii::$app->db->createCommand($so_luot_xem_tai_sql)
                    ->bindParam(':streaming', $streaming)
                    ->bindParam(':download', $download)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->queryScalar();

                // Số lượt xem của thuê bao đăng ký
                $so_luot_xem_cua_thue_bao_dang_ky_sql = 'SELECT (SELECT COUNT(user_id) 
                FROM transaction t
                INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id
                WHERE ut.source_ads = :channelTitle 
                AND t.package_id > 0 
                AND (t.action = :streaming OR t.action = :download) 
                AND t.created_time >= :startDate 
                AND t.created_time <= :endDate) + (SELECT COUNT(user_id) FROM user_content WHERE source = :channelTitle 
                AND package_id > 0 
                AND (action = :streaming OR action = :download) 
                AND created_time >= :startDate AND created_time <= :endDate)';
                $so_luot_xem_cua_thue_bao_dang_ky = Yii::$app->db->createCommand($so_luot_xem_cua_thue_bao_dang_ky_sql)
                    ->bindParam(':streaming', $streaming)
                    ->bindParam(':download', $download)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->queryScalar();

                //so_thue_bao_dang_ky_mien_phi
                $sql = "SELECT COUNT(distinct(user_id)) AS total
				FROM transaction
				WHERE action = :action 
				AND price = 0
				AND created_time >= :startDate 
				AND created_time <= :endDate
				AND source =:channelTitle";
                $so_thue_bao_dang_ky_mien_phi = Yii::$app->db->createCommand($sql)
                    ->bindParam(':action', $subscribe)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->queryScalar();

                // số thuê bao đăng ký
                $sql = "SELECT COUNT(distinct(user_id)) AS total
				FROM transaction
				WHERE action = :action 
				AND created_time >= :startDate 
				AND created_time <= :endDate
				AND source =:channelTitle";
                $so_thue_bao_dang_ky = Yii::$app->db->createCommand($sql)
                    ->bindParam(':action', $subscribe)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->queryScalar();

                // số thuê bao đăng ký qua sms
                $sql = "SELECT COUNT(distinct(user_id)) AS total
				FROM transaction
				WHERE action = :action 
				AND created_time >= :startDate 
				AND created_time <= :endDate
				AND channel = :channel
				AND source =:channelTitle";
                $so_thue_bao_dk_sms = Yii::$app->db->createCommand($sql)
                    ->bindParam(':action', $subscribe)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->bindParam(':channel', $channelSms)
                    ->queryScalar();

                // số thuê bao đăng ký qua wap
                $so_thue_bao_dk_wap = Yii::$app->db->createCommand($sql)
                    ->bindParam(':action', $subscribe)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->bindParam(':channel', $channelWap)
                    ->queryScalar();

                //so_thue_bao_bi_huy
                $sql = "SELECT COUNT(t.user_id) AS total
				FROM transaction t
				INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id
				WHERE ut.source_ads = :channelTitle
				AND t.action = :action 
				AND t.channel = :channel 
				AND t.created_time >= :startDate
				AND t.created_time <= :endDate";
                $so_thue_bao_bi_huy = Yii::$app->db->createCommand($sql)
                    ->bindParam(':action', $unsubscribe)
                    ->bindParam(':channel', $channelSystem)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->queryScalar();


                //so_thue_bao_tu_huy
                $sql = "SELECT COUNT(t.user_id) AS total
				FROM transaction t
				INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id
				WHERE ut.source_ads = :channelTitle
				AND t.action = :action 
				AND t.channel != :channel 
				AND t.created_time >= :startDate
				AND t.created_time <= :endDate";
                $so_thue_bao_tu_huy = Yii::$app->db->createCommand($sql)
                    ->bindParam(':action', $unsubscribe)
                    ->bindParam(':channel', $channelSystem)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->queryScalar();


                // Doanh thu đăng ký
                $doanh_thu_dang_ky_sql = 'SELECT sum(price) AS total
                    FROM transaction t
                    INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id
                    WHERE t.action = :action
                    AND t.price > 0 
                    AND t.created_time >= :startDate 
                    AND t.created_time < :endDate
                    AND ut.source_ads =:channelTitle';
                $doanh_thu_dang_ky = Yii::$app->db->createCommand($doanh_thu_dang_ky_sql)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->bindParam(':action', $subscribe)
                    ->queryScalar();

                // Doanh thu gia hạn
                $doanh_thu_gia_han = Yii::$app->db->createCommand($doanh_thu_dang_ky_sql)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->bindParam(':action', $monfee)
                    ->queryScalar();

                // Tổng doanh thu
                $tong_doanh_thu_sql = 'SELECT COALESCE(SUM(price), 0) 
                FROM transaction t 
                INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id 
                WHERE ut.source_ads = :channelTitle 
                AND t.price > 0 
                AND t.created_time >= :startDate 
                AND t.created_time <= :endDate';
                $tong_doanh_thu = Yii::$app->db->createCommand($tong_doanh_thu_sql)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->queryScalar();

                $data['source_' . $channelTitle] = [
                    'tong_so_luot_click' => $tong_so_luot_click,
                    'tong_so_luot_click_khong_trung_ip' => $tong_so_luot_click_khong_trung_ip,
                    'so_luot_click_nhan_dien_duoc' => $so_luot_click_nhan_dien_duoc,
                    'so_luot_click_nhan_dien_duoc_khong_trung_ip' => $so_luot_click_nhan_dien_duoc_khong_trung_ip,
                    'tong_so_luot_dang_ky' => $tong_so_luot_dang_ky,
                    'tong_so_luot_dang_ky_mien_phi' => $tong_so_luot_dang_ky_mien_phi,
                    'so_luot_huy' => $so_luot_huy,
                    'so_luot_tu_huy' => $so_luot_tu_huy,
                    'so_luot_gia_han' => $so_luot_gia_han,
                    'so_luot_xem_tai' => $so_luot_xem_tai,
                    'so_luot_xem_cua_thue_bao_dang_ky' => $so_luot_xem_cua_thue_bao_dang_ky,
                    'doanh_thu_gia_han' => $doanh_thu_gia_han,
                    'doanh_thu_dang_ky' => $doanh_thu_dang_ky,
                    'so_thue_bao_dang_ky_mien_phi' => $so_thue_bao_dang_ky_mien_phi,
                    'so_thue_bao_dang_ky' => $so_thue_bao_dang_ky,
                    'so_thue_bao_bi_huy' => $so_thue_bao_bi_huy,
                    'so_thue_bao_tu_huy' => $so_thue_bao_tu_huy,
                    'tong_doanh_thu' => $tong_doanh_thu,
                    'so_thue_bao_dk_sms' => $so_thue_bao_dk_sms,
                    'so_thue_bao_dk_wap' => $so_thue_bao_dk_wap,
                ];
            }
        }

        //Tính tất cả
        // Tổng số lượt click
        $tong_so_luot_click_sql = 'SELECT COUNT(id) FROM session WHERE source IS NOT NULL AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_luot_click = Yii::$app->db->createCommand($tong_so_luot_click_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng số lượt click không trùng ip
        $tong_so_luot_click_khong_trung_ip_sql = 'SELECT COUNT(DISTINCT ip) FROM session WHERE source IS NOT NULL AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_luot_click_khong_trung_ip = Yii::$app->db->createCommand($tong_so_luot_click_khong_trung_ip_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Số lượt click nhận diện được
        $so_luot_click_nhan_dien_duoc_sql = 'SELECT COUNT(user_id) FROM session WHERE source IS NOT NULL AND user_id IS NOT NULL AND created_time >= :startDate AND created_time <= :endDate';
        $so_luot_click_nhan_dien_duoc = Yii::$app->db->createCommand($so_luot_click_nhan_dien_duoc_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Số lươt click nhận diện được không trùng ip
        $so_luot_click_nhan_dien_duoc_khong_trung_ip_sql = 'SELECT COUNT(DISTINCT ip) FROM session WHERE source IS NOT NULL AND source <> "" AND user_id IS NOT NULL AND created_time >= :startDate AND created_time <= :endDate';
        $so_luot_click_nhan_dien_duoc_khong_trung_ip = Yii::$app->db->createCommand($so_luot_click_nhan_dien_duoc_khong_trung_ip_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng số lượt đăng ký
        $tong_so_luot_dang_ky_sql = 'SELECT (SELECT COUNT(user_id) 
        FROM transaction t
        INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id 
        WHERE t.source IN (SELECT DISTINCT(source) From ads_link) 
        AND t.action >= :subscribe 
        AND t.created_time >= :startDate 
        AND t.created_time <= :endDate) + (SELECT COUNT(user_id) 
        FROM user_content 
        WHERE 
        source IN (SELECT DISTINCT(source) From ads_link) 
        AND action = :subscribe 
        AND created_time >= :startDate 
        AND created_time <= :endDate)';
        $tong_so_luot_dang_ky = Yii::$app->db->createCommand($tong_so_luot_dang_ky_sql)
            ->bindParam(':subscribe', $subscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng số lượt đăng ký miễn phí
        $tong_so_luot_dang_ky_mien_phi_sql = 'SELECT COUNT(user_id) FROM user_content WHERE source IS NOT NULL AND source <> "" AND action = :subscribe AND price = 0 AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_luot_dang_ky_mien_phi = Yii::$app->db->createCommand($tong_so_luot_dang_ky_mien_phi_sql)
            ->bindParam(':subscribe', $subscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Số lượt hủy
        $so_luot_huy_sql = 'SELECT COUNT(user_id) 
        FROM transaction t
        INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id 
        WHERE ut.source_ads IN (SELECT DISTINCT(source) From ads_link)  
        AND t.action = :unsubscribe 
        AND t.created_time >= :startDate 
        AND t.created_time <= :endDate';
        $so_luot_huy = Yii::$app->db->createCommand($so_luot_huy_sql)
            ->bindParam(':unsubscribe', $unsubscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Số lượt tự hủy
        $so_luot_tu_huy_sql = 'SELECT COUNT(user_id) 
        FROM transaction t
        INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id 
        WHERE ut.source_ads IN (SELECT DISTINCT(source) From ads_link) 
        AND t.action = :unsubscribe 
        AND t.channel <> :channelCron 
        AND t.created_time >= :startDate 
        AND t.created_time <= :endDate';
        $so_luot_tu_huy = Yii::$app->db->createCommand($so_luot_tu_huy_sql)
            ->bindParam(':unsubscribe', $unsubscribe)
            ->bindParam(':channelCron', $channelCron)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Số lượt gia hạn
        $so_luot_gia_han_sql = 'SELECT COUNT(user_id) 
        FROM transaction t
        INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id 
        WHERE ut.source_ads IN (SELECT DISTINCT(source) From ads_link) 
        AND t.action = :monfee 
        AND t.created_time >= :startDate 
        AND t.created_time <= :endDate';
        $so_luot_gia_han = Yii::$app->db->createCommand($so_luot_gia_han_sql)
            ->bindParam(':monfee', $monfee)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Số lượt xem tải
        $so_luot_xem_tai_sql = 'SELECT (SELECT COUNT(user_id) 
        FROM transaction t
        INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id 
        WHERE ut.source_ads IN (SELECT DISTINCT(source) From ads_link) 
        AND (t.action = :streaming OR t.action = :download) 
        AND t.created_time >= :startDate 
        AND t.created_time <= :endDate) + (SELECT COUNT(user_id) 
        FROM user_content WHERE source IS NOT NULL 
        AND (action = :streaming OR action = :download) 
        AND created_time >= :startDate 
        AND created_time <= :endDate)';
        $so_luot_xem_tai = Yii::$app->db->createCommand($so_luot_xem_tai_sql)
            ->bindParam(':streaming', $streaming)
            ->bindParam(':download', $download)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Số lượt xem của thuê bao đăng ký
        $so_luot_xem_cua_thue_bao_dang_ky_sql = 'SELECT (SELECT COUNT(user_id) 
        FROM transaction t
        INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id 
        WHERE ut.source_ads IN (SELECT DISTINCT(source) From ads_link) 
        AND t.package_id > 0 
        AND (t.action = :streaming OR t.action = :download) 
        AND t.created_time >= :startDate 
        AND t.created_time <= :endDate) + (SELECT COUNT(user_id) 
        FROM user_content 
        WHERE source IS NOT NULL 
        AND package_id > 0 
        AND (action = :streaming OR action = :download) 
        AND created_time >= :startDate 
        AND created_time <= :endDate)';
        $so_luot_xem_cua_thue_bao_dang_ky = Yii::$app->db->createCommand($so_luot_xem_cua_thue_bao_dang_ky_sql)
            ->bindParam(':streaming', $streaming)
            ->bindParam(':download', $download)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // doanh thu dang ky tất cat
        $doanh_thu_dang_ky_sql = 'SELECT COALESCE(SUM(price), 0) 
        FROM transaction t
        INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id 
        WHERE ut.source_ads IN (SELECT DISTINCT(source) From ads_link)
        AND t.price > 0 
        AND t.action = :action 
        AND t.created_time >= :startDate 
        AND t.created_time <= :endDate';
        $doanh_thu_dang_ky = Yii::$app->db->createCommand($doanh_thu_dang_ky_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->bindParam(':action', $subscribe)
            ->queryScalar();

        // doanh thu gia hạn tất cả
        $doanh_thu_gia_han = Yii::$app->db->createCommand($doanh_thu_dang_ky_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->bindParam(':action', $monfee)
            ->queryScalar();

        // Tổng doanh thu
        $tong_doanh_thu_sql = 'SELECT COALESCE(SUM(price), 0) 
        FROM transaction t
        INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id 
        WHERE ut.source_ads IN (SELECT DISTINCT(source) From ads_link)
        AND t.price > 0 
        AND t.created_time >= :startDate 
        AND t.created_time <= :endDate';
        $tong_doanh_thu = Yii::$app->db->createCommand($tong_doanh_thu_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        //so_thue_bao_dang_ky_mien_phi
        $sql = "SELECT COUNT(distinct(t.user_id)) AS total
				FROM transaction t
				INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id 
                WHERE ut.source_ads IN (SELECT DISTINCT(source) From ads_link)
				AND t.action = :action 
				AND t.price = 0
				AND t.created_time >= :startDate 
				AND t.created_time <= :endDate";
        $so_thue_bao_dang_ky_mien_phi = Yii::$app->db->createCommand($sql)
            ->bindParam(':action', $subscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        $sql = "SELECT COUNT(distinct(t.user_id)) AS total
				FROM transaction t
				INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id 
                WHERE ut.source_ads IN (SELECT DISTINCT(source) From ads_link)
				AND t.action = :action 
				AND t.created_time >= :startDate 
				AND t.created_time <= :endDate";
        $so_thue_bao_dang_ky = Yii::$app->db->createCommand($sql)
            ->bindParam(':action', $subscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        //Số tb đăng ký qua sms
        $sql = "SELECT COUNT(distinct(t.user_id)) AS total
				FROM transaction t
				INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id 
                WHERE ut.source_ads IN (SELECT DISTINCT(source) From ads_link)
				AND t.action = :action 
				AND t.channel = :channel 
				AND t.created_time >= :startDate 
				AND t.created_time <= :endDate";
        $so_thue_bao_dk_sms = Yii::$app->db->createCommand($sql)
            ->bindParam(':action', $subscribe)
            ->bindParam(':channel', $channelSms)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        $so_thue_bao_dk_wap = Yii::$app->db->createCommand($sql)
            ->bindParam(':action', $subscribe)
            ->bindParam(':channel', $channelWap)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        //so_thue_bao_bi_huy
        $sql = "SELECT COUNT(distinct(t.user_id)) AS total
				FROM transaction t
				INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id 
                WHERE ut.source_ads IN (SELECT DISTINCT(source) From ads_link)
				AND t.action = :action 
				AND t.channel = :channel 
				AND t.created_time >= :startDate
				AND t.created_time <= :endDate";
        $so_thue_bao_bi_huy = Yii::$app->db->createCommand($sql)
            ->bindParam(':action', $unsubscribe)
            ->bindParam(':channel', $channelSystem)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();


        //so_thue_bao_tu_huy
        $sql = "SELECT COUNT(distinct(t.user_id)) AS total
				FROM transaction t
				INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id 
                WHERE ut.source_ads IN (SELECT DISTINCT(source) From ads_link)
				AND t.action = :action 
				AND t.channel != :channel 
				AND t.created_time >= :startDate
				AND t.created_time <= :endDate";
        $so_thue_bao_tu_huy = Yii::$app->db->createCommand($sql)
            ->bindParam(':action', $unsubscribe)
            ->bindParam(':channel', $channelSystem)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        $data['source_all'] = [
            'tong_so_luot_click' => $tong_so_luot_click,
            'tong_so_luot_click_khong_trung_ip' => $tong_so_luot_click_khong_trung_ip,
            'so_luot_click_nhan_dien_duoc' => $so_luot_click_nhan_dien_duoc,
            'so_luot_click_nhan_dien_duoc_khong_trung_ip' => $so_luot_click_nhan_dien_duoc_khong_trung_ip,
            'tong_so_luot_dang_ky' => $tong_so_luot_dang_ky,
            'tong_so_luot_dang_ky_mien_phi' => $tong_so_luot_dang_ky_mien_phi,
            'so_luot_huy' => $so_luot_huy,
            'so_luot_tu_huy' => $so_luot_tu_huy,
            'so_luot_gia_han' => $so_luot_gia_han,
            'so_luot_xem_tai' => $so_luot_xem_tai,
            'so_luot_xem_cua_thue_bao_dang_ky' => $so_luot_xem_cua_thue_bao_dang_ky,
            'doanh_thu_dang_ky' => $doanh_thu_dang_ky,
            'doanh_thu_gia_han' => $doanh_thu_gia_han,
            'so_thue_bao_dang_ky_mien_phi' => $so_thue_bao_dang_ky_mien_phi,
            'so_thue_bao_dang_ky' => $so_thue_bao_dang_ky,
            'so_thue_bao_bi_huy' => $so_thue_bao_bi_huy,
            'so_thue_bao_tu_huy' => $so_thue_bao_tu_huy,
            'so_thue_bao_dk_sms' => $so_thue_bao_dk_sms,
            'so_thue_bao_dk_wap' => $so_thue_bao_dk_wap,
            'tong_doanh_thu' => $tong_doanh_thu
        ];

        $data = serialize($data);
        $sql = 'INSERT INTO daily_report SET date = :startDate, type = 7, data = :data ON DUPLICATE KEY UPDATE data = :data';

        Yii::$app->db->createCommand($sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':data', $data)
            ->execute();
    }


    /**
     * @throws \yii\db\Exception
     * Thống kê chi tiết giao dịch (type = 8)
     */
    public function DailyReportTransaction()
    {
        $streaming = WATCH;
        $download = DOWNLOAD;
        $subscribe = SUBSCRIBE;
        $monfee = MONFEE;
        $retry_extend = ARREARS;
        $package = 1; // goi Vclip 7 ngay

        $luot_xem = array();
        $luot_tai = array();
        $luot_phat = array();
        $luot_phat_sinh_cuoc = array();

        $startDate = date('Y-m-d', strtotime('-1 day'));
        $endDate = $startDate . ' 23:59:59';

        $dateNow = date('Y-m-d', time());
        $firstMonthNow = date('Y-m-01', time());
        /*-------------------------------------Thống kê của toàn dịch vụ---------------------------*/
        // Nhóm thuê bao đăng ký
        //Số thuê bao đăng ký miễn phí
        $so_thue_bao_dang_ky_mien_phi_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :subscribe AND  price = 0 AND package_id > 0 AND created_time >= :startDate AND created_time <= :endDate';
        $so_thue_bao_dang_ky_mien_phi = Yii::$app->db->createCommand($so_thue_bao_dang_ky_mien_phi_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->bindParam(':subscribe', $subscribe)
            ->queryScalar();

        //Số thuê bao đăng ký gói cước tuần 5000đ
        $so_thue_bao_dang_ky_goi_cuoc_tuan_5000_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action = :subscribe AND  price = 5000 AND package_id = :package AND created_time >= :startDate AND created_time <= :endDate';
        $so_thue_bao_dang_ky_goi_cuoc_tuan_5000 = Yii::$app->db->createCommand($so_thue_bao_dang_ky_goi_cuoc_tuan_5000_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->bindParam(':subscribe', $subscribe)
            ->bindParam(':package', $package)
            ->queryScalar();

        //Số thuê bao gia hạn gói cước tuần 5000đ
        $so_thue_bao_gia_han_goi_cuoc_tuan_5000_sql = 'SELECT COUNT(DISTINCT user_id) FROM transaction WHERE action IN (:monfee,:retry_extend) AND package_id = :package AND created_time >= :startDate AND created_time <= :endDate';
        $so_thue_bao_gia_han_goi_cuoc_tuan_5000 = Yii::$app->db->createCommand($so_thue_bao_gia_han_goi_cuoc_tuan_5000_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->bindParam(':retry_extend', $retry_extend)
            ->bindParam(':monfee', $monfee)
            ->bindParam(':package', $package)
            ->queryScalar();

        //Số lượt xem video toan dich vu
        $so_luot_xem_video_sql = 'SELECT price ,COALESCE(COUNT(id), 0) AS sum FROM user_content WHERE action=:streaming AND created_time >= :startDate AND created_time <= :endDate GROUP BY price';
        $so_luot_xem_video = Yii::$app->db->createCommand($so_luot_xem_video_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->bindParam(':streaming', $streaming)
            ->queryAll();
        foreach ($so_luot_xem_video as $item) {
            $luot_xem[$item['price']] = ($item['sum']) ? $item['sum'] : 0;
        }

        //Số lượt tải video toan dich vu
        $so_luot_tai_video_sql = 'SELECT price ,COALESCE(COUNT(id), 0) AS sum FROM user_content WHERE action=:download AND created_time >= :startDate AND created_time <= :endDate GROUP BY price';
        $so_luot_tai_video = Yii::$app->db->createCommand($so_luot_tai_video_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->bindParam(':download', $download)
            ->queryAll();
        foreach ($so_luot_tai_video as $item) {
            $luot_tai[$item['price']] = ($item['sum']) ? $item['sum'] : 0;
        }
        $luot_tang[0] = 0;
        $luot_tang[5000] = 0;

        //Số lượt phat sinh cuoc
        $so_luot_pha_sinh_cuoc_sql = 'SELECT price ,COALESCE(COUNT(id), 0) AS sum FROM user_content WHERE price>0 AND created_time >= :startDate AND created_time <= :endDate GROUP BY price';
        $so_luot_pha_sinh_cuoc = Yii::$app->db->createCommand($so_luot_pha_sinh_cuoc_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryAll();
        foreach ($so_luot_pha_sinh_cuoc as $item) {
            $luot_phat_sinh_cuoc[$item['price']] = ($item['sum']) ? $item['sum'] : 0;
        }

        $data['cp_all'] = [
            'so_thue_bao_dang_ky_mien_phi' => $so_thue_bao_dang_ky_mien_phi,
            'so_thue_bao_dang_ky_goi_cuoc_tuan_5000' => $so_thue_bao_dang_ky_goi_cuoc_tuan_5000,
            'so_thue_bao_gia_han_goi_cuoc_tuan_5000' => $so_thue_bao_gia_han_goi_cuoc_tuan_5000,
            'luot_xem' => $luot_xem,
            'luot_tai' => $luot_tai,
            'luot_tang' => $luot_tang,
            'luot_phat_sinh_cuoc' => $luot_phat_sinh_cuoc,
        ];
        $data = serialize($data);
        $sql = 'INSERT INTO daily_report SET date = :startDate, type = 8, data = :data ON DUPLICATE KEY UPDATE data = :data';

        Yii::$app->db->createCommand($sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':data', $data)
            ->execute();
    }


    /**
     * @throws \yii\db\Exception
     * Thống kê đăng ký, hủy vas_subscribe / vas_unsubscribe / Application=MOBILE_ADS (type = 9)
     */
    public function VasSubscribe()
    {
        $startDate = date('Y-m-d', strtotime('-1 day'));
        //$startDate = '2016-03-06';
        //echo $startDate;
        $endDate = $startDate . ' 23:59:59';
        $dateNow = date('Y-m-d', time());

        $note_mobile_ads_all_sql = 'SELECT COUNT(*) FROM `vas_subscribe` WHERE application="MOBILE_ADS" AND channel="WAP" AND created_time >= :startDate AND created_time <= :endDate';
        $note_mobile_ads_all = Yii::$app->db->createCommand($note_mobile_ads_all_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();
        $data['mobile_ads_all'] = $note_mobile_ads_all;


        $note_mobile_ads_sql = "SELECT DISTINCT(note) FROM `vas_subscribe` WHERE application='MOBILE_ADS' AND channel='WAP' ";
        $note_mobile_ads = Yii::$app->db->createCommand($note_mobile_ads_sql)->queryAll();
        foreach ($note_mobile_ads as $key => $mobile_ads) {
            if ($mobile_ads['note']) {
                $note = $mobile_ads['note'];
                $mobile_ads_sql = 'SELECT COUNT(*) AS sum FROM `vas_subscribe` WHERE application="MOBILE_ADS" AND channel="WAP" AND note=:note AND created_time >= :startDate AND created_time <= :endDate';
                $mobile_ads = Yii::$app->db->createCommand($mobile_ads_sql)->bindParam(':startDate', $startDate)->bindParam(':endDate', $endDate)
                    ->bindParam(':note', $note)
                    ->queryScalar();
                $data[Utility::rewrite($note)] = $mobile_ads;

            }
        }

        $data = serialize($data);
        $sql = 'INSERT INTO daily_report SET date = :startDate, type = 9, data = :data ON DUPLICATE KEY UPDATE data = :data';

        Yii::$app->db->createCommand($sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':data', $data)
            ->execute();
    }

    public function ReprotCenter()
    {
        $startDate = date('Y-m-d', strtotime('-1 day'));
        $endDate = $startDate . ' 23:59:59';

        $dayupdate = date('Y-m-d', strtotime("-1 day", time()));

        $tempData = array(
            'date' => $dayupdate,
            'tong_doanh_thu' => $dayupdate,
            'doanh_thu_vega' => 0,
            'tong_so_thue_bao_dang_kich_hoat' => 0,
            'so_dang_ky_thue_bao_moi' => 0,
            'tong_so_thue_bao_huy_dich_vu' => 0,
            'so_thue_bao_bi_huy' => 0,
        );


        $query = new Query();
        $query->from('daily_report d')
            ->where('date= :DAYUPDATE AND type=:TYPE', [':DAYUPDATE' => $dayupdate, ':TYPE' => 1])
            ->one();
        $command = $query->createCommand();
        $tong_doanh_thu = $command->queryOne();
        $data = unserialize($tong_doanh_thu['data']);
        // so_thue_bao_bi_huy & Tong_doanh_thu & so_thue_bao_dang_ky
        if ($data) {
            foreach ($data as $key => $val) {

                //var_dump($val);die;
                if ($key == 'page_all') {
                    $tempData['tong_doanh_thu'] = $tempData['tong_doanh_thu'] + $val['tong_doanh_thu'];
                    $tempData['so_dang_ky_thue_bao_moi'] = $tempData['so_dang_ky_thue_bao_moi'] + $val['so_thue_bao_dang_ky'];
                    $tempData['tong_so_thue_bao_huy_dich_vu'] = $tempData['tong_so_thue_bao_huy_dich_vu'] + $val['so_luot_huy'];
                    $tempData['so_thue_bao_bi_huy'] = $tempData['so_thue_bao_bi_huy'] + $val['so_thue_bao_bi_huy'];
                }

            }
        }

        $query = new Query();
        $query->from('daily_report d')
            ->where('date= :DAYUPDATE AND type=:TYPE', [':DAYUPDATE' => $dayupdate, ':TYPE' => 2])
            ->one();
        $command = $query->createCommand();
        $tong_doanh_thu = $command->queryOne();
        $data = unserialize($tong_doanh_thu['data']);
        // tong_so_thue_bao_active
        if ($data) {
            foreach ($data as $key => $val) {
                $tempData['tong_so_thue_bao_dang_kich_hoat'] = $tempData['tong_so_thue_bao_dang_kich_hoat'] + $val['tong_so_thue_bao_active'];
            }
        }


        $tempData['doanh_thu_vega'] = $tempData['tong_doanh_thu'] / 2;
        var_dump($tempData);
        $dataSend = json_encode($tempData);
        $params = array(
            'username' => 'vega',
            'password' => 'vega!@#$%^',
            'service_id' => '1',
            'data' => $dataSend
        );
        try {
            $address = 'http://report.vega.com.vn/webservice/server.php?wsdl';
            $client = new \SoapClient($address, array(
                "trace" => 1,
                "exceptions" => 1,
                "cache_wsdl" => 0));
            $result = $client->reportDaily($params);
        } catch (Exception $ex) {
            Yii::log($ex->getMessage(), 'error', 'Report Daily');
        }


    }

    /**
     * @throws \yii\db\Exception
     * Thống kê quảng cáo (type = 10)
     */
    public function Xn($date = null, $syntax)
    {
        $streaming = WATCH;
        $download = DOWNLOAD;
        $subscribe = SUBSCRIBE;
        $unsubscribe = UNSUBSCRIBE;
        $monfee = MONFEE;
        $channelCron = CHANNEL_CRON;
        $channelSystem = CHANNEL_SYSTEM;
        $channelSms = CHANNEL_SMS;
        $channelWap = CHANNEL_WAP;

        $data = array();

        if(!empty($date)){
            $startDate = $date;
            $endDate = $startDate . ' 23:59:59';
        }else {
//            $startDate = date('Y-m-d', strtotime('-1 day'));
            $startDate = date('Y-m-d');
            $endDate = $startDate . ' 23:59:59';
        }

        // $listChannel = ['vega.xn','vt'];
        $xn = 'XN';

        if ($syntax == 'XN'){
            $ads = 'SMS_XN';
            $type = 10;
            $ads_channel_id = 121;
        }

        if ($syntax == 'N'){
            $ads = 'SMS_N';
            $type = 11;
            $ads_channel_id = 125;
        }
        if (empty($ads)){
            return;
        }


        $listChannelArr = [];
        $listNoteArr = [];


        $listChannel = AdsLink::find()->where(['ads_channel_id' => $ads_channel_id])->all();
        if (!empty($listChannel)) {
            foreach ($listChannel as $channel) {
                $channelTitle = $channel->source;
                $listChannelArr[] = $channelTitle;
                $note = $ads.'___'.$channelTitle;
                $listNoteArr[] = $note;

                //Tong hop cac thue bao dk qua nguon quang cao
                $sql = "INSERT INTO tmp_user_admob(msisdn, source_ads) SELECT distinct(user_id), note FROM transaction
                WHERE action = :subscribe AND created_time >= :startDate AND created_time <= :endDate
                AND channel = 'SMS'
                AND note = :note
                ON DUPLICATE KEY UPDATE tmp_user_admob.source_ads = tmp_user_admob.source_ads";
                Yii::$app->db->createCommand($sql)
                    ->bindParam(':subscribe', $subscribe)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':note', $note)
                    ->execute();

                // Tổng số lượt click
                $tong_so_luot_click_sql = 'SELECT COUNT(id) FROM log_ads_click 
                                            WHERE ads = :ads AND source = :channelTitle 
                                            AND created_time >= :startDate AND created_time <= :endDate';
                $tong_so_luot_click = Yii::$app->db->createCommand($tong_so_luot_click_sql)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':ads', $ads)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->queryScalar();

                // Tổng số lượt click không trùng ip
                $tong_so_luot_click_khong_trung_ip_sql = 'SELECT COUNT(DISTINCT user_ip) FROM log_ads_click 
                                                          WHERE ads = :ads AND source = :channelTitle 
                                                          AND created_time >= :startDate AND created_time <= :endDate';
                $tong_so_luot_click_khong_trung_ip = Yii::$app->db->createCommand($tong_so_luot_click_khong_trung_ip_sql)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':ads', $ads)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->queryScalar();

                // Số lượt click nhận diện được
                $so_luot_click_nhan_dien_duoc_sql = 'SELECT COUNT(id) FROM log_ads_click 
                                                      WHERE is_3g = 1 AND ads = :ads AND source = :channelTitle 
                                                      AND created_time >= :startDate AND created_time <= :endDate';
                $so_luot_click_nhan_dien_duoc = Yii::$app->db->createCommand($so_luot_click_nhan_dien_duoc_sql)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':ads', $ads)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->queryScalar();

                // Số lươt click nhận diện được không trùng ip
                $so_luot_click_nhan_dien_duoc_khong_trung_ip_sql = 'SELECT COUNT(DISTINCT user_ip) FROM log_ads_click 
                                                                    WHERE ads = :ads AND source = :channelTitle AND is_3g = 1 
                                                                    AND created_time >= :startDate AND created_time <= :endDate';
                $so_luot_click_nhan_dien_duoc_khong_trung_ip = Yii::$app->db->createCommand($so_luot_click_nhan_dien_duoc_khong_trung_ip_sql)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':ads', $ads)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->queryScalar();

                // Tổng số lượt đăng ký
                $tong_so_luot_dang_ky_sql = 'SELECT COUNT(user_id) 
                FROM transaction 
                WHERE note = :note 
                AND action = :subscribe 
                AND created_time >= :startDate 
                AND created_time <= :endDate';
                $tong_so_luot_dang_ky = Yii::$app->db->createCommand($tong_so_luot_dang_ky_sql)
                    ->bindParam(':subscribe', $subscribe)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':note', $note)
                    ->queryScalar();

                // Tổng số lượt đăng ký miễn phí yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy
                $tong_so_luot_dang_ky_mien_phi_sql = 'SELECT COUNT(user_id) 
                                                      FROM transaction 
                                                      WHERE note = :note 
                                                      AND action = :subscribe 
                                                      AND price = 0 
                                                      AND created_time >= :startDate 
                                                      AND created_time <= :endDate';
                $tong_so_luot_dang_ky_mien_phi = Yii::$app->db->createCommand($tong_so_luot_dang_ky_mien_phi_sql)
                    ->bindParam(':subscribe', $subscribe)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':note', $note)
                    ->queryScalar();

                // Số lượt hủy
                $so_luot_huy_sql = 'SELECT COUNT(user_id) 
                FROM transaction
                WHERE note = :note 
                AND action = :unsubscribe 
                AND created_time >= :startDate 
                AND created_time <= :endDate';
                $so_luot_huy = Yii::$app->db->createCommand($so_luot_huy_sql)
                    ->bindParam(':unsubscribe', $unsubscribe)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':note', $note)
                    ->queryScalar();

                // Số lượt tự hủy
                $so_luot_tu_huy_sql = 'SELECT COUNT(user_id) 
                FROM transaction t
                WHERE t.note =:note
                AND t.action = :unsubscribe AND channel <> :channelCron 
                AND t.created_time >= :startDate 
                AND t.created_time <= :endDate';
                $so_luot_tu_huy = Yii::$app->db->createCommand($so_luot_tu_huy_sql)
                    ->bindParam(':unsubscribe', $unsubscribe)
                    ->bindParam(':channelCron', $channelCron)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':note', $note)
                    ->queryScalar();

                // Số lượt gia hạn
                $so_luot_gia_han_sql = 'SELECT COUNT(user_id) 
                FROM transaction t
                WHERE t.note = :note 
                AND t.action = :monfee 
                AND t.created_time >= :startDate 
                AND t.created_time <= :endDate';
                $so_luot_gia_han = Yii::$app->db->createCommand($so_luot_gia_han_sql)
                    ->bindParam(':monfee', $monfee)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':note', $note)
                    ->queryScalar();

                // Số lượt xem tải
                $so_luot_xem_tai_sql = 'SELECT (SELECT COUNT(user_id) 
                FROM transaction t
                INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id
                WHERE ut.source_ads = :note 
                AND (t.action = :streaming OR t.action = :download) 
                AND t.created_time >= :startDate 
                AND t.created_time <= :endDate) + (SELECT COUNT(user_id) FROM user_content WHERE source = :channelTitle 
                AND (action = :streaming OR action = :download) 
                AND created_time >= :startDate 
                AND created_time <= :endDate)';
                $so_luot_xem_tai = Yii::$app->db->createCommand($so_luot_xem_tai_sql)
                    ->bindParam(':streaming', $streaming)
                    ->bindParam(':download', $download)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':note', $note)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->queryScalar();

                // Số lượt xem của thuê bao đăng ký
                $so_luot_xem_cua_thue_bao_dang_ky_sql = 'SELECT (SELECT COUNT(user_id) 
                FROM transaction t
                INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id
                WHERE ut.source_ads = :note 
                AND t.package_id > 0 
                AND (t.action = :streaming OR t.action = :download) 
                AND t.created_time >= :startDate 
                AND t.created_time <= :endDate) + (SELECT COUNT(user_id) FROM user_content WHERE source = :channelTitle 
                AND package_id > 0 
                AND (action = :streaming OR action = :download) 
                AND created_time >= :startDate AND created_time <= :endDate)';
                $so_luot_xem_cua_thue_bao_dang_ky = Yii::$app->db->createCommand($so_luot_xem_cua_thue_bao_dang_ky_sql)
                    ->bindParam(':streaming', $streaming)
                    ->bindParam(':download', $download)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':channelTitle', $channelTitle)
                    ->bindParam(':note', $note)
                    ->queryScalar();

                //so_thue_bao_dang_ky_mien_phi
                $sql = "SELECT COUNT(distinct(user_id)) AS total
				FROM transaction
				WHERE action = :action 
				AND price = 0
				AND created_time >= :startDate 
				AND created_time <= :endDate
				AND note =:note";
                $so_thue_bao_dang_ky_mien_phi = Yii::$app->db->createCommand($sql)
                    ->bindParam(':action', $subscribe)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':note', $note)
                    ->queryScalar();

                // số thuê bao đăng ký
                $sql = "SELECT COUNT(distinct(user_id)) AS total
				FROM transaction
				WHERE action = :action 
				AND created_time >= :startDate 
				AND created_time <= :endDate
				AND note =:note";
                $so_thue_bao_dang_ky = Yii::$app->db->createCommand($sql)
                    ->bindParam(':action', $subscribe)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':note', $note)
                    ->queryScalar();

                // số thuê bao đăng ký qua sms
                $sql = "SELECT COUNT(distinct(user_id)) AS total
				FROM transaction
				WHERE action = :action 
				AND created_time >= :startDate 
				AND created_time <= :endDate
				AND channel = :channel
				AND note =:note";
                $so_thue_bao_dk_sms = Yii::$app->db->createCommand($sql)
                    ->bindParam(':action', $subscribe)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':note', $note)
                    ->bindParam(':channel', $channelSms)
                    ->queryScalar();

                // số thuê bao đăng ký qua wap
                $so_thue_bao_dk_wap = Yii::$app->db->createCommand($sql)
                    ->bindParam(':action', $subscribe)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':note', $note)
                    ->bindParam(':channel', $channelWap)
                    ->queryScalar();

                //so_thue_bao_bi_huy
                $sql = "SELECT COUNT(t.user_id) AS total
				FROM transaction t
				WHERE t.note =:note
				AND t.action = :action 
				AND t.channel = :channel 
				AND t.created_time >= :startDate
				AND t.created_time <= :endDate";
                $so_thue_bao_bi_huy = Yii::$app->db->createCommand($sql)
                    ->bindParam(':action', $unsubscribe)
                    ->bindParam(':channel', $channelSystem)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':note', $note)
                    ->queryScalar();


                //so_thue_bao_tu_huy
                $sql = "SELECT COUNT(t.user_id) AS total
				FROM transaction t
				WHERE t.note =:note
				AND t.action = :action 
				AND t.channel != :channel 
				AND t.created_time >= :startDate
				AND t.created_time <= :endDate";
                $so_thue_bao_tu_huy = Yii::$app->db->createCommand($sql)
                    ->bindParam(':action', $unsubscribe)
                    ->bindParam(':channel', $channelSystem)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':note', $note)
                    ->queryScalar();


                // Doanh thu đăng ký
                $doanh_thu_dang_ky_sql = 'SELECT sum(price) AS total
                    FROM transaction t
                    WHERE t.action = :action
                    AND t.price > 0 
                    AND t.created_time >= :startDate 
                    AND t.created_time < :endDate
                    AND t.note =:note';
                $doanh_thu_dang_ky = Yii::$app->db->createCommand($doanh_thu_dang_ky_sql)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':note', $note)
                    ->bindParam(':action', $subscribe)
                    ->queryScalar();

                // Doanh thu gia hạn
                $doanh_thu_gia_han = Yii::$app->db->createCommand($doanh_thu_dang_ky_sql)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':note', $note)
                    ->bindParam(':action', $monfee)
                    ->queryScalar();

                // Tổng doanh thu
                $tong_doanh_thu_sql = 'SELECT COALESCE(SUM(price), 0) 
                FROM transaction t 
                WHERE t.note = :note 
                AND t.price > 0 
                AND t.created_time >= :startDate 
                AND t.created_time <= :endDate';
                $tong_doanh_thu = Yii::$app->db->createCommand($tong_doanh_thu_sql)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->bindParam(':note', $note)
                    ->queryScalar();

                $data['source_' . $channelTitle] = [
                    'tong_so_luot_click' => $tong_so_luot_click,
                    'tong_so_luot_click_khong_trung_ip' => $tong_so_luot_click_khong_trung_ip,
                    'so_luot_click_nhan_dien_duoc' => $so_luot_click_nhan_dien_duoc,
                    'so_luot_click_nhan_dien_duoc_khong_trung_ip' => $so_luot_click_nhan_dien_duoc_khong_trung_ip,
                    'tong_so_luot_dang_ky' => $tong_so_luot_dang_ky,
                    'tong_so_luot_dang_ky_mien_phi' => $tong_so_luot_dang_ky_mien_phi,
                    'so_luot_huy' => $so_luot_huy,
                    'so_luot_tu_huy' => $so_luot_tu_huy,
                    'so_luot_gia_han' => $so_luot_gia_han,
                    'so_luot_xem_tai' => $so_luot_xem_tai,
                    'so_luot_xem_cua_thue_bao_dang_ky' => $so_luot_xem_cua_thue_bao_dang_ky,
                    'doanh_thu_gia_han' => $doanh_thu_gia_han,
                    'doanh_thu_dang_ky' => $doanh_thu_dang_ky,
                    'so_thue_bao_dang_ky_mien_phi' => $so_thue_bao_dang_ky_mien_phi,
                    'so_thue_bao_dang_ky' => $so_thue_bao_dang_ky,
                    'so_thue_bao_bi_huy' => $so_thue_bao_bi_huy,
                    'so_thue_bao_tu_huy' => $so_thue_bao_tu_huy,
                    'tong_doanh_thu' => $tong_doanh_thu,
                    'so_thue_bao_dk_sms' => $so_thue_bao_dk_sms,
                    'so_thue_bao_dk_wap' => $so_thue_bao_dk_wap,
                ];
            }
        }

        //===============ALL
        $listChannelStr = '('.implode(',', $listChannelArr).')';

        $listNoteArrStr = array_map(array($this, 'addquote'), $listNoteArr);
        $listNoteStr = '('.implode(',', $listNoteArrStr).')';


        // Tổng số lượt click
        $tong_so_luot_click_sql = 'SELECT COUNT(id) FROM log_ads_click 
                                            WHERE ads = :ads  
                                            AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_luot_click = Yii::$app->db->createCommand($tong_so_luot_click_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->bindParam(':ads', $ads)
            ->queryScalar();

        // Tổng số lượt click không trùng ip
        $tong_so_luot_click_khong_trung_ip_sql = 'SELECT COUNT(DISTINCT user_ip) FROM log_ads_click 
                                                          WHERE ads = :ads 
                                                          AND created_time >= :startDate AND created_time <= :endDate';
        $tong_so_luot_click_khong_trung_ip = Yii::$app->db->createCommand($tong_so_luot_click_khong_trung_ip_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->bindParam(':ads', $ads)
            ->queryScalar();

        // Số lượt click nhận diện được
        $so_luot_click_nhan_dien_duoc_sql = 'SELECT COUNT(id) FROM log_ads_click 
                                                      WHERE is_3g = 1 AND ads = :ads 
                                                      AND created_time >= :startDate AND created_time <= :endDate';
        $so_luot_click_nhan_dien_duoc = Yii::$app->db->createCommand($so_luot_click_nhan_dien_duoc_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->bindParam(':ads', $ads)
            ->queryScalar();

        // Số lươt click nhận diện được không trùng ip
        $so_luot_click_nhan_dien_duoc_khong_trung_ip_sql = 'SELECT COUNT(DISTINCT user_ip) FROM log_ads_click 
                                                                    WHERE ads = :ads AND is_3g = 1 
                                                                    AND created_time >= :startDate AND created_time <= :endDate';
        $so_luot_click_nhan_dien_duoc_khong_trung_ip = Yii::$app->db->createCommand($so_luot_click_nhan_dien_duoc_khong_trung_ip_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->bindParam(':ads', $ads)
            ->queryScalar();

        // Tổng số lượt đăng ký
        $tong_so_luot_dang_ky_sql = "SELECT COUNT(user_id) 
                FROM transaction 
                WHERE note IN ".$listNoteStr." 
                AND action = :subscribe 
                AND created_time >= :startDate 
                AND created_time <= :endDate";
        $tong_so_luot_dang_ky = Yii::$app->db->createCommand($tong_so_luot_dang_ky_sql)
            ->bindParam(':subscribe', $subscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Tổng số lượt đăng ký miễn phí yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy
        $tong_so_luot_dang_ky_mien_phi_sql = "SELECT COUNT(user_id) 
                                                      FROM transaction 
                                                      WHERE note IN ".$listNoteStr."  
                                                      AND action = :subscribe 
                                                      AND price = 0 
                                                      AND created_time >= :startDate 
                                                      AND created_time <= :endDate";
        $tong_so_luot_dang_ky_mien_phi = Yii::$app->db->createCommand($tong_so_luot_dang_ky_mien_phi_sql)
            ->bindParam(':subscribe', $subscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Số lượt hủy
        $so_luot_huy_sql = "SELECT COUNT(user_id) 
                FROM transaction
                WHERE note IN ".$listNoteStr." 
                AND action = :unsubscribe 
                AND created_time >= :startDate 
                AND created_time <= :endDate";
        $so_luot_huy = Yii::$app->db->createCommand($so_luot_huy_sql)
            ->bindParam(':unsubscribe', $unsubscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Số lượt tự hủy
        $so_luot_tu_huy_sql = "SELECT COUNT(user_id) 
                FROM transaction t
                WHERE t.note IN ".$listNoteStr."
                AND t.action = :unsubscribe AND channel <> :channelCron 
                AND t.created_time >= :startDate 
                AND t.created_time <= :endDate";
        $so_luot_tu_huy = Yii::$app->db->createCommand($so_luot_tu_huy_sql)
            ->bindParam(':unsubscribe', $unsubscribe)
            ->bindParam(':channelCron', $channelCron)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Số lượt gia hạn
        $so_luot_gia_han_sql = "SELECT COUNT(user_id) 
                FROM transaction t
                WHERE t.note IN ".$listNoteStr." 
                AND t.action = :monfee 
                AND t.created_time >= :startDate 
                AND t.created_time <= :endDate";
        $so_luot_gia_han = Yii::$app->db->createCommand($so_luot_gia_han_sql)
            ->bindParam(':monfee', $monfee)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Số lượt xem tải
        $so_luot_xem_tai_sql = "SELECT (SELECT COUNT(user_id) 
                FROM transaction t
                INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id
                WHERE ut.source_ads IN ".$listNoteStr." 
                AND (t.action = :streaming OR t.action = :download) 
                AND t.created_time >= :startDate 
                AND t.created_time <= :endDate) + (SELECT COUNT(user_id) FROM user_content WHERE (action = :streaming OR action = :download) 
                AND created_time >= :startDate 
                AND created_time <= :endDate)";
        $so_luot_xem_tai = Yii::$app->db->createCommand($so_luot_xem_tai_sql)
            ->bindParam(':streaming', $streaming)
            ->bindParam(':download', $download)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // Số lượt xem của thuê bao đăng ký
        $so_luot_xem_cua_thue_bao_dang_ky_sql = "SELECT (SELECT COUNT(user_id) 
                FROM transaction t
                INNER JOIN tmp_user_admob ut ON ut.msisdn = t.user_id
                WHERE ut.source_ads IN ".$listNoteStr." 
                AND t.package_id > 0 
                AND (t.action = :streaming OR t.action = :download) 
                AND t.created_time >= :startDate 
                AND t.created_time <= :endDate) + (SELECT COUNT(user_id) FROM user_content WHERE package_id > 0 
                AND (action = :streaming OR action = :download) 
                AND created_time >= :startDate AND created_time <= :endDate)";
        $so_luot_xem_cua_thue_bao_dang_ky = Yii::$app->db->createCommand($so_luot_xem_cua_thue_bao_dang_ky_sql)
            ->bindParam(':streaming', $streaming)
            ->bindParam(':download', $download)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        //so_thue_bao_dang_ky_mien_phi
        $sql = "SELECT COUNT(distinct(user_id)) AS total
				FROM transaction
				WHERE action = :action 
				AND price = 0
				AND created_time >= :startDate 
				AND created_time <= :endDate
				AND note IN ".$listNoteStr;
        $so_thue_bao_dang_ky_mien_phi = Yii::$app->db->createCommand($sql)
            ->bindParam(':action', $subscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // số thuê bao đăng ký
        $sql = "SELECT COUNT(distinct(user_id)) AS total
				FROM transaction
				WHERE action = :action 
				AND created_time >= :startDate 
				AND created_time <= :endDate
				AND note IN ".$listNoteStr;
        $so_thue_bao_dang_ky = Yii::$app->db->createCommand($sql)
            ->bindParam(':action', $subscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        // số thuê bao đăng ký qua sms
        $sql = "SELECT COUNT(distinct(user_id)) AS total
				FROM transaction
				WHERE action = :action 
				AND created_time >= :startDate 
				AND created_time <= :endDate
				AND channel = :channel
				AND note IN ".$listNoteStr;
        $so_thue_bao_dk_sms = Yii::$app->db->createCommand($sql)
            ->bindParam(':action', $subscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->bindParam(':channel', $channelSms)
            ->queryScalar();

        // số thuê bao đăng ký qua wap
        $so_thue_bao_dk_wap = Yii::$app->db->createCommand($sql)
            ->bindParam(':action', $subscribe)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->bindParam(':channel', $channelWap)
            ->queryScalar();

        //so_thue_bao_bi_huy
        $sql = "SELECT COUNT(t.user_id) AS total
				FROM transaction t
				WHERE t.note IN ".$listNoteStr." 
				AND t.action = :action 
				AND t.channel = :channel 
				AND t.created_time >= :startDate
				AND t.created_time <= :endDate";
        $so_thue_bao_bi_huy = Yii::$app->db->createCommand($sql)
            ->bindParam(':action', $unsubscribe)
            ->bindParam(':channel', $channelSystem)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();


        //so_thue_bao_tu_huy
        $sql = "SELECT COUNT(t.user_id) AS total
				FROM transaction t
				WHERE t.note IN ".$listNoteStr." 
				AND t.action = :action 
				AND t.channel != :channel 
				AND t.created_time >= :startDate
				AND t.created_time <= :endDate";
        $so_thue_bao_tu_huy = Yii::$app->db->createCommand($sql)
            ->bindParam(':action', $unsubscribe)
            ->bindParam(':channel', $channelSystem)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();


        // Doanh thu đăng ký
        $doanh_thu_dang_ky_sql = "SELECT sum(price) AS total
                    FROM transaction t
                    WHERE t.action = :action
                    AND t.price > 0 
                    AND t.created_time >= :startDate 
                    AND t.created_time < :endDate
                    AND t.note IN ".$listNoteStr;
        $doanh_thu_dang_ky = Yii::$app->db->createCommand($doanh_thu_dang_ky_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->bindParam(':action', $subscribe)
            ->queryScalar();

        // Doanh thu gia hạn
        $doanh_thu_gia_han = Yii::$app->db->createCommand($doanh_thu_dang_ky_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->bindParam(':action', $monfee)
            ->queryScalar();

        // Tổng doanh thu
        $tong_doanh_thu_sql = "SELECT COALESCE(SUM(price), 0) 
                FROM transaction t 
                WHERE t.note IN ".$listNoteStr." 
                AND t.price > 0 
                AND t.created_time >= :startDate 
                AND t.created_time <= :endDate";
        $tong_doanh_thu = Yii::$app->db->createCommand($tong_doanh_thu_sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryScalar();

        $data['source_all'] = [
            'tong_so_luot_click' => $tong_so_luot_click,
            'tong_so_luot_click_khong_trung_ip' => $tong_so_luot_click_khong_trung_ip,
            'so_luot_click_nhan_dien_duoc' => $so_luot_click_nhan_dien_duoc,
            'so_luot_click_nhan_dien_duoc_khong_trung_ip' => $so_luot_click_nhan_dien_duoc_khong_trung_ip,
            'tong_so_luot_dang_ky' => $tong_so_luot_dang_ky,
            'tong_so_luot_dang_ky_mien_phi' => $tong_so_luot_dang_ky_mien_phi,
            'so_luot_huy' => $so_luot_huy,
            'so_luot_tu_huy' => $so_luot_tu_huy,
            'so_luot_gia_han' => $so_luot_gia_han,
            'so_luot_xem_tai' => $so_luot_xem_tai,
            'so_luot_xem_cua_thue_bao_dang_ky' => $so_luot_xem_cua_thue_bao_dang_ky,
            'doanh_thu_gia_han' => $doanh_thu_gia_han,
            'doanh_thu_dang_ky' => $doanh_thu_dang_ky,
            'so_thue_bao_dang_ky_mien_phi' => $so_thue_bao_dang_ky_mien_phi,
            'so_thue_bao_dang_ky' => $so_thue_bao_dang_ky,
            'so_thue_bao_bi_huy' => $so_thue_bao_bi_huy,
            'so_thue_bao_tu_huy' => $so_thue_bao_tu_huy,
            'tong_doanh_thu' => $tong_doanh_thu,
            'so_thue_bao_dk_sms' => $so_thue_bao_dk_sms,
            'so_thue_bao_dk_wap' => $so_thue_bao_dk_wap,
        ];
        //===============ALL

        $data = serialize($data);
        $sql = 'INSERT INTO daily_report SET date = :startDate, type = '.$type.', data = :data ON DUPLICATE KEY UPDATE data = :data';

        Yii::$app->db->createCommand($sql)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':data', $data)
            ->execute();
    }

    function addquote($v)
    {
        return '"'.$v.'"';
    }

}