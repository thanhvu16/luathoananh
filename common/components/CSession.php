<?php
/**
 * @author: trinh.kethanh@gmail.com
 * @date: 8/27/2014
 * @function: Class xử lý Session của hệ thống
 * @system: Content Management System
 */
namespace common\components;

use Yii;

class CSession {
    /*
     * @params: $key biến Session đã được khởi tạo
     * @params: $defaultValue Giá trị mặc định của Session khi chưa có Session nào được khởi tạo
     * @function: Trả về Session đã được khởi tạo
     */
    public static function getSession($key, $defaultValue = false) {
        if (isset(Yii::$app->session[$key])) {
            if ($defaultValue) {
                if (isset(Yii::$app->session[$key][$defaultValue])) {
                    return Yii::$app->session[$key][$defaultValue];
                }
                return false;
            }
        }
        return Yii::$app->session[$key];
    }
    /*
     * @params: $key Biến Session muốn khởi tạo
     * @params: $value Giá trị của Session muốn khởi tạo
     * @function: Hàm này sử dụng để khởi tạo Session cho 1 biến
     */
    public static function setSession($key, $value) {
        Yii::$app->session[$key] = $value;
    }
    /*
     * @params: $key biến Session đã được khởi tạo
     * @params: $defaultValue Giá trị mặc định của Session khi chưa có Session nào được khởi tạo
     * @function: Hàm này sử dụng để kiểm tra sự tồn tại của 1 Session
     */
    public static function isSetSession($key, $defaultValue = false) {
        if($defaultValue) {
            return isset(Yii::$app->session[$key]) and isset(Yii::$app->session[$key][$defaultValue]);
        }
        return isset(Yii::$app->session[$key]);
    }
    /*
     * @params: $key biến Session đã được khởi tạo
     * @params: $defaultValue Giá trị mặc định của Session khi chưa có Session nào được khởi tạo
     * @function: Hàm này sử dụng để xóa Session
     */
    public static function deleteSession($key, $defaultValue = false) {
        if ($defaultValue) {
            if (isset(Yii::$app->session[$key][$defaultValue])) {
                unset(Yii::$app->session[$key][$defaultValue]);
            }
        }
        else {
            if (isset(Yii::$app->session[$key])) {
                unset(Yii::$app->session[$key]);
            }
        }
    }
    /*
     * @params: false
     * @function: Hàm này sử dụng để xóa toàn bộ Session của hệ thống
     */
    public static function deleteAllSession() {
        Yii::$app->session->removeAll();
    }
}