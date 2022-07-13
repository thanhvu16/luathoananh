<?php
/**
 * @Function: Lớp xử lý danh mục tin tức của hệ thống
 * @Author: trinh.kethanh@gmail.com
 * @Date: 20/01/2015
 * @System: Video 2.0
 */

namespace cms\controllers;

use cms\components\BackendController;
use cms\models\Admin;
use cms\models\NewsCategory;
use cms\models\PermissionCategory;
use common\components\Utility;
use Yii;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

class PermissionCategoryController extends BackendController {

    protected $model;

    public function __construct($id, $module, $config = [])
    {
        $this->model = new PermissionCategory();
        parent::__construct($id, $module, $config);
    }

    public function actionAddItem(){
        $this->layout = false;
        $ids = ArrayHelper::getValue($_POST, 'ids');
        $accountId = ArrayHelper::getValue($_POST, 'accountId');
        if(empty($ids)) {
            echo Json::encode(['msg' => 'Data Empty', 'status' => 0]);
            Yii::$app->end();
        }
        if(empty($accountId)) {
            echo Json::encode(['msg' => 'Error', 'status' => 0]);
            Yii::$app->end();
        }
        $data = [];
        foreach (explode(',', $ids) as $id) {
            $data[] = [$id, $accountId, $this->model::ACTIVE, 0, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), Yii::$app->user->id, Yii::$app->user->id];
        }
        Yii::$app->db->createCommand()->batchInsert('permission_category', ['category_id', 'account_id', 'status', 'is_deleted', 'created_time', 'updated_time', 'created_by', 'updated_by'], $data)->execute();
        echo Json::encode(['msg' => 'Success', 'status' => 1]);
        Yii::$app->end();
    }

    public function actionPopup()
    {
        $this->layout=false;
        $model = new NewsCategory();
        $accountId = ArrayHelper::getValue($_POST, 'id', null);
        $data = $model->getListPopup($accountId, Yii::$app->request->queryParams);

        return $this->render('popup', [
            'data' => $data,
            'accountId' => $accountId
        ]);
    }


    public function actionPopupPagination()
    {
        $this->layout=false;
        $model = new NewsCategory();
        $accountId = ArrayHelper::getValue($_POST, 'id', null);
        $data = $model->getListPopup($accountId, Yii::$app->request->queryParams);

        return $this->render('popup-pag', [
            'data' => $data,
            'accountId' => $accountId
        ]);
    }

    public function actionIndex() {
        $searchModel = new Admin();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionView($id) {
        $searchModel = $this->model;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'accountId' => $id
        ]);
    }

    public function actionDelete() {
        $model = $this->model;
        $model::updateAll(['is_deleted' => $model::IS_DELETED], [
            'id' => explode(',', Yii::$app->request->post('data'))
        ]);
        echo json_encode(['status' => 1]);
        Yii::$app->end();
    }

    protected function findModel($id) {
        if (($model = $this->model::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionChangeStatus() {
        if (!Yii::$app->getRequest()->isAjax)
            Yii::$app->end();
        $id = (int) ArrayHelper::getValue($_POST, 'id', 0);
        $status = (int) ArrayHelper::getValue($_POST, 'status', 0);
        $statusChange = ($status == 1) ? 0 : 1;
        $message = ($status == 1) ? (Yii::t('cms', 'app_status_inactive_success')) : (Yii::t('cms', 'app_status_active_success'));
        $titleValue = ($status == 1) ? (Yii::t('cms', 'app_status_active')) : Yii::t('cms', 'app_status_inactive');

        $model = $this->findModel($id);
        $updateStatus = $model->updateAttributes(array('status' => $statusChange));
        if ($updateStatus) {
            echo Json::encode(array('status' => 1, 'message' => $message, 'value' => $titleValue));
            exit();
        } else {
            echo Json::encode(array('status' => -1, 'message' => Yii::t('cms', 'app_status_active_fail')));
            exit();
        }
    }
}
