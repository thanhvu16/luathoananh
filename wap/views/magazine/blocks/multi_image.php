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
    <div class="VCSortableInPreviewMode LayoutAlbumWrapper alignCenterOverflow noCaption" type="LayoutAlbum" style="">
        <div class="LayoutAlbumContent">
            <?php
            foreach ($content['images'] as $image) {
                if (empty($image['src'])) continue;
                $src = $image['src'];
                ?>

                <?php if ($count == 0 || $count % $numberOnRow == 0) { ?>
                    <div class="LayoutAlbumRow">
                <?php } ?>
                <figure class="LayoutAlbumItem">
                    <img title="<?= !empty($image['alt']) ? $image['alt'] : '' ?>"
                         alt="<?= !empty($image['alt']) ? $image['alt'] : '' ?>" src="<?= $image['src'] ?>"/>
                    <figcaption></figcaption>
                </figure>
                <?php if ($count == ($countImage - 1) || $count % $numberOnRow == ($numberOnRow - 1)) { ?>
                    </div>
                <?php } ?>

                <?php $count++;
            } ?>
        </div>
    </div>

<?php
} ?>