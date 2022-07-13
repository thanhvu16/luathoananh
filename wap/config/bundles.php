<?php

return [
    'common\components\ApplicationAsset' => [
        'css' => [
//            'css/font-awesome.min.css',
            'css/flags.css',
            'css/style.css'
        ],
        'js' => [
            'js/interstitial_timer.js',
            'js/jquery.min.js',
            'js/jquery.lazy.min.js',
            'js/application.js',
        ],
        'cssOptions' => [
            'depends' => [
            ],
        ],
        'jsOptions' => [
            'depends' => [
            ],
        ],
    ],
    'yii\web\JqueryAsset' => [
        'js'=>[]
    ],
];
