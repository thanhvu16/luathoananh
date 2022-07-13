<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 4/22/2015
 * Time: 11:09 AM
 */

namespace common\components;

use common\models\UserBase;
use vega\recognizing\Recognizer;
use vega\telco\Telco;
use yii\web\ForbiddenHttpException;
use \yii\web\Request;
use common\components\mps\MPS;
use common\components\mps\IpHelper;
use Yii;

class HttpRequest extends Request
{

    const DEFAULT_LANGUAGE = null;
    const FIRST_LANGUAGE = 1;
    const SECOND_LANGUAGE = 2;
    const THIRD_LANGUAGE = 3;
    /**
     * User model used to create user object
     * @var string
     */
    public $userModel = '\common\models\UserBase';
    /**
     * @var string
     */
    private $_languageSessionKey = 'lang';
    /**
     * @var null|int
     */
    protected $lang = self::DEFAULT_LANGUAGE;
    /**
     * @var string
     */
    private $_mSISDNSessionKey = 'mSISDN';
    /**
     * @var string
     */
    private $_userSessionKey = '_userObject';
    /**
     * @var string
     */
    protected $mSISDN;
    /**
     * @var UserBase
     */
    protected $user;

    /**
     * @param $mSISDN
     * @return $this
     */
    public function setMSISDN($mSISDN)
    {
        if (Yii::$app->telco->isRightMSISDN($mSISDN)) {
            //Yii::$app->session->set($this->_mSISDNSessionKey, $mSISDN);

            $this->mSISDN = $mSISDN;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getMSISDN()
    {
        $data_encrypted = isset($_GET['DATA']) ? $_GET['DATA'] : null;
        $signature = isset($_GET['SIG']) ? $_GET['SIG'] : null;
        if ($data_encrypted && $signature) {
            return true;
        }
        if (isset($_SESSION['userid']) && !empty($_SESSION['userid']) && !in_array($_SESSION['userid'],array('N/A','null','84')))
        {
            return $this->mSISDN = $_SESSION['userid'];
        }
        //check url la return
        if (strpos($_SERVER['REQUEST_URI'], '/api') !== false
            || strpos($_SERVER['REQUEST_URI'], '/themes/') !== false
            || strpos($_SERVER['REQUEST_URI'], '/favicon') !== false

        ){
            return;
        }

        $ip = IpHelper::realUserIP();
        $ipName = IpHelper::getIspNameByIp($ip);
        //error_log('time='.date('Y-m-d H:i:s').'|ip = '.$ip.'|ipName = '.$ipName.'|isset_loop_detect='.isset($_SESSION['loop_detect']).PHP_EOL,3,'/tmp/access_ip.log');
        //$ipName = 'VIETTEL';
        if ($ipName != 'VIETTEL' /*&& isset($_GET['detect']) && $_GET['detect'] == 1*/) {
            return true;
        }

        $_SESSION['wifi'] = 1;
        //if(!$_SESSION['detector']  || (($ipName == 'VIETTEL' && $_SESSION['loop_detect'] <= 3 ) || ($ipName == 'VIETTEL' && $_SESSION['remoteIp'] != trim($ip))) ) {
        if (($ipName == 'VIETTEL' && ($_SESSION['loop_detect'] = isset($_SESSION['loop_detect']) ? $_SESSION['loop_detect'] : 0) < 3)
                || ($ipName == 'VIETTEL' && ($_SESSION['remoteIp'] = isset($_SESSION['remoteIp']) ? $_SESSION['remoteIp'] : '') != trim($ip))
        ) {
            return true;
            $_SESSION['remoteIp'] = trim($ip);
            $_SESSION['loop_detect'] += 1;
            //error_log('time='.date('Y-m-d H:i:s').'|ip = '.$ip.'|ipName = '.$ipName.'|remoteIp_after='.$_SESSION['remoteIp'].'|loop_detect_after='.$_SESSION['loop_detect'].PHP_EOL,3,'/tmp/access_ip.log');

            MPS::instance()->processDetectMsisdn();
        } else {
            return true;
        }
    }

    /**
     * @param UserBase $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return UserBase
     * @throws ForbiddenHttpException
     */
    public function getUser()
    {
        if (!$this->user) {
            if (isset($_SESSION['already_register']) && $_SESSION['already_register'] == 1) {
                Yii::$app->user->identity=null;
                $mSMSDN = $this->getMSISDN();
                $this->setMsisdnCustorm($mSMSDN);
                $_SESSION['already_register'] = null;
            } else {
                if (!Yii::$app->user->isGuest) {
                    //if($user = Yii::$app->session->get($this->_userSessionKey))
                    //    $this->user = unserialize($user);
                    //else{
                    //    $this->user = Yii::$app->user->identity;
                    //    Yii::$app->session->set($this->_userSessionKey, serialize($this->user));
                    //}
                    $this->user = Yii::$app->user->identity;
                } else {
                    $mSMSDN = $this->getMSISDN();

                    //Giả lập nhận diện thuê bao
                    /*$mode = Yii::$app->getRequest()->getQueryParam('mode');
                    if ($mode == 'local') {
                        $mSMSDN = Yii::$app->getRequest()->getQueryParam('tel');
                    }*/

                    if (!$this->user && $mSMSDN) {
                        $this->setMsisdnCustorm($mSMSDN);
                    }
                }
            }

        }

        if ($this->user && $this->user->isPrevented()) {
            echo "System is busy!";
            exit();
            //throw new ForbiddenHttpException('Forbidden!. User is prevented',404);
        }

        return $this->user;
    }

    function setMsisdnCustorm($mSMSDN)
    {
        /**
         * @var UserBase $class
         */
        $class = $this->userModel;
        /**
         * @var UserBase $user
         */
        if ($mSMSDN !== true){
            $user = $class::find()->where(['id' => $mSMSDN])->with(['userPackages', 'userPackages.package'])->one();
            if (!$user) {
                $user = new $class(
                    [
                        'id' => $mSMSDN,
                        'active' => UserBase::ACTIVE,
                        'time_login' => date('Y-m-d H:i:s'),
                        'num_login' => 1,
                    ]);
                if ($user->save())
                    $this->user = $user;
            } else
                $this->user = $user;
        }
    }

    /**
     * Set language for user
     * @param null $lang
     * @throws \Exception
     * @return $this
     */
    public function setLanguage($lang = self::DEFAULT_LANGUAGE)
    {
        if (!in_array($lang, [self::DEFAULT_LANGUAGE, self::FIRST_LANGUAGE, self::SECOND_LANGUAGE, self::THIRD_LANGUAGE]))
            throw new \Exception('Invalid param language');

        $this->lang = $lang;

        Yii::$app->session->set($this->_languageSessionKey, $lang);

        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getLanguage()
    {
        return ($this->lang) ? $this->lang : Yii::$app->session->get($this->_languageSessionKey, self::DEFAULT_LANGUAGE);
    }

    /**
     * @param string $source
     * @return $this
     */
    public function setSource($source)
    {
        Yii::$app->session->set('source', $source);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return Yii::$app->session->get('source');
    }
}
