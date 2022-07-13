<?php
namespace wap\controllers;
use common\models\AdminBase;
use wap\models\News;
use wap\models\NewsCategory;
use Yii;
use yii\helpers\Json;
use  common\components\Utility;
use yii\data\Pagination;
use wap\components\CFunction;

class TagsController extends \wap\controllers\AppController{

    public function actionIndex(){
        $tags = Utility::rewrite(Yii::$app->request->get('alias'));
        if($tags != Yii::$app->request->get('alias')){
            return $this->redirect(CFunction::renderUrlTags($tags));
        }
        $tags = str_replace('-', ' ', $tags);
        $totalItem = News::find()
            ->where('MATCH(news.tags, news.title) AGAINST(\'"'.$tags.'"\' IN BOOLEAN MODE)')
            ->innerJoin('news_category', 'news.news_category_id=news_category.id')
            ->andWhere(['status' => 1])
            ->andWhere(['!=', 'deleted', News::DELETED])
            ->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
            ->count();
        $pages = new Pagination([
            'totalCount' => $totalItem,
            'defaultPageSize'=>30
        ]);
        $news = News::find()
            ->innerJoin('news_category', 'news.news_category_id=news_category.id')
            ->where('MATCH(news.tags, news.title) AGAINST(\'"'.$tags.'"\' IN BOOLEAN MODE)')
            ->andWhere(['status' => 1])
            ->andWhere(['!=', 'deleted', News::DELETED])
            ->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
			->orderBy('news.time_active DESC')
            ->limit($pages->limit)
            ->offset($pages->offset)
            ->asArray()->all();

        $this->pageDescription = "$tags, tin tức, hình ảnh, video mới nhất về $tags.";
        if(!empty($news[0])){
            $this->pageDescription .= ' ' . $news[0]['brief'];
        }
        $this->pageTitle = empty($news[0]) ? $tags : "{$tags}: {$news[0]['title']}";
        $this->pageKeywords = "{$tags} , tin tức {$tags}, video {$tags}, hình ảnh {$tags}, {$tags} mới nhất";

        return $this->render($this->folder . '/tags/index',[
            'list' => $news,
            'tags' => $tags,
            'pages' => $pages
        ]);
    }
}