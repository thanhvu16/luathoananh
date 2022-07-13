<?php

namespace cms\components\assets;

/**
 * AdminLTE Dependent Source Asset 
 *
 * @author  Nick Tsai <myintaer@gmail.com>
 * @package almasaeed2010/adminlte
 * @see     https://github.com/almasaeed2010/AdminLTE
 */
class PaceProgressAsset extends \yii\web\AssetBundle
{
    /**
     * Bundle with Dependent Source Package
     */
    public $sourcePath = '@vendor/almasaeed2010/adminlte';

    public $css = [
        'plugins/pace/pace.min.css',
        'bower_components/Ionicons/css/ionicons.min.css'
    ];

    public $js = [
        'bower_components/PACE/pace.min.js',
        'bower_components/chart.js/Chart.js'
    ];
    
    public $jsOptions = [
        'position' => 3,
    ];
}