<?php
/**
 * @Function: Lớp xử lý phần log action người dùng của hệ thống
 * @Author: trinh.kethanh@gmail.com
 * @Date: 28/01/2015
 * @System: Video 2.0
 */

namespace cms\controllers;

use cms\components\BackendController;
use common\models\AdminLogMBase;
use Yii;

class AdminLogController extends BackendController {
    /*
     * @params: NULL
     * @function: Hiển thị danh sách người dùng của hệ thống
     */
    public function actionAdmin() {
        $searchModel = new AdminLogMBase();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        return $this->render('admin', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }
}