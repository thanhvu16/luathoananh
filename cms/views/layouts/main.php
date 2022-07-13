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
$adminlteAsset = cms\components\assets\AdminlteAsset::register($this);
cms\components\assets\PaceProgressAsset::register($this);

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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php $this->beginBody() ?>
<div class="wrapper">

  <?= $this->render('main/header.php', [
      'directoryAsset' => $distPath
      ]) ?>

  <?= $this->render('main/_menu_nav', [
      'directoryAsset' => $distPath
  ]) ?>

  <?= $this->render('main/content.php', [
      'content' => $content, 'directoryAsset' => $distPath
      ]) ?>

</div>
<div id="page-wrapper-1"></div>
<?php $this->endBody() ?>
</body>
<script type="text/javascript">
  // To make Pace works on Ajax calls
  $(document).ajaxStart(function () {
    Pace.restart()
  })
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
</html>
<?php $this->endPage() ?>

