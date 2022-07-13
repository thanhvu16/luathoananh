
<?php
use yii\helpers\Url;
use wap\components\CFunction;
?>
<section class="breadcrumb-section text-center">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <p class="breadcrumb-title"><?= $category['title'] ?></p>
                <nav aria-label="breadcrumb" class="breadcrumb-area">
                    <ol class="breadcrumb ml-auto">
                        <li class="breadcrumb-item"><a href="<?= Url::home() ?>">Trang chủ</a></li>
						<?php foreach ($breadcrum as $item): ?>
								<li class="breadcrumb-item"><a href="<?= CFunction::renderUrlCategory($item) ?>"><?= $item['title'] ?></a></li>
						<?php endforeach; ?>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="body-content">
    <div class="body-white pb-100">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-8 order-1 order-lg-1">
                    <div class="row">
                        <div class="col-12 post-head">
                            <h1 class="post-title"><?= $news->title ?></h1>
                            <div class="post-info">
									<span class="post-author">
										<i class="fas fa-user"></i> <?= $news->pseudonym ?>
									</span>
                                <span class="post-date">
										<i class="fas fa-clock"></i> <?= date('d/m/Y', strtotime($news->time_active)) ?>
									</span>
                                <span class="post-link">
									    <a href="#" class="post-link"><i class="fas fa-link"></i><a href="<?= CFunction::renderUrlCategory($category) ?>"><?= $category['title'] ?></a></li> </a>
								    </span>
                            </div>
                        </div>
						<div class="col-12 mt-3">
							<?php if (!empty($news->menu_content)):
							$url = CFunction::renderUrlNews($news);
							$menuContent = json_decode($news->menu_content, true);
							if (!empty($menuContent)):
							?>
							<div class="tree-menu">
								<header id="toggle-tree-menu">Nội dung bài viết <span class="toggle-more-less">-</span></header>
								<ol style="">
									<?php foreach ($menuContent as $k => $v): ?>
										<li>
											<a href="<?= $url ?>#tree-menu-<?= $k ?>" data-anchor="tree-menu-<?= $k ?>">
												<?= $v['parent'] ?>
											</a>
											<?php if (!empty($v['children'])): ?>
												<ol>
													<?php foreach ($v['children'] as $kChild => $vChild): ?>
														<li>
															<a href="<?= $url ?>#tree-menu-<?= $k ?>-<?= $kChild ?>" data-anchor="tree-menu-<?= $k ?>-<?= $kChild ?>">
																<?= $vChild ?>
															</a>
														</li>
													<?php endforeach; ?>
												</ol>
											<?php endif; ?>
										</li>
									<?php endforeach; ?>
								</ol>
							</div>
						<?php
						endif;
						endif;
						?>
						</div>
                        <div class="col-12 box-detail">
                            <div class="box-detail-content">
                                <?= $news->content ?>
                            </div>
                            <!--<div class="box-social text-center mb-4">
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            </div>-->
                            <div class="box-detail-button">
                                <div class="row">
                                    <a href="tel:0908308123" class="col btn btn-info mr-3"><i class="fas fa-phone"></i> Hỏi tư vấn</a>
                                    <a href="mailto:luatsu@luathoanganh.vn" class="col btn btn-info mr-3"><i class="fas fa-envelope"></i> Gửi câu hỏi</a>
                                    <a href="/lien-he.html" class="col btn btn-info"><i class="fas fa-clock"></i> Đặt lịch hẹn</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="category-title mt-5">Tin liên quan</h4>
                    <div class="row">
                        <?php
                        foreach ($relatedNews as $r):
                            ?>
                            <div class="col-12 col-md-6 col-lg-3 mt-5">
                                <div class="box-news2">
                                    <div class="row">
                                        <div class="col-6 col-md-6 col-lg-12">
                                            <a href="<?= CFunction::renderUrlNews($r) ?>" ><div class="box-news2-thum h-111" style="background-image: url('<?= Yii::$app->request->baseUrl . '' . $r['image']?>');"></div></a>
                                        </div>
                                        <div class="col-6 col-md-6 col-lg-12">
                                            <div class="box-news2-time">
                                                <i class="fas fa-clock"></i>
                                                <span class="time-title"><?= date('d/m/Y', strtotime($r['time_active'])) ?></span>
                                            </div>
                                            <div class="box-news2-des" style="text-align: left;">
                                                <a style="text-decoration: none; color: inherit;" href="<?= CFunction::renderUrlNews($r) ?>"><?= $r['title'] ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endforeach;
                        ?>
                    </div>
                </div>
                <div class="col-12 col-lg-4 order-3 order-lg-2">
					<?= $this->render('//layouts/default/right_bar', ['cateId' => $news->news_category_id]) ?>
                </div>
            </div>
        </div>
    </div>
</section>