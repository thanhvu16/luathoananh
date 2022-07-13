<?php
/**
 * @Function: Lớp xử lý phần quản lý cp của hệ thống
 * @Author: trinh.kethanh@gmail.com
 * @Date: 17/06/2015
 * @System: Video 2.0
 */
namespace cms\controllers;

use cms\models\Cp;
use Yii;
use yii\helpers\Json;
use common\models\CpBase;
use cms\models\AdminGroup;
use yii\helpers\ArrayHelper;
use common\models\AdminGroupBase;
use yii\web\NotFoundHttpException;
use common\models\db\AdminGroupDB;
use cms\components\BackendController;

class CpController extends BackendController
{

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CpBase();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    /**
     * Displays a single Cp model.
     * @param  integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id)
        ]);
    }

    /**
     * @throws \yii\base\ExitException
     */
    public function actionChangeStatus()
    {
        if (!Yii::$app->getRequest()->isAjax)
            Yii::$app->end();
        $id = (int) ArrayHelper::getValue($_POST, 'id', 0);
        $status = (int) ArrayHelper::getValue($_POST, 'status', 0);
        $statusChange = ($status == 1) ? 0 : 1;
        $message = ($status == 1) ? (Yii::t('cms', 'app_status_inactive_success')) : (Yii::t('cms', 'app_status_active_success'));
        $titleValue = ($status == 1) ? (Yii::t('cms', 'app_status_active')) : Yii::t('cms', 'app_status_inactive');

        $model = CpBase::findOne($id);
        $updateStatus = $model->updateAttributes(array('id' => $id, 'status' => $statusChange));
        if ($updateStatus) {
            echo Json::encode(array('status' => 1, 'message' => $message, 'value' => $titleValue));
            exit();
        } else {
            echo Json::encode(array('status' => -1, 'message' => Yii::t('cms', 'app_status_active_fail')));
            exit();
        }
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new CpBase();
        $model->created_time = date('Y-m-d H:i:s', time());
        $model->created_by = Yii::$app->user->identity->getId();
        if ($model->load(Yii::$app->request->post())) {
            if (Cp::checkCpNameExists($model->name)) {
                Yii::$app->session->setFlash('error', Yii::t('cms', 'app_cp_exists'));
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Cp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param  integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->updated_time = date('Y-m-d H:i:s', time());
        $model->updated_by = Yii::$app->user->identity->getId();
        if ($model->load(Yii::$app->request->post())) {
            if (Cp::checkCpNameExists($model->name) && $model->name != Cp::getCpNameByID($id)) {
                Yii::$app->session->setFlash('error', Yii::t('cms', 'app_cp_exists'));
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model
            ]);
        }
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Xóa 1 hoặc nhiều nhóm của hệ thống
     */
    public function actionDeleteAll()
    {
        $ids = ArrayHelper::getValue($_POST, 'ids', '');
        if (!empty($ids)) {
            if (sizeof($ids) > 0) {
                AdminGroupBase::deleteAll("id IN ($ids)");
                Yii::$app->session->setFlash('success', Yii::t('cms', 'app_delete_all_success'));
                echo Json::encode(array('status' => 1, 'message' => Yii::t('cms', 'app_delete_all_success')));
                exit();
            }
        } else {
            echo Json::encode(array('status' => -1, 'message' => Yii::t('cms', 'app_delete_all_fail')));
            exit();
        }
    }

    /**
     * Finds the CpBase model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  integer $id
     * @return CpBase the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = CpBase::findOne($id);
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}