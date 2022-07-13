<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use cms\models\FbLeagueSeo;

/* @var $this yii\web\View */
/* @var $model cms\models\League */
/* @var $form yii\widgets\ActiveForm */

$typeSeos = FbLeagueSeo::getListType();
?>

<div class="league-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => 255, 'disabled' => 'disabled']) ?>

    <!--    --><?php //echo $form->field($model, 'short_name')->textInput(['maxlength' => 255, 'disabled' => 'disabled']) ?>

    <?php echo $form->field($model, 'type')->dropDownList([
        \cms\models\League::TYPE_LEAGUE => 'League',
        \cms\models\League::TYPE_CUP => 'CUP',
    ], ['disabled' => true]) ?>

    <?php echo $form->field($model, 'custom_name')->textInput(['maxlength' => 255])->label('Tên') ?>


    <?php echo $form->field($model, 'custom_short_name')->textInput(['maxlength' => 255])->label('Tên ngắn') ?>

    <?php
    /* echo $form->field($model, 'logo')->widget(
     \zxbodya\yii2\elfinder\ElFinderInput::className(),
     ['connectorRoute' => 'el-finder/connector',]
 )*/
    ?>
    <?php // echo $form->field($model, 'logo')->textInput(['maxlength' => 255]) ?>

    <?php //echo $form->field($model, 'countryLogo')->textInput(['maxlength' => 255]) ?>

    <?php echo $form->field($model, 'isHot')->checkbox() ?>

    <?php echo $form->field($model, 'sort_order')->textInput()->hint('Sắp xếp theo giảm dần.') ?>

    <section class="">
        <!-- Custom tabs (Charts with tabs)-->
        <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs pull-right">
                <?php foreach ($typeSeos as $keyT => $typeSeo) { ?>
                    <li class="<?= $keyT == FbLeagueSeo::TYPE_LTD ? 'active' : '' ?>">
                        <a href="#tab_seo_<?= $keyT ?>" data-toggle="tab"><?= $typeSeo ?></a>
                    </li>
                <?php } ?>
                <li class="pull-left header"><i class="fa fa-inbox"></i> SEO</li>
            </ul>
            <div class="tab-content">
                <?php foreach ($typeSeos as $keyT => $typeSeo) {
                    $seo = [];
                    if ($model->league_id > 0) {
                        $seo = FbLeagueSeo::findOne(['league_id' => $model->league_id, 'type' => $keyT]);
                    }
                    ?>
                    <div class="tab-pane fade in <?= $keyT == FbLeagueSeo::TYPE_LTD ? 'active' : '' ?>"
                         id="tab_seo_<?= $keyT ?>" style="position: relative;">
                        <div class="form-group field-league-sort_order">
                            <label class="control-label" for="league-seo_title_<?= $keyT ?>">Tiêu đề <?= $typeSeo ?></label>
                            <input type="text" id="league-seo_title_<?= $keyT ?>" class="form-control"
                                   name="seo[<?= $keyT ?>][title]" value="<?= !empty($seo->title)?$seo->title:'' ?>" />
                            <div class="help-block"></div>
                        </div>
                        <div class="form-group field-league-sort_order">
                            <label class="control-label" for="league-seo_des_<?= $keyT ?>">Mô tả <?= $typeSeo ?></label>
                            <textarea id="league-seo_des_<?= $keyT ?>" class="form-control"
                                      name="seo[<?= $keyT ?>][meta_desc]"><?= !empty($seo->meta_desc)?$seo->meta_desc:'' ?></textarea>
                            <div class="help-block"></div>
                        </div>
                        <div class="form-group field-league-sort_order">
                            <label class="control-label" for="league-seo_des_<?= $keyT ?>">Từ khóa <?= $typeSeo ?></label>
                            <textarea id="league-seo_keyword_<?= $keyT ?>" class="form-control"
                                      name="seo[<?= $keyT ?>][meta_keywords]"><?= !empty($seo->meta_keywords)?$seo->meta_keywords:'' ?></textarea>
                            <div class="help-block"></div>
                        </div>
                        <div class="form-group field-league-sort_order">
                                <label class="control-label" for="league-seo_short_des_<?= $keyT ?>">Giới thiệu <?= $typeSeo ?></label>
                                <textarea id="league-seo_short_des_<?= $keyT ?>" class="form-control"
                                          name="seo[<?= $keyT ?>][sapo]"><?= !empty($seo->sapo)?$seo->sapo:'' ?></textarea>
                                <div class="help-block">Nôi dung intro  <?= $typeSeo ?> theo giải đấu</div>
                            </div>

                    </div>
                <?php } ?>
            </div>
        </div>
    </section>


    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
        <?php echo Html::resetButton('Reset', ['class' => 'btn btn-default']); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<style>
    .nav-tabs-custom > .nav-tabs > li {
        border-top: 3px solid transparent;
        margin-bottom: -2px;
        margin-right: 5px !important;
    }

    .nav-tabs-custom .nav-tabs {
        border-bottom: 1px solid #f4f4f4;
    }
</style>
