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

<!-- Modal -->
<div class="modal fade" id="modalEmbed" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 id="header-info-embed" class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
                <iframe style="width: 100%; border: none; min-height: 550px; height: 500px; max-height: 550px;" onload="$('#iframe_loading').hide();$('#frame-embed').show();resetHeighModalEmbed();" src="" id="frame-embed"></iframe>
                <div id="iframe_loading" style="width: 100%; z-index: 10; text-align: center; padding: 30px;">
                    <img src="/themes/default/images/loadding.gif" />
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addBlockMagazine"  role="dialog" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body" id="content-addBlockMagazine">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</body>
<style>
    .modal-backdrop{
        z-index:-1;
    }
</style>
</html>
<?php $this->endPage() ?>

