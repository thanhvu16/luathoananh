<?php
/**
 * @Function: Lớp xử lý phần thống kê doanh thu.
 * @Author: trinh.kethanh@gmail.com
 * @Date: 23/03/2015
 * @System: Video 2.0
 */

namespace console\models;

use common\components\KLogger;
use common\components\Utility;
use Yii;
use yii\db\Query;

use common\models\TopupRequestBase;


class TopupRequest
{

    /*
     * Get msisdn
     * */
    public function getMsisdn()
    {
        // tt: transaction
        // uss: user_package
        // tr: topup_request

        $dateFrom = date('Y-m-d H:i:s',  strtotime("-3 day"));
        $sql = "SELECT
	tt.user_id,
	sum(tt.price) AS price_charge, 
	min(tt.price) AS min_charge, 
	uss.subscribed_time AS time_create, 
	NOW() AS set_time, 
	TIMESTAMPDIFF(
		HOUR,
		max(uss.subscribed_time),
		NOW()
	) AS count_date 
FROM
	`transaction` AS tt
INNER JOIN user_package AS uss ON tt.user_id = uss.user_id
LEFT JOIN topup_request AS tr ON tt.user_id = tr.msisdn
WHERE
	tt.created_time >= '" . $dateFrom . "'  
AND uss.created_time >= '" . $dateFrom . "' 
AND uss.`status` = 1
AND uss.package_id = 7
AND uss.subscribed_channel IN ('N','DK VIP')
AND tt.package_id = 7
AND tr.msisdn IS NULL
GROUP BY
	tt.user_id
HAVING
	count_date >= 71
AND count_date <= 72
AND (
	(
		price_charge >= 4000
		AND min_charge = 0
	)
	OR (
		price_charge >= 6000
		AND min_charge = 2000
	)
)";
        
        return Yii::$app->db->createCommand($sql)->queryAll();

    }

    public function checkSuccessDateMax()
    {
        $sql = "SELECT
	MAX(updated_time) as time_check
FROM
	`topup_request`
WHERE
	`status` = 0;";
        return Yii::$app->db->createCommand($sql)->queryOne();

    }

    public function getSendRequest($page, $limit)
    {
        $offset = $limit($page-1);
        $query = TopupRequestBase::find()
            ->select(['msisdn', 'money', 'id'])
            ->andWhere(['<>', 'money', null])
            ->andWhere(['<>', 'money', ''])
            ->andWhere(['or', ['status' => 2], ['status' => 0]])
            ->limit($limit)
            ->offset($offset);

        return $query->asArray()->all();
    }

    /*
     * Get msisdn
     * */
    public function getTestMsisdn()
    {
        $testMsisdn = 841658540191;
        $testPackageId = 2;
        $testPackageChannel = 'DK M1';

        // tt: transaction
        // uss: user_package
        // tr: topup_request

        $dateFrom = date('Y-m-d H:i:s',  strtotime("-3 day"));
        $sql = "SELECT
            tt.user_id,
            sum(tt.price) AS price_charge, 
            min(tt.price) AS min_charge, 
            uss.subscribed_time AS time_create, 
            NOW() AS set_time, 
            TIMESTAMPDIFF(
                HOUR,
                max(uss.subscribed_time),
                NOW()
            ) AS count_date 
        FROM
            `transaction` AS tt
        INNER JOIN user_package AS uss ON tt.user_id = uss.user_id
        LEFT JOIN topup_request AS tr ON tt.user_id = tr.msisdn
        WHERE uss.`user_id` = ".$testMsisdn."
        AND uss.`status` = 1
        AND uss.package_id = ".$testPackageId."
        AND uss.subscribed_channel = '".$testPackageChannel."'
        AND tt.package_id = ".$testPackageId."
        AND tr.msisdn IS NULL
        GROUP BY
            tt.user_id
        HAVING
            count_date >= 71
        AND count_date <= 72
        AND (
            (
                price_charge >= 4000
                AND min_charge = 0
            )
            OR (
                price_charge >= 6000
                AND min_charge = 2000
            )
        )";

        return Yii::$app->db->createCommand($sql)->queryAll();

    }
}