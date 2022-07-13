<?php

use yii\helpers\Url;
use common\components\Utility;

?>

<section class="header-section back-white">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="header">
                    <nav class="navbar navbar-expand-lg">
                        <!-- Brand -->
                        <a class="navbar-brand" href="<?= Url::home() ?>">
						<img src="/themes/default/ctyluat/img/logo.png" height="80" alt="Luật Hoàng Anh - Dịch vụ luật sư"></a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <!-- Links -->
                            <ul class="navbar-nav ml-auto">

                                <li class="nav-item active">
                                    <a class="nav-link" href="<?= Url::home() ?>">Trang chủ</a>
                                </li>

                                <!-- Dropdown -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                                        Giới thiệu
                                    </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                           href="/ve-chung-toi.html">Về chúng tôi</a>
                                        <a class="dropdown-item" href="<?= Url::to(['/default/doi-ngu']) ?>">Đội ngũ
                                            luật sư</a>
										<a class="dropdown-item"
                                         href="/gioi-thieu/chinh-sach-bao-ve-thong-tin-ca-nhan-lha5881.html">Chính sách bảo mật</a>
                                    </div>
                                </li>

                                <!-- Dropdown -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link" href="/dich-vu-luat-su.html"
                                       style="display: inline-block;">
                                        Dịch vụ luật sư
                                    </a>
                                    <i class="dropdown-toggle" id="navbardrop" data-toggle="dropdown"
                                       style="cursor: pointer;"></i>
                                    <div class="dropdown-menu">
                                        <?php foreach ($this->params['dichvu'] as $dichvu): ?>
                                            <a class="dropdown-item"
                                               href="<?= \wap\components\CFunction::renderUrlCategory($dichvu) ?>"><?= $dichvu['title'] ?></a>
                                        <?php endforeach; ?>

                                    </div>
                                </li>

                                <!-- Dropdown -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link" href="/tinh-huong-phap-luat.html"
                                       style="display: inline-block;">
                                        Hỏi đáp pháp luật
                                    </a>
                                    <i class="dropdown-toggle" id="navbardrop" data-toggle="dropdown"
                                       style="cursor: pointer;"></i>
                                    <div class="dropdown-menu">
                                        <?php foreach ($this->params['question'] as $question): ?>
                                            <a class="dropdown-item"
                                               href="<?= \wap\components\CFunction::renderUrlCategory($question) ?>"><?= $question['title'] ?></a>
                                        <?php endforeach; ?>
                                    </div>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="<?= Url::to(['/default/contact']) ?>">Liên hệ</a>
                                </li>

								<li class="nav-item cse-search">
									<a class="nav-link" href="/tim-kiem.html">
										<i class="fa fa-search" aria-hidden="true" style="font-size: 16px"></i>
										<span>
											Tìm kiếm
										</span>
									</a>
								</li>

                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>
