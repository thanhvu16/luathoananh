<?php
/**
 * @Function: Lớp xử lý phần thống kê video & chi tiết video. Chạy cronjob này để update dữ liệu thống kê theo ngày vào bảng video_stats_daily
 * @Author: trinh.kethanh@gmail.com
 * @Date: 23/03/2015
 * @System: Video 2.0
 */

namespace console\models;

use Yii;
use yii\db\Exception;

class VideoStatsDaily
{
    /**
     * @throws \yii\db\Exception
     */
    public function VideoStats()
    {
        $connection = Yii::$app->db;
        $streaming = WATCH;
        $download = DOWNLOAD;

        $startDate = date('Y-m-d', strtotime('-1 day'));
        $endDate = $startDate . ' 23:59:59';

        $connection->createCommand()->delete('video_stats_daily', ['date' => $startDate])->execute();

        // Thống kê clip
        $sqlClip = "
              SELECT
                    DISTINCT(clip.id),
                    clip.title_1,
                    clip.approved_time,
                    clip.category_id,
                    clip.page_id,
                    clip.cp_id as cp,
                    clip.approved_by,
                    clip.copyright,
                    clip.created_time,
                    clip.updated_time,
                    user_content.content_type_id,
                    user_content.action,
                    (SELECT COUNT(id) FROM user_content WHERE content_id = clip.id AND action = :streaming AND price = 0 AND content_type_id=1 AND created_time >= :startDate AND created_time <= :endDate) as streaming_free,
                    (SELECT COUNT(id) FROM user_content WHERE content_id = clip.id AND action = :streaming AND price > 0 AND content_type_id=1  AND created_time >= :startDate AND created_time <= :endDate) as streaming_charge,
                    (SELECT COUNT(id) FROM user_content WHERE content_id = clip.id AND action = :streaming AND content_type_id=1 AND created_time >= :startDate AND created_time <= :endDate) as streaming_total,
                    (SELECT COUNT(id) FROM user_content WHERE content_id = clip.id AND action = :download AND content_type_id=1 AND created_time >= :startDate AND created_time <= :endDate) as download_total,
                    (SELECT COALESCE(SUM(price), 0) FROM user_content WHERE content_id = clip.id  AND content_type_id=1  AND created_time >= :startDate AND created_time <= :endDate) as revenue_total
              FROM
                    clip
              INNER JOIN
                    user_content
              ON
                   clip.id = user_content.content_id AND user_content.content_type_id =1
              WHERE
                   user_content.content_type_id =1
              AND
                   user_content.created_time >= :startDate
              AND
                   user_content.created_time <= :endDate
        ";
        $dataClip = $connection->createCommand($sqlClip)
            ->bindParam(':streaming', $streaming)
            ->bindParam(':download', $download)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryAll();
        if(!empty($dataClip)) {
            foreach ($dataClip as $key => $value) {
                $dataClipUpdateDB[] = [
                    $startDate,
                    $value['id'],
                    $value['title_1'],
                    $value['approved_time'],
                    $value['category_id'],
                    $value['page_id'],
                    $value['cp'],
                    $value['approved_by'],
                    $value['copyright'],
                    1,
                    $value['streaming_free'],
                    $value['streaming_charge'],
                    $value['streaming_total'],
                    $value['download_total'],
                    $value['revenue_total'],
                    $value['created_time'],
                    $value['updated_time'],
                    $value['action']
                ];
            }
            try {
                $connection->createCommand()->batchInsert('video_stats_daily',
                    [
                        'date',
                        'content_id',
                        'content_name',
                        'approved_time',
                        'category_id',
                        'page_id',
                        'cp',
                        'approved_by',
                        'copyright',
                        'content_type_id',
                        'streaming_free',
                        'streaming_charge',
                        'streaming_total',
                        'download_total',
                        'revenue_total',
                        'created_time',
                        'updated_time',
                        'action'
                    ], $dataClipUpdateDB)->execute();
            } catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        }

        // Thống kê film
        $sqlFilm = "
              SELECT
                    DISTINCT(film.id),
                    film.title_1,
                    film.approved_time,
                    film.category_id,
                    film.cp_id as cp,
                    film.approved_by,
                    film.copyright,
                    film.created_time,
                    film.updated_time,
                    user_content.content_type_id,
                    user_content.action,
                    (SELECT COUNT(id) FROM user_content WHERE content_id = film.id AND action = :streaming AND price = 0 AND content_type_id=2 AND created_time >= :startDate AND created_time <= :endDate) as streaming_free,
                    (SELECT COUNT(id) FROM user_content WHERE content_id = film.id AND action = :streaming AND price != 0 AND content_type_id=2 AND created_time >= :startDate AND created_time <= :endDate) as streaming_charge,
                    (SELECT COUNT(id) FROM user_content WHERE content_id = film.id AND action = :streaming AND content_type_id=2 AND created_time >= :startDate AND created_time <= :endDate) as streaming_total,
                    (SELECT COUNT(id) FROM user_content WHERE content_id = film.id AND action = :download AND content_type_id=2 AND created_time >= :startDate AND created_time <= :endDate) as download_total,
                    (SELECT COALESCE(SUM(price), 0) FROM user_content WHERE content_id = film.id AND content_type_id=2 AND created_time >= :startDate AND created_time <= :endDate) as revenue_total
              FROM
                    film
              INNER JOIN
                    user_content
              ON
                   film.id = user_content.content_id AND user_content.content_type_id =2
              WHERE
                   user_content.content_type_id =2
              AND
                   user_content.created_time >= :startDate
              AND
                   user_content.created_time <= :endDate
        ";

        $dataFilm = $connection->createCommand($sqlFilm)
            ->bindParam(':streaming', $streaming)
            ->bindParam(':download', $download)
            ->bindParam(':startDate', $startDate)
            ->bindParam(':endDate', $endDate)
            ->queryAll();
        if(!empty($dataFilm)) {
            foreach ($dataFilm as $key => $value) {
                $dataUpdateDB[] = [
                    $startDate,
                    $value['id'],
                    $value['title_1'],
                    $value['approved_time'],
                    $value['category_id'],
                    '0',
                    $value['cp'],
                    $value['approved_by'],
                    $value['copyright'],
                    2,
                    $value['streaming_free'],
                    $value['streaming_charge'],
                    $value['streaming_total'],
                    $value['download_total'],
                    $value['revenue_total'],
                    $value['created_time'],
                    $value['updated_time'],
                    $value['action']
                ];
            }
            try {
                $connection->createCommand()->batchInsert('video_stats_daily',
                 [
                     'date',
                     'content_id',
                     'content_name',
                     'approved_time',
                     'category_id',
                     'page_id',
                     'cp',
                     'approved_by',
                     'copyright',
                     'content_type_id',
                     'streaming_free',
                     'streaming_charge',
                     'streaming_total',
                     'download_total',
                     'revenue_total',
                     'created_time',
                     'updated_time',
                     'action'
                 ], $dataUpdateDB)->execute();
            } catch (Exception $e) {
                 echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        }
    }
}
