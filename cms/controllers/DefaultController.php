<?php
/**
 * @Function: Lớp mặc định của phần admin
 * @Author: trinh.kethanh@gmail.com
 * @Date: 10/01/2015
 * @System: Content Management System 
 */

namespace cms\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use cms\models\AdminLog;
use cms\models\LoginForm;
use yii\helpers\ArrayHelper;

class DefaultController extends Controller {

    /**
     * @params: NULL
     * @function: Gọi class Error & Captcha của Yii2
     */
    public function actions() {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
            ],
        ];
    }

    /**
     * @params: NULL
     * @function: Hàm này thống báo lỗi của hệ thống
     */
    public function actionError() {
        $this->layout = 'error';
        $error = ArrayHelper::getValue($_GET, 'error', '');
        $exception = Yii::$app->errorHandler->exception;
        if ($exception == null && $error == '403') {
            return $this->render('403');
        } else if (is_object($exception)) {
            if ($exception->statusCode == '404') {
                return $this->render('404', ['exception' => $exception]);
            } elseif ($exception->statusCode == '500') {
                return $this->render('500', ['exception' => $exception]);
            } else {
                return $this->render('error', ['exception' => $exception]);
            }
        } else {
            return $this->render('404', ['exception' => '404']);
        }
    }

	/**
	 * @params: NULL
	 * @function: Hàm index của phần admin, hàm này sẽ load đầu tiên khi chạy phần admin
	 */
    public function actionIndex() {
        $this->layout = 'main';
		if (Yii::$app->user->isGuest) {
			return $this->redirect(array('default/login'));
		} else {

			return $this->render('index');
		}
    }

	/**
	 * @params: NULL
	 * @function: Hàm đăng nhập hệ thống admin
	 */
	public function actionLogin() {
		$this->layout = 'login';

		if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            //AdminLog::ActionLog();

            $userDetail = Yii::$app->user->identity;
            return $this->goBack();

        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
	}

    /**
     * @params: NULL
     * @function: Hàm logout của hệ thống admin
     */
    public function actionLogout() {
        //AdminLog::ActionLog();
        Yii::$app->user->logout();
        return $this->goHome();
    }
}