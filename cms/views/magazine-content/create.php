<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $magazine cms\models\Magazine */
/* @var $model cms\models\MagazineContent */
?>
<li  class="list-group-item" id="item_block_<?= $model->id ?>" >
    <h4 class="list-group-item-heading">
        <?= $typeContent['image'] ?> <?= $typeContent['name'] ?>
        <div class="btn-group pull-right" role="group" aria-label="...">
            <input name="blocks[]" type="hidden" value="<?= $model->id ?>" />
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                    <!--                                        <span class="caret"></span>-->
                </button>
                <ul class="dropdown-menu">
                    <li><a href="javascript:;" onclick="openEmbed('<?= Url::toRoute(['magazine-content/update', 'id' => $model->id]) ?>', 'Cập nhật khối nội dung', <?= $model->id ?>)" ><i class="fa fa-pencil"></i> Cập nhật</a></li>
                    <li><a href="javascript:;" onclick="removeBlockContent(<?= $model->id ?>)"><i class="fa fa-trash"></i> Xóa</a></li>
                </ul>
            </div>
        </div>
    </h4>
    <p class="list-group-item-text"><?= $typeContent['desc'] ?></p>
    <?php if(!empty($model->content)){ ?>
    <div class="content-detail-blick-cms">
        <?php echo $this->render('//magazine-content/contents/'.$model->block_type, [
                'model' => $model,
                'magazine' => $magazine,
        ]) ?>
    </div>
    <?php } ?>
</li>
