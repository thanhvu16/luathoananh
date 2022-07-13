<?php

return [
    'common\components\ApplicationAsset' => [
        'css' => [
            'font-awesome/css/font-awesome.css',
            'css/open-sans.css',
            'css/animate.css',
            'css/style.css',
            'css/global.css',
            'css/jquery.alerts.css',
            'css/plugins/chosen/chosen.css',
            'css/plugins/cropper/cropper.min.css',
        ],
        'js' => [
            'js/bootstrap.min.js',
            'js/jquery.metisMenu.js',
            'js/jquery.slimscroll.min.js',
            'js/jquery.alerts.js',
            'js/jquery.poshytip.min.js',
            'js/plugins/chosen/chosen.jquery.js',
            'js/plugins/cropper/cropper.min.js',
            'js/pace.min.js',
            'js/inspinia.js',
            'js/global.js',
            'js/common.js',
            'js/app.js',
            'js/modal.events.js',
            'js/jquery.table2excel.js',
        ],
        'jsOptions' => [
            'depends' => [
                'yii\web\YiiAsset',
                'yii\bootstrap\BootstrapAsset'
            ],
            'position' => 3,
        ],
        'cssOptions' => [
            'depends' => [
                'yii\web\YiiAsset',
                'yii\bootstrap\BootstrapAsset'
            ],
        ],
        'depends' => [
            'yii\jui\JuiAsset',
            'yii\web\YiiAsset',
            'yii\bootstrap\BootstrapAsset',
        ]
    ],
    'cms\components\assets\LoginAsset' => [
        'css' => [
            'font-awesome/css/font-awesome.css',
            'css/open-sans.css',
            'css/animate.css',
            'css/style.css',
        ],
        'js' => [
            'js/bootstrap.min.js',
        ],
        'jsOptions' => [
            'depends' => [
                'yii\web\YiiAsset',
                'yii\bootstrap\BootstrapAsset'
            ],
            'position' => 3,
        ],
        'cssOptions' => [
            'depends' => [
                'yii\web\YiiAsset',
                'yii\bootstrap\BootstrapAsset'
            ],
        ],
        'depends' => [
            'yii\web\YiiAsset',
            'yii\bootstrap\BootstrapAsset',
        ]
    ],
    'cms\components\assets\CustomerAsset' => [
        'css' => [
            'css/reset.css',
            'css/customer.css',
        ],
        'js' => [
            'js/bootstrap.min.js',
        ],
        'jsOptions' => [
            'depends' => [
                'yii\web\YiiAsset',
                'yii\bootstrap\BootstrapAsset'
            ],
            'position' => 3,
        ],
        'cssOptions' => [
            'depends' => [
                'yii\web\YiiAsset',
                'yii\bootstrap\BootstrapAsset'
            ],
        ],
        'depends' => [
            'yii\web\YiiAsset',
            'yii\bootstrap\BootstrapAsset',
        ]
    ],
    'cms\components\assets\DashboardAsset' => [
        'css' => [
            'font-awesome/css/font-awesome.css',
            'css/open-sans.css',
            'css/animate.css',
            'css/style.css',
            'css/global.css',
            'css/jquery.alerts.css',
        ],
        'js' => [
            'js/bootstrap.min.js',
            'js/jquery.metisMenu.js',
            'js/jquery.slimscroll.min.js',
            'js/inspinia.js',
            'js/pace.min.js',
            'js/flot/jquery.flot.js',
            'js/flot/jquery.flot.tooltip.min.js',
            'js/flot/jquery.flot.spline.js',
            'js/flot/jquery.flot.resize.js',
            'js/flot/jquery.flot.pie.js',
            'js/flot/jquery.flot.symbol.js',
            'js/jquery.easypiechart.js',
            'js/chart.js',
            'js/global.js',
            'js/jquery.alerts.js',
            'js/jquery.poshytip.min.js',
            'js/plugins/chosen/chosen.jquery.js',
            'js/plugins/cropper/cropper.min.js',
            'js/common.js',
            'js/app.js',
        ],
        'jsOptions' => [
            'depends' => [
                'yii\web\YiiAsset',
                'yii\bootstrap\BootstrapAsset'
            ],
            'position' => 3,
        ],
        'cssOptions' => [
            'depends' => [
                'yii\web\YiiAsset',
                'yii\bootstrap\BootstrapAsset'
            ],
        ],
        'depends' => [
            'yii\web\YiiAsset',
            'yii\bootstrap\BootstrapAsset',
        ]
    ],
];