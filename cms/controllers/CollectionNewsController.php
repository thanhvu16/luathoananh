<?php

namespace cms\controllers;

use cms\components\BackendController;
use cms\models\Collection;
use common\helpers\StringHelper;
use common\models\CollectionBase;
use Yii;

class CollectionNewsController extends BackendController {
    public function actionIndex() {
        $searchModel = new CollectionBase();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}
