<?php

namespace cms\controllers;

use cms\components\BackendController;
use cms\models\Tag;
use cms\models\TextLink;
use common\helpers\StringHelper;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use Yii;

class TextLinkController extends BackendController {
    protected $model;

    public function __construct($id, $module, $config = [])
    {
        $this->model = new TextLink();
        parent::__construct($id, $module, $config);
    }

    public function actionIndex() {
        $searchModel = $this->model;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id)
        ]);
    }


    public function actionCreate() {
        $model = $this->model;
        if ($model->load(Yii::$app->request->post())) {
            if(empty($model->order)) $model->order = 20;
            $model->created_time = time();
            $model->updated_time = time();
            if($model->validate() && $model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }

        }
        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            if(empty($model->order)) $model->order = 20;
            $model->updated_time = time();
            if ($model->validate() && $model->save()) {
                return $this->redirect(['index']);
            }
        }
        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
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

    public function actionChangeStatusHot() {
        if (!Yii::$app->getRequest()->isAjax)
            Yii::$app->end();
        $id = (int) ArrayHelper::getValue($_POST, 'id', 0);
        $status = (int) ArrayHelper::getValue($_POST, 'status', 0);
        $statusChange = ($status == 1) ? 0 : 1;
        $message = ($status == 1) ? (Yii::t('cms', 'app_status_inactive_success')) : (Yii::t('cms', 'app_status_active_success'));
        $titleValue = ($status == 1) ? (Yii::t('cms', 'app_status_active')) : Yii::t('cms', 'app_status_inactive');

        $model = $this->findModel($id);
        $updateStatus = $model->updateAttributes(array('seo' => $statusChange));
        if ($updateStatus) {
            echo Json::encode(array('status' => 1, 'message' => $message, 'value' => $titleValue));
            exit();
        } else {
            echo Json::encode(array('status' => -1, 'message' => Yii::t('cms', 'app_status_active_fail')));
            exit();
        }
    }

    public function actionSort() {
        $id = (int) ArrayHelper::getValue($_POST, 'id', 0);
        $orderParam = ArrayHelper::getValue($_POST, 'order',null);
        if($id){
            $cat = $this->findModel($id);
            if($orderParam != null) {
                $this->updateOrder($cat, $orderParam);

                echo json_encode(['status' => 'success']);
                Yii::$app->end();
            }
            echo json_encode(['status' => 'success']);
            Yii::$app->end();
        }
        Yii::$app->end();
    }

    protected function updateOrder($cat,$order) {
        $cat->order = $order;
        return $cat->save(false);
    }
}
