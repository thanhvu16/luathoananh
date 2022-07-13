<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cms\models\Magazine */

$timeRequest = time();
$token = \common\components\CFunction::getToken($model->id, $timeRequest);
$urlReview = 'https://luathoanganh.vn/magazine/preview.html?id='.$model->id.'&t='.$timeRequest.'&token='.$token;

$this->title = 'Thông tin Magazine: ' . ' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Magazines', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['title'] = $this->title;
$this->params['menu'] = [
    ['label'=>'Quay lại', 'url' => ['index'], 'options' => ['class' => 'btn btn-outline-primary']],
    ['label'=>'Xem trước', 'url' => $urlReview, 'options' => ['target' => '_blank', 'class' => 'btn btn-outline-primary']],
];
?>
<div class="box box-body">
<div class="magazine-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
<?php
$this->registerJsFile('/themes/default/js/magazine.js?v=1.01', ['depends' => [yii\web\JqueryAsset::className(), cms\components\assets\AdminlteAsset::className()], 'position' => \yii\web\View::POS_END])
?>
