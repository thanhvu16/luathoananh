<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 4/19/2017
 * Time: 10:45 AM
 */

namespace wap\components;
use yii\web\AssetBundle;

/**
 * Assets file cho trang login
 * Class LoginAsset
 * @package wap\components
 */
class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'themes/default/css/bootstrap.min.css',
        'themes/default/css/login.css',
        'themes/default/css/login-custom.css',
    ];
    public $js = [
    ];
//    public $depends = [
//        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
//    ];
}