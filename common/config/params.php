<?php
return [
    'languages' => [1 => ['id' => 1, 'name' => 'Tiếng Việt'], 2 => ['id' => 2, 'name' => 'Tiếng Anh']],
    'og_url' => 'http://clip.cdn.vn/',
    'cache_refresh' => 3600,
    'secrect_key' => '21ja9ih8v',
    'pagination' => [
        'normal_wap_limit' => '12',
        'normal_wap_film_limit' => '16',
    ],
    'img_url' => [
        'data_path' => '/srv/www/luathoanganh.vn/wap/www/media/',
        'data_url' => 'https://luathoanganh.vn/media/',
        'news_img_options_small' => [
            'source' => 'img.news.small',
            'width' => '132',
            'height' => '85'
        ],
        'news_img_options_medium' => [
            'source' => 'img.news.medium',
            'width' => '245',
            'height' => '163'
        ],
        'news_img_options_large' => [
            'source' => 'img.news.large',
            'width' => '700',
            'height' => '460'
        ],
        'news_img_options_base' => [
            'source' => 'image',
            'width' => '700',
            'height' => '460'
        ],
        'banners_img_options' => [
            'source' => 'img.banners',
            'width' => '640',
            'height' => '426'
        ],
        'smart_banner_img_options' => [
            'source' => 'img.smart.banner',
            'width' => '100',
            'height' => '100'
        ],
        'avatar_options' => [
            'source' => 'img.avatar',
            'width' => '150',
            'height' => '150'
        ],
        'menu_icon' => [
            'source' => 'icon.menu',
            'width' => '150',
            'height' => '150'
        ],
        'sender_avatar' => [
            'source' => 'img.sender',
            'width' => '150',
            'height' => '150'
        ],
        'imageSize' => [
            's6' => 50, 's5' => 75, 's4' => 100, 's3' => 150, 's2' => 320, 's1' => 640, 's0' => '1024'
        ],
    ],
    'banner_type' => [
        1 => 'Trang chủ',
        2 => 'Trang chi tiết video',
        3 => 'Trang kênh phim',
        4 => 'Trang chi tiết phim',
        5 => 'Trang live',
        6 => 'Trang thông tin',
    ],
];
