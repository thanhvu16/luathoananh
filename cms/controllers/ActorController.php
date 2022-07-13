<?php

namespace cms\controllers;

use Yii;
use cms\models\Actor;
use yii\web\NotFoundHttpException;
use cms\models\search\ActorSearch;
use cms\components\BackendController;

class ActorController extends BackendController {

    /**
     * Lists all Actor models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ActorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Actor model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Actor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Actor();
        if ($model->load(Yii::$app->request->post())) {
            $model->created_time = date('Y-m-d H:i:s', time());
            $model->created_by = Yii::$app->user->getId();
            if (trim($model->name == '')) {
                Yii::$app->session->setFlash('error', Yii::t('cms', 'collection_type_title'));
                return $this->render('create', ['model' => $model]);
            }
            if (Actor::find()->where(['name' => trim($model->name)])->exists()) {
                Yii::$app->session->setFlash('error', Yii::t('cms', 'collection_type_exists'));
                return $this->render('create', ['model' => $model]);
            }
            if ($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Actor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Actor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Actor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Actor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Actor::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}