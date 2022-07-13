<?php
/**
 * @Function: Lớp mặc định của phần admin
 * @Author: trinh.kethanh@gmail.com
 * @Date: 12/05/2015
 * @System: Vega Ads
 */

namespace cms\controllers;

use Yii;
use yii\web\Controller;
use cms\models\AdminUser;

class GoogleController extends Controller {

    /**
     * @return array
     */
    public function actions() {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
                'successUrl' => Yii::$app->homeUrl,
            ],
        ];
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
        $email = $attributes["emails"][0]["value"];
        $admin =  $this->getUser($email);
        if($admin)
        {
            Yii::$app->user->login($admin);
            return true;
        }
        Yii::$app->getSession()->setFlash('error', 'Không tồn tại tài khoản với email này!');
        return false;
    }

    public function getUser($email)
    {
        $user = AdminUser::findByEmail($email);
        return $user;
    }

}