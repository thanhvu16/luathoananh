<?php
use wap\components\CFunction;
use yii\helpers\Url;
use yii\widgets\LinkPager;

?>
<section class="breadcrumb-section text-center breadcrumb-section-6">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="breadcrumb-title"><?= $category->title ?></h1>
                <nav aria-label="breadcrumb" class="breadcrumb-area">
                    <ol class="breadcrumb ml-auto">
                        <li class="breadcrumb-item"><a href="<?= Url::home() ?>">Trang chủ</a></li>
						<?php foreach ($breadcrum as $k => $item): ?>
							<li class="breadcrumb-item <?php if ($k == (count($breadcrum) - 1)) echo 'active' ?>">
								<?php if ($k == (count($breadcrum) - 1)): ?>
									<?= $item['title'] ?>
								<?php else: ?>
									<a href="<?= CFunction::renderUrlCategory($item) ?>"><?= $item['title'] ?></a>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>
<section class="body-content">
    <div class="container">
    <div class="body-white pb-100">
        <div class="container">
            <div class="row">
                <div class="col-12">
					<div class="breadcrum-category">
						<?php foreach ($categories as $item): ?>
							<a href="<?= CFunction::renderUrlCategory($item) ?>"># <?= $item['title'] ?></a>
						<?php endforeach; ?>
					</div>
                </div>
            </div>

            <div class="row box-news-border">

                <?php foreach ($news as $new): ?>
                    <div class="col-12 col-md-6 col-lg-4 mt-4">
                        <div class="box-news2">
                            <a href="<?= CFunction::renderUrlNews($new) ?>">
                                <div class="box-news2-thum h235" style="background-image: url('<?= Yii::$app->request->baseUrl . $new['image']?>');"></div>
                            </a>
                            <a href="<?= CFunction::renderUrlNews($new) ?>" style="color: inherit; text-decoration: none;"><h3 class="box-news2-title"><?= $new['title'] ?></h3></a>
                            <!--<div class="box-news2-des"><?= $new['description_seo'] ?></div>
                            <!--<div class="text-center">
                                <a href="<?= CFunction::renderUrlNews($new) ?>" class="box-new2-link-detail">Chi tiết <i class="fas fa-arrow-right"></i></a>
                            </div>-->
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="col-12 mt-4">
                    <nav aria-label="Page navigation example" class="pagination-custom">
                        <?php echo LinkPager::widget([
                            'pagination' => $pages
                        ]); ?>
                    </nav>
                </div>
				
				<div class="row box-detail-lv1 mt-5">
				<div class="col-12 col-lg-6 order-2">
					<!--<div class="box-thum-about-us">
						<div class="thum-about-us" style="background-image: url('<?= Yii::$app->request->baseUrl ?>/themes/default/ctyluat/img/thum2.png');"></div>
					</div>
				</div>-->

				<div class="col-12 col-lg-6 order-1">
					<div class="box-des-about-us pt-2">
						<?= $category->page_intro ?>
					</div>
				</div>
				</div>

			  </div>

                <?php if (in_array($category->parent_id, [326,327]) && !empty($category->faqs)): ?>
                <?php $faqs = json_decode($category->faqs, true); ?>
                <div class="col-12 mt-5">
                    <h3 class="category-title mt-5">Câu hỏi thường gặp</h3>
                    <div class="box-faq">
                        <?php foreach ($faqs as $faq): ?>
                        <div class="faq">
                            <div class="question"><?= $faq['question'] ?> <span class="show-answer"><i class="fas fa-minus"></i><i class="fas fa-plus"></i></span></div>
                            <div class="answer"><?= $faq['answer'] ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>