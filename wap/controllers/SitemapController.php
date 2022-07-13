<?php
namespace wap\controllers;

use wap\controllers\AppController;
use wap\models\FbStandings;
use wap\models\League;
use wap\models\Match;
use wap\models\News;
use wap\models\NewsCategory;
use wap\components\CFunction;
use Yii;
use yii\helpers\Url;
use wap\models\Magazine;
use common\components\Utility;

class SitemapController extends AppController{
	const DIRECTORY = '/sitemap/';

	public function actionAll(){
		$this->actionIndex();
		$this->actionCategory();
		$this->actionService();
		$this->actionRenderMagazine();
		$this->actionArticle();
		$this->pingGg();
	}
	
	function pingGg(){
		$arrLink = [
			Url::base(true) . '/sitemap.xml',
		];
		
		if(!$this->checkPingArticle()){
			return;
		}
		
		$count = News::find()
			->where(['status' => 1])
			->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
			->andWhere(['!=', 'deleted', 1])
			->count();
		$countFile = ceil($count/1000);
		$arrLink[] = Url::base(true) . '/sitemap/article-'.$countFile.'.xml';
		
		foreach($arrLink as $link) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://www.google.com/ping?sitemap=" . $link);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			curl_close($ch);
		}
		

		$this->updateNewsStatusPing();
	}
	
	function updateNewsStatusPing(){
		\Yii::$app->db->createCommand("UPDATE news SET status_ping=1 
			WHERE status_ping=0 AND status = 1 
			AND ('".date('Y-m-d H:i:s')."' > time_active OR time_active is null) 
			AND deleted != 1")
			->execute();
		$query = 'UPDATE FROM news SET ';
	}
	
	function checkPingArticle(){
		$query = News::find()
			->where(['status' => 1])
			->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
			->andWhere(['!=', 'deleted', 1])
			->andWhere(['status_ping' => 0])
			->orderBy('news.id DESC')
			->count();
		return !empty($query);
	}
	
	function actionIndex(){
		$this->layout = false;
		$content = $this->getHeaderIndex();
		$content .= $this->getContentIndex();
		$content .= $this->getFooterIndex();
        $fileName = \Yii::getAlias('@webroot') . '/sitemap.xml';
		file_put_contents($fileName, $content);
		echo 'done sitemap<br/>';
	}
	
    function actionNews(){
		$this->layout = false;
		$date = date('Y-m-d H:i:s', time() - 48 * 60 * 60);
		$news = News::find()
			->where(['status' => 1])
			->andWhere("('" . $date . "' < time_active and time_active < '".date('Y-m-d H:i:s', time())."')")
			->andWhere(['!=', 'deleted', 1])
			->orderBy('created_time DESC')
			->limit(1000)
			->asArray()
			->all();
		$news = NewsCategory::getCategoryByNews($news);
		$content = $this->getHeader();
		$content .= $this->getContentNews($news);
		$content .= $this->getFooter();
        $fileName = \Yii::getAlias('@webroot') . $this::DIRECTORY . 'news.xml';
		file_put_contents($fileName, $content);
		echo 'done news<br/>';
    }

    function actionService() {
        $this->layout = false;
        $content = $this->renderPartial('service');
        $fileName = \Yii::getAlias('@webroot') . $this::DIRECTORY . 'service.xml';
        file_put_contents($fileName, $content);
        echo 'done service<br/>';
    }
	
    function actionCategory(){
		$this->layout = false;
		$content = $this->getHeaderCate();
		$content .= $this->getContentCate();
		$content .= $this->getFooter();
        $fileName = \Yii::getAlias('@webroot') . $this::DIRECTORY . 'category.xml';
		file_put_contents($fileName, $content);
		echo 'done category<br/>';
    }
	
	function actionArticle(){
		$count = News::find()
            ->innerJoin('news_category', 'news.news_category_id = news_category.id')
			->where(['status' => 1])
			->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
			->andWhere(['<>', 'deleted', 1])
			->orderBy('created_time DESC')
			->count();
		$countFile = ceil($count/1000);
		for($i = 1; $i <= $countFile; $i++){
			$offset = ($i - 1) * 1000;
            $fileName = \Yii::getAlias('@webroot') . $this::DIRECTORY . 'article-' . $i . '.xml';
            if(file_exists($fileName) && $countFile - $i  > 2){
                continue;
            }
			$news = News::find()
                ->innerJoin('news_category', 'news.news_category_id = news_category.id')
				->where(['status' => 1])
				->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
				->andWhere(['<>', 'deleted', 1])
				->limit(1000)
				->offset($offset)
				->orderBy('time_active ASC')
				->asArray()
				->all();
            $news = NewsCategory::getCategoryByNews($news);
            $content = $this->getHeaderArticle();
            $content .= $this->getContentArticle($news);
            $content .= $this->getFooter();
            file_put_contents($fileName, $content);
		}
		echo 'done article<br/>';
	}
	
	function getContentIndex(){
		$content = '';
		$content .= '<sitemap><loc>'.Url::base(true).'/sitemap/category.xml</loc><lastmod>'.date('Y-m-d', time()) .'T'.date('H:i:s', time()).'+07:00</lastmod></sitemap>';
        $content .= '<sitemap><loc>'.Url::base(true).'/sitemap/service.xml</loc><lastmod>'.date('Y-m-d', time()) .'T'.date('H:i:s', time()).'+07:00</lastmod></sitemap>';
        $content .= '<sitemap><loc>'.Url::base(true).'/sitemap/emagazine.xml</loc><lastmod>'.date('Y-m-d', time()) .'T'.date('H:i:s', time()).'+07:00</lastmod></sitemap>';
		$count = News::find()
			->where(['status' => 1])
            ->innerJoin('news_category', 'news.news_category_id = news_category.id')
			->andWhere("('" . date('Y-m-d H:i:s') . "' > time_active OR time_active is null)")
			->andWhere(['<>', 'deleted', 1])
			->orderBy('created_time DESC')
			->count();
		$countFile = ceil($count/1000);
		for($i = 1; $i <= $countFile; $i++){
			$content .= '<sitemap><loc>'.Url::base(true).'/sitemap/article-'.$i.'.xml</loc><lastmod>'.date('Y-m-d', time()) .'T'.date('H:i:s', time()).'+07:00</lastmod></sitemap>';
		}
		return $content;
	}
	
	function getContentNews($news){
		$content = '';
		$urlBase = Url::base(true);
		foreach($news as $new) {
			$time = date('Y-m-d', strtotime($new['time_active'])).'T'.date('H:i:s', strtotime($new['time_active'])).'+07:00';
			$content .= '<url>
				<loc>'.$urlBase.CFunction::renderUrlNews($new).'</loc>
				<changefreq>daily</changefreq>
				<priority>0.7</priority>
				<news:news>
				<news:publication>
				<news:name>Bongdapro</news:name>
				<news:language>vi</news:language>
				</news:publication>
				<news:publication_date>'.$time.'</news:publication_date>
				<news:title>'.$new["title"].'</news:title>
				<news:keywords>'.$new["keyword"].'</news:keywords>
				</news:news>
				</url>';
		}
		return $content;
	}
	
	function getContentArticle($news){
		$content = '';
		$urlBase = Url::base(true);
		foreach($news as $new) {
			$content .= '<url>
				<loc>'.$urlBase.CFunction::renderUrlNews($new).'</loc>
				<changefreq>daily</changefreq>
				<priority>0.7</priority>
				<lastmod>'.date('Y-m-d', strtotime($new['time_active'])) .'T'.date('H:i:s', strtotime($new['time_active'])).'+07:00</lastmod>
				</url>';
		}
		return $content;
	}
	
	function getContentCate(){
		$content = '';
        $urlBase = Url::base(true);
        $content .= '<url>
                        <loc>'.$urlBase.'/lien-he.html</loc>
                        <changefreq>always</changefreq>
                        <priority>1</priority>
                        <lastmod>2020-09-04T15:24:33+07:00</lastmod>
                    </url>';
        $content .= '<url>
                        <loc>'.$urlBase.'/gioi-thieu.html</loc>
                        <changefreq>always</changefreq>
                        <priority>1</priority>
                        <lastmod>2020-09-04T15:24:33+07:00</lastmod>
                    </url>';
		return $content;
	}
	
	function getHeaderIndex(){
		$content = '<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		return $content;
	}
	
	function getHeader(){
		$content = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-news/0.9 http://www.google.com/schemas/sitemap-news/0.9/sitemap-news.xsd">';
		return $content;
	}
	
	function getHeaderCate(){
		$content = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><url>
			<loc>'.Url::base(true).'</loc>
			<changefreq>always</changefreq>
			<priority>1</priority>
			<lastmod>'.date('Y-m-d', time()) .'T'.date('H:i:s', time()).'+07:00</lastmod>
			</url>';
		return $content;
	}
	
	function getHeaderArticle(){
		$content = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
		return $content;
	}
	
	function getFooter(){
		$content = '</urlset>';
		return $content;
	}
	
	function getFooterIndex(){
		$content = '</sitemapindex>';
		return $content;
	}
	
	public function actionRenderMagazine()
	{
		$this->layout = false;
		$content = $this->getHeaderCate();
		$content .= $this->getContentEmagazine();
		$content .= $this->getFooter();
        $fileName = \Yii::getAlias('@webroot') . $this::DIRECTORY . 'emagazine.xml';
		file_put_contents($fileName, $content);
		echo 'done magazine<br/>';
	}
	
	function getContentEmagazine()
	{
		$content = '';
        $urlBase = Url::base(true);
		$listMagazine = Magazine::find()
			->where(['status' => 1])
			->asArray()->all();
		foreach ($listMagazine as $magazine) {
			$content .= '<url>
					<loc>'.$urlBase.Url::toRoute(['magazine/detail', 'alias' => trim(Utility::rewrite($magazine['title']), '-'), 'id' => $magazine['id']]).'</loc>
					<changefreq>always</changefreq>
					<priority>1</priority>
					<lastmod>'.date('Y-m-d', time()) .'T'.date('H:i:s', time()).'+07:00</lastmod>
				</url>';
		}
		return $content;
	}
}