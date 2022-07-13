<?php
use cms\components\assets\DashboardAsset;
use yii\helpers\Html;
use yii\helpers\Url;

$bundle = DashboardAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="pragma" content="no-cache">
        <?= Html::csrfMetaTags() ?>

        <script type="text/javascript">
            var CMS_HOST_PATH = "<?php print Url::base() . '/' ?>";
        </script>

        <?php $bundle->registerAssetFiles($this); ?>

        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body> <!--class="mini-navbar"-->
    <?php $this->beginBody() ?>
    <div id="wrapper">
        <?php echo $this->render('//default/_menu_nav'); ?>
        <div id="page-wrapper" class="gray-bg">
            <?php echo $this->render('//default/_nav_top'); ?>
            <div class="wrapper wrapper-content">
                <?php echo $content; ?>
            </div>
            <div class="footer">
                <div>
                    &copy; 2019
                </div>
            </div>
        </div>
        <?php $this->endBody() ?>
        <?php echo $this->render('//default/_theme_config'); ?>
    </body>
    </html>
<?php $this->endPage() ?>