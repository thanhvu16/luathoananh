<?php
/**
 * Created by PhpStorm.
 * User: ungnv
 * Date: 4/24/2017
 * Time: 3:49 PM
 */

namespace wap\models;
use common\components\Language;
use phpDocumentor\Reflection\Types\Self_;
use Yii;

class News extends \common\models\NewsBase
{
    public static function getListNewsByCategoryId($cateId, $limit, $offset, $notId = []){
        $query = self::find()
            ->where(['news_category_id' => $cateId])
            ->andWhere(['status' => self::NEWS_ACTIVE])
            ->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
            ->andWhere(['<>', 'deleted', self::DELETED]);
        if(!empty($notId)){
            $query->andWhere(['NOT IN', 'id', array_values($notId)]);
        }
        $result = $query->orderBy('time_active DESC')
			->orderBy('time_active DESC')
            ->limit($limit)
            ->offset($offset)
            ->asArray()
            ->all();
        return $result;
    }
	
    public static function getListNewsByCategoryIdPageDetail($cateId, $limit){
        $news = self::find()
            ->where(['news_category_id' => $cateId])
            ->andWhere(['status' => self::NEWS_ACTIVE])
            ->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
            ->andWhere(['!=', 'deleted', self::DELETED])
			->orderBy('time_active DESC')
            ->limit($limit)
            ->asArray()
            ->all();
        $result = NewsCategory::getCategoryByNews($news);
        return $result;
    }

    public static function getTopCategory($cateId){
        $result = self::find()
            ->where(['news_category_id' => $cateId])
            ->andWhere(['status' => self::NEWS_ACTIVE])
            ->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
            ->andWhere(['!=', 'deleted', self::DELETED])
            ->orderBy('time_active DESC')
            ->limit(5)
            ->asArray()
            ->all();
        return $result;
    }

    public static function getTotalNewsByCateId($cateId){
        $result = self::find()
            ->where(['news_category_id' => $cateId])
            ->andWhere(['status' => self::NEWS_ACTIVE])
            ->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
            ->andWhere(['!=', 'deleted', self::DELETED])
            ->count();
        return $result;
    }

    public static function getNewsCollection($collectionId, $limit = 5){
        $nameCache = 'LIST_NEWS_BY_COLLECTION_' . $collectionId . '_LIMIT_' . $limit;
        $data = Yii::$app->cache->get($nameCache);
        if(empty($data)) {
            if (empty($collectionId)) return false;
            $collection = Collection::findOne($collectionId);
            if (!empty($collection)) {
                $listNews = self::find()
                    ->innerJoin('collection_news as c', 'c.news_id = news.id')
                    ->where(['c.collection_id' => $collection->id])
                    ->andWhere(['news.status' => self::NEWS_ACTIVE])
                    ->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
                    ->andWhere(['!=', 'news.deleted', self::DELETED])
                    ->orderBy('c.created_time DESC')
                    ->limit($limit)
                    ->asArray()
                    ->all();
                $data = ['collection' => $collection, 'listNews' => $listNews];
                \Yii::$app->cache->set($nameCache, $data, 600);
                return $data;
            }
            return false;
        }
        return ['collection' => $data['collection'], 'listNews' => $data['listNews']];
    }
	
	public static function getListNewsHot($offset = 0, $limit = 20){
        $news = News::find()
            ->where(['status' => 1])
            ->andWhere(['is_hot' => 1])
            ->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
            ->andWhere(['!=', 'deleted', self::DELETED])
            ->orderBy('time_active DESC')
            ->limit($limit)
            ->offset($offset)
            ->asArray()
            ->all();
        $result = NewsCategory::getCategoryByNews($news);
        return $result;
    }

    public static function getListNews($offset = 0, $limit = 20){
        $news = self::find()
            ->where(['status' => 1])
            ->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
            ->andWhere(['!=', 'deleted', self::DELETED])
            ->orderBy('is_hot DESC, time_active DESC, id DESC')
            ->limit($limit)
            ->offset($offset)
            ->asArray()
            ->all();
        $result = NewsCategory::getCategoryByNews($news);
        return $result;
    }

    public static function getNewsRelated($ids){
        $result = self::find()
            ->where(['IN', 'news.id', $ids])
            ->andWhere(['status' => self::NEWS_ACTIVE])
            ->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
            ->andWhere(['<>', 'deleted', self::DELETED])
            ->asArray()
            ->all();
        return $result;
    }
	
	public static function getNewsTimeStartMatch($ids, $limit = 7){
        $result = News::find()
            ->select('id, title, slug, time_start_match, news_category_id')
            ->where(['status' => self::NEWS_ACTIVE])
            ->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
            ->andWhere("time_start_match is not null  AND '" . time() . "' < time_start_match")
            ->andWhere(['news_category_id' => $ids])
            ->andWhere(['<>', 'deleted', self::DELETED])
            ->orderBy('time_start_match DESC')
            ->limit($limit)
            ->asArray()
            ->all();
		return $result;
	}

	public static function getListNewsBySender($senderId, $limit, $offset){
        $news = self::find()
            ->where(['status' => 1])
            ->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
            ->andWhere(['<>', 'deleted', self::DELETED])
            ->andWhere(['sender_id' => $senderId])
            ->orderBy('is_hot DESC')
            ->orderBy('time_active DESC')
            ->limit($limit)
            ->offset($offset)
            ->asArray()
            ->all();
        $result = NewsCategory::getCategoryByNews($news);
        return $result;
    }

