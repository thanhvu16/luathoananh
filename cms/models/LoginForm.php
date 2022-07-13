<?php
namespace cms\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $verifyCode;

    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
//            ['verifyCode', 'required'],
//            ['verifyCode', 'captcha', 'captchaAction'=>'default/captcha'],
            ['password', 'validatePassword'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('cms', 'username'),
            'password' => Yii::t('cms', 'password'),
            'verifyCode' => Yii::t('cms', 'verify_code'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            try{
                if (!$user || !$user->validatePassword($this->password)) {
                    $this->addError($attribute, Yii::t('cms', 'check_login_fail'));
                } else {
                    Yii::$app->session->set('role', $user->admin_group_id);
                }
            } catch (\Exception $e) {
                $this->addError($attribute, Yii::t('cms', 'check_login_fail'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser());
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = AdminUser::findByUsername($this->username);
        }
        return $this->_user;
    }
}