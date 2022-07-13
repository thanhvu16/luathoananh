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
class EmbedAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        'themes/default/css/switchery.min.css',
    ];
    public $js = [
        'themes/default/js/jquery-2.1.4.js',
//        'themes/default/js/jwplayer.js',
    ];
//    public $depends = [
//        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
//    ];
}