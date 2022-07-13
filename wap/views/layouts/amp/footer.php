<?php
use yii\helpers\Url;
?>
<div class="row wrap-lienhe">
    <div [class]="visible_contact ? 'col-md-10 hidden-xs lh-dialog' : 'col-md-10 hidden-xs lh-dialog hidden'" id="cont-wrap-lienhe" class="col-md-10 hidden-xs lh-dialog hidden">
	    <a href="https://m.me/115082100669536" target="_blank">
        <p>
            <amp-img src="/themes/default/ctyluat/img/messenger.png" width="50" height="40"></amp-img>
            Messenger
        </p>
		</a>
		<a href="https://zalo.me/0908308123" target="_blank">
        <p>
            <amp-img src="/themes/default/ctyluat/img/zalo.png" width="50" height="40"></amp-img>
            Zalo Chat
        </p>
		</a>
		<a href="sms:0908308123" target="_blank">
        <p>
            <amp-img src="/themes/default/ctyluat/img/sms.png" width="50" height="40"></amp-img>
            SMS
        </p>
		</a>
		<a href="mailto:luatsu@luathoanganh.vn" target="_blank">
        <p>
            <amp-img src="/themes/default/ctyluat/img/email.png" width="50" height="40"></amp-img>
            Email
        </p>
		</a>
		<a href="tel:0908308123" target="_blank">
        <p>
            <amp-img src="/themes/default/ctyluat/img/call.png" width="50" height="40"></amp-img>
            Call
        </p>
		</a>
    </div>
    <div class="col-md-4 hidden-xs">
        <amp-img id="lhe-open" on="tap:AMP.setState({visible_contact: !visible_contact})" tabindex="" role="" [class]="visible_contact ? 'hidden' : ''" class="" src="/themes/default/ctyluat/img/zalo-online.png" width="60" height="60" ></amp-img>
        <amp-img id="lhe-cancel" on="tap:AMP.setState({visible_contact: !visible_contact})" tabindex="" role="" [class]="visible_contact ? '' : 'hidden'" class="hidden" src="/themes/default/ctyluat/img/cancel.png" width="60" height="60" ></amp-img>
    </div>
