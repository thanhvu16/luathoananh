<?php
use wap\components\CFunction;
use yii\helpers\Url;
?>

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
                    <p>Công ty Luật TNHH HOANGANH IBC (sau đây gọi tắt là “Luật Hoàng Anh”) là công ty Luật được Sở Tư pháp thành phố Hà Nội cấp phép hoạt động theo Giấy chứng nhận đăng ký hoạt động số 01021810/TP/ĐKHĐ, có trụ sở tại Số 2, Ngõ 84 Trần Quang Diệu, phường Ô Chợ Dừa, quận Đống Đa, thành phố Hà Nội; hoạt động trong các lĩnh vực: Tham gia tố tụng, Tư vấn pháp luật, Đại diện ngoài tố tụng, Dịch vụ pháp lý khác.<br/>
                        Luật Hoàng Anh được sáng lập bởi những thành viên có đầy đủ Chứng chỉ hành nghề luật sư do Bộ Tư pháp cấp, có Thẻ Luật sư do Liên đoàn Luật sư Việt Nam cấp, và hiện là thành viên của Đoàn Luật sư thành phố Hà Nội.</p>
                    <a class="btn btn-info mt-3 mb-3" href="/ve-chung-toi.html" role="button">Tìm hiểu thêm</a>
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