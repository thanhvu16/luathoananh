<?php
/**
 * @Function: Lớp xử lý phần controller & action của hệ thống
 * @Author: trinh.kethanh@gmail.com
 * @Date: 15/01/2015
 * @System: Video 2.0
 */
namespace cms\controllers;

use cms\models\AdminAction;
use common\models\AdminActionBase;
use common\models\AdminControllerBase;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use cms\components\BackendController;
use common\models\AdminBase;
use Yii;

class AdminActionController extends BackendController {
    /*
     * @params: NULL
     * @function: Hàm này cập nhật controller & action vào db
     */
    public function actionUpdatePermission() {
        $connection = Yii::$app->db;

        /*-----------------------------Xử lý Controller-------------------------------------*/
        // Danh sách controller trong file controller tại @cms/controller
        $listFileController = glob(Yii::getAlias('@cms').'/controllers'."/*Controller.php");

        if (is_array($listFileController) && sizeof($listFileController) > 0) { // Nếu tồn tại file controller tại @cms/controller
            foreach ($listFileController as $controller) {
                $class = basename($controller, ".php");
                $arrayFileController[] = $class;
                $arrayFileControllerInsert[] = [$class, $class, date('Y-m-d H:i:s', time())];
            }
            // Danh sách controller đã insert vào db
            $listDBController = AdminControllerBase::find()->asArray()->all();

            if (is_array($listDBController) && sizeof($listDBController) > 0) { // Nếu đã có dữ liệu trong db rùi thì phân tích mảng có trong db
                foreach ($listDBController as $controller) {
                    $arrayDBController[] = $controller['controller'];
                }
                // So sánh 2 mảng, nếu trùng nhau thì không insert, ngược lại sẽ insert các controller mới
                $listUpdateController = array_diff($arrayFileController, $arrayDBController);
                if ($listUpdateController == []) { // Không tồn tại Controller mới không cần insert
                } else { // Insert mảng đã diff
                    foreach ($listUpdateController as $controller) {
                        $listUpdateDB[] = [$controller, $controller, date('Y-m-d H:i:s', time())];
                    }
                    $connection->createCommand()->batchInsert('admin_controller', ['controller', 'desc', 'updated_time'], $listUpdateDB)->execute();
                }
                $listDeleteController = array_diff($arrayDBController, $arrayFileController);
                if (!empty($listDeleteController)) {
                    $connection->createCommand()->delete('admin_controller', ['controller' => $listDeleteController])->execute();
                    $connection->createCommand()->delete('admin_action', ['admin_controller_id' => AdminAction::getControllerID($listDeleteController)])->execute();
                }
            } else { // Insert mảng arrayController load từ file vào db
                $connection->createCommand()->batchInsert('admin_controller', ['controller', 'desc', 'updated_time'], $arrayFileControllerInsert)->execute();
            }
        }

        /*-----------------------------Xử lý Action-------------------------------------*/
        $listController = AdminControllerBase::find()->andWhere('id NOT IN (101, 138)')->asArray()->all(); // Load hết controller trong db
        if (is_array($listController) && sizeof($listController) > 0) {
            foreach ($listController as $key => $value) {
                $arrayActionController = [];
                $arrayActionControllerInsert = [];
                $listIDController[] = $value['id'];

                $controllerName = $value['controller'];
                $className = "cms\\controllers\\{$controllerName}";

                if (class_exists($className)) { // Nếu tồn tại namespace
                    $obj = new $className($controllerName, NULL);
                    $methods = get_class_methods($obj); // Lấy toàn bộ action theo controllers
                    foreach ($methods as $method) {
                        if ($method != 'actions' AND preg_match('/^action/', $method)) {
                            $arrayActionController[] = $method;
                            $arrayActionControllerInsert[] = [$method, $method, date('Y-m-d H:i:s', time()), $value['id'], AdminActionBase::ACTION_ACTIVE]; // Mảng này insert okie
                        }
                    }
                    $listDBAction = AdminActionBase::find() // Load toàn bộ action trong db theo controller ID
                        ->where('admin_controller_id = :controllerID', [':controllerID' => $value['id']])
                        ->asArray()
                        ->all();

                    if (is_array($listDBAction) && sizeof($listDBAction) > 0) { // Nếu db đã có dữ liệu thì load ra
                        $arrayActionDB = [];
                        foreach ($listDBAction as $k => $action) {
                            $arrayActionDB[] = $action['action'];
                        }
                        $listUpdateAction = array_diff($arrayActionController, $arrayActionDB);
                        if ($listUpdateAction == []) {
                        } else {
                            foreach ($listUpdateAction as $action) {
                                $listUpdateDBAction[] = [$action, $action, date('Y-m-d H:i:s', time()), $value['id'], AdminActionBase::ACTION_ACTIVE];
                            }
                            $connection->createCommand()->batchInsert('admin_action', ['action', 'desc', 'updated_time', 'admin_controller_id', 'status'], $listUpdateDBAction)->execute();
                        }
                        $listDeleteAction = array_diff($arrayActionDB, $arrayActionController);
                        if (!empty($listDeleteAction)) {
                            $connection->createCommand()->delete('admin_action', ['action' => $listDeleteAction])->execute();
                        }

                    } else if (!empty($arrayActionControllerInsert)) { // Ngược lại sẽ insert action load từ file
                        $connection->createCommand()->batchInsert('admin_action', ['action', 'desc', 'updated_time', 'admin_controller_id', 'status'], $arrayActionControllerInsert)->execute();
                    }
                }
            }
            /*------------------------------Thực hiện xóa toàn bộ action của bảng action khi xóa controller-------------*/
            $listAllAction = AdminAction::find()->asArray()->all();
            if (is_array($listAllAction) && sizeof($listAllAction) > 0) {
                foreach ($listAllAction as $key => $value) {
                    $listIDControllerAction[] = $value['admin_controller_id'];
                }
                $listAllActionDelete = array_diff($listIDControllerAction, $listIDController);
                $connection->createCommand()->delete('admin_action', ['admin_controller_id' => $listAllActionDelete])->execute();
            }
        }
        echo Json::encode(['status' => 1]);
        exit();
    }
    /*
     * @params: NULL
     * @function: Hàm này xử lý việc thay đổi description của action
     */
    public function actionChangeDescAction() {
        $id = (int) ArrayHelper::getValue($_POST, 'id', 0);
        $desc = ArrayHelper::getValue($_POST, 'desc', '');
        if (!empty($id)) {
            $updateDesc = AdminActionBase::findOne($id)->updateAttributes(['desc' => $desc]);
            if ($updateDesc) {
                echo Json::encode(array('status' => 1, 'desc' => $desc,'message' => Yii::t('cms', 'edit_desc_action_success')));
                exit();
            } else {
                echo Json::encode(array('status' => -1, 'message' => Yii::t('cms', 'edit_desc_action_fail')));
                exit();
            }
        } else {
            echo Json::encode(array('status' => -1, 'message' => Yii::t('cms', 'edit_desc_action_fail')));
            exit();
        }
    }
    /*
     * @params: NULl
     * @function: Hàm này xử lý việc xóa action
     */
    public function actionDeleteAction() {
        $id = (int) ArrayHelper::getValue($_POST, 'id', 0);
        if (!empty($id)) {
            $updateDesc = AdminActionBase::findOne($id)->updateAttributes(['status' => 0]);
            if ($updateDesc) {
                echo Json::encode(array('status' => 1, 'message' => Yii::t('cms', 'delete_action_success')));
                exit();
            } else {
                echo Json::encode(array('status' => -1, 'message' => Yii::t('cms', 'delete_action_fail')));
                exit();
            }
        } else {
            echo Json::encode(array('status' => -1, 'message' => Yii::t('cms', 'delete_action_fail')));
            exit();
        }
    }
    /**
     * Finds the Admin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  integer $id
     * @return AdminBase the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        $model = AdminAction::findOne($id);
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}