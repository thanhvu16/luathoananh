<?php
/**
 * @Function: Lớp mặc định của phần wap
 * @Author: longnh2@vega.com.vn
 * @Date: 18/04/2017
 * @System: Mclip version 2
 */

namespace wap\controllers;

use common\components\CFunction;
use common\components\Utility;
use common\models\LeagueBase;
use common\models\MagazineBase;
use common\models\MagazineContentBase;
use common\models\MatchBase;
use common\models\TeamBase;
use wap\models\FbCountry;
use wap\models\FbLeagueSeo;
use wap\models\FbOdds;
use wap\models\League;
use wap\models\Match;
use wap\models\News;
use wap\models\NewsCategory;
use wap\models\OddsRatio;
use wap\models\Team;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class MagazineController extends AppController
{


    public function actionIndex()
    {
        $limit = 10;
        if(Yii::$app->request->isAjax){
            $this->layout = false;
            $page = Yii::$app->request->get('page', 2);
            $offset = ($page - 1)* $limit;
            $magazines = MagazineBase::find()
            ->where(['status' => MagazineBase::STATUS_PUBLISH])
            ->andWhere(['<=', 'public_time', date('Y-m-d H:i:s')])
            ->limit($limit)->offset($offset)->orderBy('id DESC')->all();

            return $this->render('_list',[
                'magazines' => $magazines
            ]);
        }
        $this->layout = '@app/views/layouts/magazine.php';
        $this->canonical = Url::current([], 'https');

        $this->pageTitle = 'Emagazine - Thethao.vn';
        $this->pageDescription = 'Emagazine - Chuyên trang của thethao.vn với đồ họa đẹp, hình ảnh chất lượng cao với những câu chuyện chuyên sâu, chân dung nhân vật, vấn đề thời sự nóng hổi bóng đá thể thao.';
        $this->pageKeywords = 'emagazine, thethao.vn, tin the thao, bong da, the thao';

        $magazines = MagazineBase::find()
            ->where(['status' => MagazineBase::STATUS_PUBLISH])
            ->andWhere(['<=', 'public_time', date('Y-m-d H:i:s')])
            ->limit($limit)->offset(0)->orderBy('id DESC')->all();

        return $this->render('index',[
            'magazines' => $magazines
        ]);
    }

    public function actionDetail(){
        $id = Yii::$app->request->get('id', 0);

        $model = MagazineBase::find()
            ->where(['id' => $id, 'status' => MagazineBase::STATUS_PUBLISH])
            ->andWhere(['<=', 'public_time', date('Y-m-d H:i:s')])
            ->one();

        if (empty($model))
            throw new NotFoundHttpException('The requested page does not exist.');

        $this->canonical = Url::current([], 'https');

        $this->pageTitle = $model->title;
        $this->pageDescription = $model->seo_description;
        $this->pageKeywords = $model->seo_keywords;
        $this->pageOgImage = $model->image;


        $contents = MagazineContentBase::find()->where(['magazine_id' => $model->id])->orderBy('sort_order ASC')->all();
        $newsRelated = [];
        if(!empty($model->rel_ids)) {
            $relIds =  explode(',', $model->rel_ids);
            $newsRelated = News::getNewsRelated($relIds);
            $newsRelated = NewsCategory::getCategoryByNews($newsRelated);
        }

        $this->layout = '@app/views/layouts/magazine.php';
        return $this->render('detail', [
            'contents' => $contents,
            'model' => $model,
            'newsRelated' => $newsRelated,
        ]);
    }

    public function actionPreview(){
        $id = Yii::$app->request->get('id', 0);
        $t = Yii::$app->request->get('t', 0);
        $token = Yii::$app->request->get('token', 0);

        if(!CFunction::checkToken($id, $t, $token)){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $model = MagazineBase::findOne(['id' => $id]);

        if (empty($model))
            throw new NotFoundHttpException('The requested page does not exist.');

        $contents = MagazineContentBase::find()->where(['magazine_id' => $model->id])->orderBy('sort_order ASC')->all();

        $this->layout = '@app/views/layouts/magazine.php';
        return $this->render('detail', [
            'contents' => $contents,
            'model' => $model,
        ]);
    }

    public function actionLoadMore()
    {
        $this->layout = false;
        $page = (int)Yii::$app->request->get('page', 1);
        if(empty($page)) $page = 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;
        $listNews = News::getListNewsNew($offset, $limit);
        if(Yii::$app->mobiledetect->isMobile()){
            echo \wap\widgets\ListMobileWidget::widget(['list' => $listNews, 'needH' => 'h4']);
        }else{
            echo \wap\widgets\ListWidget::widget(['list' => $listNews]);
        }
        die;
    }
}