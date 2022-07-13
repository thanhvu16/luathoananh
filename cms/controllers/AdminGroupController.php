<?php
/**
 * @Function: Lớp xử lý phần nhóm người dùng của hệ thống
 * @Author: trinh.kethanh@gmail.com
 * @Date: 08/01/2015
 * @System: Video 2.0
 */
namespace cms\controllers;

use Yii;
use yii\helpers\Json;
use cms\models\AdminGroup;
use common\models\AdminBase;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use common\models\AdminGroupBase;
use yii\web\NotFoundHttpException;
use common\models\db\AdminGroupDB;
use common\models\AdminActionBase;
use cms\components\BackendController;
use common\models\AdminControllerBase;
use common\models\AdminGroupPermissionBase;

class AdminGroupController extends BackendController
{

    /**
     * @return string
     */
    public function actionAdmin()
    {
        $searchModel = new AdminGroupBase();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        return $this->render('admin', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    /**
     * Displays a single Menu model.
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

        $model = AdminGroupBase::findOne($id);
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
        $model = new AdminGroupDB();
        $model->created_time = date('Y-m-d H:i:s', time());
        $model->created_by = Yii::$app->user->identity->getId();
        if ($model->load(Yii::$app->request->post())) {
            if (AdminGroup::checkGroupNameExists($model->group_name)) {
                Yii::$app->session->setFlash('error', Yii::t('cms', 'app_group_exists'));
                return $this->render('create', [
                    'model' => $model,
                ]);
            } else if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SystemGroup model.
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
            if (AdminGroup::checkGroupNameExists($model->group_name) && $model->group_name != AdminGroup::getGroupNameByID($id)) {
                Yii::$app->session->setFlash('error', Yii::t('cms', 'app_group_exists'));
                return $this->render('update', [
                    'model' => $model,
                ]);
            } else if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model
            ]);
        }
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionPermission()
    {
        $id = (int) ArrayHelper::getValue($_GET, 'id', 0);
        $rawData = [];
        $adminDataProvider = new ActiveDataProvider([
            'query' => AdminBase::find()->andWhere('admin_group_id = :groupID', [':groupID' => $id])->with(['createdBy','group']),
            'pagination' => ['pageSize' => 12]
        ]);

        $permission = AdminGroupPermissionBase::find()
            ->select(['controller', 'permission'])
            ->where('admin_group_id = :groupID', [':groupID' => $id])
            ->all();

        $arrayGroupPermission = array();
        if (!empty($permission)) {
            foreach ($permission as $row) {
                $arrayGroupPermission[$row['controller']] = unserialize($row['permission']);
            }
        }
        $arrayController = AdminControllerBase::find()->select(['id','controller'])->with(['actionActive','createdBy'])->all();
        if (!empty($arrayController)) {
            foreach ($arrayController as $key => $item) {
                $temp = [];
                $temp['id'] = $item->id;
                $temp['controller'] = $item->controller;

                //$arrayAction = AdminActionBase::find()
                //    ->where('admin_controller_id = :ID', [':ID' => $item->id])
                //    ->andWhere('status = :status', [':status' => 1])
                //    ->all();
                $arrayAction = $item->actionActive;
                foreach ($arrayAction as $k => $model) {
                    $temp['attributes'][$model->action] = $model->action;
                    if (isset($arrayGroupPermission[$item->controller]))
                        $temp['permission'][$model->action] = in_array($model->action, $arrayGroupPermission[$item->controller]) ? $model->action : false;
                    else
                        $temp['permission'][$model->action] = false;
                }
                $rawData[] = $temp;
            }
        }
        $arrayDataProvider = new ArrayDataProvider([
            'allModels' => $rawData,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);
        return $this->render('permission', array(
            'model' => $this->findModel($id),
            'adminDataProvider' => $adminDataProvider,
            'arrayDataProvider' => $arrayDataProvider
        ));
    }

    /**
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionPermissionAdd()
    {
        $id = isset($_REQUEST['id']) ? (int) ArrayHelper::getValue($_REQUEST, 'id', 0) : false;
        if (!$id) {
            throw new NotFoundHttpException('The requested page does not exist.');
        } else {
            $listController = glob(Yii::getAlias('@cms').'/controllers'."/*Controller.php");
            if (is_array($listController) && sizeof($listController) > 0) {
                foreach ($listController as $controller) {
                    $class = basename($controller, ".php");
                    $objGroupPermission = AdminGroupPermissionBase::find()
                        ->select('permission')
                        ->where('admin_group_id = :groupID', [':groupID' => $id])
                        ->andWhere('controller = :controller', [':controller' => $class])
                        ->one();
                    if (isset($_REQUEST[$class])) {
                        if (!$objGroupPermission) {
                            $groupPermission = new AdminGroupPermissionBase();
                            $groupPermission->controller = $class;
                            $groupPermission->admin_group_id = $id;
                            $groupPermission->permission = serialize($_REQUEST[$class]);
                            $groupPermission->insert();
                        } else {
                            $objGroupPermission->permission = serialize($_REQUEST[$class]);
                            AdminGroupPermissionBase::updateAll(['permission' => serialize($_REQUEST[$class])], 'admin_group_id = :groupID AND controller = :controller', [':groupID' => $id, ':controller' => $class]);
                        }
                    } else {
                        AdminGroupPermissionBase::deleteAll('admin_group_id = :groupID AND controller = :controller', [':groupID' => $id, ':controller' => $class]);
                    }
                }
            }
        }
        Yii::$app->session->setFlash('success', Yii::t('cms', 'permission_success'));
        return $this->redirect(['permission', 'id' => $id]);
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
        return $this->redirect(['admin']);
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
     * Finds the BSystemGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  integer $id
     * @return AdminGroupBase the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = AdminGroupBase::findOne($id);
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}