<?php
/**
 * @Function: Lớp mặc định của phần admin
 * @Author: trinh.kethanh@gmail.com
 * @Date: 29/11/2014
 * @System: Content Management System
 */
namespace cms\models;

use common\behaviors\TimestampBehavior;
use Yii;
use common\models\AdminGroupBase;
use common\behaviors\ChangedBehavior;
use yii\behaviors\BlameableBehavior;

class AdminGroup extends AdminGroupBase
{
    const GROUP_CTV = 16; //Phóng viên, CTV
    const GROUP_BTV = 17; //BTV, chủ mục lần 1
    const GROUP_CVCC = 14; //BBT, Trưởng ban
    const GROUP_TBT = 15; //BBT, Trưởng ban
    const GROUP_ADMIN = 1;

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
    public static function getGroupStatus() {
        return array(
            0 => AdminGroupBase::GROUP_INACTIVE,
            1 => AdminGroupBase::GROUP_ACTIVE
        );
    }

    /**
     * @param $status
     * @return string
     */
    public static function getGroupStatusText($status)
    {
        if ($status == 1) {
            $textStatus = AdminGroupBase::GROUP_ACTIVE;
        } else {
            $textStatus = AdminGroupBase::GROUP_INACTIVE;
        }
        return $textStatus;
    }

    /**
     * @return array
     */
    public static function getGroupStatusFilter()
    {
        return [
            ['value' => 0, 'status' => AdminGroupBase::GROUP_INACTIVE],
            ['value' => 1, 'status' => AdminGroupBase::GROUP_ACTIVE]
        ];
    }

    /**
     * @param $groupName
     * @return bool
     */
    public static function checkGroupNameExists($groupName)
    {
        $result = AdminGroupBase::find()
            ->where(['group_name' => $groupName])
            ->exists();
        if ($result)
            return true;
        else
            return false;
    }

    /**
     * @param $idGroup
     * @return mixed|string
     */
    public static function getGroupNameByID($idGroup)
    {
        $groupName = '';
        $result = AdminGroupBase::find()
            ->select('group_name')
            ->where(['id' => $idGroup])
            ->one();

        if (!empty($result)) {
            $groupName = $result->group_name;
        }
        return $groupName;
    }

    /**
     * @param $controllerName
     * @return string
     */
    public static function getDescriptionController($controllerName)
    {
        return Yii::t('controller', "$controllerName");
    }
}