<?php
use common\components\ApplicationAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\Menu;
use yii\bootstrap\Alert;

$bundle = ApplicationAsset::register($this);
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
            var YII_CSRF_TOKEN = "<?php print Yii::$app->request->csrfToken; ?>";
        </script>

        <?php $bundle->registerAssetFiles($this); ?>

        <title>CMS Everest</title>
        <?php $this->head() ?>
    </head>
    <body><!--class="mini-navbar"-->
    <?php $this->beginBody() ?>
    <div class="wait">Đang tải dữ liệu</div>
    <div id="wrapper">
        <?php echo $this->render('//default/_menu_nav'); ?>
        <div id="page-wrapper" class="gray-bg">
            <?php echo $this->render('//default/_nav_top'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2><?php echo isset($this->params['title']) ? $this->params['title'] : '' ?></h2>
                    <?= Breadcrumbs::widget([
                        'itemTemplate' => "<li>{link}</li>\n",
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                </div>
                <div class="col-lg-2"></div>
            </div>
            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <?php
                                    if (isset($this->params['menu_config'])) {
                                        echo $this->params['menu_config'];
                                    } else {
                                        echo Menu::widget([
                                            'items' => isset($this->params['menu']) ? $this->params['menu'] : []
                                        ]);
                                    }
                                ?>
                            </div>
                            <div class="ibox-content">
                                <?php
                                    if ($flash = Yii::$app->session->getFlash('unsuccess')) {
                                        echo Alert::widget(['options' => ['class' => 'alert-danger'], 'body' => $flash]);
                                    } else if ($flash = Yii::$app->session->getFlash('error')) {
                                        echo Alert::widget(['options' => ['class' => 'alert-danger'], 'body' => $flash]);
                                    } else if ($flash = Yii::$app->session->getFlash('success')) {
                                        echo Alert::widget(['options' => ['class' => 'alert-success'], 'body' => $flash]);
                                    }
                                    echo $content;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer">
                <div>
                    &copy; 2019
                </div>
            </div>
        </div>
    <?php $this->endBody() ?>
    <?php echo $this->render('//default/_theme_config'); ?>
    <div class="overlay-loading">
        <div class="la-ball-clip-rotate">
            <div></div>
        </div>
    </div>
    </body>
    </html>
<?php $this->endPage() ?>