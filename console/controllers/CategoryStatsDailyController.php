<?php
/**
 * @Function: Lớp xử lý phần thống kê chuyên mục, đẩy dữ liệu vào bảng category_stats_daily
 * @Author: trinh.kethanh@gmail.com
 * @Date: 17/03/2015
 * @System: Video 2.0
 */

namespace console\controllers;

use Yii;
use yii\console\Exception;
use yii\console\Controller;
use console\models\CategoryStatsDaily;

class CategoryStatsDailyController extends Controller
{
    /**
     * Thong ke chuyen muc
     */
    public function actionCategoryStats()
    {
        $run = new CategoryStatsDaily();
        try {
            $run->CategoryStats();
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}