    public static function getTotalListNewsBySender($senderId){
        $total = self::find()
            ->where(['status' => 1])
            ->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
            ->andWhere(['<>', 'deleted', self::DELETED])
            ->count();
        return $total;
    }

    public static function getListNewsBySport($sportId){
        $category = NewsCategory::getCategoryBySport($sportId);
        $categoryIds = array_column($category, 'id');
        $result = self::find()
            ->where(['status' => 1])
            ->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
            ->andWhere(['<>', 'deleted', self::DELETED])
            ->andWhere(['news_category_id' => $categoryIds])
            ->orderBy('is_hot DESC')
            ->orderBy('time_active DESC')
            ->limit(8)
            ->asArray()
            ->all();
        return $result;
    }

    public static function getListNewsByDateRange($startDate, $endDate){
        $result = News::find()
            ->where(['status' => 1])
            ->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
            ->andWhere(['<>', 'deleted', News::DELETED])
            ->andWhere("time_start_match BETWEEN '".strtotime($startDate)."' AND '".strtotime($endDate)."'")
            ->orderBy('news_category_id')
            ->orderBy('time_start_match DESC')
            ->asArray()
            ->all();
        return $result;
    }

    public static function getListNewsByLeagueId($leagueId, $limit, $offset){
        $result = self::find()
            ->where(['status' => 1])
            ->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
            ->andWhere(['<>', 'deleted', self::DELETED])
            ->andWhere(['league_id' => $leagueId])
            ->orderBy('is_hot DESC')
            ->orderBy('time_active DESC')
            ->limit($limit)
            ->offset($offset)
            ->asArray()
            ->all();
        return $result;
    }

    public static function getTotalNewsByLeagueId($leagueId){
        $result = self::find()
            ->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
            ->andWhere(['<>', 'deleted', self::DELETED])
            ->andWhere(['league_id' => $leagueId])
            ->count();
        return $result;
    }

    public static function getNewsHot($limit = 12) {
        $notId = 2;
        $listChildCate = NewsCategory::getCategoryChildren($notId);
        $listChildCate = array_column($listChildCate, 'id');
        array_push($listChildCate, $notId);
        $result = self::find()
            ->where('news_category_id NOT IN ('.implode(',', $listChildCate).')')
            ->andWhere(['status' => self::NEWS_ACTIVE])
            ->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
            ->andWhere(['<>', 'deleted', self::DELETED])
            ->orderBy('is_hot DESC, time_active DESC')
            ->limit($limit)
            ->asArray()
            ->all();
        return $result;
    }
    public static function getNewsHotIndex($categories, $isHot, $limit = 12) {
        $result = self::find()
            ->where(['status' => self::NEWS_ACTIVE])
            ->andFilterWhere(['is_hot' => is_numeric($isHot) ? $isHot : null])
            ->andWhere(['<>', 'deleted', self::DELETED])
            ->andFilterWhere(['news_category_id' => $categories])
            ->orderBy('is_hot DESC, time_active DESC')
            ->limit($limit)
            ->asArray()
            ->all();
        return $result;
    }

    public static function getNewsHot2($limit = 12, $notInIds = []) {
        $result = self::find()
            ->where(['status' => self::NEWS_ACTIVE])
//            ->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
            ->andWhere(['<>', 'deleted', self::DELETED]);

        if (!empty($notInIds)) {
            $result = $result->andWhere('id NOT IN ('.implode(',', $notInIds).')');
        }

        $result = $result->orderBy('is_hot DESC, time_active DESC')
        ->limit($limit)
        ->asArray()
        ->all();

        return $result;
    }

    public static function getListNewsByCategories($categories, $limit = 8){

        foreach ($categories as $key => $category) {
            $categoryIds = [$category['id']];
            $listChildCate = $category['children'];
            $childrenId = array_column($listChildCate, 'id');
            $categoryIds = array_merge($categoryIds, $childrenId);
            $news = self::find()
            ->where(['status' => 1])
            ->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
            ->andWhere(['<>', 'deleted', self::DELETED])
            ->andWhere(['news_category_id' => $categoryIds])
            ->orderBy('is_hot DESC')
            ->orderBy('time_active DESC')
            ->limit($limit)
            ->asArray()
            ->all();
            $news = NewsCategory::getCategoryByNews($news);
            $categories[$key]['news'] = $news;
        }
        
        return $categories;
    }

    public static function getListNewsByCateId($cateId, $limit = 8, $page = 1)
    {
        $offset = ($page - 1) * $limit;
        $news = self::find()
        ->where(['status' => 1])
        ->andWhere(['<>', 'deleted', self::DELETED])
        ->andWhere(['news_category_id' => $cateId])
        ->orderBy('is_hot DESC')
        ->orderBy('time_active DESC')
        ->offset($offset)
        ->limit($limit)
        ->asArray()
        ->all();
		$news = NewsCategory::getCategoryByNews($news);
        return $news;
    }

    public static function countListNewsByCateId($cateId)
    {
        $news = self::find()
        ->where(['status' => 1])
        ->andWhere(['<>', 'deleted', self::DELETED])
        ->andWhere(['news_category_id' => $cateId])
        ->count();

        return $news;
    }

    public static function getNewsById($id)
    {
        $news = self::find()
            ->select(['news.*', 'news_category.title AS cate_title'])
            ->where(['status' => 1])
            ->andWhere(['<>', 'deleted', self::DELETED])
            ->andWhere(['news.id' => $id])
            ->leftJoin('news_category', 'news.news_category_id = news_category.id')
            ->asArray()
            ->one();

        return $news;
    }
}