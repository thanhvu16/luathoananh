<?php

namespace wap\controllers;

use wap\models\News;
use Yii;
use wap\models\NewsCategory;
use common\components\Utility;
use yii\helpers\Url;
use wap\components\CFunction;

class RssController extends AppController {
    const DIRECTORY = '/folder-rss/';

	public function actionIndex(){
		$menu = NewsCategory::getListCategory();
        return $this->render("index", [
			'menu' => $menu
		]);
	}
	
	public function actionCreateRss(){
		$this->layout = false;
		$menu = NewsCategory::getListCategory();
		
		foreach($menu as $v){
			$ids = NewsCategory::getAllChildren($v['id']);
			$ids[] = $v['id'];
			for ($i = 0; $i < count($ids); $i++) {
				$idsChild = NewsCategory::getAllChildren($ids[$i]);
				if (!empty($idsChild)) {
					$ids = array_merge($ids, $idsChild);
					for ($j = 0; $j < count($idsChild); $j++) {
						$idsChildFinal = NewsCategory::getAllChildren($idsChild[$j]);
						$ids = array_merge($ids, $idsChildFinal);
					}
				}
			}
			$news = News::getListNewsByCategoryId($ids, 50, 0);
			
			$urlHome = Url::base(true);
			
			$content = '<rss xmlns:slash="http://purl.org/rss/1.0/modules/slash/" version="2.0"><channel>';
			$content .= '<title>'.$v["title"].' - Luật Hoàng Anh RSS</title>
							<description>Luật Hoàng Anh RSS</description>
							<image>
							<url>'.$urlHome.'/themes/default/images/logo.png</url>
							<title>Công ty luật Hoàng Anh - Tư vấn pháp luật, dịch vụ luật sư</title>
							<link>'.$urlHome.'</link>
							</image>
							<pubDate>'.date('r', time()).'</pubDate>
							<generator>Luật Hoàng Anh</generator>
							<link>'.$urlHome.Url::toRoute(["/rss/detail", "alias" => Utility::rewrite($v['title'])]).'</link>';
			foreach($news as $new){
				$content .= '<item>
								<title>'.$new['title'].'</title>
								<description>'.$new['brief'].'</description>
								<pubDate>'.date('r', strtotime($new['time_active'])).'</pubDate>
								<link>'.$urlHome.CFunction::renderUrlNews($new).'</link>
								<guid>'.$urlHome.CFunction::renderUrlNews($new).'</guid>
								<slash:comments>0</slash:comments>
							</item>';
			}
			$content .= '</channel></rss>';
            $fileName = \Yii::getAlias('@webroot') . $this::DIRECTORY . Utility::rewrite($v['title']) . '.xml';
			file_put_contents($fileName,  $content);
		}
		echo 'done rss<br/>';
	}
	
	public function actionDetail(){
		$alias = Utility::rewrite(Yii::$app->request->get('alias'));
        $fileName = \Yii::getAlias('@webroot') . $this::DIRECTORY . $alias . '.xml';
		if(!is_file($fileName)){
			return $this->redirect('/404');
		}
		$content = file_get_contents($fileName);
	
		Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
		Yii::$app->response->headers->set('Content-Type', 'text/xml');
		Yii::$app->response->data = $content;
	}
}