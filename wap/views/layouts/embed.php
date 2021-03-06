<!DOCTYPE html>
<?php

use wap\components\EmbedAsset;
use wap\components\WapAsset;
use yii\helpers\Html;
use yii\helpers\Url;

//$bundle = WapAsset::register($this);
$bundle = EmbedAsset::register($this);
?>
<?php $this->beginPage() ?>
<html lang="<?= Yii::$app->language ?>">

<head>
	<html xmlns="http://www.w3.org/1999/xhtml" lang="vi-VN">

	<head itemscope="" itemtype="http://schema.org/WebPage">
		<link rel="icon" href="/favicon-16x16.ico?v=1">
		<meta charset="UTF-8" />
		<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
		<title><?php echo Html::encode(Yii::$app->controller->pageTitle) ?></title>
		<meta charset="<?= Yii::$app->charset ?>" />
		<meta name="description" content="<?php echo Html::encode(Yii::$app->controller->pageDescription) ?>" />
		<meta name="keywords" content="<?php echo Html::encode(Yii::$app->controller->pageKeywords) ?>" />
		<meta name="news_keywords" content="<?php echo Html::encode(Yii::$app->controller->pageKeywords) ?>" />
		<?php if (!empty(Yii::$app->controller->canonical)) { ?>
			<link rel="canonical" href="<?php echo Yii::$app->controller->canonical; ?>" />
		<?php } else { ?>
			<link rel="canonical" href="<?php echo Yii::$app->request->getAbsoluteUrl(); ?>" />
		<?php } ?>

		<?php if (!empty(Yii::$app->controller->canonicalAmp)) { ?>
			<link rel="amphtml" href="<?php echo Yii::$app->controller->canonicalAmp; ?>">
		<?php } ?>
		<meta name="robots" content="index, follow" />
		<meta name="Googlebot-News" content="index, follow" />
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
		<meta http-equiv="content-language" content="vi" />
		<meta http-equiv="Cache-control" content="Public" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta http-equiv="pragma" content="no-cache" />
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
		<script type="text/javascript">
			var WAP_HOST_PATH = "<?php print Url::base() . '/' ?>";
			var YII_CSRF_TOKEN = "<?php print Yii::$app->request->csrfToken; ?>";
			var YII_CSRF_TOKEN_NAME = "<?php print Yii::$app->request->csrfParam; ?>";
		</script>
		<script type="application/ld+json">
			[{
					"@context": "http://schema.org",
					"@type": "Organization",
					"url": "https://luathoanganh.vn/",
					"logo": "https://luathoanganh.vn/themes/default/ctyluat/img/logo.png",
					"contactPoint": [{
						"@type": "ContactPoint",
						"telephone": "(+84) 908308123",
						"contactType": "customer service",
						"areaServed": "VN",
						"availableLanguage": "Vietnamese"
					}]
				},
				{
					"@context": "http://schema.org",
					"@type": "Person",
					"name": "Lu???t Ho??ng Anh",
					"sameAs": [
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
					"name": "C??ng ty lu???t Ho??ng Anh - T?? v???n ph??p lu???t, d???ch v??? lu???t s??",
					"image": "https://luathoanganh.vn/themes/default/ctyluat/img/logo.png",
					"@id": "https://luathoanganh.vn/",
					"url": "https://luathoanganh.vn/",
					"description": "C??ng ty lu???t Ho??ng Anh - V??n ph??ng lu???t s?? uy t??n t???i H?? N???i. C??ng ty t?? v???n ph??p lu???t, d???ch v??? lu???t s?? h??ng ?????u Vi???t Nam.",
					"telephone": "(+84) 908308123",
					"priceRange": "$$",
					"address": {
						"@type": "PostalAddress",
						"streetAddress": "S??? 2/84 - Tr???n Quang Di???u - Ph?????ng ?? Ch??? D???a - Qu???n ?????ng ??a - TP H?? N???i",
						"addressLocality": "H?? N???i",
						"postalCode": "115490",
						"mainEntityOfPage": "https://goo.gl/maps/HGmjAZ3mmCyL1xCs7",
						"addressCountry": "VN"
					},
					"geo": {
						"@type": "GeoCoordinates",
						"latitude": 21.014541,
						"longitude": 105.8214142
					},
					"openingHoursSpecification": {
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

		<?php if (!empty(Yii::$app->controller->schema)) echo Yii::$app->controller->schema; ?>

		<link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500&display=swap" rel="stylesheet" />
		<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet" />
		<?php $bundle->registerAssetFiles($this); ?>
		<?php $this->head() ?>
	</head>

<body cz-shortcut-listen="true">
	<?php $this->beginBody() ?>
	<?php echo $content; ?>
	<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>