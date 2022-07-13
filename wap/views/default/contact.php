<?php
use yii\helpers\Url;
?>
<section class="breadcrumb-section text-center breadcrumb-section-5">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<h1 class="breadcrumb-title">Liên hệ</h1>
					<nav aria-label="breadcrumb" class="breadcrumb-area">
					  <ol class="breadcrumb ml-auto">
					    <li class="breadcrumb-item"><a href="<?= Url::home() ?>">Trang chủ</a></li>
					    <li class="breadcrumb-item active" aria-current="page">Liên hệ</li>
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
					<div class="col-12 col-lg-6 box-contact">
						<h2 class="category-title mt-5">Thông Tin Liên Hệ</h2>
						<p class="contact-info mt-5">
							<i class="fas fa-map-marker-alt"></i><span>Số 2/84 - Trần Quang Diệu - Phường Ô Chợ Dừa - Quận Đống Đa - TP Hà Nội</span>
						</p>
						<p class="contact-info">
							<i class="fas fa-phone"></i><span>0908 308 123</span>
						</p>
						<p class="contact-info">
							<i class="fas fa-envelope"></i><span>luatsu@luathoanganh.vn</span>
						</p>
						<div class="maps">
							<iframe src="https://www.google.com/maps/embed/v1/place?q=place_id:ChIJRRCGEHerNTERuwp8YFCN44c&key=AIzaSyCmrqXLQJF_O6B3dX0zRyVpmBI-Ud5uP40" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
						</div>
					</div>
					<div class="col-12 col-lg-6">
						<h2 class="category-title mt-5">Gửi Yêu cầu</h2>
						<form action="" method="POST" enctype="multipart/form-data">
							<div class="form-group mt-5">
								<p style="    color: #0008ff;font-weight: bold;"><?php if(!empty($message)) echo $message ?></p>
								<p style="    color: #ff0000;font-weight: bold;"><?php if(!empty($error)) echo $error ?></p>
							</div>
							<input name="_csrf" type="hidden" value="<?php print Yii::$app->request->csrfToken; ?>" />
							<div class="form-group mt-5">
							  	<input type="text" name="User[fullname]" required class="form-control" placeholder="Họ tên *">
							</div>
							<div class="form-group">
							  	<input type="text" class="form-control" name="User[email]" required placeholder="Email *">
							</div>
							<div class="form-group">
							  	<input type="text" class="form-control" name="User[phone]" required placeholder="Số điện thoại *">
							</div>
							<div class="form-group">
								<input type="file" name="User[file]">(Kích thước tệp tối đa 25Mb)
							</div>
							<div class="form-group">
								<textarea class="form-control" name="User[note]" rows="5" placeholder="Yêu cầu (tối đa 50000 kí tự)" maxlength="50000"></textarea>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-info btn-call-now">Gửi</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

	</section>