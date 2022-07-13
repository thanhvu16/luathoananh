<?php
/**
 * @Function: Lớp xử lý phần File manager của hệ thống. Cấu hình ElFinder Fille manager
 * @Author: trinh.kethanh@gmail.com
 * @Date: 20/01/2015
 * @System: Video 2.0
 */

namespace cms\controllers;

use Yii;
use yii\web\Controller;
use zxbodya\yii2\elfinder\ConnectorAction;

class ElFinderController extends Controller {
    /*
     * @return: Cấu hình ElFinder Fille manager
     */
    public function actions() {
        return [
            'connector' => array(
                'class' => ConnectorAction::className(),
                'settings' => array(
                    'root' => Yii::$app->params['img_url']['data_path'] . '/uploads',
                    'URL' => Yii::$app->params['img_url']['data_url'] . '/uploads/',
                    'rootAlias' => 'Home',
                    'mimeDetect' => 'none'
                )
            ),
        ];
    }
}