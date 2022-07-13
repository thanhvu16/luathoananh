<?php
return [
    [
        'pattern' => 'clear-cache',
        'route'   => 'default/clear-cache',
        'suffix'  => '',
    ],
	
	['pattern'=>'dang-nhap/<action>','route'=>'login/<action>', 'suffix'=>'.html'],
	['pattern'=>'binh-luan/<action>','route'=>'comment-embed/<action>', 'suffix'=>'.html'],
	
    ['pattern'=>'magazine/preview','route'=>'magazine/preview', 'suffix'=>'.html'],
    ['pattern'=>'magazine/<alias:.*?>-<id:[0-9]+>','route'=>'magazine/detail', 'suffix'=>'.html'],
    ['pattern'=>'rss','route'=>'rss/index', 'suffix'=>'.html'],
    ['pattern'=>'create-rss','route'=>'rss/create-rss', 'suffix'=>'.html'],
    ['pattern'=>'rss/<alias:.*?>','route'=>'rss/detail', 'suffix'=>'.rss'],
    [
        'pattern' => 'export/<action>',
        'route'   => 'export/<action>',
        'suffix'  => '.html',
    ],
    [
        'pattern' => 'sitemap/<action>',
        'route'   => 'sitemap/<action>',
        'suffix'  => '.html',
    ],
	[
        'pattern' => 'lien-he',
        'route'   => 'default/contact',
        'suffix'  => '.html',
    ],
    [
        'pattern' => 'doi-ngu/<alias:.*?>-dn<id:[0-9]+>',
        'route'   => 'default/doi-ngu-view',
        'suffix'  => '.html'
    ],
	[
        'pattern' => 'doi-ngu-luat-su',
        'route'   => 'default/doi-ngu',
        'suffix'  => '.html',
    ],
    [
        'pattern' => 'tag/<alias:.*?>',
        'route'   => 'tags/index',
        'suffix'  => '.html',
    ],
    [
        'pattern' => 'tag/<alias:.*?>/trang-<page:[0-9]+>',
        'route'   => 'tags/index',
        'suffix'  => '.html',
    ],
	[
        'pattern' => '<alias:.*?>/<slug:.*?>-lha<id:[0-9]+>-amp',
        'route' => 'news/amp',
        'suffix' => '.html',
        'encodeParams' => true
    ],
    [
        'pattern' => '<alias:.*?>/<slug:.*?>-lha<id:[0-9]+>',
        'route' => 'news/index',
        'suffix' => '.html',
        'encodeParams' => true
    ],
    [
        'pattern' => 've-chung-toi',
        'route'   => 'news/index',
        'suffix'  => '.html',
        'defaults' => ['id' => 41]
    ],
    [
        'pattern' => '<slug:[a-zA-Z0-9\-]+>/<alias:[a-zA-Z0-9\-]+>/trang-<page:[0-9]+>',
        'route'   => 'news-category/list',
        'suffix'  => '.html',
    ],
    [
        'pattern' => '<alias:[a-zA-Z0-9\-]+>',
        'route'   => 'news-category/list-parent',
        'suffix'  => '.html',
    ],
    [
        'pattern' => '<slug:[a-zA-Z0-9\-]+>/<alias:[a-zA-Z0-9\-]+>',
        'route'   => 'news-category/list',
        'suffix'  => '.html',
    ],

];