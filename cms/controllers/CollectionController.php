<?php

namespace cms\controllers;

use cms\components\BackendController;
use cms\models\Collection;
use cms\models\CollectionNews;
use cms\models\News;
use common\helpers\StringHelper;
use common\models\CollectionBase;
use yii\helpers\Json;
use Yii;
use yii\helpers\ArrayHelper;

class CollectionController extends BackendController {
    public function actionIndex() {
        $searchModel = new CollectionBase();
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
        $model = new Collection();
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                return $this->redirect(['index']);
            }

        } else {
            return $this->render('update', [
                'model' => $model
            ]);
        }
    }

    protected function findModel($id) {
        if (($model = Collection::findOne($id)) !== null) {
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

        $model = $this->findModel();
        $updateStatus = $model->updateAttributes(array('status' => $statusChange));
        if ($updateStatus) {
            echo Json::encode(array('status' => 1, 'message' => $message, 'value' => $titleValue));
            exit();
        } else {
            echo Json::encode(array('status' => -1, 'message' => Yii::t('cms', 'app_status_active_fail')));
            exit();
        }
    }

    public function actionGetListNews()
    {
        $this->layout = false;
        $id = (int)ArrayHelper::getValue($_POST, 'id', 0);
        $type = ArrayHelper::getValue($_POST, 'type', null);
        $model = $this->findModel($id);

        return $this->render('popup', [
            'data' => $model,
            'type' => $type,
        ]);
    }

    public function actionPopup()
    {
        $this->layout=false;
        $model = new News();
        $collectionId = ArrayHelper::getValue($_POST, 'id', null);
        $data = $model->getListPopup($collectionId, Yii::$app->request->queryParams);

        return $this->render('popup', [
            'data' => $data,
            'collectionId' => $collectionId
        ]);
    }
    public function actionPopupPagination()
    {
        $this->layout=false;
        $model = new News();
        $collectionId = ArrayHelper::getValue($_POST, 'id', null);
        $data = $model->getListPopup($collectionId, Yii::$app->request->queryParams);

        return $this->render('popup-pag', [
            'data' => $data,
        ]);
    }

    public function actionList($id)
    {
        $model = new News();
        $data = $model->getListNewsCollection($id);

        return $this->render('popup-list', [
            'data' => $data,
            'collectionId' => $id
        ]);
    }

    public function actionAddNews(){
        $collectionId = ArrayHelper::getValue($_POST, 'collectionId', null);
        $newsId = ArrayHelper::getValue($_POST, 'data', null);
        if (!empty($collectionId) && !empty($newsId)):
            CollectionNews::addNews($collectionId,$newsId);
            echo Json::encode(['status' => 1, 'message' => Yii::t('cms', 'Update success')]);
        else:
            echo Json::encode(['status' => -1, 'message' => Yii::t('cms', 'Cập nhật lỗi')]);
        endif;
        Yii::$app->end();
    }

    public function actionDeleteNews(){
        $collectionId = ArrayHelper::getValue($_POST, 'collectionId', null);
        $newsId = ArrayHelper::getValue($_POST, 'data', null);
        if (!empty($collectionId) && !empty($newsId)):
            CollectionNews::deleteNews($collectionId,$newsId);
            echo Json::encode(['status' => 1, 'message' => Yii::t('cms', 'Update success')]);
        else:
            echo Json::encode(['status' => -1, 'message' => Yii::t('cms', 'Cập nhật lỗi')]);
        endif;
        Yii::$app->end();
    }

    public function actionSortNews(){

        $id = (int) ArrayHelper::getValue($_POST, 'id', 0);
        $collectionId = (int) ArrayHelper::getValue($_POST, 'collectionId', 0);
        $sort = ArrayHelper::getValue($_POST, 'sort',null);
        $orderParam = ArrayHelper::getValue($_POST, 'order',null);
        if($id && $collectionId){
            $cat = CollectionNews::findOne(['collection_id' => $collectionId, 'news_id' => $id]);
            if($orderParam != null) {
                $cat->order = $orderParam;
                $cat->save();
            }
            echo json_encode(['status' => 'success']);
            Yii::$app->end();
        }
        Yii::$app->end();
    }
}
