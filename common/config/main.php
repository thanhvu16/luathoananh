<?php
Yii::setAlias('@themes', dirname(dirname(__DIR__)) . '/themes');
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/web');
Yii::setAlias('@wap', dirname(dirname(__DIR__)) . '/wap');
Yii::setAlias('@cms', dirname(dirname(__DIR__)) . '/cms');
Yii::setAlias('@mbaby', dirname(dirname(__DIR__)) . '/mbaby');
Yii::setAlias('@film', dirname(dirname(__DIR__)) . '/film');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');
Yii::setAlias('@vega', dirname(__DIR__).'/extensions/vega');
return [
    'basePath' => dirname(__DIR__),
	'timeZone' => 'Asia/Ho_Chi_Minh',
    'runtimePath' => dirname(__DIR__) . '/../../runtime',
    'name' => 'OTT CMS',
    'language' => 'vi',
    'sourceLanguage' => 'en',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'extensions' => require(__DIR__ . '/../../vendor/yiisoft/extensions.php'),
    'bootstrap' => ['log'],
    'components' => [
        'elasticsearch' => [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
                ['http_address' => '192.168.241.114:9200']
            ]
        ],
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            //'dsn' => 'mongodb://videoAdmin:dVeD5tYl@192.168.241.107:27017/video',
            'dsn' => 'mongodb://localhost:27017/mclip_v2',
        ],
        'gearman' => [
            'class' => 'shakura\yii2\gearman\GearmanComponent',
            'servers' => [
                ['host' => '127.0.0.1', 'port' => 4730],
            ],
            'user' => 'developer',
            'jobs' => [
                'sendSms' => [
                    'class' => 'common\components\script\jobs\SendSms'
                ],
                'Gamification' => [
                    'class' => 'common\components\Gamification'
                ],
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache'
        ],
        'mobiledetect' => [
            'class' => 'dkeeper\mobiledetect\Detect',
        ],
        'log' => [
            'flushInterval' => 1,
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning']
                ],
                [
                    'class' => 'common\components\CLogSystem',
                    'levels' => ['info', 'error'],
                    'categories' => ['content'],
                    'logFile' => '@runtime/logs/content_log/content_log.log',
                    'logFormat' => '[time], level [text]', // [tex] = [channel] [network_type] [user_id] [package_id] [package_name] [action] [price] [content_id] [part_id] [content_type] [category_id] [category_name] [cp_id] [cp_name]
                    'logVars' => [''],                     // Example: Call log: Yii::info([channel|user_id|package_id|package_name|action|price|source], 'content');
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20
                ],
                [
                    'class' => 'common\components\CLogSystem',
                    'levels' => ['info', 'error'],
                    'categories' => ['sub'],
                    'logFile' => '@runtime/logs/sub_log/sub_log.log',
                    'logFormat' => '[time] level [text]', // [text] = [channel|user_id|package_id|package_name|action|price|source]
                    'logVars' => [''],                    // Example: Call log: Yii::info([channel|user_id|package_id|package_name|action|price|source], 'sub');
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20
                ],
                [
                    'class' => 'common\components\CLogSystem',
                    'levels' => ['info', 'error'],
                    'categories' => ['access'],
                    'logFile' => '@runtime/logs/access_log/access_log.log',
                    'logFormat' => '[time] level, [text]', // [text] = [channel] [network_type] [user_id] [package_id] [package_name] [ip] [source] [model] [user_agent]
                    'logVars' => [''],                     // Example: Call log: Yii::info([channel|user_id|package_id|package_name|action|price|source], 'access');
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20
                ],
                [
                    'class' => 'common\components\CLogSystem',
                    'levels' => ['info', 'error', 'warning'],
                    'categories' => ['sms'],
                    'logFile' => '@runtime/logs/sms_log/sms.log',
                    'logFormat' => '[time] level, [text]', // [text] = [channel] [network_type] [user_id] [package_id] [package_name] [ip] [source] [model] [user_agent]
                    'logVars' => [''],                     // Example: Call log: Yii::info([channel|user_id|package_id|package_name|action|price|source], 'access');
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20
                ],
                [
                    'class' => 'common\components\CLogSystem',
                    'levels' => ['info', 'error', 'warning'],
                    'categories' => ['charging'],
                    'logFile' => '@runtime/logs/charging_log/charging.log',
                    'logFormat' => '[time] level, [text]', // [text] = [channel] [network_type] [user_id] [package_id] [package_name] [ip] [source] [model] [user_agent]
                    'logVars' => [''],                     // Example: Call log: Yii::info([channel|user_id|package_id|package_name|action|price|source], 'access');
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20,
                    'exportInterval' => 1,
                ],
                [
                    'class' => 'common\components\CLogSystem',
                    'levels' => ['info'],
                    'categories' => ['urlGenerator'],
                    'logFile' => '@runtime/logs/urlGenerator/urlGenerator.log',
                    'logFormat' => '[time] level, [text]', // [text] = [channel] [network_type] [user_id] [package_id] [package_name] [ip] [source] [model] [user_agent]
                    'logVars' => [''],                     // Example: Call log: Yii::info([channel|user_id|package_id|package_name|action|price|source], 'access');
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20
                ],
                [
                    'class' => 'common\components\CLogSystem',
                    'levels' => ['info', 'error', 'warning'],
                    'categories' => ['script_request_all'],
                    'logFile' => '@runtime/logs/script/request_all.log',
                    'logFormat' => '[time] level, [text]', // [text] = [channel] [network_type] [user_id] [package_id] [package_name] [ip] [source] [model] [user_agent]
                    'logVars' => [''],                     // Example: Call log: Yii::info([channel|user_id|package_id|package_name|action|price|source], 'access');
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20
                ],
                [
                    'class' => 'common\components\CLogSystem',
                    'levels' => ['info', 'error', 'warning'],
                    'categories' => ['script_request_soap_cancel'],
                    'logFile' => '@runtime/logs/script/soap/request_cancel.log',
                    'logFormat' => '[time] level, [text]', // [text] = [channel] [network_type] [user_id] [package_id] [package_name] [ip] [source] [model] [user_agent]
                    'logVars' => [''],                     // Example: Call log: Yii::info([channel|user_id|package_id|package_name|action|price|source], 'access');
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20
                ],
                [
                    'class' => 'common\components\CLogSystem',
                    'levels' => ['info', 'error', 'warning'],
                    'categories' => ['script_request_soap_register'],
                    'logFile' => '@runtime/logs/script/soap/request_register.log',
                    'logFormat' => '[time] level, [text]', // [text] = [channel] [network_type] [user_id] [package_id] [package_name] [ip] [source] [model] [user_agent]
                    'logVars' => [''],                     // Example: Call log: Yii::info([channel|user_id|package_id|package_name|action|price|source], 'access');
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20
                ],
                [
                    'class' => 'common\components\CLogSystem',
                    'levels' => ['info', 'error', 'warning'],
                    'categories' => ['script_request_soap_delete'],
                    'logFile' => '@runtime/logs/script/soap/request_delete.log',
                    'logFormat' => '[time] level, [text]', // [text] = [channel] [network_type] [user_id] [package_id] [package_name] [ip] [source] [model] [user_agent]
                    'logVars' => [''],                     // Example: Call log: Yii::info([channel|user_id|package_id|package_name|action|price|source], 'access');
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20
                ],
                [
                    'class' => 'common\components\CLogSystem',
                    'levels' => ['info', 'error', 'warning'],
                    'categories' => ['script_request_soap_phone'],
                    'logFile' => '@runtime/logs/script/soap/request_phone.log',
                    'logFormat' => '[time] level, [text]', // [text] = [channel] [network_type] [user_id] [package_id] [package_name] [ip] [source] [model] [user_agent]
                    'logVars' => [''],                     // Example: Call log: Yii::info([channel|user_id|package_id|package_name|action|price|source], 'access');
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20
                ]
            ],
        ],
        'request' => [
            'enableCookieValidation' => true,
            'enableCsrfValidation' => true,
            'cookieValidationKey' => 'cms@123'
        ],
        'i18n' => array(
            'translations' => array(
                'app' => array('class' => 'yii\i18n\PhpMessageSource', 'basePath' => '@common/messages'),
                'frontend' => array('class' => 'yii\i18n\PhpMessageSource', 'basePath' => '@common/messages'),
                'wap' => array('class' => 'yii\i18n\PhpMessageSource', 'basePath' => '@common/messages'),
                'cms' => array('class' => 'yii\i18n\PhpMessageSource','basePath' => '@common/messages'),
                'console' => array('class' => 'yii\i18n\PhpMessageSource', 'basePath' => '@common/messages'),
                'film' => array('class' => 'yii\i18n\PhpMessageSource', 'basePath' => '@common/messages'),
                'mbaby' => array('class' => 'yii\i18n\PhpMessageSource','basePath' => '@common/messages'),
                'api' => array('class' => 'yii\i18n\PhpMessageSource','basePath' => '@common/messages'),
                'controller' => array('class' => 'yii\i18n\PhpMessageSource','basePath' => '@common/messages'),
                'kvdrp' => array('class' => 'yii\i18n\PhpMessageSource','basePath' => '@common/messages'),
				'yii2mod.comments' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
            )
        )
    ]
];
