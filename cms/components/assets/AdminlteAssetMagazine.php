<?php

namespace cms\components\assets;

/**
 * AdminLTE Dependent Source Asset 
 *
 * @author  Nick Tsai <myintaer@gmail.com>
 * @package almasaeed2010/adminlte
 * @see     https://github.com/almasaeed2010/AdminLTE
 */
class AdminlteAssetMagazine extends \yii\web\AssetBundle
{
    /**
     * Bundle with Dependent Source Package
     */
    public $sourcePath = '@vendor/almasaeed2010/adminlte/dist';

    public $css = [
        'css/AdminLTE.min.css',
    ];

    public $js = [
        'js/adminlte.min.js'
    ];

    /**
     * @package yidas/yii2-fontawesome
     * @see     https://github.com/yidas/yii2-fontawesome
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\jui\JuiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'cms\components\assets\FontawesomeAsset',
//        'common\components\ApplicationAsset'
    ];

    /**
     * @var string|bool Choose skin color, eg. `'skin-blue'` or set `false` to disable skin loading
     * @see https://almsaeedstudio.com/themes/AdminLTE/documentation/index.html#layout
     */
    public $skin = 'skin-blue-light';

    /**
     * @inherit
     */
    public function init()
    {
        // Append skin color file if specified
        if ($this->skin) {
            if (('_all-skins' !== $this->skin) && (strpos($this->skin, 'skin-') !== 0)) {
                throw new Exception('Invalid AdminLTE skin specified');
            }
            $this->css[] = sprintf('css/skins/%s.css', $this->skin);
        }

        parent::init();
    }
}