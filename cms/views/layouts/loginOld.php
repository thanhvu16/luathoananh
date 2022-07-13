<?php

use cms\components\assets\LoginAsset;
use yii\helpers\Html;
use yii\helpers\Url;

$bundle = LoginAsset::register($this);
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
    <div class="middle-box text-center loginscreen  animated fadeInDown">
        <?php echo $content; ?>
    </div>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>