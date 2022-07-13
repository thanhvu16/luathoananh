<?php

namespace cms\controllers;

use cms\components\BackendController;
use cms\models\Ads;
use cms\models\Config;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\components\KLogger;
use common\components\Utility;

class AdsController extends BackendController
{
    protected $model;
    protected $configSite;

    public function __construct($id, $module, $config = [])
    {
        $this->model = new Ads();
        parent::__construct($id, $module, $config);
    }

    public function actionIndex()
    {
        $searchModel = $this->model;
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id)
        ]);
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
        $updateStatus = $model->updateAttributes(array('is_hot' => $statusChange));
        if ($updateStatus) {
            echo Json::encode(array('status' => 1, 'message' => $message, 'value' => $titleValue));
            exit();
        } else {
            echo Json::encode(array('status' => -1, 'message' => Yii::t('cms', 'app_status_active_fail')));
            exit();
        }
    }

    public function actionChangeStatus()
    {
        if (!Yii::$app->getRequest()->isAjax)
            Yii::$app->end();
        $id = (int) ArrayHelper::getValue($_POST, 'id', 0);
        $status = (int) ArrayHelper::getValue($_POST, 'status', 0);
        $statusChange = ($status == 1) ? 0 : 1;
        $message = ($status == 1) ? (Yii::t('cms', 'Update status inactive success')) : (Yii::t('cms', 'Update status active success'));
        $titleValue = ($status == 1) ? (Yii::t('cms', 'Update status active success')) : Yii::t('cms', 'Update status inactive success');

        $model = $this->findModel($id);
        if ($model instanceof $this->model) {
            $updateStatus = $model->updateAttributes(array('id' => $id, 'status' => $statusChange));
            if ($updateStatus) {
                echo Json::encode(array('status' => 1, 'message' => $message, 'value' => $titleValue));
                exit();
            } else {
                echo Json::encode(array('status' => -1, 'message' => Yii::t('cms', 'app_status_active_fail')));
                exit();
            }
        }
    }

    public function actionCreate()
    {
        $model = $this->model;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
				if(Yii::$app->request->post('image-data')){
                    $imgEncode =Yii::$app->request->post('image-data');
                    Utility::uploadImgAds($imgEncode,$model);
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()) {
				if(Yii::$app->request->post('image-data')){
                    $imgEncode =Yii::$app->request->post('image-data');
                    Utility::uploadImgAds($imgEncode,$model);
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionDeleteAll()
    {
        $ids = ArrayHelper::getValue($_POST, 'ids', '');
        if (!empty($ids)) {
            if (sizeof($ids) > 0) {
                $this->model->deleteAllItem("id IN ($ids)");
                Yii::$app->session->setFlash('success', Yii::t('cms', 'Delete all success'));
                echo Json::encode(array('status' => 1, 'message' => Yii::t('cms', 'Delete all success')));
                exit();
            }
        } else {
            echo Json::encode(array('status' => -1, 'message' => Yii::t('cms', 'Delete all fail')));
            exit();
        }
    }

    protected function findModel($id)
    {
        $model = $this->model::findOne($id);
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}