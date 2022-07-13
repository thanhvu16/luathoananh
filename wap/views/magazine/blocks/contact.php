<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

/**
 * @var \cms\models\MagazineContent $model
 * @var \cms\models\Magazine $magazine
 * @var \yii\web\View $this
 */
$image = '';
$text = '';
$class = 'alignCenterOverflow';
$content = null;
$width = 100;
$common = [];
if (!empty($model->content)) {
    $content = unserialize($model->content);
	if (!empty($content['common']))
		$common = $content['common'];
}

?>
<div class="block-magazine">
	<?php foreach ($common as $key => $v):
		echo $this->render('common', ['common' => $v]);
	endforeach; ?>
	<div class="box-contact mt-5">
		<div class="">
			<p class="contact-info">
				<i class="fas fa-map-marker-alt"></i><span>Địa điểm</span>
			</p>
			<p class="content-info">
				<?= !empty($content['address']) ? $content['address'] : '' ?>
			</p>
			<p class="contact-info">
				<i class="fas fa-phone"></i><span>Số điện thoại</span>
			</p>
			<p class="content-info">
				<?= !empty($content['phone']) ? $content['phone'] : '' ?>
			</p>
			<p class="contact-info">
				<i class="fas fa-envelope"></i><span>Email</span>
			</p>
			<p class="content-info">
				<?= !empty($content['mail']) ? $content['mail'] : '' ?>
			</p>
		</div>
		<div class="">
			<form action="" method="POST" enctype="multipart/form-data">
				<div class="form-group">
					<p style="color: #0008ff;font-weight: bold;"><?php if(!empty($message)) echo $message ?></p>
					<p style="color: #ff0000;font-weight: bold;"><?php if(!empty($error)) echo $error ?></p>
				</div>
				<input name="_csrf" type="hidden" value="<?php echo Yii::$app->request->csrfToken; ?>" />
				<div style="display: flex;justify-content: space-between">
					<div class="form-group" style="width: calc(50% - 5px)">
						<input type="text" name="User[fullname]" required class="form-control" placeholder="Họ tên *">
					</div>
					<div class="form-group" style="width: calc(50% - 5px)">
						<input type="text" class="form-control" name="User[email]" required placeholder="Email *">
					</div>
				</div>
				<div class="form-group">
					<input type="text" class="form-control" name="User[phone]" required placeholder="Số điện thoại *">
				</div>
				<div class="form-group">
					<textarea class="form-control" name="User[note]" rows="5" placeholder="Để lại lời nhắn cho chúng tôi" maxlength="50000"></textarea>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-info btn-call-now">Đăng ký ngay</button>
				</div>
			</form>
		</div>
	</div>
</div>

<style>

</style>