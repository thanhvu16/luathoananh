<?php
use yii\helpers\Html;
use yii\helpers\Url;
/**
 * @var \yii\web\View $this
 * @var \common\models\MagazineBase[] $magazines
 */
$countMaga = count($magazines);
if ($countMaga > 0) {
    for ($i = 0; $i < $countMaga; $i++) {
        $magazine = $magazines[$i];
        $title = Html::encode($magazine->title);
        $url = Url::toRoute(['magazine/detail', 'slug' => \common\components\CFunction::unsignString($magazine->title), 'id' => $magazine->id]);
?>
        <li>
            <div class="emag-post">
                <a href="<?= $url ?>" title="<?= $title ?>" class="thumb">
                    <i style="background-image: url('<?= $magazine->image ?>')"></i>
                </a>
                <div class="total">
                    <a href="<?= $url ?>" title="<?= $title ?>" class="title"><?= $title ?></a>
                    <div class="funct">
                        <div class="left">
                            <div class="date" title="2021-01-03T20:51:00"><?= \common\components\CFunction::humanTiming($magazine->public_time) ?></div>
                        </div>
                    </div>
                    <div class="sapo">
                        <?= Html::encode($magazine->sapo) ?>
                    </div>
                </div>
            </div>
        </li>
<?php }
} ?>