<?php

namespace wap\components;
use cms\models\Cbox;
use common\components\KLogger;
use common\components\Utility;
use PHPUnit\Runner\Exception;
use wap\models\News;
use wap\models\NewsCategory;
use Yii;
use yii\helpers\Url;
use yii\log\Logger;


class CFunction
{
	public static function unsignString($str, $separator = '-', $keepSpecialSign = false) {
        $str = trim($str);
        $str = str_replace(array("à","á","ạ","ả","ã","ă","ằ","ắ","ặ","ẳ","ẵ","â","ầ","ấ","ậ","ẩ","ẫ"),"a", $str);
        $str = str_replace(array("À","Á","Ạ","Ả","Ã","Ă","Ằ","Ắ","Ặ","Ẳ","Ẵ","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ"),"A", $str);
        $str = str_replace(array("è","é","ẹ","ẻ","ẽ","ê","ề","ế","ệ","ể","ễ"),"e", $str);
        $str = str_replace(array("È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ"),"E", $str);
        $str = str_replace("đ","d", $str);
        $str = str_replace("Đ","D", $str);
        $str = str_replace(array("ỳ","ý","ỵ","ỷ","ỹ","ỹ"),"y", $str);
        $str = str_replace(array("Ỳ","Ý","Ỵ","Ỷ","Ỹ"),"Y", $str);
        $str = str_replace(array("ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ"),"u", $str);
        $str = str_replace(array("Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ"),"U", $str);
        $str = str_replace(array("ì","í","ị","ỉ","ĩ"),"i", $str);
        $str = str_replace(array("Ì","Í","Ị","Ỉ","Ĩ"),"I", $str);
        $str = str_replace(array("ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ","ờ","ớ","ợ","ở","ỡ"),"o", $str);
        $str = str_replace(array("Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ","Ờ","Ớ","Ợ","Ở","Ỡ"),"O", $str);
        if ($keepSpecialSign == false) {
            $str = str_replace(array('–','…','“',"'","~","!","@","#","$","%","^","&","*","/","\\","?","<",">","'","\"",":",";","{","}","[","]","|","(",")",",",".","`","+","=","-"), "", $str);
            $str = preg_replace("/[^_A-Za-z0-9- ]/i", '', $str);
            $str = str_replace(' ', $separator, $str);
            $str = strtolower($str);
        }
        $str = str_replace(array("039039"),"", $str);
		if(!empty($str))
        return $str;
		else
		return 'video';
    }

    public static function renderUrlCategory($category){
	    if (is_numeric($category)) {
	        $category = NewsCategory::getCategory($category);
        }
	    $params = [
	        'alias' => $category['route']
        ];
	    if ($category['parent_id'] != 0) {
	        $categoryParent = NewsCategory::getCategory($category['parent_id']);
            $params['slug'] = $categoryParent['route'];
            array_unshift($params, 'news-category/list');
        } else {
            array_unshift($params, 'news-category/list-parent');
        }
	    return Url::toRoute($params);
    }

    public static function renderUrlTags($tags){
        if(empty($tags))
            return '#';
        $url = Url::toRoute(['tags/index', 'alias' => Utility::rewrite($tags)]);
        return  $url;
    }

    public static function genUrlImageNews($news, $size = 'large'){
        if(is_object($news)){
            return $news->image;
        }
        return $news['image'];
    }

    public static function renderUrlNews($news, $category = []){
        if(empty($category)){
            if(!empty($news['croute']))
                $category = ['route' => $news['croute']];
            else
                $category = NewsCategory::getCategory($news['news_category_id']);
        }
        $news['slug'] = trim(Utility::rewrite($news['slug']), '-');
        if(!empty($category['route'])){
            $category['route'] = trim(Utility::rewrite($category['route']), '-');
            return Url::toRoute(['news/index', 'alias' => $category['route'], 'slug' => $news['slug'], 'id' => $news['id']]);
        } else {
            return Url::toRoute(['news/index', 'slug' => $news['slug'], 'id' => $news['id']]);
        }
    }
	
	public static function renderUrlNewsAmp($news, $category = []){
        if(empty($category)){
            if(!empty($news['croute']))
                $category = ['route' => $news['croute']];
            else
                $category = NewsCategory::getCategory($news['news_category_id']);
        }
        $news['slug'] = trim(Utility::rewrite($news['slug']), '-');
        if(!empty($category['route'])){
            $category['route'] = trim(Utility::rewrite($category['route']), '-');
            return Url::toRoute(['news/amp', 'alias' => $category['route'], 'slug' => $news['slug'], 'id' => $news['id']]);
        } else {
            return Url::toRoute(['news/amp', 'slug' => $news['slug'], 'id' => $news['id']]);
        }
    }

    public static function renderUrlDoiNgu($person)
    {
        return Url::toRoute(['default/doi-ngu-view', 'alias' => $person['slug'], 'id' => $person['id']]);
    }

    public static function getDayofWeek($weekday){
        switch($weekday) {
            case 'monday':
                $weekday = 'Thứ hai';
                break;
            case 'tuesday':
                $weekday = 'Thứ ba';
                break;
            case 'wednesday':
                $weekday = 'Thứ tư';
                break;
            case 'thursday':
                $weekday = 'Thứ năm';
                break;
            case 'friday':
                $weekday = 'Thứ sáu';
                break;
            case 'saturday':
                $weekday = 'Thứ bảy';
                break;
            default:
                $weekday = 'Chủ nhật';
                break;
        }
        return $weekday;
    }

    public static function getDayofWeekByDate($date){
        $weekday = date('l', strtotime($date));
        $weekday = self::getDayofWeek(strtolower($weekday));
        return $weekday . ', ' . date('d/m/Y', strtotime($date));
    }

    public static function getDayofWeekByDateGMT($date){
        $weekday = date('l', strtotime($date));
        $weekday = self::getDayofWeek(strtolower($weekday));
        return $weekday . ', ' . date('d/m/Y', strtotime($date)) . ', ' . date('H:i', strtotime($date)) . ' (GMT+7)';
    }

    public static function sendMail($content) {
	    $htmlContent = Yii::$app->controller->renderPartial('//default/mail', compact('content'));
        $mail = Yii::$app->mailer->compose()
            ->setFrom('noreply@gmail.com')
            ->setTo('luatsu@luathoanganh.vn')
            ->setSubject('Yêu cầu tư vấn Luật Hoàng Anh');
		if(!empty($content->file)) {
			$mail->attach($content->file);
		}
            $mail->setHtmlBody($htmlContent)
            ->send();
        return true;
    }
	public static function diffTime($time, $showHour = false){
        $diff = time() - strtotime($time);
        if($diff > 86400){
            $numberDay = floor($diff / 86400);
            if($numberDay < 7){
                return floor($diff / 86400) . ' ngày trước';
            }else{
                if($showHour){
                    return date('H:i d/m/Y', strtotime($time));
                }
                return date('d/m/Y', strtotime($time));
            }
        }elseif ($diff > 3600)
            return floor($diff / 3600) . ' giờ trước';
        else
            return floor($diff / 60) . ' phút trước';
    }
}