<?php
/**
 * @Author: trinh.kethanh@gmail.com
 * @Date: 19/11/2014
 * @Function: Class xử lý các hàm sử dụng chung của hệ thống
 * @System: Video 2.0
 */
namespace common\components;

use Yii;
use yii\web\JqueryAsset;
use yii\bootstrap\BootstrapAsset;

class CFunction {

    public static function getUrlEmbed($model){
        return 'https://khandai.vip/embed/'.$model->id.'.html';
    }
    /*
     * @params: NULL
     * @function: Lấy đường dẫn sở của hệ thống
     */
    public static function getThemeBaseUrl() {
        return Yii::$app->view->theme->baseUrl;
    }
    /*
     * @params: NULL
     * @function: Lấy đường dẫn cơ sở của ảnh
     */
    public static function getImageBaseUrl() {
        return Yii::$app->view->theme->baseUrl.'/images/';
    }
    /*
     * @params: $fileName -> Tên file js cần đăng ký
     * @function: Đăng ký file js cho hệ thống
     */
    public static function regJsFile($fileName = true) {
        Yii::$app->view->registerJsFile(self::getThemeBaseUrl().'/js/'.$fileName, ['depends' => JqueryAsset::className()]);
    }
    /*
     * @params: $fileName -> Tên file js cần đăng ký
     * @function: Register file css cho hệ thống
     */
    public static function regCssFile($fileName = true) {
        Yii::$app->view->registerCssFile(self::getThemeBaseUrl().'/css/'.$fileName, ['depends' => BootstrapAsset::className()]);
    }
    /*
     * @params: $key -> Tham số cần lấy
     * @function: Lấy giá trị của 1 tham số trong phần params của hệ thống
     */
    public static function getParams($key) {
        return Yii::$app->params[$key];
    }
    /*
     * @params: $key1 = [] -> Tham số mảng cần lấy có dạng mảng
     * @params: $key2 -> Tham số cần lấy nằm trong mảng của $key1
     * @function: Lấy giá trị của 1 tham số trong phần params của hệ thống
     */
    public static function getParamsArray($key1, $key2) {
        return Yii::$app->params[$key1][$key2];
    }
    /*
     * @params: $themeName -> Tên theme cần tạo
     * @function: Tạo theme cho hệ thống
     */
    public static function setTheme($themeName) {
        Yii::$app->view->theme->baseUrl = rtrim(Yii::getAlias('@web/themes/'.$themeName), '/');
        Yii::$app->view->theme->basePath = Yii::getAlias('@themes/'.$themeName);
        Yii::$app->view->theme->pathMap['@app/views'] = ['@themes/'.$themeName.'/views'];
    }
    /*
     * @params: $str -> Chuỗi dữ liệu cần xử lý
     * @params: $separator -> Ký tự thay thế (Mặc định là '-')
     * @params: $keepSpecialSign -> Mặc định là false, nếu tham số này là true thì hàm này sẽ giữ lại những ký tự đặc biệt
     * @function: Hàm này sử dụng để loại bỏ tiếng việt có dấu & ký tự đặc biệt trong 1 chuỗi, thay thế bằng 1 ký tự khác
     */
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
    /*
     * @params: $arrayParams Mảng dữ liệu truyền vào
     * @function: Hàm này sử dụng để cover 1 mảng và trả về 1 chuỗi các params đã được implode bằng dấu &
     */
    public static function implodeParams($arrayParams) {
        $dataCover = '';
        $i         = 0;
        foreach ($arrayParams as $key => $value) {
            if ($i == 0) {
                $dataCover .= $key . '=' . $value;
            } else {
                $dataCover .= '&' . $key . '=' . $value;
            }
            $i++;
        }
        return $dataCover;
    }
    /*
     * $params: $url Địa chỉ cần redirect tới
     * @function: Hàm này sử dụng để redirect hệ thống tới 1 địa chỉ url đã chỉ định
     */
    public static function redirect($url, $js = false) {
        if ($js) {
            echo '<script type="text/javascript">window.document.location.href="'. $url .'";</script>';
            exit();
        } else {
            @header('Location: '. $url);
            exit();
        }
    }
    /*
     * @params: $array Biến cần debug
     * @params: $dump Nếu sử dụng var_dump thì thêm biến này vào
     * @function: Sử dụng để debug 1 biến hoặc 1 hàm
     */
    public static function debug($array, $dump = false) {
        echo '<pre>';
        if ($dump) {
            var_dump($array);
        } else {
            print_r($array);
        }
        echo '</pre>';
        exit();
    }
   /*
    * @params: NULL
    * @function: Hàm này sử dụng để tạo id của bảng cms_system_user
    */
    public static function GUID() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
    }
    /*
    * @params: $phoneNumber Số điện thoại chưa đúng dạng chuẩn (0xxx)
    * @return: Trả về số điện thoại đúng dạng chuẩn (84xxx)
    */
    public static function makePhoneNumberStandard($phoneNumber) {
        $phoneNumberStandard = '';
        if ($phoneNumber != '') {
            if (substr($phoneNumber, 0, 1) == '0') {
                $phoneNumberStandard = substr($phoneNumber, 1, strlen($phoneNumber));
            }
            else if (substr($phoneNumber, 0, 2) == '84') {
                $phoneNumberStandard = substr($phoneNumber, 2, strlen($phoneNumber));
            }
            $phoneNumberStandard = '84'.$phoneNumberStandard;
        }
        return $phoneNumberStandard;
    }
    /*
     * @params: $action->Action cần format
     * @function: Hàm này format action theo đúng định dạng (actionName)
     */
    public static function formatAction($action) {
        $actionCover = str_replace('-', ' ', $action);
        $actionFormat = str_replace(' ', '', ucwords($actionCover));
        return 'action' . $actionFormat;
    }
    /*
     * @params: $options -> Thuộc tính cần thêm id
     * @params: $id -> Tên id
     * @function: Hàm này add id vào 1 options
     */
    public static function addCssId(&$options, $id) {
        if (isset($options['id'])) {
            $ids = ' ' . $options['id'] . ' ';
            if (strpos($ids, ' ' . $id . ' ') === false) {
                $options['id'] .= ' ' . $id;
            }
        } else {
            $options['id'] = $id;
        }
    }
    /*
     * @params: $time = false -> Thời gian cần format
     * @return: Hàm này sử dụng để formatDate về dạng ISODate
     */
    public static function formatDate($time = false) {
        if (!$time) $time = time();
        return date("Y-m-d", $time) . 'T' . date("H:i:s", $time) .'+00:00';
    }
    /*
     * @params: $value -> Giá trị cần format
     * @return: Hàm này định dạng tháng & ngày theo đúng dạng
     */
    public static function formatMonthDay($value) {
        if (strlen($value) == 1)
            return '0'.$value;
        else
            return $value;
    }

    public static function humanTiming($str_time)
    {
        $time_stamp = strtotime($str_time);
        return date('d/m/Y', $time_stamp);
        $time = time() - $time_stamp; // to get the time since that moment
        $time = ($time<1)? 1 : $time;
        $tokens = array (
            3600 => 'giờ trước',
            60 => 'phút trước',
            1 => 'giây trước'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            if($unit == 3600 && $numberOfUnits > 23){
                return date('d/m/Y', $time_stamp);
            }
            return $numberOfUnits.' '.$text;
        }
        return date('d/m/Y', $time_stamp);
    }
	public static function getToken($id, $t){
        $key = 'BwpGVVKx6r9uy4FVQQB1ZOur2mdJajpV';
        $tokenServer = md5($t.$key.$id);
        return $tokenServer;
    }
	public static function checkToken($id, $t, $token){
        if(empty($token) || empty($t) || empty($token)){
            return false;
        }
        $key = 'BwpGVVKx6r9uy4FVQQB1ZOur2mdJajpV';
        $tokenServer = md5($t.$key.$id);

        if($token != $tokenServer){
            return false;
        }

        if($t < time() - 1200){
            return false;
        }

        return true;
    }

}
