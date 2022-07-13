<?php
use common\components\ApplicationAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\Menu;
use yii\bootstrap\Alert;
/* @var $this \yii\web\View */
/* @var $content string */

/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code `$this->layout = 'login';` in your controller.
 * (`yii\web\ErrorAction` also support changing layout by setting `layout` property)
 */
$action = Yii::$app->controller->action->id;
if (in_array($action, ['login', 'error'])) { 

    echo $this->render('login', ['content' => $content]);
    return;
}

/**
 * You could set your AppAsset depended with AdminlteAsset 
 */
// \backend\assets\AppAsset::register($this);
// \app\assets\AppAsset::register($this);
$adminlteAsset = cms\components\assets\AdminlteAssetMagazine::register($this);
$distPath = $adminlteAsset->baseUrl;
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
  <meta charset="<?= Yii::$app->charset ?>"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?= Html::csrfMetaTags() ?>
  <script type="text/javascript">
    var CMS_HOST_PATH = "<?php print Url::base() . '/' ?>";
    var YII_CSRF_TOKEN = "<?php print Yii::$app->request->csrfToken; ?>";
  </script>
  <title><?= Html::encode($this->title) ?></title>
  <?php $this->head() ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php $this->beginBody() ?>
<?php if (Yii::$app->session->hasFlash('error')) { ?>
    <div class="alert alert-danger">
        <button class="close" data-close="alert"></button>
        <span><?php echo Yii::$app->session->getFlash('error') ?></span>
    </div>
<?php } elseif (Yii::$app->session->hasFlash('success')) { ?>
    <div class="alert alert-success">
        <button class="close" data-close="alert"></button>
        <span><?php echo Yii::$app->session->getFlash('success') ?></span>
    </div>
<?php } ?>
<?php echo $content ?>
<?php $this->endBody() ?>
<style>
    html{
        overflow-y: auto;
    }
    .box-body{
        padding: 0 !important;
    }
</style>
</body>

</html>
<?php $this->endPage() ?>

