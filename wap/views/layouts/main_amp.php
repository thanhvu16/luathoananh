<!DOCTYPE html>
<?php
use wap\components\WapAsset;
use yii\helpers\Html;
use yii\helpers\Url;

//$bundle = WapAsset::register($this);
?>
<?php $this->beginPage() ?>
    <html amp lang="<?= Yii::$app->language ?>">
    <head>
        <link rel="icon" href="/favicon-16x16.ico?v=1">
        <meta charset="UTF-8" />
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <title><?php echo Html::encode(Yii::$app->controller->pageTitle) ?></title>
        <meta name="description" content="<?php echo Html::encode(Yii::$app->controller->pageDescription) ?>" />
        <meta name="keywords" content="<?php echo Html::encode(Yii::$app->controller->pageKeywords) ?>" />
        <meta name="news_keywords" content="<?php echo Html::encode(Yii::$app->controller->pageKeywords) ?>" />

        <?php if(!empty(Yii::$app->controller->canonical)){ ?>
            <link rel="canonical" href="<?php echo Yii::$app->controller->canonical; ?>"/>
        <?php } else { ?>
            <link rel="canonical" href="<?php echo Yii::$app->request->getAbsoluteUrl(); ?>"/>
        <?php } ?>

        <meta name="robots" content="index, follow" />
        <meta name="Googlebot-News" content="index, follow" />
        <meta http-equiv="content-language" content="vi" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name='dmca-site-verification' content='TnRDM1QxSC9HK1RwdkZuelJpb2I1UT090' />

        <!--Facebook-->
        <meta property="og:locale" content="vi_VN" />
        <meta property="og:site_name" content="Luathoanganh.vn" />
        <meta property="og:title" content="<?php echo Html::encode(Yii::$app->controller->pageTitle) ?>" />
        <meta property="og:description" content="<?php echo Html::encode(Yii::$app->controller->pageDescription) ?>" />
        <meta property="og:url" content="<?php echo Yii::$app->request->getAbsoluteUrl(); ?>" />
        <meta property="og:image" content="<?php echo Html::encode(Yii::$app->controller->pageOgImage) ?>" />
        <meta property="og:type" content="article" />

        <?= Html::csrfMetaTags() ?>

		<script type="application/ld+json">
		[
		{
			"@context": "http://schema.org",
			"@type" : "Organization",
			"url" : "https://luathoanganh.vn/",
			"logo"	: "https://luathoanganh.vn/themes/default/ctyluat/img/logo.png",
			"contactPoint": 
				[
			{
				"@type": "ContactPoint",
				"telephone": "(+84) 908308123",
				"contactType": "customer service",
				"areaServed": "VN",
				"availableLanguage": "Vietnamese"
			}
				]
		},
		{
			"@context": "http://schema.org",
			"@type": "Person",
			"name": "Luật Hoàng Anh",
			"sameAs": 
			[
				"https://wakelet.com/@luathoanganh",
				"https://www.provenexpert.com/luathoanganh/",
				"https://www.question2answer.org/qa/user/luathoanganh",
				"https://www.bahamaslocal.com/userimages/92433/10/luathoanganh.html",
				"https://www.brownbook.net/account/profile/4610437",
				"https://www.myminifactory.com/users/luathoanganh",
				"https://vhearts.net/luathoanganh",
				"https://twitter.com/luathoanganh",
				"https://www.youtube.com/channel/UCDXBlGudDwfWGmhw1R7HP6Q/about",
				"https://soundcloud.com/luathoanganh",
				"https://www.behance.net/luathoanganh",
				"https://about.me/luthoanganh",
				"https://luathoanganh.blogspot.com/",
				"https://vi.gravatar.com/luathoanganh",
				"https://www.blogger.com/profile/06835800263423686151",
				"https://www.deviantart.com/luathoanganh",
				"https://vimeo.com/luathoanganh",
				"https://www.twitch.tv/luathoanganh/about",
				"https://luathoanganh.tumblr.com/about",
				"https://getpocket.com/@luathoanganh",
				"https://www.spreaker.com/user/14840817",
				"https://www.mixcloud.com/luathoanganh/",
				"https://www.metooo.io/u/luathoanganh",
				"https://www.wishlistr.com/luathoanganh/",
				"https://www.sqlservercentral.com/forums/user/luathoanganh",
				"https://worldcosplay.net/member/989225",
				"https://tune.pk/user/luathoanganh/about",
				"https://qiita.com/luathoanganh",
				"https://www.tickaroo.com/user/60e7abd32d6402f69a0040cf",
				"https://fliphtml5.com/homepage/kjdmt"

			]
		},
		{
			"@context": "https://schema.org",
			"@type": "LocalBusiness",
			"name": "Công ty luật Hoàng Anh - Tư vấn pháp luật, dịch vụ luật sư",
			"image": "https://luathoanganh.vn/themes/default/ctyluat/img/logo.png",
			"@id": "https://luathoanganh.vn/",
			"url": "https://luathoanganh.vn/",
			"description":"Công ty luật Hoàng Anh - Văn phòng luật sư uy tín tại Hà Nội. Công ty tư vấn pháp luật, dịch vụ luật sư hàng đầu Việt Nam.",
			"telephone": "(+84) 908308123",
			"priceRange": "$$",
			"address": {
			"@type": "PostalAddress",
			"streetAddress": "Số 2/84 - Trần Quang Diệu - Phường Ô Chợ Dừa - Quận Đống Đa - TP Hà Nội",
			"addressLocality": "Hà Nội",
			"postalCode": "115490",
			"mainEntityOfPage":"https://goo.gl/maps/HGmjAZ3mmCyL1xCs7",
			"addressCountry": "VN"},
			"geo": {
			"@type": "GeoCoordinates",
			"latitude": 21.014541,
			"longitude": 105.8214142
			},
			"openingHoursSpecification": 
			{
				"@type": "OpeningHoursSpecification",
				"dayOfWeek": [
				"Monday",
				"Tuesday",
				"Wednesday",
				"Thursday",
				"Friday",
				"Saturday"
				],
				"opens": "08:00",
				"closes": "17:30"
			}
		}
	]
	</script>

        <?php if(!empty(Yii::$app->controller->schema)) echo Yii::$app->controller->schema; ?>
		
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" />
        <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">

        <script async src="https://cdn.ampproject.org/v0.js"></script>
		
        <!--<script async custom-element="amp-social-share" src="https://cdn.ampproject.org/v0/amp-social-share-0.1.js"></script>
        <script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>
        <script async custom-element="amp-ad" src="https://cdn.ampproject.org/v0/amp-ad-0.1.js"></script>
        <script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></scrip> -->
		
		<script async custom-element="amp-bind" src="https://cdn.ampproject.org/v0/amp-bind-0.1.js"></script>
		<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
		<script async custom-element="amp-font" src="https://cdn.ampproject.org/v0/amp-font-0.1.js"></script>

        <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
        <style amp-custom><?php 
			$styleAll = '';
			$style = file_get_contents('themes/default/ctyluat/amp/style-amp.min.css');
            $style = str_replace('img/', '/themes/default/ctyluat/img/', $style);
            $style = str_replace('../fonts/', '/themes/default/font-awesome-4.7.0/fonts/', $style);
            $style = str_replace('!important', '', $style);
			$styleAll .= $style;
			
            echo $styleAll;
        ?></style>
		
        <?php //$bundle->registerAssetFiles($this); ?>
        <?php //$this->head() ?>

    </head>
    <body >
			<!-- Global site tag (gtag.js) - Google Analytics -->
		<amp-analytics type="gtag" data-credentials="include">
        <script type="application/json">
            {
                "vars": {
                    "gtag_id": "G-W879QKG3M0",
                    "linker": {
                        "domains": ["luathoanganh.vn"]
                    }
                ,
                    "config": {
                        "G-W879QKG3M0": {
                            "groups": "default"
                        }
                    }
                }
            }
        </script>
        </amp-analytics>
    <?php $this->beginBody() ?>
        <div id="wapper-all-site">
            <?php echo $this->render('//layouts/amp/header') ?>
            <?php echo $content; ?>
            <?php echo $this->render('//layouts/amp/footer') ?>
        </div>
    <a href="#" class="btn-back-top">
        <i class="fa fa-arrow-up text-white" aria-hidden="true"></i>
    </a>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>
