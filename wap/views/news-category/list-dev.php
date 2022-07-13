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
						<?php foreach ($categories as $k => $item): ?>
						<div class="bg-breadcrum-category" style="backgroud-image: <?= $item['image'] ?>">
							<a href="<?= CFunction::renderUrlCategory($item) ?>">
								<p class="mb-0">
									# <?= $k + 1 ?>
								</p>
								<p class="mb-0">
									<?= $item['title'] ?>
								</p>
							</a>
						</div>
						<?php endforeach; ?>
					</div>
                </div>
            </div>
				<div style="border: 1px solid #85bec9;border-radius:10px;padding:20px 10px;margin: 10px 0px">
					<div class="content-page-intro" id="content-page-intro">
						<?= $category->page_intro ?>
						<?php if (!empty($category->faqs)): ?>
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
					<div class="readmore_content_exists">
						<button id="readmore_content" onclick="showMorePageIntro()">
							<span class="arrow"><span></span></span>
							Đọc tiếp
						</button>
					</div>
				</div>
            <div class="row">
                <?php if (!empty($news)): $new = $news[0]; ?>
				<div class="col-12 col-md-6">
					<div class="box-news3">
						<a href="<?= CFunction::renderUrlNews($new) ?>" >
							<img src="<?= Yii::$app->request->baseUrl . $new['image']?>" width="100%" height="auto"/>
						</a>
						<p class="news-category pt-2 pb-2 mb-0">
							<a href="<?= CFunction::renderUrlCategory($new['cid']) ?>" >
								<?= $new['cname'] ?>
							</a>
							<span><?= CFunction::diffTime($new['time_active']) ?></span>
						</p>
						<a href="<?= CFunction::renderUrlNews($new) ?>" style="color: inherit; text-decoration: none;">
							<h3 class="box-news2-title fs-large mt-0"><?= $new['title'] ?></h3>
						</a>
					</div>
				</div>
                <?php endif; ?>
				<div class="col-12 col-md-6 pr-0">
					<div class="list-news-new">
					<?php foreach ($news as $k => $new): if ($k == 0) continue; if ($k > 4) break; ?>
						<div class="box-news3">
							<a href="<?= CFunction::renderUrlNews($new) ?>" >
								<img src="<?= Yii::$app->request->baseUrl . $new['image']?>" width="100%" height="auto"/>
							</a>
							<p class="news-category pt-2 pb-2 mb-0">
								<a href="<?= CFunction::renderUrlCategory($new['cid']) ?>" >
									<?= $new['cname'] ?>
								</a>
								<span><?= CFunction::diffTime($new['time_active']) ?></span>
							</p>
							<a href="<?= CFunction::renderUrlNews($new) ?>" style="color: inherit; text-decoration: none;">
								<h3 class="box-news3-title"><?= $new['title'] ?></h3>
							</a>
						</div>
					<?php endforeach; ?>
					</div>
				</div>
			</div>
			</div>

            <div class="row">
				<div class="col-12 col-md-8 mt-4">
					<div class="row">
						<?php foreach ($news as $k => $new): if ($k < 5) continue; ?>
							<div class="col-12 mt-3">
								<div class="row">
									<div class="col-4 pr-0 box-news3">
										<a href="<?= CFunction::renderUrlNews($new) ?>">
											<img src="<?= Yii::$app->request->baseUrl . $new['image']?>" width="100%" height="auto"/>
										</a>
									</div>
									<div class="col-8">
										<a href="<?= CFunction::renderUrlNews($new) ?>" style="color: inherit; text-decoration: none;">
											<h4 class="box-news3-title font-weight-bold mt-1 mb-0"><?= $new['title'] ?></h4>
										</a>										
										<p class="news-category pt-0 pb-2 mb-0">
											<a href="<?= CFunction::renderUrlCategory($new['cid']) ?>" >
												<?= $new['cname'] ?>
											</a>
											<span><?= CFunction::diffTime($new['time_active']) ?></span>
										</p>
									</div>
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
					</div>
				</div>
				<div class="col-12 col-md-4 mt-4">
					<?= $this->render('//layouts/default/right_bar', ['cateId' => $category->id]) ?>
				</div>
			  </div>
            </div>
        </div>
    </div>
</section>