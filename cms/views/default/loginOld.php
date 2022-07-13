<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\captcha\Captcha;
    $this->title = 'Cms Everest';
?>
<div>
    <div>
        <h1 class="logo-name">Everest</h1>
    </div>
    <h3>Welcome to CMS Everest</h3>
    <?php $form = ActiveForm::begin(); ?>
        <div class="form-group">
            <?php echo $form->field($model, 'username')->textInput(['class' => 'form-control', 'placeholder' => Yii::t('cms', 'username'), 'required' => ""])->label(''); ?>
        </div>
        <div class="form-group">
            <?php echo $form->field($model, 'password')->passwordInput(['class' => 'form-control', 'placeholder' => Yii::t('cms', 'password'), 'required' => ""])->label(''); ?>
        </div>
        <div class="form-group">
            <?php echo $form->field($model, 'verifyCode')->widget(Captcha::className(), [ 'captchaAction' => '/default/captcha', 'template' => '<div class="row"><div class="col-lg-7">{input}</div><div class="col-lg-4">{image}</div></div>', 'options' => ['placeholder' => Yii::t('cms', 'verify_code'), 'class' => 'form-control']])->label(''); ?>
        </div>
        <?php echo Html::submitButton(Yii::t('cms', 'login'), ['class' => 'btn btn-outline-primary block full-width m-b']); ?>
    <?php ActiveForm::end(); ?>
    <?php /*echo yii\authclient\widgets\AuthChoice::widget([
        'baseAuthUrl' => ['google/auth']
    ])*/ ?>
</div>