<?php
/**
 * @Function: Lớp kiểm tra quyền user cho từng action
 * @Author: trinh.kethanh@gmail.com
 * @Date: 17/01/2015
 * @System: Content Management System
 */
namespace cms\components;

use Yii;
use cms\models\AdminUser;
use common\models\AdminGroupPermissionBase;
use common\models\AdminPermissionBase;

class AdminPermission {
    /*
     * @params: $controller -> Tên controller
     * @params: $action -> action trong controller trên
     * @function: Hàm này sử dụng để kiểm tra quyền hạn của user cho từng controller & action trong controller đó
     */
    public static function checkUserPermission($controller, $action) {
        if (!isset(Yii::$app->user->identity->username)) {
            return false;
        }
        if (isset(Yii::$app->user->identity->username) && (Yii::$app->user->identity->username === 'admin'))
            return true;
        if (isset(Yii::$app->user->identity->username) && !Yii::$app->session->get('role')) {
            $user = AdminUser::findByUsername(Yii::$app->user->identity->username);
            Yii::$app->session->set('role', $user->admin_group_id);
        }

        $userDetail = Yii::$app->user->identity;
        $userGroupId = $userDetail->admin_group_id;
        if ($userGroupId == 13){
            if ($controller == 'daily-report' && $action == 'xn-syntax-report'){
                return true;
            }
        }

        $controller = str_replace('-', '', strtoupper($controller . 'Controller'));
        $adminID = Yii::$app->user->identity->getId();
        $groupID = Yii::$app->session->get('role');
//        $groupPermission =
//            AdminGroupPermissionBase::find()
//                ->where('admin_group_id = :groupID', [':groupID' => $groupID])
//                ->all();
        $groupPermission = Yii::$app->user->identity->adminGroupPermission;

//        $userPermission =
//            AdminPermissionBase::find()
//                ->where('admin_id = :adminID', [':adminID' => $adminID])
//                ->all();
        $userPermission = Yii::$app->user->identity->adminPermissionAttribute;

        $arrayGroupPermission = array();
        $arrayUserPermission = array();
        if (is_array($groupPermission)) {
            foreach ($groupPermission as $row) {
                $arrayGroupPermission[strtoupper($row->controller)] = unserialize($row->permission);
            }
        }
        if (is_array($userPermission)) {
            foreach ($userPermission as $row) {
                $arrayUserPermission[strtoupper($row->controller)] = unserialize($row->permission);
            }
        }

        //$resutUserPermission = array_merge($arrayGroupPermission, $arrayUserPermission);
        $resutUserPermission = array_merge_recursive($arrayGroupPermission, $arrayUserPermission);
        if (is_array($resutUserPermission)) {
            if (isset($resutUserPermission[$controller]) && in_array($action, $resutUserPermission[$controller])) {
                return true;
            }
        }
        return false;
    }
}