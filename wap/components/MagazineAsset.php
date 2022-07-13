<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 4/19/2017
 * Time: 10:45 AM
 */

namespace wap\components;
use yii\web\AssetBundle;


class MagazineAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'themes/default/ctyluat/css/all.css',
        'themes/default/ctyluat/style.css',
        'themes/default/lib/bootstrap.min.css',
        'themes/default/font-awesome-4.7.0/css/font-awesome.min.css',
        'themes/default/owl-carousel/owl.carousel.css',
        'themes/default/css/style.css',
        'themes/magazine/default/css/style.css',
    ];
    public $js = [
        'themes/default/lib/jquery.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js',
        'themes/default/lib/bootstrap.min.js',
        'themes/default/owl-carousel/owl.carousel.js',
        'themes/default/js/custom.js',
        'themes/magazine/default/js/jquery.min.js',
        'themes/magazine/default/js/main.js',
    ];
}