<?php
use cms\components\assets\CustomerAsset;
use yii\helpers\Html;
use yii\helpers\Url;

$bundle = CustomerAsset::register($this);
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script type="text/javascript">
            var BACKEND_HOST_PATH = "<?php print Url::base() . '/' ?>";
        </script>

        <?php $bundle->registerAssetFiles($this); ?>

        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="gray-bg">
    <?php $this->beginBody() ?>
    <div class="contain_popup">
        <div class="p8">
            <?php echo $this->render('//customer/_navbar'); ?>
            <div class="containdatapopup">
                <?php echo $this->render('//customer/_header'); ?>
                <?php echo $content; ?>
            </div>
        </div>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>