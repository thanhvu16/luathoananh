<?php
Yii::setAlias('@console', dirname(__DIR__));
Yii::setAlias('@runnerScript', dirname(__DIR__) . '/yii');
Yii::setAlias('@themes', dirname(dirname(__DIR__)) . '/themes');
Yii::setAlias('@common', dirname(dirname(__DIR__)). '/common');
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/web');
Yii::setAlias('@wap', dirname(dirname(__DIR__)) . '/wap');
Yii::setAlias('@cms', dirname(dirname(__DIR__)) . '/cms');
Yii::setAlias('@mbaby', dirname(dirname(__DIR__)) . '/mbaby');
Yii::setAlias('@film', dirname(dirname(__DIR__)) . '/film');
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');
Yii::setAlias('@vega', dirname(dirname(__DIR__)) . '/common/extensions/vega/');
$db = require(__DIR__ . '/../../common/config/db.php');
return [
    'id' => 'app-console',
    'timeZone' => 'Asia/Ho_Chi_Minh',
    'vendorPath' => dirname(__DIR__) . '/../vendor',
    'runtimePath' => dirname(__DIR__) . '/../../runtime',
    'extensions' => require(__DIR__ . '/../../vendor/yiisoft/extensions.php'),
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    //'modules' => YII_ENV_DEV ? ['gii' => 'yii\gii\Module'] : [],
    'controllerNamespace' => 'console\controllers',
    'modules' => !YII_ENV_PROD ? [
        'debug' => [
            'class' => 'yii\debug\Module',
            'allowedIPs' => ['1.2.3.4', '127.0.0.1', '::1', '118.70.124.143', '113.190.252.218', '183.81.9.171', '123.25.21.138'],
            'panels' => [
                'mongodb' => [
                    'class' => 'yii\\mongodb\\debug\\MongoDbPanel',
                ],
                'elasticsearch' => [
                    'class' => 'yii\\elasticsearch\\DebugPanel',
                ]
            ]
        ]
    ] : [],
    'controllerMap' => [
        'cron' => [
            'class' => 'mitalcoi\cronjobs\CronController',
            'cronJobs' =>[
                'category-stats-daily/clip-stats' => ['cron' => '* * * * *'],
                'category-stats-daily/film-stats' => ['cron' => '* * * * *']
            ]
        ],
        'gearman' => [
            'class' => 'shakura\yii2\gearman\GearmanController',
            'gearmanComponent' => 'gearman',
            'fork'=>true,
        ],
        'script' => [
            'class'=>'common\components\script\controllers\console\ScriptController'
        ],
        'test' => [
            'class'=>'common\components\script\controllers\console\TestController'
        ]

    ],
    'components' => [
        'db' => $db,
        /*'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://videoAdmin:dVeD5tYl@localhost:27017/video',
        ],*/
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
                    'logFile' => '@runtime/logs/content_log.log',
                    'logFormat' => '[time], level [text]', // [tex] = [channel] [network_type] [user_id] [package_id] [package_name] [action] [price] [content_id] [part_id] [content_type] [category_id] [category_name] [cp_id] [cp_name]
                    'logVars' => [''],                     // Example: Call log: Yii::info([channel|user_id|package_id|package_name|action|price|source], 'content');
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20
                ],
                [
                    'class' => 'common\components\CLogSystem',
                    'levels' => ['info', 'error'],
                    'categories' => ['sub'],
                    'logFile' => '@runtime/logs/sub_log.log',
                    'logFormat' => '[time] level [text]', // [text] = [channel|user_id|package_id|package_name|action|price|source]
                    'logVars' => [''],                    // Example: Call log: Yii::info([channel|user_id|package_id|package_name|action|price|source], 'sub');
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20
                ],
                [
                    'class' => 'common\components\CLogSystem',
                    'levels' => ['info', 'error'],
                    'categories' => ['access'],
                    'logFile' => '@runtime/logs/access_log.log',
                    'logFormat' => '[time] level, [text]', // [text] = [channel] [network_type] [user_id] [package_id] [package_name] [ip] [source] [model] [user_agent]
                    'logVars' => [''],                     // Example: Call log: Yii::info([channel|user_id|package_id|package_name|action|price|source], 'access');
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20
                ],
                [
                    'class' => 'common\components\CLogSystem',
                    'levels' => ['info', 'error', 'warning'],
                    'categories' => ['sms'],
                    'logFile' => '@runtime/logs/sms.log',
                    'logFormat' => '[time] level, [text]', // [text] = [channel] [network_type] [user_id] [package_id] [package_name] [ip] [source] [model] [user_agent]
                    'logVars' => [''],                     // Example: Call log: Yii::info([channel|user_id|package_id|package_name|action|price|source], 'access');
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20
                ],
                [
                    'class' => 'common\components\CLogSystem',
                    'levels' => ['info', 'error', 'warning'],
                    'categories' => ['charging'],
                    'logFile' => '@runtime/logs/charging.log',
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
                    'logFile' => '@runtime/logs/urlGenerator.log',
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
        'gearman' => [
            'class' => 'shakura\yii2\gearman\GearmanComponent',
            'servers' => [
                ['host' => '127.0.0.1', 'port' => 4730],
            ],
            'user' => 'developer',
            'jobs' => [
                'Gamification' => [
                    'class' => 'common\components\Gamification'
                ],
            ]
        ],
        'telco'=>[
            'class'=>'vega\telco\vinaphone\Vinaphone',
            'recognizers'=>[
                'headerRecognizer'=>[
                    'class'=>'vega\telco\vinaphone\recognizing\HeaderRecognizer',
                    'mSISDNHeaders'=>[
                        'HTTP_X_WAP_MSISDN',
                        'X_WAP_MSISDN',
                        'X-WAP-MSISDN',
                        'X-Wap-MSISDN',
                        'MSISDN',
                        'msisdn',
                        'HTTP_MSISDN'
                    ],
                ]
            ],
            'chargers'=>[
                'package'=>[
                    'class'=>'vega\telco\vinaphone\charging\PackageCharger',
                    'baseUrl'=>'http://10.1.10.86:8080/billing/billing',
                    'chargerName'=>'VCLIP',
                    'username'=>'vclip',
                    'password'=>'vclip#vega@2012',
                    'behaviors'=>[
                        'logMysqlDB'=>[
                            'class'=>'common\components\script\behaviors\charging\LogMysqlDBChargingBehavior'
                        ],
                        'logFile'=>[
                            'class'=>'common\components\script\behaviors\charging\LogFileChargingBehavior'
                        ],
                        'logMongoDB'=>[
                            'class'=>'common\components\script\behaviors\charging\LogMongoDBChargingBehavior'
                        ],
                    ]
                ],
                'test'=>[
                    'class'=>'common\components\test\Charger',
                    'baseUrl'=>'http://10.1.10.86:8080/billing/billing',
                    'chargerName'=>'VCLIP',
                    'username'=>'vclip',
                    'password'=>'vclip#vega@2012',
                    'behaviors'=>[
                        'logMysqlDB'=>[
                            'class'=>'common\components\script\behaviors\charging\LogMysqlDBChargingBehavior'
                        ],
                        'logFile'=>[
                            'class'=>'common\components\script\behaviors\charging\LogFileChargingBehavior'
                        ],
                        'logMongoDB'=>[
                            'class'=>'common\components\script\behaviors\charging\LogMongoDBChargingBehavior'
                        ],
                    ]
                ],
                'raw'=>[
                    'class'=>'common\components\test\RawCharger',
                    'baseUrl'=>'http://10.1.10.86:8080/billing/billing',
                    'chargerName'=>'VCLIP',
                    'username'=>'vclip',
                    'password'=>'vclip#vega@2012',
                    'behaviors'=>[
                        'logMysqlDB'=>[
                            'class'=>'common\components\script\behaviors\charging\LogMysqlDBChargingBehavior'
                        ],
                        'logFile'=>[
                            'class'=>'common\components\script\behaviors\charging\LogFileChargingBehavior'
                        ],
                        'logMongoDB'=>[
                            'class'=>'common\components\script\behaviors\charging\LogMongoDBChargingBehavior'
                        ],
                    ]
                ],
            ],
            'smsSenders'=>[
                'freeSMS'=>[
                    'class'=>'vega\telco\vinaphone\sms\SMSSender',
                    'behaviors'=>[
                        'logMysqlDB'=>[
                            'class'=>'common\components\script\behaviors\sms\LogMysqlDBSMSBehavior'
                        ],
                        'logFile'=>[
                            'class'=>'common\components\script\behaviors\sms\LogFileSMSBehavior'
                        ],
                        'logMongoDB'=>[
                            'class'=>'common\components\script\behaviors\sms\LogMongoDBSMSBehavior'
                        ],
                    ],
                    'baseUrl'=>'http://10.1.10.67:8080/api/soap',
                    'username'=>'vclip',
                    'password'=>'vclip123',
                    'mSISDNSender'=>9234,
                    'serviceNumber'=>9234,
                    'charge'=>0,
                    'messageType'=>0,
                    'sMSC'=>9234,
                ],
                'testSMS'=>[
                    'class'=>'common\components\test\SMSSender',
                    'behaviors'=>[
                        'logMysqlDB'=>[
                            'class'=>'common\components\script\behaviors\sms\LogMysqlDBSMSBehavior'
                        ],
                        'logFile'=>[
                            'class'=>'common\components\script\behaviors\sms\LogFileSMSBehavior'
                        ],
                        'logMongoDB'=>[
                            'class'=>'common\components\script\behaviors\sms\LogMongoDBSMSBehavior'
                        ],
                    ],
                    'baseUrl'=>'http://210.211.101.16:6090/cp/sendBulkSms',
                    'username'=>'mkid',
                    'password'=>'mkid@123312',
                    'appID'=>'58',
                    'key'=>'123',
                    'mSISDNSender'=>7062,
                    'serviceNumber'=>7062,
                    'messageType'=>0,
                ],
            ],
            'urlGenerators'=>[
                'confirmRegistration'=>[
                    'class'=>'vega\telco\vinaphone\helper\RedirectUrlGenerator',
                    'baseUrl'=>'http://dk.vinaphone.com.vn/reg.jsp',
                    'cPID'=>'VEGA',
                    'service'=>'VCLIP',
                    'channel'=>1,
                    'securePass'=>'vega@4XXs@',
                ],
                'confirmCancellation'=>[
                    'class'=>'vega\telco\vinaphone\helper\RedirectUrlGenerator',
                    'baseUrl'=>'http://dk.vinaphone.com.vn/unreg.jsp',
                    'cPID'=>'VEGA',
                    'service'=>'VCLIP',
                    'channel'=>1,
                    'securePass'=>'vega@4XXs@',
                ],
                'confirmRegistrationTest'=>[
                    'class'=>'vega\telco\vinaphone\helper\RedirectUrlGenerator',
                    'baseUrl'=>'http://dangky.mobifone.com.vn/wap/html/sp/confirm_html5.jsp',
                    'cPID'=>'VEGA',
                    'service'=>'VCLIP',
                    'channel'=>1,
                    'securePass'=>'vega@4XXs@',
                ],
            ],
        ],
        'scriptManager'=>[
            'class'=>'common\components\ScriptManager',
            'scripts'=>[
                'default'=>[
                    'class'=>'common\components\script\Vinaphone',
                ],
                'live'=>[
                    'class'=>'common\components\script\VinaphoneLive',
                ],
                'filmContent'=>[
                    'class'=>'common\components\script\VinaphoneFilmContent',
                ],
                'clipContent'=>[
                    'class'=>'common\components\script\VinaphoneClipContent',
                ],
            ]
        ],
    ],
    'params' => require(__DIR__ . '/params.php')
];