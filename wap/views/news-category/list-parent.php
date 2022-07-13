<?php
use yii\helpers\Url;
use common\components\Utility;
?>
<section class="breadcrumb-section text-center breadcrumb-section-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="breadcrumb-title"><?= $category->title ?></h1>
                <nav aria-label="breadcrumb" class="breadcrumb-area">
                    <ol class="breadcrumb ml-auto">
                        <li class="breadcrumb-item"><a href="<?= Url::home() ?>">Trang chủ</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= $category->title ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="body-content">
    <div class="body-white text-center">
        <div class="container">
            <div class="row">

                <?php
                foreach($categories as $cate):
                    ?>
                    <div class="col-12 col-sm-6 col-md-4 col-ld-4 mx-auto">
                        <div class="box-news box-news-back <?= $cate['title_seo'] ?>">
                            <span class="icon icon-block m-auto" style="background-image: url(<?= $cate['image'] ?>)"></span>
                            <a href="<?= \wap\components\CFunction::renderUrlCategory($cate) ?>" class="box-title mt-3 mb-2"><?= $cate['title'] ?></a>
                            <p class="box-des"><?= $cate['desc'] ?></p>
                            <a href="<?= \wap\components\CFunction::renderUrlCategory($cate) ?>" class="box-btn-detail">Chi tiết</a>
                        </div>
                    </div>
                <?php
                endforeach;
                ?>

            </div>
        </div>
    </div>
    <?php $categoriesId = array_column($categories, 'id'); $categoriesId[] = $category->id; ?>
    <?= \wap\widgets\HotNewsWidget::widget(['categories' => $categoriesId, 'isHot' => false]) ?>

    <?= \wap\widgets\DoiNguWidget::widget(); ?>

</section>