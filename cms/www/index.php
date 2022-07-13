<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

require(__DIR__ . '/../../common/config/predefine.php');

if(isset($_GET['dev']) && $_GET['dev'] == 1){
    error_reporting(E_ALL);
    ini_set("display_startup_errors","1");
    ini_set("display_errors","1");
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER',true);
}else{
    @error_reporting(1);
    @ini_set('display_errors', 1);
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER',true);
}

//defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');
defined('YII_GEARMAN') or define('YII_GEARMAN', false);
defined('YII_MONGO') or define('YII_MONGO', true);

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
//require(__DIR__ . '/../../../yiiphp7/basic/vendor/yiisoft/yii2/Yii.php');
//require('../../yiiphp7/basic/vendor/yiisoft/yii2/Yii.php');

require(__DIR__ . '/../../common/config/functions.php');

$config = require(__DIR__ . '/../../cms/config/main.php');
(new yii\web\Application($config))->run();


