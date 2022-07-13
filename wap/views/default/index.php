<?php

use yii\helpers\Url;
use common\components\Utility;
use wap\components\CFunction;

?>
<section class="body-content">
    <div class="body-white text-center">
        <div class="container">

            <div class="row">
                <div class="col-12">
					<a href="/dich-vu-luat-su.html">
                    <h2 class="body-title">DỊCH VỤ LUẬT SƯ</h2>
					</a>
                </div>
            </div>

            <div class="row">
                <?php
                foreach ($categories as $cate):
                    ?>
                    <div class="col-12 col-sm-6 col-md-4 col-ld-4">
                        <div class="box-news">
                            <span class="icon icon-block m-auto"
                                  style="background-image: url(<?= $cate['image'] ?>)"></span>
                            <a href="<?= \wap\components\CFunction::renderUrlCategory($cate) ?>"
                               class="box-title mt-2 mb-2"><?= $cate['title'] ?></a>
                            <p class="box-des mb-2"><?= $cate['desc'] ?></p>
                            <!--<a href="<?= \wap\components\CFunction::renderUrlCategory($cate) ?>"
                               class="box-btn-detail">Chi tiết</a>-->
                        </div>
                    </div>
                <?php
                endforeach;
                ?>
            </div>
        </div>
    </div>

    <div class="body-back1 text-center">
        <div class="container">

            <div class="row">
                <div class="col-12">
                    <h2 class="body-title">CHÚNG TÔI Ở ĐÂY</h2>
                </div>
            </div>

            <div class="row">
                <div class="box-content1 col-12">
                    <div class="col-10 mx-auto">
                        <p class="title-box-content1">
                            Để tư vấn, cung cấp các giải pháp cho các vấn đề pháp lý bạn đang gặp phải <br>
                            <span class="title-box-content1-small">
                                Hãy gửi đề nghị tư vấn/yêu cầu cung cấp dịch vụ pháp lý của bạn cho chúng tôi. Luật Hoàng Anh sẽ cố gắng phản hồi cho bạn trong vòng 24h.
                            </span>
                        </p>
                        <a class="btn btn-info mt-3 mb-3" href="<?= Url::to(['/default/contact']) ?>" role="button">
                            Gửi yêu cầu
                        </a>
                    </div>
                </div>
            </div>

            <div class="row mt-5 mb-3">

                <div class="col-12 col-md-4 col-lg-3">
                    <div class="box-index">
                        <span class="icon icon-10 m-auto"></span>
                        <span class="index-detail">
                            <span class="index-title">>1.000</span><br>
                            Khách hàng cá nhân
                        </span>
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <div class="box-index">
                        <span class="icon icon-11 m-auto"></span>
                        <span class="index-detail">
                            <span class="index-title">>500</span><br>
                            Doanh nghiệp
                        </span>
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <div class="box-index">
                        <span class="icon icon-12 m-auto"></span>
                        <span class="index-detail">
                            <span class="index-title">>100</span><br>
                            Việc tố tụng
                        </span>
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <div class="box-index">
                        <span class="icon icon-13 m-auto"></span>
                        <span class="index-detail">
                            <span class="index-title">>11</span><br>
                            Kinh nghiệm
                        </span>
                    </div>
                </div>

            </div>

        </div>
    </div>


    <div class="body-white text-center pb-100">
        <div class="container">

            <div class="row">
                <div class="col-12">
                    <h2 class="body-title">LUẬT SƯ CỦA CHÚNG TÔI</h2>
                </div>
            </div>

            <div class="row mt-5">
                <?php
                foreach($doingu as $d):
                    ?>
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="<?= CFunction::renderUrlDoiNgu($d); ?>">
                            <div class="box-person">
                                <div class="person-image" style="background-image: url('<?= $d['image'] ?>');"></div>
                                <div class="person-info">
                                    <div class="person-name"><?= $d['title']; ?></div>
                                    <div class="person-title"><?= $d['brief']; ?></div>
                                    <div class="person-social">
                                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                                        <a href="#"><i class="fab fa-twitter"></i></a>
                                        <a href="#"><i class="fab fa-instagram"></i></a>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="body-back2 text-center">
        <div class="container">

            <div class="row">
                <div class="col-12">
                    <h2 class="body-title">VỀ CHÚNG TÔI</h2>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-12 col-lgb-6 order-2">
                    <div class="box-thum-about-us">
                        <div class="thum-about-us" style="background-image: url('/themes/default/ctyluat/img/thum2.png');"></div>
                    </div>
                </div>
                <div class="col-12 col-lgb-6 order-1">
                    <div class="box-des-about-us pt-2">
                        <p>Công ty Luật TNHH HOANGANH IBC (sau đây gọi tắt là “Luật Hoàng Anh”) là công ty Luật được Sở Tư pháp thành phố Hà Nội cấp phép hoạt động theo Giấy chứng nhận đăng ký hoạt động số 01021810/TP/ĐKHĐ, có trụ sở tại Số 2, Ngõ 84 Trần Quang Diệu, phường Ô Chợ Dừa, quận Đống Đa, thành phố Hà Nội; hoạt động trong các lĩnh vực: Tham gia tố tụng, Tư vấn pháp luật, Đại diện ngoài tố tụng, Dịch vụ pháp lý khác. Luật Hoàng Anh được sáng lập bởi những thành viên có đầy đủ Chứng chỉ hành nghề luật sư do Bộ Tư pháp cấp, có Thẻ Luật sư do Liên đoàn Luật sư Việt Nam cấp, và hiện là thành viên của Đoàn Luật sư thành phố Hà Nội.</p>
                        <a class="btn btn-info mt-3 mb-3" href="/ve-chung-toi.html" role="button">Tìm hiểu thêm</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= \wap\widgets\HotNewsWidget::widget() ?>
	
	<div class="body-white text-center pb-100 owl-theme">
    <div class="container">

        <div class="row">
            <div class="col-12">
                <h2 class="body-title">HỎI ĐÁP PHÁP LUẬT</h2>
            </div>
        </div>
		<div class="owl-carousel-hoidap">
            <?php
			$countAll = count($hoidap);
			$count = 0;
            foreach ($hoidap as $news):
                ?>
				<?php if($count%8 == 0){ ?>
				<div class="item-slide-home w-100">
					<div class="row">
				<?php } ?>
                <div class="col-12 col-md-6 col-lg-3 mt-5">
                    <div class="box-news2">
                        <a class="box-news2-thum"
                           href="<?= \wap\components\CFunction::renderUrlNews($news) ?>"
                           style="background-image: url('<?= Yii::$app->request->baseUrl . '' . $news['image'] ?>');"></a>
                        <div class="box-news2-time"><i class="fas fa-clock"></i><span
                                class="time-title"><?= substr($news['updated_time'], 0, 10) ?></span></div>
                        <div class="box-news2-des" style="text-align: left;">
                            <a style="text-decoration: none; color: inherit;"
                               href="<?= \wap\components\CFunction::renderUrlNews($news) ?>"><?= $news['title'] ?></a>
                        </div>
                    </div>
                </div>
				<?php if($count%8 == 7 || $countAll==$count){ ?>
					</div>
				</div>
				<?php } ?>
            <?php 
			$count++;
			endforeach; 
			$this->registerJs('
				$(".owl-carousel-hoidap").owlCarousel({
					margin:10,
					loop:false,
					autoWidth:false,
					items:1
				});
			');
			?>
        </div>
    </div>
</div>
</section>