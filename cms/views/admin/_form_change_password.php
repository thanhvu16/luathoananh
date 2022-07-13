<?php
    use yii\grid\GridView;
    use yii\helpers\Html;

    $this->params['breadcrumbs'] = [
        [
            'label' => Yii::t('cms', 'mnu_admin_user'),
            'url' => ['admin']
        ],
        [
            'label' => Yii::t('cms', 'mnu_admin_change_password'),
            'template' => "<li>{link}</li>\n"
        ]
    ];
    $this->params['menu'] = [
        ['label'=>Yii::t('cms', 'app_back'), 'url' => ['admin'], 'options' => ['class' => 'btn btn-outline-primary']],
    ];
    $this->title = Yii::$app->name.' - '.Yii::t('cms', 'mnu_admin_change_password');
    $this->params['title'] = \yii\helpers\Html::encode(Yii::t('cms', 'mnu_admin_change_password'));
?>
<div class="form-change-password box-body">
    <form method="POST" action="<?php echo Yii::$app->urlManager->createUrl('admin/change-password', ['id' => $id])?>">
        <div class="form-change-password">
            <label class="control-label"><?php echo Yii::t('cms', 'current_password'); ?></label>
            <input type="password" name="current_password" value="" class="form-control" />
            <div class="help-block"></div>
        </div>
        <div class="form-change-password">
            <label class="control-label"><?php echo Yii::t('cms', 'new_password'); ?></label>
            <input type="password" name="new_password" value="" class="form-control" />
            <div class="help-block"></div>
        </div>
        <div class="form-change-password">
            <label class="control-label"><?php echo Yii::t('cms', 're_new_password'); ?></label>
            <input type="password" name="re_new_password" value="" class="form-control" />
            <div class="help-block"></div>
        </div>
        <div class="form-change-password">
            <?php echo Html::submitButton(Yii::t('cms', 'app_save'), ['style' => 'margin-top: 18px; margin-bottom: 12px;', 'class' => 'btn btn-outline-primary']); ?>
            <?php echo Html::resetButton(Yii::t('cms', 'app_cancel'), ['style' => 'margin-top: 18px; margin-bottom: 12px;', 'class' => 'btn btn-outline-primary']); ?>
        </div>
    </form>
</div>