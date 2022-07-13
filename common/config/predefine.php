<?php

/**
 *  define const default
 */
defined('NORMAL_PAGE') or define('NORMAL_PAGE', 1);
defined('NORMAL_LIMIT') or define('NORMAL_LIMIT', 5);
defined('NORMAL_FILM_LIMIT') or define('NORMAL_FILM_LIMIT',16);

defined('WATCH') or define('WATCH', 'streaming');
defined('DOWNLOAD') or define('DOWNLOAD', 'download');
defined('SUBSCRIBE') or define('SUBSCRIBE', 'subscribe');
defined('REGISTER') or define('REGISTER', 'REGISTER');
defined('CANCEL') or define('CANCEL', 'unsubscribe');
defined('UNSUBSCRIBE') or define('UNSUBSCRIBE', 'unsubscribe');
defined('MONFEE') or define('MONFEE', 'extend');
define('EXTEND','extend');
defined('ARREARS') or define('ARREARS', 'retry_extend');
defined('SEND') or define('SEND', 'send');
defined('RESEND') or define('RESEND', 'resend');

defined('CHANNEL_SMS') or define('CHANNEL_SMS', 'SMS');
defined('CHANNEL_WAP') or define('CHANNEL_WAP', 'WAP');
defined('CHANNEL_CRON') or define('CHANNEL_CRON', 'CRON');
defined('CHANNEL_APP') or define('CHANNEL_APP', 'APP');
defined('CHANNEL_VAS') or define('CHANNEL_VAS', 'VAS');  
defined('CHANNEL_CMS') or define('CHANNEL_CMS', 'CSKH');
defined('CHANNEL_SYSTEM') or define('CHANNEL_SYSTEM', 'SYSTEM');

defined('MO') or define('MO', 'mo');
defined('MT') or define('MT', 'mt');

defined('CONTENT_LIMIT_VIEW_APP') or define('CONTENT_LIMIT_VIEW_APP',5);
defined('CONTENT_LIMIT_VIEW_WAP') or define('CONTENT_LIMIT_VIEW_WAP',5);

// Max offset API
defined('OFFSET_MAX_API') or define('OFFSET_MAX_API',60);
// Lấy mật khẩu max 5 lần
defined('RESTORE_PASSWORD') or define('RESTORE_PASSWORD',5);
// Định nghĩa id mã promotion_event
defined('PROMOTION_EVENT_ID') or define('PROMOTION_EVENT_ID',1);
// Định nghĩa action promotion là đăng kí gói
defined('ACTION_REGISTER') or define('ACTION_REGISTER',1);
// Định nghĩa action promotion là gia hạn
defined('ACTION_RENEW') or define('ACTION_RENEW',2);
// Định nghĩa action promotion là xem clip
defined('ACTION_CLIP') or define('ACTION_CLIP',3);
// Định nghĩa action promotion là xem clip may mắn
defined('ACTION_CLIP_LUCKY') or define('ACTION_CLIP_LUCKY',4);
// Định nghĩa action promotion là xem clip lẻ tính phí
defined('ACTION_CLIP_BUYCONTENT') or define('ACTION_CLIP_BUYCONTENT',5);
// Định nghĩa type clip is lucky
defined('LUCKY_TYPE') or define('LUCKY_TYPE',1);
// Định nghĩa point đăng kí package VCLIP
defined('POINT_VCLIP') or define('POINT_VCLIP',5000);
// Định nghĩa point đăng kí package daily
defined('POINT_V1') or define('POINT_V1',2000);
// Định nghĩa point đăng kí package tuần
defined('POINT_V7') or define('POINT_V7',10000);
// Định nghĩa point đăng kí package tháng
defined('POINT_V30') or define('POINT_V30',30000);
// Định nghĩa point xem clip thường
defined('POINT_CLIP') or define('POINT_CLIP',50);
// Định nghĩa point xem clip ,may mắn
defined('POINT_CLIP_LUCKY') or define('POINT_CLIP_LUCKY',500);
// Định nghĩa point xem clip mua lẻ
defined('POINT_CLIP_BUYCONTENT') or define('POINT_CLIP_BUYCONTENT',1000);

defined('PACKAGE_ID_VCLIP') or define('PACKAGE_ID_VCLIP',1);
defined('PACKAGE_ID_V1') or define('PACKAGE_ID_V1',7);
defined('PACKAGE_ID_V7') or define('PACKAGE_ID_V7',13);
defined('PACKAGE_ID_V30') or define('PACKAGE_ID_V30',15);
//định nghĩa limit winner week
defined('WINNERS_WEEK') or define('WINNERS_WEEK',11);
//định nghĩa flg giải Chung cuộc
defined('FLG_WINNER_FINAL') or define('FLG_WEEK_FINAL',9);
defined('WINNERS_FINAL') or define('WINNERS_FINAL',3);

defined('DEBUG') or define('DEBUG',false);

defined('SMS_MESSAGE_SYNTAX_Y') or define('SMS_MESSAGE_SYNTAX_Y','Tuyet voi! Quy khach duoc tham gia CTKM "Xem la thich-Click nhan tien ngay" cong 5.000d vao TK chinh khi duy tri dich vu trong 48h. Chi tiet truy cap http://vclip.vn, LH 9191(200d/phut). Tran trong!');

defined('TOPUP_DATE_START') or define('TOPUP_DATE_START','2017-07-16 08:00:00');
defined('TOPUP_DATE_END') or define('TOPUP_DATE_END','2017-09-15 23:59:59');

defined('SESSION_TIMEOUT') or define('SESSION_TIMEOUT',604800);//one week

defined('STATUS_ACTIVE') or define('STATUS_ACTIVE', 1);
defined('STATUS_INACTIVE') or define('STATUS_INACTIVE', 0);

defined('USING_WIFI') or define('USING_WIFI', 'wifi');
defined('USING_3G') or define('USING_3G', '3g');

defined('LOGGED_IN_FREE_VIEW') or define('LOGGED_IN_FREE_VIEW', 5);
defined('UN_LOGGED_IN_FREE_VIEW') or define('UN_LOGGED_IN_FREE_VIEW', 10);

defined('BASE_WEB_URL') or define('BASE_WEB_URL', 'http://mclip.vn/');