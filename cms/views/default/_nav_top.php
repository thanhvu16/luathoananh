<div class="row border-bottom">
    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-outline-primary " href="javascript:void(0)"><i class="fa fa-bars"></i> </a>
            <form role="search" class="navbar-form-custom" method="post" action="#">
                <div class="form-group">
                    <input type="text" placeholder="Search..." class="form-control" name="top-search" id="top-search">
                </div>
            </form>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li><span class="m-r-sm text-muted welcome-message">Welcome to Video 2.0</span></li>
            <li><a href="<?php echo \yii\helpers\Url::toRoute(['default/logout']); ?>"><i class="fa fa-sign-out"></i><?php echo Yii::t('cms', 'logout'); ?></a></li>
        </ul>
    </nav>
</div>