<?php
/**
 * @Function: Lớp xử lý phần thống kê chuyên mục
 * @Author: trinh.kethanh@gmail.com
 * @Date: 23/03/2015
 * @System: Video 2.0
 */

namespace console\models;

use Yii;
use yii\db\Exception;
use  console\components\QueryBuilder;

class CategoryStatsDaily
{
    /**
     * @throws \yii\db\Exception
     */
    public function CategoryStats()
    {
        $connection = Yii::$app->db;
        $streaming = WATCH;
        $download = DOWNLOAD;

        $startDate = date('Y-m-d', strtotime('-1 day'));
        $endDate = $startDate . ' 23:59:59';

        $connection->createCommand()->delete('category_stats_daily', ['date' => $startDate])->execute();
        $runInsert = new QueryBuilder();

        // Thống kê category clip
        /*$sqlClip = " SELECT category.name_1,category.id as category_id,user_content.content_type_id,user_content.page_id,user_content.created_time,user_content.cp_id as cpid
                    (SELECT COUNT(id) FROM user_content WHERE  category_id = category.id AND action = :streaming AND price = 0 AND cp_id = cpid AND created_time >= :startDate AND created_time <= :endDate) as streaming_free,
                    (SELECT COUNT(id) FROM user_content WHERE  category_id = category.id AND action = :streaming AND price != 0 AND cp_id = cpid AND created_time >= :startDate AND created_time <= :endDate) as streaming_charge,
                    (SELECT COUNT(id) FROM user_content WHERE  category_id = category.id AND action = :streaming AND cp_id = cpid AND created_time >= :startDate AND created_time <= :endDate) as streaming_total,
                    (SELECT COUNT(id) FROM user_content WHERE  category_id = category.id AND action = :download AND cp_id = cpid AND created_time >= :startDate AND created_time <= :endDate) as download_total,
                    (SELECT COALESCE(SUM(price), 0) FROM user_content WHERE category_id = category.id AND action = :download AND cp_id = cpid AND created_time >= :startDate AND created_time <= :endDate) as revenue_total
              FROM category
              INNER JOIN user_content ON category.id = user_content.category_id
              WHERE category.deleted=0 AND
                   user_content.created_time >= :startDate AND user_content.created_time <= :endDate
              GROUP BY category.id,cpid
        ";*/


        /*$sqlClip = " SELECT category.name_1,category.id as category_id,user_content.content_type_id,user_content.page_id,user_content.created_time,user_content.cp_id as cpid,
                    (SELECT COUNT(id) FROM user_content WHERE  category_id = category.id AND action = :streaming AND price = 0 AND cp_id = cpid AND created_time >= :startDate AND created_time <= :endDate) as streaming_free,
                    (SELECT COUNT(id) FROM user_content WHERE  category_id = category.id AND action = :streaming AND price != 0 AND cp_id = cpid AND created_time >= :startDate AND created_time <= :endDate) as streaming_charge
              FROM category
              INNER JOIN user_content ON category.id = user_content.category_id
              WHERE category.deleted=0 AND
                   user_content.created_time >= :startDate AND user_content.created_time <= :endDate
              GROUP BY category.id,cpid
        ";*/


        $sqlClip = " SELECT category.name_1,category.id as category_id,user_content.content_type_id,user_content.page_id,user_content.created_time,user_content.cp_id as cpid
              FROM category
              INNER JOIN user_content ON category.id = user_content.category_id
              WHERE category.deleted=0 AND
                   user_content.created_time >= :startDate AND user_content.created_time <= :endDate
              GROUP BY category.id,cpid
        ";


        $dataCategoryClip = $connection->createCommand($sqlClip)
            // ->bindParam(':streaming', $streaming)
//            ->bindParam(':download', $download)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryAll();

        if(!empty($dataCategoryClip)) {
            foreach ($dataCategoryClip as $key => $value) {
                $cpId = isset($value['cpid'])?$value['cpid']:0;

                $streaming_free_query = '(SELECT COUNT(id) FROM user_content WHERE  category_id = :category AND action = :streaming AND price = 0 AND cp_id = :cpid AND created_time >= :startDate AND created_time <= :endDate) ';
                $streaming_free_count = $connection->createCommand($streaming_free_query)
                    ->bindParam(':category', $value['category_id'])
                    ->bindParam(':streaming', $streaming)
                    ->bindParam(':cpid', $cpId)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->queryScalar();

                $streaming_charge_query = '(SELECT COUNT(id) FROM user_content WHERE  category_id = :category AND action = :streaming AND price != 0 AND cp_id = :cpid AND created_time >= :startDate AND created_time <= :endDate) ';
                $streaming_charge_count = $connection->createCommand($streaming_charge_query)
                    ->bindParam(':category', $value['category_id'])
                    ->bindParam(':streaming', $streaming)
                    ->bindParam(':cpid', $cpId)
                    ->bindParam(':startDate', $startDate)
                    ->bindParam(':endDate', $endDate)
                    ->queryScalar();
                $streaming_total_count = $streaming_free_count + $streaming_charge_count;

                $dataCategoryClipUpdateDB[] = [
                    $startDate,
                    $value['category_id'],
                    $value['content_type_id'],
                    $cpId,
                    $value['page_id'],
                    $value['created_time'],
                    $streaming_free_count,
                    $streaming_charge_count,
                    $streaming_total_count,
//                    $value['download_total'],
//                    $value['revenue_total']
                    0,
                    0
                ];
            }
            try {
                $runInsert->batchInsertDuplicate('category_stats_daily',
                    [
                        'date',
                        'category_id',
                        'content_type_id',
                        'cp_id',
                        'page_id',
                        'created_time',
                        'streaming_free',
                        'streaming_charge',
                        'streaming_total',
                        'download_total',
                        'revenue_total'
                    ], $dataCategoryClipUpdateDB, [
                        'date',
                        'category_id',
                        'content_type_id',
                        'cp_id',
                        'page_id',
                        'created_time',
                        'streaming_free',
                        'streaming_charge',
                        'streaming_total',
                        'download_total',
                        'revenue_total'
                    ]);
            } catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        }
        echo 'done';
        return '';
        // Thống kê category film
        $sqlFilm = "SELECT category.name_1,category.id as category_id,user_content.content_type_id,user_content.cp_id,user_content.page_id,user_content.created_time,
                    (SELECT COUNT(id) FROM user_content WHERE  category_id = category.id AND action = :streaming AND price = 0 AND created_time >= :startDate AND created_time <= :endDate) as streaming_free,
                    (SELECT COUNT(id) FROM user_content WHERE  category_id = category.id AND action = :streaming AND price != 0 AND created_time >= :startDate AND created_time <= :endDate) as streaming_charge,
                    (SELECT COUNT(id) FROM user_content WHERE  category_id = category.id AND action = :streaming AND created_time >= :startDate AND created_time <= :endDate) as streaming_total,
                    (SELECT COUNT(id) FROM user_content WHERE  category_id = category.id AND action = :download AND created_time >= :startDate AND created_time <= :endDate) as download_total,
                    (SELECT COALESCE(SUM(price), 0) FROM user_content WHERE category_id = category.id AND action = :download AND created_time >= :startDate AND created_time <= :endDate) as revenue_total
              FROM category
              INNER JOIN user_content ON category.id = user_content.category_id
              WHERE category.deleted=0 AND user_content.content_type_id=2 AND
                   user_content.created_time >= :startDate AND user_content.created_time <= :endDate
              GROUP BY category.id
        ";
        $dataCategoryFilm = $connection->createCommand($sqlFilm)
            ->bindParam(':streaming', $streaming)
            ->bindParam(':download', $download)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryAll();
        if(!empty($dataCategoryFilm)) {
            foreach ($dataCategoryFilm as $key => $value) {
                $dataCategoryFilmUpdateDB[] = [
                    $startDate,
                    $value['category_id'],
                    $value['content_type_id'],
                    $value['cp_id'],
                    '0',
                    $value['created_time'],
                    $value['streaming_free'],
                    $value['streaming_charge'],
                    $value['streaming_total'],
                    $value['download_total'],
                    $value['revenue_total']
                ];
            }
            try {
                $runInsert->batchInsertDuplicate('category_stats_daily',
                    [
                        'date',
                        'category_id',
                        'content_type_id',
                        'cp_id',
                        'page_id',
                        'created_time',
                        'streaming_free',
                        'streaming_charge',
                        'streaming_total',
                        'download_total',
                        'revenue_total'
                    ], $dataCategoryFilmUpdateDB, [
                        'date',
                        'category_id',
                        'content_type_id',
                        'cp_id',
                        'page_id',
                        'created_time',
                        'streaming_free',
                        'streaming_charge',
                        'streaming_total',
                        'download_total',
                        'revenue_total'
                    ]);
            }  catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        }
    }
}