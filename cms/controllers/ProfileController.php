<?php

namespace cms\controllers;

use cms\models\Admin;
use cms\components\BackendController;
use cms\models\AdminUser;
use common\helpers\StringHelper;
use Yii;
use yii\helpers\Url;

class ProfileController extends BackendController
{
    public function actionChangePassword(){
        if(Yii::$app->request->isPost) {
            $passwordCurrent = trim(Yii::$app->request->post('passwordCurrent'));
            $passwordNew = trim(Yii::$app->request->post('passwordNew'));
            $passwordVerify = trim(Yii::$app->request->post('passwordVerify'));

            if(empty($passwordCurrent) || empty($passwordNew) || empty($passwordVerify)) {
                Yii::$app->session->setFlash('error', 'Vui lòng nhập đầy đủ thông tin');
                return $this->render('change-password');
            }

            if($passwordNew !== $passwordVerify) {
                Yii::$app->session->setFlash('error', 'Password Verify không khớp');
                return $this->render('change-password');
            }

            $model = $this->findModel();
            if(!Yii::$app->security->validatePassword($passwordCurrent, $model->password)) {
                Yii::$app->session->setFlash('error', 'Password không chính xác');
                return $this->render('change-password');
            }

            if(strlen($passwordNew) > 50 || strlen($passwordNew) < 6) {
                Yii::$app->session->setFlash('error', 'Password phải lớn hơn 6 kí tự và nhỏ hơn 50 kí tự');
                return $this->render('change-password');
            }

            $model->password = Yii::$app->security->generatePasswordHash($passwordNew);
            if($model->save()) {
                Yii::$app->session->setFlash('success', 'Thay đổi mật khẩu thành công');
                return  $this->redirect('/');
            }
            Yii::$app->session->setFlash('error', 'Có lỗi xảy ra');
        }
        return $this->render('change-password');
    }

    protected function findModel(){
        $model = Admin::findOne(Yii::$app->user->id);
        return $model;
    }
}
