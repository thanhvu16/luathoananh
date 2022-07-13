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
class WapAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'themes/default/ctyluat/css/all.css',
        'themes/default/ctyluat/style.css',
        'themes/default/lib/bootstrap.min.css',
        'themes/default/font-awesome-4.7.0/css/font-awesome.min.css',
        'themes/default/owl-carousel/owl.carousel.css',
        'themes/default/owl-carousel/owl.theme.css',
    ];
    public $js = [
        'themes/default/lib/jquery.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js',
        'themes/default/lib/bootstrap.min.js',
        'themes/default/owl-carousel/owl.carousel.js',
        'themes/default/js/custom.js',
//        'themes/default/js/jwplayer.js',
    ];
//    public $depends = [
//        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
//    ];
}