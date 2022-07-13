<?php

namespace wap\controllers;

use common\models\NewsCategoryBase;
use wap\components\MinifyHelper;
use wap\models\NewsCategory;
use wap\models\News;
use wap\models\Sender;
use wap\widgets\CenterContentWidget;
use wap\widgets\HomeMobileWidget;
use wap\widgets\HomePcWidget;
use wap\widgets\MenuAmpWidget;
use wap\widgets\MenuMobileWidget;
use wap\widgets\MenuPcWidget;
use wap\widgets\RightBarPcWidget;
use Yii;
use common\components\Utility;

class ExportController extends AppController {

	public function actionLowExport(){
        $this->actionExportCategory();
    }

	private function replaceHtml($html){
        $content = MinifyHelper::html($html);
        return $content;
    }

    private function actionExportCategory(){
	    $allCate = NewsCategoryBase::find()
            ->orderBy('order')
            ->orderBy('created_time')
            ->asArray()->all();;
	    $str = "<?php" . PHP_EOL;
	    $str .= "return ['categoryExport' => [" . PHP_EOL;
	    foreach ($allCate as $key => $cate){
            $str .= $cate['id']." => [" . PHP_EOL;
            foreach ($cate as $k => $v){
                if($k == 'page_intro'){
                    continue;
//                    $str .= "'".$k."' => '".str_replace("'", "\'", $v)."'," . PHP_EOL;
                }
                $str .= "'".$k."' => '".str_replace("'", "\'", $v)."'," . PHP_EOL;
            }
            $str .= "]," . PHP_EOL;
        }
	    $str .= "] ];";
        file_put_contents(dirname(__FILE__).'/../config/category.php',$str);
        echo 'done category <br/>';
    }

	private function actionUpdateTags(){
	    $allNews = News::find()
            ->where(['!=', 'deleted', 1])
			->andWhere('tags is NULL')
			->andWhere('keyword is NOT NULL')
            ->asArray()->all();

	    foreach ($allNews as $news){
	        if(empty($news['keyword'])){
	            continue;
            }
			$string = $news['keyword'];
			$string = Utility::stripText($string);
			$string = strtolower(trim($string));
			$string = preg_replace('/[^a-z0-9- -,]/', '-', $string);
			$string = preg_replace('/-+/', " ", $string);
	        $model = News::findOne($news['id']);
			$model->tags = $string;
	        $model->save(false);
        }
	    echo 'done';
    }
}
