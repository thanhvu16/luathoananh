<?php
use yii\helpers\Url;
?>
<section class="breadcrumb-section text-center">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<h1 class="breadcrumb-title">Đội ngũ luật sự</h1>
					<nav aria-label="breadcrumb" class="breadcrumb-area">
					  <ol class="breadcrumb ml-auto">
					    <li class="breadcrumb-item"><a href="<?= Url::home() ?>">Trang chủ</a></li>
					    <li class="breadcrumb-item"><a href="<?= Url::to('/default/doi-ngu') ?>">Đội ngũ luật sư</a></li>
					    <li class="breadcrumb-item active" aria-current="page"><?= $news['title'] ?></li>
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
					<div class="col-12 col-lg-7 order-1 order-lg-1">
						<div class="row">
							<div class="col-12 col-lg-6">
								<div class="box-detal-image" style="background-image: url('<?= $news['image'] ?>');"></div>
							</div>
							<div class="col-12 col-lg-6">
								<div class="box-detail-info">
									<h1 class="detail-name"><?= $news['title'] ?></h1>
									<p class="detail-title"><?= $news['brief'] ?></p>
									<div class="box-contacts">
										<p class="contact-title">Liên Hệ</p>
										<p class="contact-details">
                                            <?= $news['pseudonym'] ?>
										</p>
									</div>
									<div class="box-socials">
										<p class="social-title">Social Media:</p>
										<p class="social-details">
											<a href="#"><i class="fab fa-facebook-f"></i></a>
											<a href="#"><i class="fab fa-twitter"></i></a>
											<a href="#"><i class="fab fa-linkedin-in"></i></a>
										</p>
									</div>
								</div>
							</div>
							<div class="col-12 box-detail">
								<div class="box-detail-content">
                                    <?= $news['content'] ?>
                                </div>
								<div class="box-detail-button">
									<div class="row">
	                                    <a href="#" class="col btn btn-info mr-3"><i class="fas fa-phone"></i> Hỏi tư vấn</a>
	                                    <a href="#" class="col btn btn-info mr-3"><i class="fas fa-envelope"></i> Gửi câu hỏi</a>
	                                    <a href="#" class="col btn btn-info"><i class="fas fa-clock"></i> Đặt lịch hẹn</a>
	                                </div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-5 order-3 order-lg-2">
						<div class="box-right">
							<div class="form-group has-search">
							    <input type="text" class="form-control" placeholder="Bạn muộn tìm hiểu về vấn đề gì?">
							    <span class="fa fa-search form-control-feedback"></span>
							</div>
							<div class="box-categories">
								<h2 class="category-title">Danh mục chính</h2>
								<div class="custom-dropdown-menu">
									<ul>
										<li>
											<a href="/ve-chung-toi.html">Về chúng tôi</a><i class="fas fa-angle-right color-blue"></i>
										</li>
										<li class="active">
											<a href="/doi-ngu-luat-su.html">Đội ngũ luật sư</a><i class="fas fa-angle-right color-blue"></i>
										</li>
									</ul>
								</div>
							</div>
							<div class="box-categories">
								<h2 class="category-title">Tags</h2>
								<div class="box-tags">
                                    <?php $tags = $news['keyword'];
                                    if(!empty($tags)):
                                        $tags = explode(',', $tags);
                                        foreach($tags as $tag):
                                            if(!empty($tag)): ?>
                                                <a href="<?= CFunction::renderUrlTags($tag) ?>" class="tag">
                                                    <?= $tag ?>
                                                </a>
                                            <?php endif;
                                        endforeach;
                                    endif; ?>
								</div>
							</div>
							<div class="box-advisory text-center">
								<div>
									<a class="navbar-brand" href="#"><img src="/themes/default/ctyluat/img/logo3.png" alt=""></a>
								</div>
								<div>
									<a href="tel:19001234" class="btn btn-info btn-call-now">Gọi ngay</a>
								</div>
								<p class="phone-call-now"><a href="tel:19001234">1900 1234</a></p>
								<p class="bottom-title">Tổng đài luật sư trực tuyến</p>
							</div>
						</div>
					</div>
					<div class="col-12 order-2 order-lg-3 mt-5">
						<h2 class="category-title">Tin khác</h2>
						<div class="row mt-5">
                            <?php
                                foreach ($realateNews as $r):
                            ?>
							<div class="col-12 col-md-6 col-lg-3">
                                <a href="<?= \wap\components\CFunction::renderUrlNews($r) ?>">
                                    <div class="box-person">
                                        <div class="person-image" style="background-image: url('<?= $r['image'] ?>');"></div>
                                        <div class="person-info">
                                            <div class="person-name"><?= $r['title'] ?></div>
                                            <div class="person-title"><?= $r['brief'] ?></div>
                                            <div class="person-social">
                                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                                <a href="#"><i class="fab fa-twitter"></i></a>
                                                <i class="fab fa-linkedin-in"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
							</div>
                            <?php endforeach; ?>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>