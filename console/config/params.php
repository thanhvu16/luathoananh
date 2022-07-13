<?php
return [
    /*-------------------------------Video---------------------------*/
    'service' => 'MclipServiceCMC',
    'update_status_url' => 'http://cmc.vega.com.vn/api/setVideoStatus?sid=%s&video_id=%s&status=%s',
    'data_url' => 'http://cmc.vega.com.vn/api/getVideo?sid=%s&limit=%s',
    'on_progress_url' => 'http://clip.vn/api/setMobileVideoStatus?site_id=%s&video_id=%s&status=2',
    //'save_dir' => '/var/vlive.vn/media/media1/',
    'save_dir' => '/mclip_storage/media/media4/',
    //'image_save_dir' => '/var/vlive.vn/data/img.video/',
    'image_save_dir' => '/mclip_storage/data/img.video/media4/',
    //'image_save_dir' => '/var/tmp/img.video/',
    'site_id' => 2,
//    'php_bin' => '/usr/local/php5/bin/php',
    'php_bin' => '/usr/bin/php',
    'timeout' => 60 * 100,
    'max_download' => 20,
    'data_limit' => 5,

    /*------------------------------Film------------------------------------*/
    'update_status_url_film' => 'http://cmc.vega.com.vn/api/setFilmStatus?sid=%s&film_id=%s&status=%s',
    'data_url_film' => 'http://cmc.vega.com.vn/api/getFilm?sid=%s&limit=%s',
    'save_dir_film' => '/mclip_storage/data/img.poster',
    'image_save_dir_film' => '/mclip_storage/data/img.poster',

    /*-----------------------------PUSH MESSENGE ----------------------------*/
    // Google


    /*'push_notify_google_api_key' => 'AIzaSyBIkf4RvIknjUUbPE4aBeJcVwtT7C8BH54', //longnh1
//    'push_notify_google_gcm_url' => 'http://android.googleapis.com/gcm/send',
    'push_notify_google_gcm_url' => 'https://gcm-http.googleapis.com/gcm/send',*/


    'push_notify_google_api_key' => 'AIzaSyA2yOPyS4EtQb1vhGXQv6B9m08tU7rggqQ',
//    'push_notify_google_gcm_url' => 'http://android.googleapis.com/gcm/send',
    'push_notify_google_gcm_url' => 'https://gcm-http.googleapis.com/gcm/send',


    // WINDOWSPHONE
    'push_notify_windowsphone_secret_key' => 'FI18MTDu47CIsbO6DqZEeyuwMNV6/P9r',
    'push_notify_windowsphone_sid' => 'ms-app://s-1-15-2-516036592-376019398-2152424961-705447869-4212971813-3503993488-2422192389',

    // IOS
    //'push_notify_ssl_dev'=>'ssl://gateway.sandbox.push.apple.com:2195',//Dev
    'push_notify_ssl'=>'ssl://gateway.push.apple.com:2195',//Product
    'push_notify_sslfb'=>'ssl://feedback.push.apple.com:2196',
    'push_notify_pass_phrase'=> '123312',
    //'push_notify_pen_path_dev'=>'/var/www/vegaprojects/app/vclip2.0/console/Vclip_dev.pem',//Dev
    'push_notify_pen_path'=>'/var/www/mclip2.0/console/CertificatesProductMclip.pem',//Product
//    'push_notify_pen_path'=>'/var/www/mclip2.0/console/CertificatesProductMclip.p12',//Product

    'img_url' => [
        'data_path' => '/mclip_storage/data/img.video/',
        'data_url' => 'http://static.mclip.vn',
        'notify_img_options' => [
            'source' => 'img.notify',
            'width' => '80',
            'height' => '80'
        ],
        'imageSize' => [
            's6' => 50, 's5' => 75, 's4' => 100, 's3' => 150, 's2' => 320, 's1' => 640, 's0' => '1024'
        ],
    ],
    'sms' => array(
        'remote_wsdl' => 'http://10.60.105.203:8110/smsws?wsdl',
        'remote_username' => 'mclip',
        'remote_password' => 'mclip@@9120@#',
        'server' => 'http://10.60.105.203:8110/smsws?wsdl',
        'namespace' => 'http://gateway.sms.vega/',
        'username' => 'mclip',
        'password' => 'mclip@@9120@#',
        'service_number' => '9120',
        'service_name' => 'mclip',
        'keywords'	    => array('ON', 'OFF', 'TC'),
    ),
    'sms_job_type' => [
        '0' => 'Đã đăng ký, có truy cập',
        '1' => 'Đã đăng ký, không truy cập',
        '2' => 'Gia hạn thành công, có truy cập',
        '3' => 'Gia hạn thành công, không truy cập'
    ],
    'hash_key' => '3GrtYh19a'
];