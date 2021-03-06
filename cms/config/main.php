
<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/params.php')
);
$db = require(__DIR__ . '/../../common/config/db.php');
$bundles = require(__DIR__ . '/bundles.php');
$components = require(__DIR__ . '/../../common/config/components.php');

return array_replace_recursive(
    require(__DIR__ . '/../../common/config/main.php'),
    [
        'id' => 'app-cms',
        'vendorPath' => dirname(__DIR__) . '/../vendor',
        'extensions' => require(__DIR__ . '/../../vendor/yiisoft/extensions.php'),
        'basePath' => dirname(__DIR__),
        'defaultRoute' => 'default',
        'bootstrap' => ['log', 'debug'],		
        'modules' => [
            'gii' => [
                'class' => 'yii\gii\Module',
                'allowedIPs' => ['127.0.0.1', '::1'],
                'generators' => [
                    'crud'   => [
                        'class'     => 'cms\templates\gii\crud\Generator',
                    ],
                    'model'   => [
                        'class'     => 'cms\templates\gii\model\Generator'
                    ],
                    'mongoDbModel' => [
                        'class' => 'yii\mongodb\gii\model\Generator'
                    ]
                ]
            ],
            'debug' => [
                'class' => 'yii\debug\Module',
                //'allowedIPs' => ['1.2.3.4'],
                'panels' => [
                    'mongodb' => [
                        'class' => 'yii\\mongodb\\debug\\MongoDbPanel',
                    ],
                    'elasticsearch' => [
                        'class' => 'yii\\elasticsearch\\DebugPanel',
                    ]
                ]
            ],
			'comment' => [
                'class' => 'yii2mod\comments\Module',
            ],
        ],
        'controllerNamespace' => 'cms\controllers',
        'components' => array_merge($components, [
            'db' => $db,
            'user' => [
                'identityClass' => 'cms\models\AdminUser',
                'enableAutoLogin' => true,
            ],
            'session' => [
                'class' => 'yii\web\Session',
                'cookieParams' => ['httponly' => true, 'lifetime' => 24 *60 * 60],
                'timeout' => 3600*5, //session expire
                'useCookies' => true,
            ],
            'errorHandler' => [
                'errorAction' => 'default/error'
            ],
            'view' => [
                'theme' => [
                    'class'=>'common\components\Theme',
                    'active' => 'default',
                    'pathMap' => [
                        '@app/views' => [
                            '@cms/views'
                        ]
                     ]
                ]
            ],
            'assetManager' => [
                'forceCopy' => !YII_ENV_PROD,
                'bundles'=>$bundles,
            ],
            'authClientCollection' => [
                'class' => 'yii\authclient\Collection',
                'clients' => [
                    'google' => [
//                        'class' => 'yii\authclient\clients\GoogleOAuth',
//                        'class' => 'yii\authclient\clients\Google',
                        'class' => 'yii\authclient\clients\GoogleOAuth',
                        'clientId' => '441368620449-s3g8836t2nr882erc17f99ajbcu0pdu5.apps.googleusercontent.com',
                        'clientSecret' => 'P_spkfgTGlr48UZfFhnurVM-',
//						'clientId' => '1051304982031-s3506m5odsudlf6jurem0hq222kcq478.apps.googleusercontent.com',
//                        'clientSecret' => 'oXEGzCUe_bgvxeRjTqAMhxyZ',
                    ],
                ],
            ],
            'urlManager' => [
                'class' => 'yii\web\UrlManager',
                'enablePrettyUrl' => true,
                'showScriptName' => false,
                'suffix' => '.html',
                'rules' => [
                    ['pattern'=>'<controller:\w+>/<id:\d+>','route'=>'<controller>/view','suffix'=>'.html'],
                    ['pattern'=>'<controller:\w+>/<action:\w+>/<id:\d+>','route'=>'<controller>/<action>','suffix'=>'.html'],
                    ['pattern'=>'<controller:\w+>/<action:\w+>','route'=>'<controller>/<action>','suffix'=>'.html'],
                    ['pattern'=>'module/<module:\w+>/<controller:\w+>/<action:\w+>','route'=>'<module>/<controller>/<action>','suffix'=>'.html'],
                    ['pattern'=>'debug/<controller>/<action>','route'=>'debug/<controller>/<action>','suffix'=>'.html'],
                    ['pattern'=>'cskh/','route'=>'cskh/index', 'suffix'=>''],
                    ['pattern'=>'cskh/<action>','route'=>'cskh/<action>', 'suffix'=>''],
                    ['pattern'=>'cskh/<action>','route'=>'cskh/<action>', 'suffix'=>'/'],
                    ['pattern'=>'customer/<action>','route'=>'customer/<action>', 'suffix'=>''],
                ]
            ]
        ]),
        'params' => $params,
        'controllerMap' => [
            'elFinder' => [
                'class' => 'mihaildev\elfinder\PathController',
                'access' => ['@'],
                'disabledCommands' => ['preview', 'rm', 'duplicate', 'rename', 'extract', 'archive', 'help', 'netmount', 'info', 'cut', 'download', 'mkfile'],
                'connectOptions' => [
                    'bind' => array(
                        'upload.pre mkdir.pre mkfile.pre rename.pre archive.pre ls.pre' => [
                            'Plugin.Normalizer.cmdPreprocess',
                            'Plugin.Sanitizer.cmdPreprocess',
                        ],
                        'ls' => [
                            'Plugin.Normalizer.cmdPostprocess',
                            'Plugin.Sanitizer.cmdPostprocess',
                        ],
                        'upload.presave' => [
                            'Plugin.Normalizer.onUpLoadPreSave',
                            'Plugin.Sanitizer.onUpLoadPreSave',
                        ],
                    ),
                    'plugin' => [
                        'Sanitizer' => [
                            'enable' => true,
                            'targets' => ['\\', '/', ':', '*', '?', '"', '<', '>', '|', '&'],
                            'replace' => '-'
                        ],
                        'AutoResize' => ['enable' => true,],
                        'AutoRotate' => ['enable' => true],
                        'Normalizer' => [
                            'enable' => true,
                            'nfc' => true,
                            'nfkc' => true,
                            'lowercase' => true,
                            'convmap' => [
                                ' ' => '-',
                                ',' => '-',
                                '^' => '-',
                                '??' => 'a', '??' => 'a', '???' => 'a', '??' => 'a', '???' => 'a',
                                '??' => 'a', '???' => 'a', '???' => 'a', '???' => 'a', '???' => 'a', '???' => 'a',
                                '??' => 'a', '???' => 'a', '???' => 'a', '???' => 'a', '???' => 'a', '???' => 'a',
                                '??' => 'a', '??' => 'a', '???' => 'a', '??' => 'a', '???' => 'a',
                                '??' => 'a', '???' => 'a', '???' => 'a', '???' => 'a', '???' => 'a', '???' => 'a',
                                '??' => 'a', '???' => 'a', '???' => 'a', '???' => 'a', '???' => 'a', '???' => 'a',
                                '??' => 'd', '??' => 'd',
                                '??' => 'e', '??' => 'e', '???' => 'e', '???' => 'e', '???' => 'e',
                                '??' => 'e', '???' => 'e', '???' => 'e', '???' => 'e', '???' => 'e', '???' => 'e',
                                '??' => 'e', '??' => 'e', '???' => 'e', '???' => 'e', '???' => 'e',
                                '??' => 'e', '???' => 'e', '???' => 'e', '???' => 'e', '???' => 'e', '???' => 'e',
                                '??' => 'i', '??' => 'i', '???' => 'i', '??' => 'i', '???' => 'i',
                                '??' => 'i', '??' => 'i', '???' => 'i', '??' => 'i', '???' => 'i',
                                '??' => 'o', '??' => 'o', '???' => 'o', '??' => 'o', '???' => 'o',
                                '??' => 'o', '???' => 'o', '???' => 'o', '???' => 'o', '???' => 'o', '???' => 'o',
                                '??' => 'o', '???' => 'o', '???' => 'o', '???' => 'o', '???' => 'o', '???' => 'o',
                                '??' => 'o', '??' => 'o', '???' => 'o', '??' => 'o', '???' => 'o',
                                '??' => 'o', '???' => 'o', '???' => 'o', '???' => 'o', '???' => 'o', '???' => 'o',
                                '??' => 'o', '???' => 'o', '???' => 'o', '???' => 'o', '???' => 'o', '???' => 'o',
                                '??' => 'u', '??' => 'u', '???' => 'u', '??' => 'u', '???' => 'u',
                                '??' => 'u', '???' => 'u', '???' => 'u', '???' => 'u', '???' => 'u', '???' => 'u',
                                '??' => 'u', '??' => 'u', '???' => 'u', '??' => 'u', '???' => 'u',
                                '??' => 'u', '???' => 'u', '???' => 'u', '???' => 'u', '???' => 'u', '???' => 'u',
                                '???' => 'y', '??' => 'y', '???' => 'y', '???' => 'y', '???' => 'y',
                                'Y' => 'y', '???' => 'y', '??' => 'y', '???' => 'y', '???' => 'y', '???' => 'y'
                            ]
                        ],
                    ],
                    'onlyMimes' => ["image/png", "application/x-shockwave-flash"]
                ],
                'root' => [
                    //n???u localhost th?? nh?? d?????i n???u tr??n domain th?? baseUrl ????? tr???ng
                    'baseUrl' => 'https://luathoanganh.vn/media/uploads',
                    'basePath' => '/srv/www/luathoanganh.vn/wap/www/media/uploads',
                    'path' => '/',
                    'name' => '/uploads/',
                    'options' => [
                        'uploadOverwrite' => false,
                        'defaults'   => array('read' => true, 'write' => true,'locked'=>true),
                    ],
                ]
            ],
        ]
    ]
);