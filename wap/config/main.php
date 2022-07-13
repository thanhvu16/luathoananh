<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/category.php'),
    require(__DIR__ . '/sender.php')
);
$db = require(__DIR__ . '/../../common/config/db.php');
$bundles = require(__DIR__ . '/bundles.php');
$components = require(__DIR__ . '/../../common/config/components.php');
$urlRules = require(__DIR__ . '/url_rules.php');

return array_replace_recursive(
    require(__DIR__ . '/../../common/config/main.php'),
    [
        'id' => 'app-wap',
        'vendorPath' => dirname(__DIR__) . '/../vendor',
        'extensions' => require(__DIR__ . '/../../vendor/yiisoft/extensions.php'),
        'basePath' => dirname(__DIR__),
        'defaultRoute' => 'default',
        'bootstrap' => [
		'log', 
		'assetsAutoCompress', 
		//'debug'
		],
        'modules' => [
            'gii' => [
                'class' => 'yii\gii\Module',
                'allowedIPs' => ['127.0.0.1', '::1'],
            ],
            'debug' => [
                'class' => 'yii\debug\Module',
                'allowedIPs' => ['1.2.3.4', '127.0.0.1'],
            ],
			'comment' => [
                'class' => 'yii2mod\comments\Module',
            ],
        ],
        'controllerNamespace' => 'wap\controllers',
        'components' => array_merge($components, [
            'db' => $db,
            'mailer' => [
                'class' => 'yii\swiftmailer\Mailer',
                'transport' => [
                    'class' => 'Swift_SmtpTransport',
                    'host' => 'smtp.gmail.com',  // e.g. smtp.mandrillapp.com or smtp.gmail.com
                    'username' => 'y0mn9x@gmail.com',
                    'password' => 'kospytcsjzufxzue',
                    'port' => '587', // Port 25 is a very common port too
                    'encryption' => 'tls', // It is often used, check your provider or mail server specs
                ],
            ],
            'solr' => [
                'class' => 'sammaye\solr\Client',
                'options' => [
                    'endpoint' => [
                        'solr1' => [
                            'host' => '10.58.82.57',
                            'port' => '8988',
                            'path' => '/solr/mclip/'
                        ]
                    ]
                ]
            ],
            'user' => [
				'identityClass' => 'wap\models\CommentUser',
                //'identityClass' => 'wap\models\User',
                'enableAutoLogin' => true,
                'autoRenewCookie' => true,
                'authTimeout' => 31557600,
            ],
            'errorHandler' => [
                'errorAction' => 'default/error'
            ],
            'authClientCollection' => [
                'class' => 'yii\authclient\Collection',
                'clients' => [
                    'facebook' => [
                        'class' => 'yii\authclient\clients\Facebook',
                        'clientId' => '543039294166929',
                        'clientSecret' => 'e7a9c012b4b61e53942fe3249ed5ab0c',
                    ],
					'google' => [
                        'class' => 'yii\authclient\clients\Google',
                        'clientId' => '89457873589-8fr24gh540sl9ak2l9jeul8u2e5m8ao5.apps.googleusercontent.com',
                        'clientSecret' => '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',
                    ],
                ],
            ],
            'view' => [
                'theme' => [
                    'class'=>'common\components\Theme',
                    'active' => 'default',
//                    'active' => 'summer',
//                    'active' => 'christmas',
//                    'active' => 'tet',
                    'pathMap' => [
                        '@app/views' => [
                            '@wap/views'
                        ]
                    ]
                ]
            ],
            'assetManager' => [
//                'forceCopy' => !YII_ENV_PROD,  //bật dòng này lên khi lên thật
                'forceCopy' => !YII_ENV_PROD,
                'bundles'=>$bundles,
                'appendTimestamp' => true,
            ],
            'urlManager' => [
                'class' => 'yii\web\UrlManager',
                'enablePrettyUrl' => true,
                'showScriptName' => false,
                'suffix' => '.html',
                'rules' => $urlRules
            ],
            'formatter' => [
                'dateFormat' => 'dd/MM/yyyy',
            ],

            'assetsAutoCompress' =>
                [
                    'class'                         => '\skeeks\yii2\assetsAuto\AssetsAutoCompressComponent',
                    'enabled'                       => false,

                    'readFileTimeout'               => 3,           //Time in seconds for reading each asset file

                    'cssCompress'                   => false,        //Enable minification css in html code

                    'cssFileCompile'                => false,        //Turning association css files ************
                    'cssFileRemouteCompile'         => false,       //Trying to get css files to which the specified path as the remote file, skchat him to her.
                    'cssFileCompress'               => false,        //Enable compression and processing before being stored in the css file
                    'cssFileBottom'                 => false,       //Moving down the page css files
                    'cssFileBottomLoadOnJs'         => false,       //Transfer css file down the page and uploading them using js

                    'jsCompress'                    => false,        //Enable minification js in html code
                    'jsCompressFlaggedComments'     => false,        //Cut comments during processing js
                    'jsFileCompile'                 => false,        //Turning association js files ************
                    'jsFileRemouteCompile'          => false,       //Trying to get a js files to which the specified path as the remote file, skchat him to her.
                    'jsFileCompress'                => false,        //Enable compression and processing js before saving a file
                    'jsFileCompressFlaggedComments' => false,        //Cut comments during processing js

                    'htmlCompress'                  => false,        //Enable compression html
                    'htmlCompressOptions'           =>              //options for compressing output result
                        [
                            'extra' => true,        //use more compact algorithm
                            'no-comments' => true   //cut all the html comments
                        ],
                ],
        ]),
        'params' => $params,
//        'controllerMap' => [
//            'vas-portal' => [
//                'class' => 'common\components\script\controllers\wap\VasController',
//            ],
//        ],
    ]
);