<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cms\models\MagazineContent */

$this->title = 'Update Magazine Content: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Magazine Contents', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
$this->params['title'] = 'Update';
$this->params['menu'] = [
    ['label'=>'Back', 'url' => ['index'], 'options' => ['class' => 'btn btn-primary']],
];
?>
<div class="magazine-content-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<?php
$this->registerJsFile('/themes/default/js/magazine.js', ['depends' => [yii\web\JqueryAsset::className(), cms\components\assets\AdminlteAsset::className()], 'position' => \yii\web\View::POS_END])
?>
