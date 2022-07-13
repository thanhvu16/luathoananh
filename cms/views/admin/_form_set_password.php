<?php
use yii\helpers\Html;
use cms\models\Admin;
use yii\helpers\ArrayHelper;

$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('cms', 'mnu_admin_user'),
        'url' => ['admin']
    ],
    [
        'label' => Yii::t('cms', 'mnu_admin_set_password'),
        'template' => "<li>{link}</li>\n"
    ]
];

$this->title = Yii::$app->name.' - '.Yii::t('cms', 'mnu_admin_set_password');
$this->params['title'] = \yii\helpers\Html::encode(Yii::t('cms', 'mnu_admin_set_password'));
?>
<div class="box-header with-border">
    <?= Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i> '.Yii::t('cms', 'app_back'), ['admin'], ['class' => 'btn btn-outline-primary']) ?>
</div>
<div class="form-change-password box-body">
    <form method="POST" action="<?php echo Yii::$app->urlManager->createUrl('admin/set-password'); ?>">
        <div class="form-change-password">
            <label class="control-label"><?php echo Yii::t('cms', 'username'); ?></label>
            <?php echo Html::dropDownList('username', ArrayHelper::getValue($_POST, 'username', null), ArrayHelper::map(Admin::find()->all(), 'id', 'username'), ['prompt'=>Yii::t('cms', 'select_username'), 'class' => 'form-control']); ?>
            <div class="help-block"></div>
        </div>
        <div class="form-change-password">
            <label class="control-label"><?php echo Yii::t('cms', 'new_password'); ?></label>
            <input type="password" name="new_password" value="<?php echo ArrayHelper::getValue($_POST, 'new_password', null); ?>" class="form-control" />
            <div class="help-block"></div>
        </div>
        <div class="form-change-password">
            <label class="control-label"><?php echo Yii::t('cms', 're_new_password'); ?></label>
            <input type="password" name="re_new_password" value="<?php echo ArrayHelper::getValue($_POST, 're_new_password', null); ?>" class="form-control" />
            <div class="help-block"></div>
        </div>
        <div class="form-change-password">
            <?php echo Html::submitButton('<i class="fa fa-check" aria-hidden="true"></i> '.Yii::t('cms', 'app_save'), ['style' => 'margin-top: 18px; margin-bottom: 12px;', 'class' => 'btn btn-outline-primary']); ?>
            <?php echo Html::resetButton('<i class="fa fa-ban" aria-hidden="true"></i> '.Yii::t('cms', 'app_cancel'), ['style' => 'margin-top: 18px; margin-bottom: 12px;', 'class' => 'btn btn-outline-primary']); ?>
        </div>
    </form>
</div>