<?php

namespace cms\components\assets;

/**
 * AdminLTE Dependent Source Asset 
 *
 * @author  Nick Tsai <myintaer@gmail.com>
 * @package almasaeed2010/adminlte
 * @see     https://github.com/almasaeed2010/AdminLTE
 */
class CustomAsset extends \yii\web\AssetBundle
{
    /**
     * Bundle with Dependent Source Package
     */
    public $sourcePath = '@webroot/themes/default';

    public $css = [
        'css/style.css',
        'css/global.css',
        'css/animate.css',
        'css/jquery.alerts.css',
        'css/plugins/cropper/cropper.min.css',
    ];

    public $js = [
        'js/plugins/chosen/chosen.jquery.js',
        'js/plugins/cropper/cropper.min.js',
        'js/app.js',
        'js/jquery.alerts.js',
        'js/global.js',
        'js/common.js',
        'js/jquery.poshytip.min.js',
    ];
    
    public $jsOptions = [
        'position' => 3,
    ];

    /**
     * @package yidas/yii2-fontawesome
     * @see     https://github.com/yidas/yii2-fontawesome
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'cms\components\assets\FontawesomeAsset',
    ];
}