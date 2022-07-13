<?php

namespace wap\controllers;

use wap\models\News;
use Yii;
use yii\web\Controller;

class CommentEmbedController extends AppController
{

    
    public function actionIndex($id){
        if(empty($id)) {
			throw new \yii\web\NotFoundHttpException;
		}
        $news = News::findOne(['id' => $id]);
        if(empty($news) || ($news->status != 1 && !isset($_GET['preview']))) {
			throw new \yii\web\NotFoundHttpException;
		}

        if($news->deleted == 1 || strtotime($news->time_active) > time()){
			throw new \yii\web\NotFoundHttpException;
		}
        $this->layout = 'embed';
        return $this->render('//comment/index', [
            'model' => $news
        ]);
    }

}