</div>
<section class="footer-section">
        <div class="body-back3">
                <div class="container">

                        <div class="row footer-bordex-bt">
                                <div class="col-12 col-md-6 col-lg-3">
                                        <p class="footer-title">LIÊN HỆ VỚI CHÚNG TÔI</p>
                                        <p class="footer-text"><i class="fas fa-map-marker-alt"></i><span>Số 2/84 - Trần Quang Diệu - Phường Ô Chợ Dừa - Quận Đống Đa - TP Hà Nội</span></p>
                                        <p class="footer-text"><i class="fas fa-phone"></i><span>0908 308 123</span></p>
                                        <p class="footer-text"><i class="fas fa-envelope"></i></i><span>luatsu@luathoanganh.vn</span></p>
										<div class="footer-text box-text-footer"><p>Chịu trách nhiệm nội dung:</p> <p><strong>Luật sư Nguyễn Đình Hiệp</strong></p>
										<p>Giấy đăng ký hoạt động số 01021810/TP/ĐKHĐ cấp bởi Sở Tư pháp thành phố Hà Nội.</p>
										</div>
                                        <p class="footer-text box-text-footer"><i class="fas fa-clock"></i><span>T2 - T7 8.00 - 17h30. CN Nghỉ</span></p>
                                        <p class="footer-social">
                                                <a href="https://www.facebook.com/Lu%E1%BA%ADt-Ho%C3%A0ng-Anh-115082100669536"><i class="fab fa-facebook-f"></i></a>
                                                <a href="https://twitter.com/luathoanganh"><i class="fab fa-twitter"></i></a>
                                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
												<a href="https://www.youtube.com/channel/UCwlm17yyO6h8W9xMHyToqYg"><i class="fab fa-youtube"></i></a>
                                        </p>
										
                                </div>

                                <div class="col-12 col-md-6 col-lg-3">
                                        <p class="footer-title">DỊCH VỤ LUẬT SƯ</p>
                                    <?php foreach ($this->params['dichvu'] as $dichvu): ?>
                                        <p class="footer-text"><a href="<?= \wap\components\CFunction::renderUrlCategory($dichvu) ?>"><i class="fas fa-angle-right color-blue"></i><span><?= $dichvu['title'] ?></span></a></p>
                                    <?php endforeach; ?>
                                </div>

                                <div class="col-12 col-md-6 col-lg-3">
                                        <p class="footer-title">Hỏi đáp pháp luật</p>
                                    <?php foreach ($this->params['question'] as $question): ?>
                                        <p class="footer-text"><a href="<?= \wap\components\CFunction::renderUrlCategory($question) ?>"><i class="fas fa-angle-right color-blue"></i><span><?= $question['title'] ?></span></a></p>
                                    <?php endforeach; ?>
                                </div>

                                <div class="col-12 col-md-6 col-lg-3">
                                        <p class="footer-title">Liên kết</p>
                                        <p class="footer-text"><a href="/luat-su-doanh-nghiep/thu-tuc-doanh-nghiep.html"><i class="fas fa-angle-right color-blue"></i><span>Dịch vụ thành lập công ty trọn gói</span></a></p>
                                        <p class="footer-text"><a href="/luat-su-hon-nhan/dich-vu-tu-van-ve-ly-hon-lha130.html"><i class="fas fa-angle-right color-blue"></i><span>Luật sư ly hôn</span></a></p>
                                        <p class="footer-text"><a href="/luat-su-doanh-nghiep/dich-vu-tu-van-xin-giay-phep-con-lha954.html"><i class="fas fa-angle-right color-blue"></i><span>Xin giấy phép con</span></a></p>
										<p class="footer-text"><a href="/gioi-thieu/chinh-sach-bao-ve-thong-tin-ca-nhan-lha5881.html"><i class="fas fa-angle-right color-blue"></i><span>Chính sách bảo vệ thông tin cá nhân</span></a></p>
										<p class="footer-text"><a href="/magazine/dich-vu-to-chuc-dai-hoi-dong-co-dong-truc-tuyen-giai-phap-dhdcd-64.html"><i class="fas fa-angle-right color-blue"></i><span>Dịch vụ tổ chức Đại hội đồng cổ đông trực tuyến</span></a></p>
										<p><a href="//www.dmca.com/Protection/Status.aspx?ID=f0a99e5d-794c-4116-9148-f9bb878e883b" title="DMCA.com Protection Status" class="dmca-badge"> <amp-img src="https://images.dmca.com/Badges/DMCA_logo-grn-btn180w.png?ID=f0a99e5d-794c-4116-9148-f9bb878e883b" alt="DMCA.com Protection Status" width="180" height="65"></amp-img></a>  <!--<script src="https://images.dmca.com/Badges/DMCABadgeHelper.min.js"> </script>--></p>
										<p><a href="http://online.gov.vn/Home/WebDetails/87549?AspxAutoDetectCookieSupport=1" title="Luật Hoàng Anh - thông báo Bộ Công Thương"> <amp-img src="https://luathoanganh.vn/media/uploads/2021/logosalenoti.png" alt="Luật Hoàng Anh - thông báo Bộ Công Thương"  width="180" height="68"></amp-img></a></p>
                                </div>
                        </div>

                        <div class="row footer-copyright text-center">
                                <div class="col-12"><i class="far fa-copyright"></i> Bản quyền thuộc về Luật Hoàng Anh - Mọi sự sao chép phải được sự chấp thuận của Luật Hoàng Anh bằng văn bản.</div>
                        </div>
                </div>
        </div>
</section>

<section class="menu-fixed-mb justify-content-between">
	<div class="d-flex justify-content-start">
		<h3 class="title-block">
			TƯ VẤN LUẬT
			<span class>Miễn phí</span>
		</h3>
		<amp-img src="/themes/default/ctyluat/img/icon-fixed-mb.png" width="24" height="24" style="margin: 11px 10px 0px 10px" ></amp-img>
	</div>
	<div class="text-center" style="width: 80px">
		<a href="https://zalo.me/0908308123" target="_blank">
			<amp-img src="/themes/default/ctyluat/img/icon-zalo-fixed.png" width="24" height="24" style="margin: -25px 10px 0px 20px" ></amp-img>
		</a>
		<a href="https://zalo.me/0908308123" target="_blank" class="text-white d-block text-center" style="margin-top: 0; margin-left: 10px; font-weight: 700;">Zalo</a>
	</div>
	<div class="text-center">
		<a href="tel:0908308123">
			<amp-img src="/themes/default/ctyluat/img/icon-phone-fixed.png" width="24" height="24" style="margin: -25px 10px 0px 20px" ></amp-img>
		</a>
		<a href="tel:0908308123" class="text-white d-block" style="margin-top: 0; font-weight: 700;">0908 308 123</a>
	</div>
</section>

<!--<zone id="kcx4nu82"></zone>-->