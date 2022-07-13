<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

  <!-- Logo -->
  <a href="<?= Url::base(true) ?>" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>E</b>VG</span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>Admin</b>EVG</span>
  </a>

  <!-- Header Navbar -->
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?=$directoryAsset?>/img/user2-160x160.jpg" class="user-image" alt="User Image">
            <span class="hidden-xs"><?= Yii::$app->user->identity->fullname ?></span>
          </a>
          <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header">
              <img src="<?=$directoryAsset?>/img/user2-160x160.jpg" class="img-circle" alt="User Image">

              <p>
                <?= Yii::$app->user->identity->fullname ?>
                <small>Member since <?= date('d-M-Y', strtotime(Yii::$app->user->identity->created_time)) ?></small>
              </p>
            </li>
<!--             Menu Body 
            <li class="user-body">
              <div class="row">
                <div class="col-xs-4 text-center">
                  <a href="#">Followers</a>
                </div>
                <div class="col-xs-4 text-center">
                  <a href="#">Sales</a>
                </div>
                <div class="col-xs-4 text-center">
                  <a href="#">Friends</a>
                </div>
              </div>
               /.row 
            </li>-->
            <!-- Menu Footer-->
            <li class="user-footer">
              <div class="pull-left">
                <a href="<?= Url::toRoute(['profile/change-password', 'id' => Yii::$app->user->getId()]) ?>" class="btn btn-outline-primary btn-flat"><i class="fa fa-user" aria-hidden="true"></i> Change Password</a>
              </div>
              <div class="pull-right">
                <a href="<?php echo \yii\helpers\Url::toRoute(['default/logout']); ?>" class="btn btn-outline-primary btn-flat"><i class="fa fa-sign-out" aria-hidden="true"></i> Sign out</a>
              </div>
            </li>
          </ul>
        </li>
        <!-- Control Sidebar Toggle Button -->
        <li>
          <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
        </li>
      </ul>
    </div>

  </nav>
</header>