<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model cms\models\League */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Leagues', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="league-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?/*= Html::a('Update', ['update', 'id' => $model->league_id], ['class' => 'btn btn-primary']) */?><!--
        --><?/*= Html::a('Delete', ['delete', 'id' => $model->league_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) */?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'league_id',
            'name',
            'custom_name',
            'custom_short_name',
            'short_name',
            'type',
            'sub_league_name',
            'status',
            'color',
            'logo',
            'created_time',
            'created_by',
            'updated_time',
            'updated_by',
            'totalRound',
            'currentRound',
            'currentSeason',
            'countryId',
            'country',
            'countryLogo',
            'areaId',
            'isHot',
            'sort_order',
        ],
    ]) ?>

</div>
