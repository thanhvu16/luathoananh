<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

/**
 * @var \cms\models\MagazineContent $model
 * @var \yii\web\View $this
 */

if (!empty($model->content)) {
    $content = unserialize($model->content);
}
if (!empty($content['images'])) {
    $numberOnRow = !empty($content['number_on_row']) ? $content['number_on_row'] : 2;

    $countImage = count($content['images']);
    $count = 0; ?>
    <div class="">
        <div class="row">
            <?php
            foreach ($content['images'] as $image) {
                if (empty($image['src'])) continue;
                $src = $image['src'];
                ?>


                <div class="col-sm-3" style="margin-top: 10px;">

                    <figure class="LayoutAlbumItem">
                        <img title="<?= !empty($image['alt']) ? $image['alt'] : '' ?>"
                             alt="<?= !empty($image['alt']) ? $image['alt'] : '' ?>" src="<?= $image['src'] ?>"/>
                        <figcaption></figcaption>
                    </figure>

                </div>


                <?php $count++;
            } ?>
        </div>
    </div>

    <?php
} ?>