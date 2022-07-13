<?php

namespace common\models;
require_once '../../console/components/hash/vendor/autoload.php';

use common\components\KLogger;
use common\components\Mongo;
use common\components\mps\IpHelper;
use common\components\script\telco\ServiceRequest;
use wap\models\UserPackage;
use Yii;
use yii\helpers\Html;
use Hashids\Hashids;


class SessionBase extends \common\models\db\SessionDB{

    /**
    * @return array relational rules.
    */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [];
    }

    public static function logSession($params = array())
    {
        $ip = IpHelper::realUserIP();
        $msisdn = isset($params['user_id']) ? $params['user_id']: null;
        $packageID = isset($params['package_id'])?$params['package_id']:0;
        $userPackageStatus = isset($params['user_package_status'])? $params['user_package_status']: UserPackageBase::CANCELED_STATE;
        $browser = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        //$source = Yii::$app->request->getSource();

        $useragent = Yii::$app->mobiledetect->getUserAgent();
        $method = $params['method'];
        if (strpos(strtolower($_SERVER['SERVER_NAME']), 'api')) {
            $method = 'api';
        }

        $default = [
            'user_id' => $msisdn,
            'ip' => $ip,
            'package_id' => $packageID,
            'user_package_status' => $userPackageStatus,
            'detector_response_time' => 0,
            'model' => '',
            'ua' => $useragent,
            'method' => $method,
            'created_time' => date("Y-m-d H:i:s"),
            'os' => '',
            'os_version' => 0,
            'browser' => $browser,
            'source' => null,
            'referer' => $referer,
            'page_id' =>'',
        ];

        $params = array_merge($default,$params);

        $session = new SessionBase();

        //if($msisdn=='841236129588'){
        //    echo $userPackageStatus;
        //    var_dump($session);die;
        //}

        $source = Yii::$app->session->get('source');
        $params['source'] = (!empty($source))?$source:null;

        if (!empty(Yii::$app->params['hashPrioritize'])){
            $sourceHk = Yii::$app->session->get('hk');
            $params['source'] = $sourceHk;
            if (!empty($sourceHk)){
                $hashKey = Yii::$app->params['hash_key'];
                $hashids = new Hashids($hashKey);

                $shorter = substr($sourceHk,3);
                $idx = $hashids->decode($shorter);

                if (!empty($idx[0])){
                    if (empty($params['user_id'])){
                        $params['user_id'] = '84'.$idx[0];
                    }
                }
            }
        }

        if(ServiceRequest::isMongoOn())
            Mongo::insertSessionReport($msisdn, $ip, $packageID, 0, false, $useragent, $method, false, 0, $browser, $source, $referer, false);

        $session->setAttributes($params);

        return $session->save(false);
    }

    public static function checkSessionLogin(){
        $session_login = Yii::$app->session->get('session_login');
        $today = date('Y-m-d H:i:s');
        //$endtime = strtotime(date('Y-m-d H:i:s', strtotime($today)) . ' -30 seconds');
        if(!$session_login){
            $created_time = date("Y-m-d H:i:s");
            Yii::$app->session->set('session_login',$created_time);
            return true;
        }else return false;
    }
}
