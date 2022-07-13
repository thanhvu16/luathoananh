<!DOCTYPE html>
<?php

use wap\components\MagazineAsset;
use yii\helpers\Html;
use yii\helpers\Url;

$bundle = MagazineAsset::register($this);
?>
<?php $this->beginPage() ?>
<html lang="<?= Yii::$app->language ?>">

<head>
	<html xmlns="http://www.w3.org/1999/xhtml" lang="vi-VN">

	<head itemscope="" itemtype="http://schema.org/WebPage">
		<link rel="icon" href="/favicon.ico">
		<meta charset="UTF-8" />
		<meta content="width=device-width, initial-scale=1.0" name="viewport" />
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

		<?php if (!empty(Yii::$app->controller->nextPage)) { ?>
			<link rel="next" href="<?php echo Yii::$app->controller->nextPage; ?>">
		<?php } ?>

		<?php if (!empty(Yii::$app->controller->prevPage)) { ?>
			<link rel="prev" href="<?php echo Yii::$app->controller->prevPage; ?>">
		<?php } ?>

		<meta name="robots" content="index, follow" />
		<meta name="Googlebot-News" content="index, follow" />
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
		<meta http-equiv="content-language" content="vi" />
		<meta http-equiv="Cache-control" content="Public" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta http-equiv="pragma" content="no-cache" />

		<!--Facebook-->
		<meta property="og:locale" content="vi_VN" />
		<meta property="og:site_name" content="Luật hoàng anh" />
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
		<?php if (!empty(Yii::$app->controller->schema)) echo Yii::$app->controller->schema; ?>

        <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
        <?php $bundle->registerAssetFiles($this); ?>
        <?php $this->head() ?>
	</head>

<body>
	<?php $this->beginBody() ?>
	<?php echo $this->render('//layouts/default/header') ?>
	<?php echo $content; ?>
	<?php echo $this->render('//layouts/default/footer') ?>
	<?php $this->endBody() ?>

</body>

</html>
<?php $this->endPage() ?>