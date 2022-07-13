
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
				<div class="d-flex justify-content-start breadcrum-small">
							<a href="/">
								<amp-img src="/themes/default/ctyluat/img/icon-home.png" width="16" height="16" ></amp-img>
							</a>
							<?php foreach ($breadcrum as $item): ?>
							<span class="pl-2 pr-2">
								<img src="/themes/default/ctyluat/img/bc-next.png" width="6" height="10"/>
							</span>
							<a href="<?= CFunction::renderUrlCategory($item) ?>"><?= $item['title'] ?></a>
							<?php endforeach; ?>
						</div>
						
						<h1 class="post-title title-new mt-2"><?= $news->title ?></h1>
						
						<div class="post-info">
							<span class="post-author post-author-new">
								<i class="fas fa-user"></i> <?= $news->pseudonym ?>
							</span>
							<a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(Url::base(true) . CFunction::renderUrlNews($news)) ?>" target="_blank">
								<amp-img src="/themes/default/ctyluat/img/icon-fb.png" style="margin-top: -3px;" width="24" height="24" ></amp-img>
							</a>
							<span class="post-date post-date-new">
								<?= CFunction::getDayofWeekByDateGMT($news->time_active) ?>
							</span>
						</div>
					<div class="row">
						
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
                                <?= \common\components\Utility::convertContentAmp($news->content) ?>
                            </div>
                            <div style="border: 2px solid rgb(23 162 184);border-radius: 8px;overflow: hidden;box-shadow: rgba(0, 0, 0, 0.28) 0px 2px 5px;font-size: 17px;margin: 0px auto;max-width: 95%; margin-top: 10px;">
								 <div style="text-align: center;background: rgb(23 162 184);font-size: 22px;padding: 10px;font-weight: bold;color: white;">CAM KẾT DỊCH VỤ</div> 
									<div style="padding: 15px;">
										 <ul>
											<li>Đồng hành cùng Thân chủ.</li>
											<li>Phương án tốt, giải pháp hay.</li>
											<li><span style="color:#FF8C00"><strong>Bảo mật - Uy tín - Tin cậy - Chi phí thấp - Hiệu quả cao.</strong></span></li>
											<li>Dịch vụ pháp lý tốt số 2 tại Việt Nam.</li>
											<li>Cam kết <span style="color:#B22222"><strong>HOÀN TIỀN</strong></span> nếu thực hiện dịch vụ không thành công.</li>
										</ul>

										<p style="text-align:justify"><em>Cảm ơn quý vị và các bạn đã tin tưởng <strong><span style="color:#0000FF">Luật Hoàng Anh</span></strong>, nếu có thắc mắc muốn giải đáp hãy liên hệ ngay cho chúng tôi.</em></p>

									</div>
								</div>	
                        </div>
                    </div>
					<div class="mt-3 col-12 p-3" style="background: #F7F7F7;">
							<div class="row">
								<div class="col-12 col-md-5">
									<div class="d-flex justify-content-between mb-3 p-2" style="background-color: #fff;border-radius: 8px;">
										<div class="d-flex" style="align-self: center">
											<img src="/themes/default/ctyluat/img/call.gif" width="50" height="50" />
										</div>
										<div style="width: calc(100% - 55px)">
											<h4 style="color: #1E3358;font-size: 14px;font-weight: 700">Tổng đài tư vấn pháp luật</h4>
											<div style="color: #FF0000;font-size: 14px;font-weight: 700"><a href="tel:0908308123"><span style="color: #FF0000;font-size: 14px;font-weight: 700">0908 308 123</span></a></div>
										</div>
									</div>
									<a href="mailto:luatsu@luathoanganh.vn" class="col btn btn-info mr-3"><i class="fas fa-envelope"></i> Gửi câu hỏi</a>
									<a href="/lien-he.html" class="col btn btn-info mt-2 mb-3"><i class="fas fa-clock"></i> Đặt lịch hẹn</a>
								</div>
								<div class="col-12 col-md-7">
									<div class="p-3 pb-4 pt-4" style="background-color: #fff;border-radius: 8px;">
										<h3 class="text-center" style="color: #1E3358;font-size: 18px;font-weight: 700">CÔNG TY LUẬT HOÀNG ANH</h3>
										<div class="d-flex justify-content-between">
										<img src="/themes/default/ctyluat/img/location.png" width="24" height="24" />
										<p class="mb-1" style="font-size: 14px;line-height: 130%;font-weight:400;letter-spacing: -0.0083em;color: #585858;width: calc(100% - 30px)"><strong>Dịch vụ Luật Sư uy tín hàng đầu tại Hà Nội.</strong></p>
										</div>
										<div class="d-flex justify-content-between">
											<img src="/themes/default/ctyluat/img/location.png" width="24" height="24" />
											<p class="mb-1" style="font-size: 14px;line-height: 130%;font-weight:400;letter-spacing: -0.0083em;color: #585858;width: calc(100% - 30px)">Số 2/84 - Trần Quang Diệu - Phường Ô Chợ Dừa - Quận Đống Đa - TP Hà Nội</p>
										</div>
										<div class="d-flex justify-content-between">
											<img src="/themes/default/ctyluat/img/location.png" width="24" height="24" />
											<p class="mb-1" style="font-size: 14px;line-height: 130%;font-weight:400;letter-spacing: -0.0083em;color: #585858;width: calc(100% - 30px)"><strong>Email:</strong>    luatsu@luathoanganh.vn</p>
										</div>
									</div>
								</div>
							</div>
						</div>
                    <h4 class="category-title mt-5">Tin liên quan</h4>
                    <div class="row">
                        <?php
                        foreach ($relatedNews as $r):
                            ?>
                            <div class="col-12 mt-3">
                                <div class="box-news2">
                                    <div class="d-flex">
                                        <a href="<?= CFunction::renderUrlNews($r) ?>" >
												<amp-img src="<?= Yii::$app->request->baseUrl . '' . $r['image']?>" width="134" height="93" /> </amp-img>
										</a>
                                        
                                        <div class="ml-2 box-news3 mt-0" style="text-align: left;">
												<a href="<?= CFunction::renderUrlNews($r) ?>" style="color: inherit; text-decoration: none;">
													<h3 class="box-news3-title mb-0 mt-0"><?= $r['title'] ?></h3>
												</a>								
												<p class="news-category pt-1 pb-2 mb-0 mt-0">
													<a href="<?= CFunction::renderUrlCategory($r['cid']) ?>" >
														<?= $r['cname'] ?>
													</a>
													<span><?= CFunction::diffTime($r['time_active']) ?></span>
												</p>
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
					<?= $this->render('//layouts/amp/right_bar', ['cateId' => $news->news_category_id]) ?>
                </div>
            </div>
        </div>
    </div>
</section>