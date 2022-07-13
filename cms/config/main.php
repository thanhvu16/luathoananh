
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
                                'à' => 'a', 'á' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a',
                                'ă' => 'a', 'ằ' => 'a', 'ắ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ặ' => 'a',
                                'â' => 'a', 'ầ' => 'a', 'ấ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ậ' => 'a',
                                'À' => 'a', 'Á' => 'a', 'Ả' => 'a', 'Ã' => 'a', 'Ạ' => 'a',
                                'Ă' => 'a', 'Ằ' => 'a', 'Ắ' => 'a', 'Ẳ' => 'a', 'Ẵ' => 'a', 'Ặ' => 'a',
                                'Â' => 'a', 'Ầ' => 'a', 'Ấ' => 'a', 'Ẩ' => 'a', 'Ẫ' => 'a', 'Ậ' => 'a',
                                'đ' => 'd', 'Đ' => 'd',
                                'è' => 'e', 'é' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e',
                                'ê' => 'e', 'ề' => 'e', 'ế' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ệ' => 'e',
                                'È' => 'e', 'É' => 'e', 'Ẻ' => 'e', 'Ẽ' => 'e', 'Ẹ' => 'e',
                                'Ê' => 'e', 'Ề' => 'e', 'Ế' => 'e', 'Ể' => 'e', 'Ễ' => 'e', 'Ệ' => 'e',
                                'ì' => 'i', 'í' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
                                'Ì' => 'i', 'Í' => 'i', 'Ỉ' => 'i', 'Ĩ' => 'i', 'Ị' => 'i',
                                'ò' => 'o', 'ó' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o',
                                'ô' => 'o', 'ồ' => 'o', 'ố' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o',
                                'ơ' => 'o', 'ờ' => 'o', 'ớ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o',
                                'Ò' => 'o', 'Ó' => 'o', 'Ỏ' => 'o', 'Õ' => 'o', 'Ọ' => 'o',
                                'Ô' => 'o', 'Ồ' => 'o', 'Ố' => 'o', 'Ổ' => 'o', 'Ỗ' => 'o', 'Ộ' => 'o',
                                'Ơ' => 'o', 'Ờ' => 'o', 'Ớ' => 'o', 'Ở' => 'o', 'Ỡ' => 'o', 'Ợ' => 'o',
                                'ù' => 'u', 'ú' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u',
                                'ư' => 'u', 'ừ' => 'u', 'ứ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u',
                                'Ù' => 'u', 'Ú' => 'u', 'Ủ' => 'u', 'Ũ' => 'u', 'Ụ' => 'u',
                                'Ư' => 'u', 'Ừ' => 'u', 'Ứ' => 'u', 'Ử' => 'u', 'Ữ' => 'u', 'Ự' => 'u',
                                'ỳ' => 'y', 'ý' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y',
                                'Y' => 'y', 'Ỳ' => 'y', 'Ý' => 'y', 'Ỷ' => 'y', 'Ỹ' => 'y', 'Ỵ' => 'y'
                            ]
                        ],
                    ],
                    'onlyMimes' => ["image/png", "application/x-shockwave-flash"]
                ],
                'root' => [
                    //nếu localhost thì như dưới nếu trên domain thì baseUrl để trống
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