<?php

namespace wap\controllers;

use common\components\Utility;
use wap\models\CommentUser;
use Yii;
use yii\helpers\StringHelper;
use yii\web\Controller;

class LoginController extends Controller
{

    const APP_STORE_CLIENT_SECRET = '';
    const APP_STORE_CLIENT_ID = '';
    const APP_STORE_REDIRECT_URI = 'https://luathoanganh.vn/dang-nhap/app-store-response.html';

    /**
     * @return array
     */
    public function actions()
    {
        //$url_redirect = Yii::$app->request->get('url_redirect');
        $urlSuccess = Yii::$app->homeUrl;
       // echo Yii::$app->request->referrer;die;
		$output_array = [];
		preg_match('/https:\/\/luathoanganh.vn\/(.*).html/', Yii::$app->request->referrer, $output_array);
        //if (!empty(Yii::$app->request->referrer) && strpos(Yii::$app->request->referrer, 'https://luathoanganh.vn/') === 0) {
        if (!empty(Yii::$app->request->referrer) && !empty($output_array)) {
            Yii::$app->session->set('login_url_success_callback', Yii::$app->request->referrer.'#wrap-comment');
        }
		
		$login_url_success_callback = Yii::$app->session->get('login_url_success_callback');
        if(!empty($login_url_success_callback)){
            $urlSuccess = $login_url_success_callback;
        }
		// echo $urlSuccess;die;
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
                'successUrl' => $urlSuccess,
                //'_successUrl' => $urlSuccess,
                'cancelCallback' => [$this, 'cancelCallback'],
            ],
        ];
    }

    public function cancelCallback($client)
    {
        //var_dump($client);die;
        // uncomment this to see which attributes you get back
        //echo "<pre>";print_r($client->getUserAttributes());echo "</pre>";exit;
        // check if user is already logged in. if so, do nothing
        if (!Yii::$app->user->isGuest) {
            return;
        }
        // attempt to log in as an existing user
        if ($this->attemptLogin($client)) {
            return;
        }
    }

    public function successCallback($client)
    {
        // uncomment this to see which attributes you get back
        //echo "<pre>";print_r($client->getUserAttributes());echo "</pre>";exit;
        // check if user is already logged in. if so, do nothing
        if (!Yii::$app->user->isGuest) {
            return;
        }
        // attempt to log in as an existing user
        if ($this->attemptLogin($client)) {
            return;
        }
    }




    /**
     * Attempt to log user in by checking if $userAuth already exists in the db,
     * or if a user already has the views address
     *
     * @param \yii\authclient\BaseClient $client
     * @return bool
     */
    protected function attemptLogin($client)
    {

        $attributes = $client->getUserAttributes();

        $authclient = Yii::$app->request->get('authclient');

        if ($authclient == 'facebook') {
            $email = !empty($attributes["email"]) ? $attributes["email"] : '';

            $avatar = 'http://graph.facebook.com/'.$attributes["id"].'/picture?type=square';
            $name = $attributes["name"];
        } else {
            $email = !empty($attributes["email"]) ? $attributes["email"]: '';
			$avatar = !empty($attributes["picture"]) ? $attributes["picture"]: '';
            $name = !empty($attributes["name"]) ? $attributes["name"]: '';
        }

        if (empty($email)) {
            Yii::$app->getSession()->setFlash('error', 'Kh??ng t???n t???i Email!');
            return false;
        }

        $user =  $this->getUser($email);
        if ($user) {

            if($user->status !== CommentUser::STATUS_ACTIVE){
                Yii::$app->getSession()->setFlash('error', 'Email c???a b???n ???? b??? kho?? tr??n h??? th???ng.');
                return false;
            }

            Yii::$app->user->login($user, 7*86400);
			
            return true;
        } else {
            $user = new CommentUser();
            $user->name = !empty($name)?$name:'';
            $user->username = $email;
            $user->email = $email;

            $user->avatar = !empty($avatar)?$avatar:'';

            $user->status = CommentUser::STATUS_ACTIVE;

            $user->source = $authclient;

            $password = Yii::$app->security->generateRandomString(12);

            $user->setPassword($password);
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();

            $user->save(false);

            Yii::$app->user->login($user, 7*86400);
			
			
            return true;
        }

        Yii::$app->getSession()->setFlash('error', 'Kh??ng t???n t???i t??i kho???n v???i email n??y!');
        return false;
    }

    public function getUser($email)
    {
        $user = CommentUser::findByEmail($email);
        return $user;
    }


    public function actionAppStore()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $state = bin2hex(random_bytes(5));
        Yii::$app->session->set('state_code_app_store', $state);
        $authorize_url = 'https://appleid.apple.com/auth/authorize' . '?' . http_build_query([
            'response_type' => 'code',
            'response_mode' => 'form_post',
            'client_id' => self::APP_STORE_CLIENT_ID,
            'redirect_uri' => self::APP_STORE_REDIRECT_URI,
            'state' => $state,
            'scope' => 'name email',
        ]);

        return $this->redirect($authorize_url);
    }


    public function actionAppStoreResponse()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (isset($_POST['code'])) {
            $state = Yii::$app->session->get('state_code_app_store');
            if ($state != $_POST['state']) {
                Yii::$app->session->setFlash('error', 'Authorization server returned an invalid state parameter');
                goto c;
            }

            if (isset($_REQUEST['error'])) {
                Yii::$app->session->setFlash('error', 'Authorization server returned an error: ' . htmlspecialchars($_REQUEST['error']));
                goto c;
            }

            $response = $this->http('https://appleid.apple.com/auth/token', [
                'grant_type' => 'authorization_code',
                'code' => $_POST['code'],
                'redirect_uri' => self::APP_STORE_REDIRECT_URI,
                'client_id' => self::APP_STORE_REDIRECT_URI,
                'client_secret' => self::APP_STORE_CLIENT_SECRET,
            ]);

            if (!isset($response->access_token)) {
                Yii::$app->session->setFlash('error', 'Error getting an access token');
                goto c;
            }

            //echo '<h3>Access Token Response</h3>';
            //echo '<pre>';
            //print_r($response);
            //echo '</pre>';


            $claims = explode('.', $response->id_token)[1];
            $claims = json_decode(base64_decode($claims), true);
            if (empty($claims['email'])) {
                Yii::$app->session->setFlash('error', '????ng nh???p kh??ng th??nh c??ng do kh??ng l???y ???????c th??ng tin email.');
                goto c;
            }
            //echo '<pre>';
            //print_r($claims);
            //echo '</pre>';
            $user = $this->getUser($claims['email']);
            if ($user) {
                Yii::$app->user->login($user);
                return $this->goHome();
            } else {
                $user = new CommentUser();
                $user->username = $claims['email'];
                $user->email = $claims['email'];
                $user->status = CommentUser::STATUS_ACTIVE;
                $password = Yii::$app->security->generateRandomString(8);
                $user->password = Yii::$app->security->generatePasswordHash($password);
                $user->save(false);

                Yii::$app->user->login($user);
                return $this->goHome();
            }

            Yii::$app->getSession()->setFlash('error', 'Kh??ng t???n t???i t??i kho???n v???i email n??y!');
            goto c;
        }


        c:
        return $this->redirect('user/signin');
    }


    private function http($url, $params = [])
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($params)
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'User-Agent: curl', # Apple requires a user agent header at the token endpoint
        ]);
        $response = curl_exec($ch);
        return json_decode($response);
    }

    public function actionLogout(){
        Yii::$app->user->logout();
        return $this->redirect(Yii::$app->homeUrl);
    }
}
