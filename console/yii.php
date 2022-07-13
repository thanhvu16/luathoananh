#!/usr/local/php5-ver2/bin/php -q
<?php
require(__DIR__ . '/../common/config/predefine.php');

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'prod');
defined('YII_GEARMAN') or define('YII_GEARMAN', false);
defined('YII_MONGO') or define('YII_MONGO', true);
defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
defined('STDOUT') or define('STDOUT', fopen('php://stdout', 'w'));

require(__DIR__ . '/../vendor/autoload.php');
//require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
//require(__DIR__ . '/../../yiiphp7/basic/vendor/yiisoft/yii2/Yii.php');
require('../../yiiphp7/basic/vendor/yiisoft/yii2/Yii.php');
$config = require(__DIR__ . '/../console/config/main.php');
$application = new yii\console\Application($config);
$exitCode = $application->run();
exit($exitCode);
