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
					    <li class="breadcrumb-item active" aria-current="page">Đội ngũ luật sư</li>
					  </ol>
					</nav>
				</div>
			</div>
		</div>
	</section>

	<section class="body-content">
		<div class="body-white text-center pb-100">
			<div class="container">
				<div class="row mt-5">
                    <?php foreach ($news as $n): ?>
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="<?= \wap\components\CFunction::renderUrlDoiNgu($n) ?>">
                            <div class="box-person">
                                <div class="person-image" style="background-image: url('<?= $n['image'] ?>');"></div>
                                <div class="person-info">
                                    <div class="person-name"><?= $n['title'] ?></div>
                                    <div class="person-title"><?= $n['brief'] ?></div>
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
	</section>