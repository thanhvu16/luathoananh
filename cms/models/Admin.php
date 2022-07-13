<?php

namespace cms\models;

use common\behaviors\TimestampBehavior;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\helpers\Html;
use common\models\AdminBase;
use common\behaviors\ChangedBehavior;

class Admin extends AdminBase
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => ChangedBehavior::className(),
            ],
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_time', 'updated_time'],
                    self::EVENT_BEFORE_UPDATE => ['updated_time']
                ]
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function getAdminStatus()
    {
        return array(
            0 => AdminBase::ADMIN_INACTIVE,
            1 => AdminBase::ADMIN_ACTIVE
        );
    }

    /**
     * @return array
     */
    public static function getAdminStatusFilter()
    {
        return [
            ['value' => 0, 'status' => AdminBase::ADMIN_INACTIVE],
            ['value' => 1, 'status' => AdminBase::ADMIN_ACTIVE]
        ];
    }

    /**
     * @param $status
     * @return string
     */
    public static function getGroupStatusText($status)
    {
        if ($status == 1) {
            $textStatus = AdminBase::ADMIN_ACTIVE;
        } else {
            $textStatus = AdminBase::ADMIN_INACTIVE;
        }
        return $textStatus;
    }

    /**
     * @param $adminName
     * @return bool
     */
    public static function checkAdminNameExists($adminName)
    {
        $result = AdminBase::find()
            ->where(['username' => $adminName])
            ->exists();
        if ($result)
            return true;
        else
            return false;
    }

    /**
     * @param $idAdmin
     * @return string
     */
    public static function getAdminNameByID($idAdmin)
    {
        $adminName = null;
        $result = AdminBase::find()
            ->select('username')
            ->where(['id' => $idAdmin])
            ->one();
        if (!empty($result)) {
            $adminName = Html::encode($result->username);
        }
        return $adminName;
    }

    /**
     * @param $username
     * @return bool
     */
    public static function checkUsernamePattern($username)
    {
        if (preg_match('/^[a-zA-Z].{3,}$/', $username)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $password
     * @return bool
     */
    public static function checkPasswordPattern($password)
    {
        if (preg_match('/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%]).{8,}/', $password)) {
            return true;
        } else {
            return false;
        }
    }

    public static function getListAdmin(){
        $result = self::find()->select('id, fullname as name')->asArray()->all();
        $list = ['' => 'Tất cả người đăng'];
        foreach($result as $v){
            $list[$v['id']] = $v['name'];
        }
        return $list;
    }
}
